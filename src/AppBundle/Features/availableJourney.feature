Feature: Available Journey calculator

    Scenario: Check authentication and unknow routes
        When I send a "GET" request to "/available_journeys.jsonld"
        Then the response status code should be 401
        When I send a "POST" request to "/available_journeys.jsonld"
        Then the response status code should be 405
        When I send a "PUT" request to "/available_journeys/1.jsonld"
        Then the response status code should be 405
        When I send a "DELETE" request to "/available_journeys/1.jsonld"
        Then the response status code should be 405

    @emptyDatabase
    Scenario: GET Available Journey
        Given entities "AppBundle\Entity\Currency" :
            | name              | code |
            | Euro              | EUR  |
            | Dollard Américain | USD  |
            | Livre sterling    | GBP  |
        Given entities "AppBundle\Entity\Country" :
            | name         | capitalName | codeAlpha3 | AppBundle\Entity\Currency:code | visaInformation | visaDuration |
            | France       | Paris       | FRA        | EUR                            | Visa gratuit    | 90 jours     |
            | Royaume-Unis | Londres     | UNK        | GBP                            | Visa très cher  | 30 jours     |
            | Etat-Unis    | Washington  | USA        | USD                            | ESTA            | 30 jours     |
        Given entities "AppBundle\Entity\Destination" :
            | name     | AppBundle\Entity\Country:name | latitude    | longitude  |
            | Paris    | France                        | 2.2946583   | 48.8580101 |
            | Lyon     | France                        | 4.8492387   | 45.7635056 |
            | New-York | Etat-Unis                     | -73.9862683 | 40.7590453 |
            | Londres  | Royaume-Unis                  | -0.0775694  | 51.5082493 |
        When je lance la récupération des transports possibles
        Then les possibilitées de transports sont :
            | depuis   | jusqu'à  | prix avion | temps avion | prix train | temps train | prix bus | temps bus |
            | Paris    | Lyon     | 136        | 269         | 82         | 152         | 21       | 452       |
            | Paris    | Londres  | 111        | 319         | 235        | 205         | 47       | 587       |
            | Paris    | New-York | 469        | 725         |            |             |          |           |
            | Lyon     | Paris    | 136        | 270         | 83         | 133         | 21       | 458       |
            | Lyon     | Londres  | 150        | 321         | 261        | 342         | 32       | 1081      |
            | Lyon     | New-York | 483        | 837         |            |             |          |           |
            | Londres  | Paris    | 114        | 311         | 235        | 201         | 52       | 616       |
            | Londres  | Lyon     | 153        | 294         | 253        | 407         | 38       | 988       |
            | Londres  | New-York | 496        | 681         |            |             |          |           |
            | New-York | Paris    | 469        | 622         |            |             |          |           |
            | New-York | Lyon     | 483        | 778         |            |             |          |           |
            | New-York | Londres  | 493        | 638         |            |             |          |           |
        When je supprime les transports liés à la destination "Lyon"
        Then les possibilitées de transports sont :
            | depuis   | jusqu'à  | prix avion | temps avion | prix train | temps train | prix bus | temps bus |
            | Paris    | Londres  | 111        | 319         | 235        | 205         | 47       | 587       |
            | Paris    | New-York | 469        | 725         |            |             |          |           |
            | Londres  | Paris    | 114        | 311         | 235        | 201         | 52       | 616       |
            | Londres  | New-York | 496        | 681         |            |             |          |           |
            | New-York | Paris    | 469        | 622         |            |             |          |           |
            | New-York | Londres  | 493        | 638         |            |             |          |           |
        Given les utilisateurs :
            | nom | mot de passe | email       | role      |
            | gui | gui          | gui@gui.gui | ROLE_USER |
        Given I add "Content-Type" header equal to "application/json"
        Given I authenticate the user "gui"

        When I send a "GET" request to "/available_journeys.jsonld"
        Then the response status code should be 200
        And the response should be in JSON
        And the header "Content-Type" should be equal to "application/ld+json; charset=utf-8"
        And the JSON should be equal to:
        """
        {
          "@context": "\/contexts\/AvailableJourney",
          "@id": "\/available_journeys",
          "@type": "hydra:Collection",
          "hydra:member": [
              {
                  "@id": "\/available_journeys\/1",
                  "@type": "AvailableJourney",
                  "id": 1,
                  "fromDestination": "\/destinations\/4",
                  "toDestination": "\/destinations\/3",
                  "flyPrices": 496,
                  "flyTime": 681,
                  "busPrices": null,
                  "busTime": null,
                  "trainPrices": null,
                  "trainTime": null
              },
              {
                  "@id": "\/available_journeys\/3",
                  "@type": "AvailableJourney",
                  "id": 3,
                  "fromDestination": "\/destinations\/4",
                  "toDestination": "\/destinations\/1",
                  "flyPrices": 114,
                  "flyTime": 311,
                  "busPrices": 52,
                  "busTime": 616,
                  "trainPrices": 235,
                  "trainTime": 201
              },
              {
                  "@id": "\/available_journeys\/4",
                  "@type": "AvailableJourney",
                  "id": 4,
                  "fromDestination": "\/destinations\/3",
                  "toDestination": "\/destinations\/4",
                  "flyPrices": 493,
                  "flyTime": 638,
                  "busPrices": null,
                  "busTime": null,
                  "trainPrices": null,
                  "trainTime": null
              },
              {
                  "@id": "\/available_journeys\/6",
                  "@type": "AvailableJourney",
                  "id": 6,
                  "fromDestination": "\/destinations\/3",
                  "toDestination": "\/destinations\/1",
                  "flyPrices": 469,
                  "flyTime": 622,
                  "busPrices": null,
                  "busTime": null,
                  "trainPrices": null,
                  "trainTime": null
              },
              {
                  "@id": "\/available_journeys\/10",
                  "@type": "AvailableJourney",
                  "id": 10,
                  "fromDestination": "\/destinations\/1",
                  "toDestination": "\/destinations\/4",
                  "flyPrices": 111,
                  "flyTime": 319,
                  "busPrices": 47,
                  "busTime": 587,
                  "trainPrices": 235,
                  "trainTime": 205
              },
              {
                  "@id": "\/available_journeys\/11",
                  "@type": "AvailableJourney",
                  "id": 11,
                  "fromDestination": "\/destinations\/1",
                  "toDestination": "\/destinations\/3",
                  "flyPrices": 469,
                  "flyTime": 725,
                  "busPrices": null,
                  "busTime": null,
                  "trainPrices": null,
                  "trainTime": null
              }
          ],
          "hydra:totalItems": 6,
          "hydra:search": {
              "@type": "hydra:IriTemplate",
              "hydra:template": "\/available_journeys.jsonld{?fromDestination,fromDestination[],toDestination,toDestination[]}",
              "hydra:variableRepresentation": "BasicRepresentation",
              "hydra:mapping": [
                  {
                      "@type": "IriTemplateMapping",
                      "variable": "fromDestination",
                      "property": "fromDestination",
                      "required": false
                  },
                  {
                      "@type": "IriTemplateMapping",
                      "variable": "fromDestination[]",
                      "property": "fromDestination",
                      "required": false
                  },
                  {
                      "@type": "IriTemplateMapping",
                      "variable": "toDestination",
                      "property": "toDestination",
                      "required": false
                  },
                  {
                      "@type": "IriTemplateMapping",
                      "variable": "toDestination[]",
                      "property": "toDestination",
                      "required": false
                  }
              ]
          }
        }
        """


        When I send a "GET" request to "/available_journeys.jsonld?fromDestination=/destinations/4&toDestination=/destinations/1"
        Then the response status code should be 200
        And the response should be in JSON
        And the header "Content-Type" should be equal to "application/ld+json; charset=utf-8"
        And the JSON should be equal to:
        """
        {
            "@context": "\/contexts\/AvailableJourney",
            "@id": "\/available_journeys",
            "@type": "hydra:Collection",
            "hydra:member": [
                {
                    "@id": "\/available_journeys\/3",
                    "@type": "AvailableJourney",
                    "id": 3,
                    "fromDestination": "\/destinations\/4",
                    "toDestination": "\/destinations\/1",
                    "flyPrices": 114,
                    "flyTime": 311,
                    "busPrices": 52,
                    "busTime": 616,
                    "trainPrices": 235,
                    "trainTime": 201
                }
            ],
            "hydra:totalItems": 1,
            "hydra:view": {
                "@id": "\/available_journeys.jsonld?fromDestination=%2Fdestinations%2F4&toDestination=%2Fdestinations%2F1",
                "@type": "hydra:PartialCollectionView"
            },
            "hydra:search": {
                "@type": "hydra:IriTemplate",
                "hydra:template": "\/available_journeys.jsonld{?fromDestination,fromDestination[],toDestination,toDestination[]}",
                "hydra:variableRepresentation": "BasicRepresentation",
                "hydra:mapping": [
                    {
                        "@type": "IriTemplateMapping",
                        "variable": "fromDestination",
                        "property": "fromDestination",
                        "required": false
                    },
                    {
                        "@type": "IriTemplateMapping",
                        "variable": "fromDestination[]",
                        "property": "fromDestination",
                        "required": false
                    },
                    {
                        "@type": "IriTemplateMapping",
                        "variable": "toDestination",
                        "property": "toDestination",
                        "required": false
                    },
                    {
                        "@type": "IriTemplateMapping",
                        "variable": "toDestination[]",
                        "property": "toDestination",
                        "required": false
                    }
                ]
            }
        }
        """


    @emptyDatabase
    Scenario: Mettre à jour les voyages après l'ajout d'un trajets
        Given entities "AppBundle\Entity\Currency" :
            | name              | code |
            | Euro              | EUR  |
        Given entities "AppBundle\Entity\Country" :
            | name      | capitalName | codeAlpha3 | AppBundle\Entity\Currency:code | visaInformation | visaDuration | priceAccommodation | priceLifeCost |
            | France    | Paris       | FRA        | EUR                            | Visa gratuit    | 90 jours     |                    |               |
        Given entities "AppBundle\Entity\Destination" :
            | name      | AppBundle\Entity\Country:name | latitude   | longitude  | priceAccommodation | priceLifeCost |
            | Paris     | France                        | 48.864592  | 2.336492   | 30                 | 20            |
            | Lyon      | France                        | 45.756573  | 4.818846   | 15                 | 10            |
            | Marseille | France                        | 43.288654  | 5.354511   | 20                 | 20            |
            | Sens      | France                        | 42.288654  | 5.654511   | 12                 | 15            |
        Given entities "AppBundle\Entity\AvailableJourney" :
            | fromDestination:AppBundle\Entity\Destination:name | toDestination:AppBundle\Entity\Destination:name | flyPrices | flyTime | trainPrices | trainTime | busPrices | busTime |
            | Lyon                                              | Marseille                                       | 1         | 2       | 3           | 4         | 5         | 6       |
        Given les utilisateurs :
            | nom | mot de passe | email       | role      |
            | gui | gui          | gui@gui.gui | ROLE_USER |
        Given entities "AppBundle\Entity\Voyage" :
            | name | startDate(\DateTime) | startDestination:AppBundle\Entity\Destination:name | AppBundle\Entity\User:username | token  |
            | TDM  | 2017-01-20           | Paris                                              | gui                            | TOKEN1 |
        Given entities "AppBundle\Entity\Stage" :
            | AppBundle\Entity\Voyage:name | AppBundle\Entity\Destination:name | AppBundle\Entity\Country:name | nbDays | position | AppBundle\Entity\AvailableJourney:id | transportType |
            | TDM                          | Lyon                              |                               | 1      | 0        | 1                                    | TRAIN         |
            | TDM                          | Marseille                         |                               | 2      | 1        |                                      |               |
            | TDM                          | Sens                              |                               | 3      | 2        |                                      |               |

        Given entities "AppBundle\Entity\AvailableJourney" :
            | fromDestination:AppBundle\Entity\Destination:name | toDestination:AppBundle\Entity\Destination:name | flyPrices | flyTime | trainPrices | trainTime | busPrices | busTime |
            | Marseille                                         | Sens                                            | 11        | 12      | 13          | 14        | 15        | 16      |
            | Paris                                             | Lyon                                            | 21        | 22      | 23          | 24        | 25        | 26      |

        When je met à jour les voyages avec les trajets disponibles

        Given I add "Content-Type" header equal to "application/json"
        Given I authenticate the user "gui"

        When I send a "GET" request to "/voyages/1.jsonld"
        Then the response status code should be 200
        And the response should be in JSON
        And the header "Content-Type" should be equal to "application/ld+json; charset=utf-8"
        And the JSON node "@id" should be equal to "/voyages/1"
        And the JSON node "transportType" should be equal to "FLY"
        And the JSON node "availableJourney" should be equal to "/available_journeys/3"

        When I send a "GET" request to "/stages/1.jsonld"
        Then the response status code should be 200
        And the JSON node "transportType" should be equal to "TRAIN"
        And the JSON node "availableJourney" should be equal to "/available_journeys/1"

        When I send a "GET" request to "/stages/2.jsonld"
        Then the response status code should be 200
        And the JSON node "transportType" should be equal to "FLY"
        And the JSON node "availableJourney" should be equal to "/available_journeys/2"

        When I send a "GET" request to "/stages/3.jsonld"
        Then the response status code should be 200
        And the JSON node "transportType" should be null
        And the JSON node "availableJourney" should be null


        When je supprime les transports liés à la destination "Lyon"

        When I send a "GET" request to "/voyages/1.jsonld"
        Then the response status code should be 200
        And the response should be in JSON
        And the header "Content-Type" should be equal to "application/ld+json; charset=utf-8"
        And the JSON node "@id" should be equal to "/voyages/1"
        And the JSON node "transportType" should be null
        And the JSON node "availableJourney" should be null

        When I send a "GET" request to "/stages/1.jsonld"
        Then the response status code should be 200
        And the JSON node "transportType" should be null
        And the JSON node "availableJourney" should be null

        When I send a "GET" request to "/stages/2.jsonld"
        Then the response status code should be 200
        And the JSON node "transportType" should be equal to "FLY"
        And the JSON node "availableJourney" should be equal to "/available_journeys/2"

        When I send a "GET" request to "/stages/3.jsonld"
        Then the response status code should be 200
        And the JSON node "transportType" should be null
        And the JSON node "availableJourney" should be null



