<?php

namespace App\Http\Controllers\Payment_Methods;

use App\Models\PaymentRequest;
use App\Services\PaymentMethods\AirtelMoneyClient;
use App\Traits\Processor;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;
use Throwable;

class AirtelMoneyPaymentController extends Controller
{
    use Processor;

    public function index(Request $request): View|RedirectResponse|JsonResponse
    {
        $validator = Validator::make($request->all(), ['payment_id' => 'required|uuid']);

        if ($validator->fails()) {
            return response()->json(
                $this->response_formatter(GATEWAYS_DEFAULT_400, null, $this->error_processor($validator)),
                400,
            );
        }

        $payment = $this->findAirtelPayment((string) $request->query('payment_id'));

        if ($payment === null) {
            return response()->json($this->response_formatter(GATEWAYS_DEFAULT_204), 404);
        }

        if ($payment->is_paid) {
            return $this->payment_response($payment, 'success');
        }

        if (strtoupper((string) $payment->currency_code) !== 'UGX') {
            abort(422, 'Airtel Money Uganda payments must use UGX.');
        }

        $details = $this->paymentDetails($payment);
        if (! empty($details['airtel_transaction_id']) && strtoupper((string) ($details['airtel_status'] ?? '')) !== 'TF') {
            return view('payment.airtel-money-pending', [
                'payment' => $payment,
                'phone' => $details['airtel_msisdn'] ?? '',
            ]);
        }

        $payer = json_decode((string) $payment->payer_information, true);
        $phone = is_array($payer) ? (string) ($payer['phone'] ?? '') : '';

        return view('payment.airtel-money', compact('payment', 'phone'));
    }

    public function start(Request $request): View|RedirectResponse|JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'payment_id' => 'required|uuid',
            'phone' => 'required|string|max:30',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $payment = $this->findAirtelPayment((string) $request->input('payment_id'));

        if ($payment === null) {
            return response()->json($this->response_formatter(GATEWAYS_DEFAULT_204), 404);
        }

        if ($payment->is_paid) {
            return $this->payment_response($payment, 'success');
        }

        if (strtoupper((string) $payment->currency_code) !== 'UGX') {
            return back()->withErrors(['payment' => 'Airtel Money Uganda payments must use UGX.']);
        }

        $amount = (float) $payment->payment_amount;
        if ($amount <= 0 || floor($amount) !== $amount) {
            return back()->withErrors(['payment' => 'Airtel Money requires a positive whole-number UGX amount.']);
        }

        $msisdn = AirtelMoneyClient::normalizeUgandaMsisdn((string) $request->input('phone'));
        if ($msisdn === null) {
            return back()->withErrors(['phone' => 'Enter a valid Uganda mobile number, such as 0700 000 000.'])->withInput();
        }

        try {
            $client = $this->airtelClient(requireActive: true);
        } catch (Throwable) {
            return back()->withErrors([
                'payment' => 'Airtel Money is not configured or enabled. Contact the store administrator.',
            ])->withInput();
        }

        $details = $this->paymentDetails($payment);
        if (! empty($details['airtel_transaction_id']) && strtoupper((string) ($details['airtel_status'] ?? '')) !== 'TF') {
            return view('payment.airtel-money-pending', [
                'payment' => $payment,
                'phone' => $details['airtel_msisdn'] ?? $msisdn,
            ]);
        }

        $transactionId = (string) \Illuminate\Support\Str::uuid();
        $reference = 'AIRTEL-'.str_replace('-', '', $transactionId);
        $details['airtel_transaction_id'] = $transactionId;
        $details['airtel_reference'] = $reference;
        $details['airtel_msisdn'] = $msisdn;
        $details['airtel_status'] = 'TIP';
        $payment->additional_data = json_encode($details);
        $payment->save();

        try {
            $client->initiateCollection(
                msisdn: $msisdn,
                amount: (int) $amount,
                transactionId: $transactionId,
                reference: $reference,
            );
        } catch (Throwable $exception) {
            // Keep the transaction reference so a timeout cannot trigger a duplicate charge.
            Log::warning('Airtel Money collection request could not be confirmed.', [
                'payment_id' => $payment->id,
                'http_status' => $exception->getCode() ?: null,
            ]);
        }

