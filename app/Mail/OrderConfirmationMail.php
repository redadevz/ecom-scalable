<?php

declare(strict_types=1);

namespace App\Mail;

use App\Models\OrderHeader;
use App\Settings\ShopSettings;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

/**
 * Sent to a customer right after they place an online order. Queued so checkout
 * stays fast — the request returns immediately and the queue worker renders/sends.
 */
class OrderConfirmationMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(public int $orderId) {}

    public function envelope(): Envelope
    {
        $order = OrderHeader::find($this->orderId);

        return new Envelope(
            subject: 'Your order ' . ($order?->order_no ?? '') . ' is confirmed',
        );
    }

    public function content(): Content
    {
        $order = OrderHeader::with(['orderLines.item', 'customer'])->findOrFail($this->orderId);
        $settings = app(ShopSettings::class);

        return new Content(
            markdown: 'emails.order-confirmed',
            with: [
                'order'    => $order,
                'lines'    => $order->orderLines,
                'currency' => $settings->currency_symbol,
                'shopName' => config('app.name', 'Shop'),
            ],
        );
    }
}
