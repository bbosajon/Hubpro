@extends('payment.layouts.master')

@section('content')
    <main class="container py-5" style="max-width: 560px">
        <div class="card shadow-sm">
            <div class="card-body p-4 text-center">
                <h1 class="h3 mb-2">Approve your Airtel Money payment</h1>
                <p class="text-muted">
                    Airtel will send a payment prompt to {{ $phone ?: 'your phone' }}.
                    Approve it on your phone to finish paying UGX {{ number_format((float) $payment->payment_amount, 0) }}.
                </p>
                <div class="spinner-border text-danger my-3" role="status">
                    <span class="visually-hidden">Checking payment status</span>
                </div>
                <p id="airtel-status-message" role="status" aria-live="polite">Waiting for Airtel to confirm your payment…</p>
                <button id="airtel-check-status" type="button" class="btn btn-outline-danger" hidden>
                    Check payment status
                </button>
                <p class="small text-muted mt-3 mb-0">
                    Do not submit another payment while this request is being checked.
                </p>
            </div>
        </div>
    </main>

    <script>
        (() => {
            const statusUrl = @json(route('airtel-money.status', ['payment_id' => $payment->id]));
            const message = document.getElementById('airtel-status-message');
            const spinner = document.querySelector('.spinner-border');
            const checkButton = document.getElementById('airtel-check-status');
            let checks = 0;
            let timer;

            async function checkStatus() {
                checkButton.disabled = true;
                try {
                    const response = await fetch(statusUrl, {
                        headers: { 'Accept': 'application/json' },
                        cache: 'no-store'
                    });
                    const result = await response.json();

                    if (result.redirect_url && (result.status === 'success' || result.status === 'failed')) {
                        window.location.assign(result.redirect_url);
                        return;
                    }

                    message.textContent = result.message || 'Waiting for Airtel to confirm your payment…';
                } catch (error) {
                    message.textContent = 'Could not reach the status service. Your payment is still being checked.';
                } finally {
                    checkButton.disabled = false;
                }

                checks += 1;
                if (checks < 45) {
                    timer = window.setTimeout(checkStatus, 8000);
                } else {
                    spinner.hidden = true;
                    checkButton.hidden = false;
                    message.textContent = 'Payment is still pending. Check again when you have approved the prompt.';
                }
            }

            checkButton.addEventListener('click', () => {
                window.clearTimeout(timer);
                checks = 0;
                spinner.hidden = false;
                checkButton.hidden = true;
                checkStatus();
            });

            checkStatus();
        })();
    </script>
@endsection
