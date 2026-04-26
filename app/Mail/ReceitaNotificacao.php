<?php

namespace App\Mail;

use App\Models\Receita;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ReceitaNotificacao extends Mailable
{
    use Queueable, SerializesModels;

    public string $acao; // 'criada' ou 'atualizada'

    public function __construct(
        public Receita $receita,
        string $acao = 'criada'
    ) {
        $this->acao = $acao;
    }

    public function envelope(): Envelope
    {
        $titulo = $this->acao === 'criada' ? 'Nova Receita Cadastrada' : 'Receita Atualizada';
        return new Envelope(subject: "{$titulo}: {$this->receita->nome}");
    }

    public function content(): Content
    {
        return new Content(view: 'emails.receita-notificacao');
    }

    public function attachments(): array
    {
        return [];
    }
}
