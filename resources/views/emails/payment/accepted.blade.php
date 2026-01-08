@component('mail::message')
# Payment Successful

Hello {{ $payment->customer->name }},

Your payment with ID **{{ $payment->payment_id }}** for **RM {{ number_format($payment->amount,2) }}** has been **accepted**.

Thank you for your payment!

Thanks,<br>
{{ config('app.name') }}
@endcomponent
