<x-mail::message>
#Halo, {{ $donation->name }}!

Terima kasih atas donasi Anda! Donasi untuk {{ $donation->user->name }} telah berhasil.

<x-mail::panel>
    - **Nomor Transaksi:** {{ $payment['payment_id'] }}
    - **Jumlah Donasi:** Rp {{ number_format($donation->amount, 0, ',', '.') }}
    - **Status :** {{ ucfirst($payment['status']) }}
</x-mail::panel>

Silakan klik tombol  di bawah ini untuk melihat detail donasi Anda.
<x-mail::button :url="$payment['payment_url']">
Button Text
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
