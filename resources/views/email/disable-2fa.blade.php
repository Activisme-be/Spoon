@component('mail::message')
# 2fa authenticatie is uitgeschakeld op uw account.

Via deze weg willen je laten weten dat de 2fa authenticatie voor uw account op {{ config('app.name') }}
is uitgeschakeld. U kan deze ten alle tijden terug activeren in de account beveiligings instellingen van de applicatie of website.

Met vriendelijke groet,<br>
{{ config('app.name') }}
@endcomponent
