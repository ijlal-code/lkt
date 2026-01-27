<?php

namespace App\Mail;

use App\Models\Sp2a;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Attachment; // PENTING
use Illuminate\Queue\SerializesModels;

class Sp2aNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $sp2a;
    protected $pdfData; // Variabel untuk menyimpan data PDF

    /**
     * Terima data SP2A dan Data PDF (Binary)
     */
    public function __construct(Sp2a $sp2a, $pdfData)
    {
        $this->sp2a = $sp2a;
        $this->pdfData = $pdfData;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            // Subjek otomatis dari database
            subject: 'SP2A Terbit: ' . $this->sp2a->nomor_sp2a,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.sp2a_notification', // View body email yang sudah Anda buat sebelumnya
        );
    }

    /**
     * Lampirkan PDF di sini
     */
    public function attachments(): array
    {
        return [
            Attachment::fromData(fn () => $this->pdfData, 'Surat_SP2A_' . $this->sp2a->nomor_sp2a . '.pdf')
                ->withMime('application/pdf'),
        ];
    }
}