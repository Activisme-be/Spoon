@component('mail::message')
# Aanvraag voor het resetten van je 2FA.

The body of your message.

@component('mail::button', ['url' => ''])
Button Text
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
