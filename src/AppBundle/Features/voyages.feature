Feature: CRUD Voyages

    Scenario: Create Voyage
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
            | nom | mot de passe | email       |
            | gui | gui          | gui@gui.gui |



