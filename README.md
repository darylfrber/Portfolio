# Daryl Färber's Persoonlijke Portfolio

Welkom bij het GitHub-repository van mijn persoonlijke portfolio. Deze portfolio is ontworpen om bezoekers een blik te geven op mijn voorgaande projecten en wat ik tot nu toe heb bereikt. Bovendien biedt het een platform voor geïnteresseerden om contact met mij op te nemen. Daarnaast is er een speciaal gedeelte gewijd aan schoolprojecten waar gebruikers kunnen inloggen en registreren om mijn meest recente werkzaamheden te bekijken.

🔗 [Live Preview](https://darylfarber.nl)

## Functionaliteiten

De portfolio bevat de volgende secties en functionaliteiten:

- **Index**: Het startpunt waar bezoekers worden verwelkomd.
- **Projectpagina**: Een overzicht van mijn voorgaande projecten.
- **Contactpagina**: Een formulier waarmee bezoekers contact met mij kunnen opnemen.
- **Schoolsectie**: Hier kunnen gebruikers:
  - Inloggen en registreren.
  - Mijn laatste projecten bekijken via een mappenverkenner.
  - De BitLab API raadplegen voor specifieke projectdetails.

## Installatie

Om de portfolio lokaal op te zetten, volgt u deze stappen:

1. Clone het repository naar uw lokale machine.
2. Navigeer naar de map in de terminal met `cd`.
3. Zorg ervoor dat PHP en Composer zijn geïnstalleerd op uw machine.
4. Installeer de vereiste afhankelijkheden met `composer install`.
5. Maak een bestand aan genaamd `.ENV` en vul het in met de volgende configuratie:

BITLAB_API_KEY="uw_api_key_hier"  
DATABASE_HOST="uw_host_hier"
DATABASE_NAME="uw_database_naam_hier"
DATABASE_USER="uw_database_gebruikersnaam_hier"
DATABASE_PASS="uw_database_wachtwoord_hier"

6. Zorg dat de database instellingen correct staan en dat de database is aangemaakt.
7. U hoeft geen database te importeren; deze wordt automatisch aangemaakt via de applicatie!

## Technologieën

De portfolio is gemaakt met behulp van:

- PHP
- Composer
- Twig
- RedBean
- Bootstrap
- JavaScript
- HTML
- CSS