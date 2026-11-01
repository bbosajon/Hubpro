<?php

namespace App\Http\Requests\Vendor\Withdraw;

use App\Http\Requests\BaseRequest;
use App\Models\WithdrawalMethod;
use App\Services\PaymentMethods\AirtelMoneyClient;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class AddRequest extends BaseRequest
{
    protected function prepareForValidation(): void
    {
        if (! $this->has('withdraw_method') && $this->has('withdraw_method_id')) {
            $this->merge(['withdraw_method' => $this['withdraw_method_id']]);
        }
    }

    public function rules(): array
    {
        return [
            'withdraw_method' => [
                'required',
                'integer',
                Rule::exists('withdrawal_methods', 'id')->where(
                    fn ($query) => $query->where('is_active', 1)->where('vendor_status', 1),
                ),
            ],
            'amount' => ['required', 'numeric', 'gt:0'],
        ];
    }

    public function after(): array
    {
        return [
            function (Validator $validator): void {
                if ($validator->errors()->isNotEmpty()) {
                    return;
                }

                $method = WithdrawalMethod::query()
                    ->whereKey($this->input('withdraw_method'))
                    ->where('is_active', 1)
                    ->where('vendor_status', 1)
                    ->first(['method_name']);

                if ($method?->method_name !== 'Airtel Money Uganda') {
                    return;
                }

                $phone = $this->input('method_info.phone');
                if (! is_string($phone) || AirtelMoneyClient::normalizeUgandaMsisdn($phone) === null) {
                    $validator->errors()->add(
                        'method_info.phone',
                        translate('Enter a valid Uganda Airtel Money phone number.'),
                    );
                }
            },
        ];
    }

    public function messages(): array
    {
        return [
            'withdraw_method.required' => translate('Choose a withdrawal method'),
            'withdraw_method.exists' => translate('This withdrawal method is not available'),
            'amount.required' => translate('The amount is required'),
            'amount.numeric' => translate('The amount must be a number'),
            'amount.gt' => translate('Amount must be greater than zero'),
        ];
    }
}
