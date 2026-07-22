<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Queue\SerializesModels;

class BookingReceiptMail extends Mailable
{
    use Queueable, SerializesModels;

    public $booking;
    public $user;
    public $pdfPath;

    public function __construct($booking, $user, $pdfPath)
    {
        $this->booking = $booking;
        $this->user = $user;
        $this->pdfPath = $pdfPath;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Booking Confirmation - Calmay River Hotel',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.booking-receipt',
        );
    }

    public function attachments(): array
    {
        return [
            Attachment::fromPath($this->pdfPath)
                ->as('Booking_Receipt_' . $this->booking->booking_id . '.pdf')
                ->withMime('application/pdf'),
        ];
    }
}