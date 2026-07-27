@component('mail::message')
# Thanks for your order, {{ $order->customer?->first_name }}!

We’ve received order **{{ $order->order_no }}** and it’s now confirmed.

@component('mail::table')
| Item | Qty | Total |
|:-----|:---:|------:|
@foreach ($lines as $line)
| {{ $line->item?->name ?? '—' }} | {{ (int) $line->quantity }} | {{ number_format((float) $line->price, 2) }} {{ $currency }} |
@endforeach
| **Total** | | **{{ number_format((float) $order->price, 2) }} {{ $currency }}** |
@endcomponent

You’ll pay when you collect your order at the store. We’ll let you know as soon as it’s ready for pickup.

@component('mail::button', ['url' => url('/account/orders/' . $order->id)])
Track your order
@endcomponent

Thanks,<br>
{{ $shopName }}
@endcomponent
