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
                  "fromDestination": {
                      "@id": "\/destinations\/4",
                      "@type": "Destination",
                      "id": 4,
                      "name": "Londres",
                      "slug": "londres"
                  },
                  "toDestination": {
                      "@id": "\/destinations\/3",
                      "@type": "Destination",
                      "id": 3,
                      "name": "New-York",
                      "slug": "new-york"
                  },
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
                  "fromDestination": {
                      "@id": "\/destinations\/4",
                      "@type": "Destination",
                      "id": 4,
                      "name": "Londres",
                      "slug": "londres"
                  },
                  "toDestination": {
                      "@id": "\/destinations\/1",
                      "@type": "Destination",
                      "id": 1,
                      "name": "Paris",
                      "slug": "paris"
                  },
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
                  "fromDestination": {
                      "@id": "\/destinations\/3",
                      "@type": "Destination",
                      "id": 3,
                      "name": "New-York",
                      "slug": "new-york"
                  },
                  "toDestination": {
                      "@id": "\/destinations\/4",
                      "@type": "Destination",
                      "id": 4,
                      "name": "Londres",
                      "slug": "londres"
                  },
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
                  "fromDestination": {
                      "@id": "\/destinations\/3",
                      "@type": "Destination",
                      "id": 3,
                      "name": "New-York",
                      "slug": "new-york"
                  },
                  "toDestination": {
                      "@id": "\/destinations\/1",
                      "@type": "Destination",
                      "id": 1,
                      "name": "Paris",
                      "slug": "paris"
                  },
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
                  "fromDestination": {
                      "@id": "\/destinations\/1",
                      "@type": "Destination",
                      "id": 1,
                      "name": "Paris",
                      "slug": "paris"
                  },
                  "toDestination": {
                      "@id": "\/destinations\/4",
                      "@type": "Destination",
                      "id": 4,
                      "name": "Londres",
                      "slug": "londres"
                  },
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
                  "fromDestination": {
                      "@id": "\/destinations\/1",
                      "@type": "Destination",
                      "id": 1,
                      "name": "Paris",
                      "slug": "paris"
                  },
                  "toDestination": {
                      "@id": "\/destinations\/3",
                      "@type": "Destination",
                      "id": 3,
                      "name": "New-York",
                      "slug": "new-york"
                  },
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
                  "fromDestination": {
                      "@id": "\/destinations\/4",
                      "@type": "Destination",
                      "id": 4,
                      "name": "Londres",
                      "slug": "londres"
                  },
                  "toDestination": {
                      "@id": "\/destinations\/1",
                      "@type": "Destination",
                      "id": 1,
                      "name": "Paris",
                      "slug": "paris"
                  },
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



    @skip
    Scenario: Mettre à jour les voyages après l'ajout d'un trajets
        Given entity "AppBundle\Entity\Currency" :
            | name | code |
            | Euro | EUR  |
        Given entity "AppBundle\Entity\Country" :
            | name   | capitalName | codeAlpha3 | AppBundle\Entity\Currency:code | visaInformation | visaDuration |
            | France | Paris       | FRA        | EUR                            | Visa gratuit    | 90 jours     |
        Given entities "AppBundle\Entity\Destination" :
            | name      | AppBundle\Entity\Country:name | latitude    | longitude  |
            | Paris     | France                        | 2.2946583   | 48.8580101 |
            | Lyon      | France                        | 4.8492387   | 45.7635056 |
            | Marseille | France                        | -73.9862683 | 40.7590453 |
            | Dijon     | France                        | -0.0775694  | 51.5082493 |
        Given entities "AppBundle\Entity\AvailableJourney" :
            | fromDestination:AppBundle\Entity\Destination:name | toDestination:AppBundle\Entity\Destination:name | flyPrices | flyTime | trainPrices | trainTime | busPrices | busTime |
            | Lyon                                              | Marseille                                       | 207       | 211     | 66          | 212       | 24        | 280     |
        Given les utilisateurs :
            | nom     |
            | guilhem |
        When l'utilisateur "guilhem" crée les voyages suivants :
            | nom | date de départ | destination de départ |
            | TDM | 01/01/2015     | Paris                 |
        When j'ajoute les étapes suivantes au voyage "TDM" :
            | destination | nombre de jour |
            | Lyon        | 1              |
            | Marseille   | 1              |
            | Dijon       | 1              |
        Then il existe les transports suivants au voyage "TDM" :
            | depuis | jusqu'à   | type de transport |
            | Lyon   | Marseille | BUS               |
        Given entities "AppBundle\Entity\AvailableJourney" :
            | fromDestination:AppBundle\Entity\Destination:name | toDestination:AppBundle\Entity\Destination:name | flyPrices | flyTime | trainPrices | trainTime | busPrices | busTime |
            | Paris                                             | Lyon                                            | 52        | 56      | 50          | 120       | 5         | 630     |
            | Marseille                                         | Dijon                                           | 52        | 56      | 50          | 120       | 5         | 630     |
        When je met à jour les voyages avec les trajets disponibles
        Then il existe les transports suivants au voyage "TDM" :
            | depuis    | jusqu'à   | type de transport |
            | Paris     | Lyon      | BUS               |
            | Lyon      | Marseille | BUS               |
            | Marseille | Dijon     | BUS               |
        When je supprime les transports liés à la destination "Dijon"
        Then les possibilitées de transports sont :
            | depuis | jusqu'à   | prix avion | temps avion | prix train | temps train | prix bus | temps bus |
            | Paris  | Lyon      | 52         | 56          | 50         | 120         | 5        | 630       |
            | Lyon   | Marseille | 207        | 211         | 66         | 212         | 24       | 280       |
        Then il existe les transports suivants au voyage "TDM" :
            | depuis | jusqu'à   | type de transport |
            | Paris  | Lyon      | BUS               |
            | Lyon   | Marseille | BUS               |

    @skip
    Scenario: debug calcul trajet disponible
        When j'affiche le trajet trouvé entre "san-francisco" et "yosemite-park"
