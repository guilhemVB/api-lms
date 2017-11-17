Feature: Stats voyage

    @emptyDatabase
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
            | nom | mot de passe | email       | role      |
            | gui | gui          | gui@gui.gui | ROLE_USER |

        Given entities "AppBundle\Entity\Voyage" :
            | name | startDate(\DateTime) | startDestination:AppBundle\Entity\Destination:name | AppBundle\Entity\User:username | token  |
            | TDM  | 2015-01-01           | Paris                                              | gui                            | TOKEN1 |

        Given I add "Content-Type" header equal to "application/json"
        Given I authenticate the user "gui"

        When I send a "POST" request to "/stages.jsonld" with body:
        """
        {
            "voyage": "/voyages/1",
            "nbDays":7,
            "destination": "/destinations/2",
            "position": 0
        }
        """
        Then the response status code should be 201

        When I send a "POST" request to "/stages.jsonld" with body:
        """
        {
            "voyage": "/voyages/1",
            "nbDays":3,
            "destination": "/destinations/3",
            "position": 1
        }
        """
        Then the response status code should be 201

        When I send a "POST" request to "/stages.jsonld" with body:
        """
        {
            "voyage": "/voyages/1",
            "nbDays":8,
            "destination": "/destinations/4",
            "position": 2
        }
        """
        Then the response status code should be 201

        When I send a "POST" request to "/stages.jsonld" with body:
        """
        {
            "voyage": "/voyages/1",
            "nbDays":2,
            "destination": "/destinations/5",
            "position": 3
        }
        """
        Then the response status code should be 201

        When I send a "POST" request to "/stages.jsonld" with body:
        """
        {
            "voyage": "/voyages/1",
            "nbDays":1,
            "destination": "/destinations/1",
            "position": 4
        }
        """
        Then the response status code should be 201

        When I send a "POST" request to "/stages.jsonld" with body:
        """
        {
            "voyage": "/voyages/1",
            "nbDays":2,
            "country": "/countries/2",
            "position": 5
        }
        """
        Then the response status code should be 201

        When I send a "GET" request to "/voyages/1/statistics.json"
        Then the response status code should be 200
        And the response should be in JSON
        And the header "Content-Type" should be equal to "application/json"

        And the JSON node "nbStages" should be equal to "6"
        And the JSON node "totalCost" should be equal to "2820"
        And the JSON node "nbDays" should be equal to "23"
        And the JSON node "startDate" should be equal to "2015-01-01"
        And the JSON node "endDate" should be equal to "2015-01-24"
        And the JSON node "nbCountries" should be equal to "3"
        And the JSON node "crowFliesDistance" should be equal to "13068.820923585"
        And the JSON node "mainDestination->name" should be equal to "New-York"

        When I send a "PUT" request to "/stages/1.jsonld" with body:
        """
        {
            "voyage": "/voyages/1",
            "nbDays":7,
            "destination": "/destinations/2",
            "position": 0,
            "transportType": "FLY"
        }
        """
        Then the response status code should be 200

        When I send a "GET" request to "/voyages/1/statistics.json"
        Then the response status code should be 200
        And the response should be in JSON
        And the header "Content-Type" should be equal to "application/json"

        And the JSON node "nbStages" should be equal to "6"
        And the JSON node "totalCost" should be equal to "3003"
        And the JSON node "nbDays" should be equal to "23"
        And the JSON node "startDate" should be equal to "2015-01-01"
        And the JSON node "endDate" should be equal to "2015-01-24"
        And the JSON node "nbCountries" should be equal to "3"
        And the JSON node "crowFliesDistance" should be equal to "13068.820923585"
        And the JSON node "mainDestination->name" should be equal to "New-York"
