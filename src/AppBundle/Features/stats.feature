@skip
Feature: Stats voyage

    Scenario: Calculer les stats
        Given entities "AppBundle\Entity\Currency" :
            | name              | code |
            | Euro              | EUR  |
            | Dollard Américain | USD  |
        Given entities "AppBundle\Entity\Country" :
            | name      | capitalName | codeAlpha3 | AppBundle\Entity\Currency:code | visaInformation | visaDuration | priceAccommodation | priceLifeCost |
            | France    | Paris       | FRA        | EUR                            | Visa gratuit    | 90 jours     |                    |               |
            | Belgique  | Bruxelles   | BEL        | EUR                            | Visa gratuit    | 90 jours     | 25                 | 25            |
            | Etat-Unis | Washington  | USA        | USD                            | ESTA            | 30 jours     |                    |               |
        Given entities "AppBundle\Entity\Destination" :
            | name      | AppBundle\Entity\Country:name | latitude   | longitude  | priceAccommodation | priceLifeCost |
            | Paris     | France                        | 48.864592  | 2.336492   | 30                 | 20            |
            | Lyon      | France                        | 45.756573  | 4.818846   | 15                 | 10            |
            | Marseille | France                        | 43.288654  | 5.354511   | 20                 | 20            |
            | New-York  | Etat-Unis                     | 40.732977  | -73.993414 | 60                 | 35            |
            | Boston    | Etat-Unis                     | 42.359370  | -71.059168 | 50                 | 40            |
            | Bruges    | Belgique                      | 50.8439026 | 4.3469415  | 30                 | 25            |
        Given les destinations par défaut :
            | pays      | destination |
            | France    | Paris       |
            | Belgique  | Bruges      |
            | Etat-Unis | New-York    |
        Given entities "AppBundle\Entity\AvailableJourney" :
            | fromDestination:AppBundle\Entity\Destination:name | toDestination:AppBundle\Entity\Destination:name | flyPrices | flyTime | trainPrices | trainTime | busPrices | busTime |
            | Paris                                             | Lyon                                            | 52        | 56      | 50          | 120       | 5         | 390     |
            | Lyon                                              | Marseille                                       | 207       | 211     | 66          | 212       | 24        | 280     |
            | Marseille                                         | New-York                                        | 599       | 859     |             |           |           |         |
            | New-York                                          | Boston                                          |           |         | 195         | 279       |           |         |
            | Boston                                            | Paris                                           |           |         |             |           | 612       | 876     |
        Given les utilisateurs :
            | nom     |
            | guilhem |
        When l'utilisateur "guilhem" crée les voyages suivants :
            | nom | date de départ | destination de départ |
            | TDM | 01/01/2015     | Paris                 |
        When j'ajoute les étapes suivantes au voyage "TDM" :
            | destination | pays     | nombre de jour |
            | Lyon        |          | 7              |
            | Marseille   |          | 3              |
            | New-York    |          | 8              |
            | Boston      |          | 2              |
            | Paris       |          | 1              |
            |             | Belgique | 2              |
        Then il existe les transports suivants au voyage "TDM" :
            | depuis    | jusqu'à   | type de transport |
            | Paris     | Lyon      | BUS               |
            | Lyon      | Marseille | BUS               |
            | Marseille | New-York  | FLY               |
            | New-York  | Boston    | TRAIN             |
            | Boston    | Paris     | BUS               |
        Then les statistiques du voyage "TDM" sont :
            | nb étapes | cout total | durée | date départ | date retour | nb de pays | distance | étape principale |
            | 6         | 2820       | 23    | 01/01/2015  | 24/01/2015  | 3          | 13069    | New-York         |
        When je change le mode de transport à "FLY" pour le trajet de "Lyon" à "Marseille" du voyage "TDM"
        Then il existe les transports suivants au voyage "TDM" :
            | depuis    | jusqu'à   | type de transport |
            | Paris     | Lyon      | BUS               |
            | Lyon      | Marseille | FLY               |
            | Marseille | New-York  | FLY               |
            | New-York  | Boston    | TRAIN             |
            | Boston    | Paris     | BUS               |
        Then les statistiques du voyage "TDM" sont :
            | nb étapes | cout total | durée | date départ | date retour | nb de pays | distance | étape principale |
            | 6         | 3003       | 23    | 01/01/2015  | 24/01/2015  | 3          | 13069    | New-York         |



