Feature: CRUD Voyages

    Scenario: Check authentication
        When I send a "GET" request to "/voyages.jsonld"
        Then the response status code should be 401
        When I send a "POST" request to "/voyages.jsonld"
        Then the response status code should be 401
        When I send a "PUT" request to "/voyages/1.jsonld"
        Then the response status code should be 401
        When I send a "DELETE" request to "/voyages/1.jsonld"
        Then the response status code should be 401

    @emptyDatabase
    Scenario: CRUD Voyages
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
        Given I add "Content-Type" header equal to "application/json"
        Given I authenticate the user "gui"

        When I send a "POST" request to "/voyages.jsonld" with body:
        """
        {
            "startDate": "2018-01-01",
            "startDestination":"/destinations/1",
        }
        """
        Then the response status code should be 400

        When I send a "POST" request to "/voyages.jsonld" with body:
        """
        {
            "name":"TDM",
            "startDestination":"/destinations/1",
        }
        """
        Then the response status code should be 400

        When I send a "POST" request to "/voyages.jsonld" with body:
        """
        {
            "name":"TDM",
            "startDate": "2018-01-01"
        }
        """
        Then the response status code should be 400

        When I send a "POST" request to "/voyages.jsonld" with body:
        """
        {
            "name":"TDM",
            "startDate": "2018-01-01",
            "startDestination":"/destinations/1",
            "showPricesInPublic": false
        }
        """
        Then the response status code should be 201
        And the response should be in JSON
        And the header "Content-Type" should be equal to "application/ld+json; charset=utf-8"
        And the JSON should be equal to:
        """
        {
            "@context": "\/contexts\/Voyage",
            "@id": "\/voyages\/1",
            "@type": "Voyage",
            "id": 1,
            "name": "TDM",
            "token": "TOKEN_MOCK",
            "urlMinified": "google.com\/shortenMOCK",
            "showPricesInPublic": false,
            "startDate": "2018-01-01",
            "startDestination": {
                "@id": "\/destinations\/1",
                "@type": "Destination",
                "id": 1,
                "name": "Paris",
                "slug": "paris"
            },
            "stages": [],
            "transportType": null,
            "availableJourney": null
        }
        """

        When I send a "PUT" request to "/voyages/1.jsonld" with body:
        """
        {
            "name":"TDM 222",
            "startDate": "2020-01-01",
            "startDestination":"/destinations/2",
            "showPricesInPublic": false,
            "availableJourney":"/available_journeys/1"
        }
        """
        Then the response status code should be 200
        And the response should be in JSON
        And the header "Content-Type" should be equal to "application/ld+json; charset=utf-8"
        And the JSON should be equal to:
        """
        {
            "@context": "\/contexts\/Voyage",
            "@id": "\/voyages\/1",
            "@type": "Voyage",
            "id": 1,
            "name": "TDM 222",
            "token": "TOKEN_MOCK",
            "urlMinified": "google.com\/shortenMOCK",
            "showPricesInPublic": false,
            "startDate": "2020-01-01",
            "startDestination": {
                "@id": "\/destinations\/2",
                "@type": "Destination",
                "id": 2,
                "name": "Lyon",
                "slug": "lyon"
            },
            "stages": [],
            "transportType": null,
            "availableJourney": {
                "@id": "\/available_journeys\/1",
                "@type": "AvailableJourney",
                "id": 1,
                "fromDestination": {
                    "@id": "\/destinations\/1",
                    "@type": "Destination",
                    "id": 1,
                    "name": "Paris",
                    "slug": "paris"
                },
                "toDestination": {
                    "@id": "\/destinations\/2",
                    "@type": "Destination",
                    "id": 2,
                    "name": "Lyon",
                    "slug": "lyon"
                },
                "flyPrices": 52,
                "flyTime": 56,
                "busPrices": 5,
                "busTime": 390,
                "trainPrices": 50,
                "trainTime": 120
            }
        }
        """

        When I send a "GET" request to "/voyages/1.jsonld"
        Then the response status code should be 200
        And the response should be in JSON
        And the header "Content-Type" should be equal to "application/ld+json; charset=utf-8"
        And the JSON should be equal to:
        """
        {
            "@context": "\/contexts\/Voyage",
            "@id": "\/voyages\/1",
            "@type": "Voyage",
            "id": 1,
            "name": "TDM 222",
            "token": "TOKEN_MOCK",
            "urlMinified": "google.com\/shortenMOCK",
            "showPricesInPublic": false,
            "startDate": "2020-01-01",
            "startDestination": {
                "@id": "\/destinations\/2",
                "@type": "Destination",
                "id": 2,
                "name": "Lyon",
                "slug": "lyon"
            },
            "stages": [],
            "transportType": null,
            "availableJourney": {
                "@id": "\/available_journeys\/1",
                "@type": "AvailableJourney",
                "id": 1,
                "fromDestination": {
                    "@id": "\/destinations\/1",
                    "@type": "Destination",
                    "id": 1,
                    "name": "Paris",
                    "slug": "paris"
                },
                "toDestination": {
                    "@id": "\/destinations\/2",
                    "@type": "Destination",
                    "id": 2,
                    "name": "Lyon",
                    "slug": "lyon"
                },
                "flyPrices": 52,
                "flyTime": 56,
                "busPrices": 5,
                "busTime": 390,
                "trainPrices": 50,
                "trainTime": 120
            }
        }
        """

        When I send a "DELETE" request to "/voyages/1.jsonld"
        Then the response status code should be 204

        When I send a "GET" request to "/voyages/1.jsonld"
        Then the response status code should be 404