        return view('payment.airtel-money-pending', compact('payment') + ['phone' => $msisdn]);
    }

    public function status(string $payment_id): JsonResponse
    {
        if (! \Illuminate\Support\Str::isUuid($payment_id)) {
            return response()->json(['status' => 'error', 'message' => 'Invalid payment reference.'], 422);
        }

        $payment = $this->findAirtelPayment($payment_id);
        if ($payment === null) {
            return response()->json(['status' => 'error', 'message' => 'Payment not found.'], 404);
        }

        if ($payment->is_paid) {
            return $this->terminalResponse($payment, 'success');
        }

        $details = $this->paymentDetails($payment);
        if (strtoupper((string) ($details['airtel_status'] ?? '')) === 'TF') {
            return $this->terminalResponse($payment, 'fail');
        }

        $transactionId = (string) ($details['airtel_transaction_id'] ?? '');
        if ($transactionId === '') {
            return response()->json(['status' => 'pending', 'message' => 'Payment request has not started.'], 200);
        }

        try {
            $response = $this->airtelClient(requireActive: false)->checkPaymentStatus($transactionId);
        } catch (Throwable $exception) {
            Log::warning('Airtel Money payment status check failed.', [
                'payment_id' => $payment->id,
                'http_status' => $exception->getCode() ?: null,
            ]);

            return response()->json([
                'status' => 'pending',
                'message' => 'Payment status is temporarily unavailable. We will check again.',
            ]);
        }

        $transaction = AirtelMoneyClient::transactionData($response);
        if (isset($transaction['id']) && ! hash_equals($transactionId, (string) $transaction['id'])) {
            Log::warning('Airtel Money returned a different transaction reference.', ['payment_id' => $payment->id]);

            return response()->json(['status' => 'pending', 'message' => 'Still checking your payment.']);
        }

        if (isset($transaction['amount']) && (float) $transaction['amount'] !== (float) $payment->payment_amount) {
            Log::warning('Airtel Money returned a different payment amount.', ['payment_id' => $payment->id]);

            return response()->json(['status' => 'pending', 'message' => 'Still checking your payment.']);
        }

        if (isset($transaction['currency']) && strtoupper((string) $transaction['currency']) !== 'UGX') {
            Log::warning('Airtel Money returned a different payment currency.', ['payment_id' => $payment->id]);

            return response()->json(['status' => 'pending', 'message' => 'Still checking your payment.']);
        }

        $result = AirtelMoneyClient::classifyStatus($response);

        if ($result === 'paid') {
            return $this->markPaid($payment, $transactionId);
        }

        if ($result === 'failed') {
            return $this->markFailed($payment, $transactionId);
        }

        $this->savePendingStatus($payment, $transactionId, (string) ($transaction['status'] ?? 'TIP'));

        return response()->json([
            'status' => 'pending',
            'message' => 'Approve the Airtel Money prompt on your phone.',
        ]);
    }

    private function findAirtelPayment(string $paymentId): ?PaymentRequest
    {
        return PaymentRequest::query()
            ->where('id', $paymentId)
            ->where('payment_method', 'airtel_money')
            ->first();
    }

    private function airtelClient(bool $requireActive): AirtelMoneyClient
    {
        $config = $this->payment_config('airtel_money', 'payment_config');

        if ($config === null || ($requireActive && (int) $config->is_active !== 1)) {
            throw new \RuntimeException('Airtel Money is not active.');
        }

        $mode = (string) ($config->mode ?? 'test');
        $values = json_decode((string) ($mode === 'live' ? $config->live_values : $config->test_values), true);

        if (! is_array($values) || empty($values['client_id']) || empty($values['client_secret'])) {
            throw new \RuntimeException('Airtel Money credentials are missing.');
        }

        return new AirtelMoneyClient(
            clientId: (string) $values['client_id'],
            clientSecret: (string) $values['client_secret'],
            mode: $mode === 'live' ? 'live' : 'test',
        );
    }

    private function paymentDetails(PaymentRequest $payment): array
    {
        $details = json_decode((string) $payment->additional_data, true);

        return is_array($details) ? $details : [];
    }

    private function savePendingStatus(PaymentRequest $payment, string $transactionId, string $status): void
    {
        DB::transaction(function () use ($payment, $transactionId, $status): void {
            $lockedPayment = PaymentRequest::query()->whereKey($payment->id)->lockForUpdate()->first();
            if ($lockedPayment === null || $lockedPayment->is_paid) {
                return;
            }

            $details = $this->paymentDetails($lockedPayment);
            if (! hash_equals((string) ($details['airtel_transaction_id'] ?? ''), $transactionId)) {
                return;
            }
            $details['airtel_status'] = strtoupper($status) ?: 'TIP';
            $lockedPayment->additional_data = json_encode($details);
            $lockedPayment->save();
        });
    }

    private function markPaid(PaymentRequest $payment, string $transactionId): JsonResponse
    {
        $shouldCallHook = DB::transaction(function () use ($payment, $transactionId): bool {
            $lockedPayment = PaymentRequest::query()->whereKey($payment->id)->lockForUpdate()->first();
            if ($lockedPayment === null || $lockedPayment->is_paid) {
                return false;
            }

            $details = $this->paymentDetails($lockedPayment);
            if (! hash_equals((string) ($details['airtel_transaction_id'] ?? ''), $transactionId)) {
                return false;
            }

            $details['airtel_status'] = 'TS';
            $lockedPayment->payment_method = 'airtel_money';
            $lockedPayment->transaction_id = $transactionId;
            $lockedPayment->is_paid = 1;
            $lockedPayment->additional_data = json_encode($details);
            $lockedPayment->save();

            return true;
        });

        $payment->refresh();
        if (! $shouldCallHook && ! $payment->is_paid) {
            return response()->json([
                'status' => 'pending',
                'message' => 'Still checking your payment.',
            ]);
        }

        if ($shouldCallHook && ! empty($payment->success_hook) && function_exists($payment->success_hook)) {
            call_user_func($payment->success_hook, $payment);
        }

        return $this->terminalResponse($payment, 'success');
    }

    private function markFailed(PaymentRequest $payment, string $transactionId): JsonResponse
    {
        $shouldCallHook = DB::transaction(function () use ($payment, $transactionId): bool {
            $lockedPayment = PaymentRequest::query()->whereKey($payment->id)->lockForUpdate()->first();
            if ($lockedPayment === null || $lockedPayment->is_paid) {
                return false;
            }

            $details = $this->paymentDetails($lockedPayment);
            if (
                ! hash_equals((string) ($details['airtel_transaction_id'] ?? ''), $transactionId)
                || strtoupper((string) ($details['airtel_status'] ?? '')) === 'TF'
            ) {
                return false;
            }

            $details['airtel_status'] = 'TF';
            $lockedPayment->additional_data = json_encode($details);
            $lockedPayment->save();

            return true;
        });

        $payment->refresh();
        if ($payment->is_paid) {
            return $this->terminalResponse($payment, 'success');
        }
        if (! $shouldCallHook && strtoupper((string) ($this->paymentDetails($payment)['airtel_status'] ?? '')) !== 'TF') {
            return response()->json([
                'status' => 'pending',
                'message' => 'Still checking your payment.',
            ]);
        }

        if ($shouldCallHook && ! empty($payment->failure_hook) && function_exists($payment->failure_hook)) {
            call_user_func($payment->failure_hook, $payment);
        }

        return $this->terminalResponse($payment, 'fail');
    }

    private function terminalResponse(PaymentRequest $payment, string $result): JsonResponse
    {
        $redirect = $this->payment_response($payment, $result);
        $redirectUrl = $redirect instanceof RedirectResponse
            ? $redirect->getTargetUrl()
            : route('payment-'.$result);

        return response()->json([
            'status' => $result === 'success' ? 'success' : 'failed',
            'message' => $result === 'success' ? 'Payment confirmed.' : 'The payment was not completed.',
            'redirect_url' => $redirectUrl,
        ]);
    }
}
