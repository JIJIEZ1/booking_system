@component('mail::message')
# Payment Rejected

Hello {{ $payment->customer->name }},

Your payment with ID **{{ $payment->payment_id }}** for **RM {{ number_format($payment->amount,2) }}** has been **rejected**.

Please contact support or try again.

Thanks,<br>
{{ config('app.name') }}
@endcomponent
