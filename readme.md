# Activisme-BE - Spoon 

Spoon is een laravel template voor onze projecten waar alleen RVB leden op moeten kunnen inloggen. 

Indien je geen developer van de organisatie bent kun je deze template gebruiken. 
Maar hou er rekening mee dat we geen ondersteuning bieden aan mensen die geen lid zijn van onze organisatie. 

Kort samengevat: Als je deze template gebruikt sta je alleen voor. 

### Documentatie

Alle nodig documentatie voor Spoon kan [hier](https://activisme-be.github.io/Spoon-documentatie/) bekeken worden.

## Installatie 

### Laravel app

Download de hoofd branch van de template 

```bash 
git clone https://github.com/Activisme-be/Spoon.git
```
Maak een kopie van `.env.example` en hernoem het naar `.env`

Installeer vervolgens de composer dependencies 

```bash
composer install
```

En om af te ronden kon je je database configureren en de ERD laten lopen met de nodige seeds. 

```bash
php artisan migrate --seed
```

### Assets 

Het installeren van de front-end assets en zijn depenencies vraagt `npm`. 

```bash
npm install
```

Spoon maakt gebruik van Laravel Mix om de asset files op te bouwen. Voor het bouwen van de assets voer je het volgende commando uit. 

```bash
npm run dev
```

De beschikbare build taken kun je terug vinden in de `package.json` file.

### Colofon 
In het algemeen accepteren we geen PR's van buitenstaander in deze Repository.
Maar als je een bg hebt gevonden of een idee hebt richting een verbetering. 
Zijn we blij als je het met ons deelt en of bereid bent om het uit te werken of the verhelpen. 


### Framework sync

Spoon word regelmatig bijgewerkt met veranderen in het Laravel framework. 
De laatste synchronizatie is uitgevoerd op 26/12/2019. 

Met als laatste commit: [Merge pull request #5196 from laravel/revert-5006-analysis-qxPGgA](https://github.com/laravel/laravel/commit/25c36eb592c57e075acc32a12703005f67c4ec10)

### Licentie 
Spoon en Larevel zijn vrijgegeven als open-source sofware onder de MIT licentie
