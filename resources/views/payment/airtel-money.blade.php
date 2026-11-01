@extends('payment.layouts.master')

@section('content')
    <main class="container py-5" style="max-width: 560px">
        <div class="card shadow-sm">
            <div class="card-body p-4">
                <h1 class="h3 mb-2">Pay with Airtel Money</h1>
                <p class="text-muted mb-4">Uganda · UGX {{ number_format((float) $payment->payment_amount, 0) }}</p>

                @if ($errors->any())
                    <div class="alert alert-danger" role="alert">
                        @foreach ($errors->all() as $error)
                            <div>{{ $error }}</div>
                        @endforeach
                    </div>
                @endif

                <form method="POST" action="{{ route('airtel-money.start') }}">
                    @csrf
                    <input type="hidden" name="payment_id" value="{{ $payment->id }}">

                    <div class="mb-3">
                        <label for="airtel-phone" class="form-label">Airtel Money phone number</label>
                        <input
                            id="airtel-phone"
                            name="phone"
                            type="tel"
                            class="form-control"
                            value="{{ old('phone', $phone) }}"
                            placeholder="0700 000 000"
                            autocomplete="tel"
                            inputmode="tel"
                            maxlength="30"
                            required
                            aria-describedby="airtel-phone-help"
                        >
                        <div id="airtel-phone-help" class="form-text">Enter a Uganda number, with or without +256.</div>
                    </div>

                    <button type="submit" class="btn btn-danger w-100">Request payment</button>
                </form>
            </div>
        </div>
    </main>
@endsection
