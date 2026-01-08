<?php

namespace App\Mail;

use App\Models\Payment;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PaymentRejectedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $payment;

    /**
     * Create a new message instance.
     *
     * @param Payment $payment
     */
    public function __construct(Payment $payment)
    {
        $this->payment = $payment;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Payment Rejected')
                    ->markdown('emails.payment.rejected')
                    ->with([
                        'paymentId' => $this->payment->payment_id,
                        'amount' => $this->payment->amount,
                        'customerName' => $this->payment->customer->name,
                        'paymentMethod' => $this->payment->payment_method,
                    ]);
    }
}
