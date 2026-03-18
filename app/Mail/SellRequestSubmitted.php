<?php

namespace App\Mail;

use App\Models\SellRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SellRequestSubmitted extends Mailable
{
    use Queueable, SerializesModels;

    public SellRequest $sellRequest;

    public function __construct(SellRequest $sellRequest)
    {
        $this->sellRequest = $sellRequest;
    }

    public function build(): self
    {
        return $this->subject('New sell-your-device enquiry')
            ->view('emails.sell-request-submitted');
    }
}

