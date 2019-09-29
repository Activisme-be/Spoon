@component('mail::message')
# Aanvraag voor het resetten van je 2FA.

Geachte,

Recent hebben wij een aanvraag gehad om de 2FA authenticatie om je account te resetten. Vandaar deze mail!
Met de onderstaande knop kun je via een unieke URL de 2FA verwijderen van je account. Zonder enige token
in te geven. We raden je wel aan direct terug opnieuw 2FA authenticatie te installeren op uw account.

@component('mail::button', ['url' => $resetRoute])
2FA resetten
@endcomponent

Met vriendelijke groet,<br>
{{ config('app.name') }}
@endcomponent
