@component('mail::message')
# Verfikasi Email

Terima kasih sudah mendaftar. Silakan klik tombol dibawah untuk verifikasi email.

@component('mail::button', ['url' => $data['link']])
Verifikasi
@endcomponent

Kode : <b>{{ $data['kode'] }}</b>

Terima Kasih.<br>
Team Samid, 2022
@endcomponent
