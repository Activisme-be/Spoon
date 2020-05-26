@component('mail::message')
# 2FA authenticatie is geactiveerd op uw account.

We hebben de installatie van two factor authenticatie voltooid voor jouw account. Alsook vind je
hieronder de recovery codes voor je account.

## Recovery codes

@foreach($tokens as $key => $value)
**{{ $loop->iteration }})** {{ $value }} <br>
@endforeach

Recovery codes zijn de enige manier om terug toegang tot je account te verkrijgen. Vandaar dat we willen vragen om
ze veilig te bewaren aangezien je altijd het risico loopt om je gsm kwijt te raken. Of accidentieel de 2FA te verwijderen
in Google Authenticator.


{{ config('app.name') }} is niet in staat om je account te recoveren. Verwijder ook na het noteren van je recovery codes deze mail.
Om te garanderen dat u alleen deze codes kent.

Met vriendelijke groet,<br>
{{ config('app.name') }}
@endcomponent
