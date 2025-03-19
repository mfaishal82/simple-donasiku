<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class DonationInvoiceMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $donation;
    public $payment;

    /**
     * Create a new message instance.
     */
    public function __construct($donation, $payment)
    {
        $this->donation = $donation;
        $this->payment = $payment;
    }

    public function build()
    {
        return $this->subject('Donation Invoice')
            ->markdown('emails.donation-invoice', [
                'donation' => $this->donation,
                'payment' => $this->payment,
            ]);
    }
}
