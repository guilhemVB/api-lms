@skip
Feature: Stages

    Scenario: Supprimer une étapes
        Given entities "AppBundle\Entity\Currency" :
            | name              | code |
            | Euro              | EUR  |
            | Dollard Américain | USD  |
        Given entities "AppBundle\Entity\Country" :
            | name      | capitalName | codeAlpha3 | AppBundle\Entity\Currency:code | visaInformation | visaDuration |
            | France    | Paris       | FRA        | EUR                            | Visa gratuit    | 90 jours     |
            | Belgique  | Bruxelles   | BEL        | EUR                            | Visa gratuit    | 90 jours     |
            | Etat-Unis | Washington  | USA        | USD                            | ESTA            | 30 jours     |
        Given entities "AppBundle\Entity\Destination" :
            | name      | AppBundle\Entity\Country:name | latitude   | longitude  |
            | Paris     | France                        | 48.864592  | 2.336492   |
            | Lyon      | France                        | 45.756573  | 4.818846   |
            | Marseille | France                        | 43.288654  | 5.354511   |
            | New-York  | Etat-Unis                     | 40.732977  | -73.993414 |
            | Boston    | Etat-Unis                     | 42.359370  | -71.059168 |
            | Bruxelles | Belgique                      | 50.8439026 | 4.3469415  |
        Given entities "AppBundle\Entity\AvailableJourney" :
            | fromDestination:AppBundle\Entity\Destination:name | toDestination:AppBundle\Entity\Destination:name | flyPrices | flyTime | trainPrices | trainTime | busPrices | busTime |
            | Marseille                                         | New-York                                        | 599       | 859     |             |           |           |         |
            | New-York                                          | Lyon                                            | 710       | 529     |             |           |           |         |
            | Marseille                                         | Lyon                                            | 207       | 211     | 66          | 212       | 24        | 280     |
        Given les utilisateurs :
            | nom     |
            | guilhem |
        When l'utilisateur "guilhem" crée les voyages suivants :
            | nom | date de départ | destination de départ |
            | TDM | 01/01/2015     | Paris                 |
        When j'ajoute les étapes suivantes au voyage "TDM" :
            | destination | pays     | nombre de jour |
            | Boston      |          | 1              |
            | Paris       |          | 2              |
            | Boston      |          | 3              |
            | Lyon        |          | 4              |
            | Marseille   |          | 5              |
            | New-York    |          | 6              |
            | Lyon        |          | 7              |
            |             | Belgique | 8              |
        Then il existe les transports suivants au voyage "TDM" :
            | depuis    | jusqu'à  | type de transport |
            | Marseille | New-York | FLY               |
            | New-York  | Lyon     | FLY               |
        When je supprime l'étape "New-York" à la position 6 du voyage "TDM"
        Then la voyage "TDM" à les étapes suivantes :
            | destination | pays     | nombre de jour | position |
            | Boston      |          | 1              | 1        |
            | Paris       |          | 2              | 2        |
            | Boston      |          | 3              | 3        |
            | Lyon        |          | 4              | 4        |
            | Marseille   |          | 5              | 5        |
            | Lyon        |          | 7              | 6        |
            |             | Belgique | 8              | 7        |
        Then il existe les transports suivants au voyage "TDM" :
            | depuis    | jusqu'à | type de transport |
            | Marseille | Lyon    | BUS               |


    Scenario: Changer l'ordre des étapes -> de 2 à 3
        Given entities "AppBundle\Entity\Currency" :
            | name              | code |
            | Euro              | EUR  |
            | Dollard Américain | USD  |
        Given entities "AppBundle\Entity\Country" :
            | name      | capitalName | codeAlpha3 | AppBundle\Entity\Currency:code | visaInformation | visaDuration |
            | France    | Paris       | FRA        | EUR                            | Visa gratuit    | 90 jours     |
            | Etat-Unis | Washington  | USA        | USD                            | ESTA            | 30 jours     |
        Given entities "AppBundle\Entity\Destination" :
            | name      | AppBundle\Entity\Country:name | latitude  | longitude  |
            | Paris     | France                        | 48.864592 | 2.336492   |
            | Lyon      | France                        | 45.756573 | 4.818846   |
            | Marseille | France                        | 43.288654 | 5.354511   |
            | New-York  | Etat-Unis                     | 40.732977 | -73.993414 |
            | Boston    | Etat-Unis                     | 42.359370 | -71.059168 |
        Given entities "AppBundle\Entity\AvailableJourney" :
            | fromDestination:AppBundle\Entity\Destination:name | toDestination:AppBundle\Entity\Destination:name | flyPrices | flyTime | trainPrices | trainTime | busPrices | busTime |
            | Paris                                             | Lyon                                            | 1         | 10      |             |           |           |         |
            | Lyon                                              | Marseille                                       | 1         | 10      |             |           |           |         |
            | New-York                                          | Marseille                                       |           |         |             |           | 1         | 10      |
            | New-York                                          | Boston                                          | 1         | 10      |             |           |           |         |
        Given les utilisateurs :
            | nom     |
            | guilhem |
        When l'utilisateur "guilhem" crée les voyages suivants :
            | nom | date de départ | destination de départ |
            | TDM | 01/01/2015     | Paris                 |
        When j'ajoute les étapes suivantes au voyage "TDM" :
            | destination | nombre de jour |
            | Lyon        | 7              |
            | Marseille   | 3              |
            | New-York    | 8              |
            | Boston      | 2              |
        When je change l'étape "Marseille" du voyage "TDM" de la position 2 à la position 3
        Then la voyage "TDM" à les étapes suivantes :
            | destination | nombre de jour | position |
            | Lyon        | 7              | 1        |
            | New-York    | 8              | 2        |
            | Marseille   | 3              | 3        |
            | Boston      | 2              | 4        |
        Then il existe les transports suivants au voyage "TDM" :
            | depuis   | jusqu'à   | type de transport |
            | Paris    | Lyon      | FLY               |
            | New-York | Marseille | BUS               |

    Scenario: Changer l'ordre des étapes -> de 4 à 1
        Given entities "AppBundle\Entity\Currency" :
            | name              | code |
            | Euro              | EUR  |
            | Dollard Américain | USD  |
        Given entities "AppBundle\Entity\Country" :
            | name      | capitalName | codeAlpha3 | AppBundle\Entity\Currency:code | visaInformation | visaDuration |
            | France    | Paris       | FRA        | EUR                            | Visa gratuit    | 90 jours     |
            | Etat-Unis | Washington  | USA        | USD                            | ESTA            | 30 jours     |
        Given entities "AppBundle\Entity\Destination" :
            | name      | AppBundle\Entity\Country:name | latitude  | longitude  |
            | Paris     | France                        | 48.864592 | 2.336492   |
            | Lyon      | France                        | 45.756573 | 4.818846   |
            | Marseille | France                        | 43.288654 | 5.354511   |
            | Dijon     | France                        | 43.288654 | 5.354511   |
            | New-York  | Etat-Unis                     | 40.732977 | -73.993414 |
            | Boston    | Etat-Unis                     | 42.359370 | -71.059168 |
        Given entities "AppBundle\Entity\AvailableJourney" :
            | fromDestination:AppBundle\Entity\Destination:name | toDestination:AppBundle\Entity\Destination:name | flyPrices | flyTime | trainPrices | trainTime | busPrices | busTime |
            | Paris                                             | New-York                                        | 1         | 10      |             |           |           |         |
            | Lyon                                              | Dijon                                           |           |         | 10          | 1         |           |         |
            | Marseille                                         | Boston                                          |           |         |             |           | 1         | 10      |
        Given les utilisateurs :
            | nom     |
            | guilhem |
        When l'utilisateur "guilhem" crée les voyages suivants :
            | nom | date de départ | destination de départ |
            | TDM | 01/01/2015     | Paris                 |
        When j'ajoute les étapes suivantes au voyage "TDM" :
            | destination | nombre de jour |
            | Lyon        | 7              |
            | Dijon       | 3              |
            | Marseille   | 13             |
            | New-York    | 8              |
            | Boston      | 2              |
        When je change l'étape "New-York" du voyage "TDM" de la position 4 à la position 1
        Then la voyage "TDM" à les étapes suivantes :
            | destination | nombre de jour | position |
            | New-York    | 8              | 1        |
            | Lyon        | 7              | 2        |
            | Dijon       | 3              | 3        |
            | Marseille   | 13             | 4        |
            | Boston      | 2              | 5        |
        Then il existe les transports suivants au voyage "TDM" :
            | depuis    | jusqu'à  | type de transport |
            | Paris     | New-York | FLY               |
            | Lyon      | Dijon    | TRAIN             |
            | Marseille | Boston   | BUS               |

