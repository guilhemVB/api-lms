Feature: CRUD Countries

    Scenario: Check unknown routes POST - PUT - DELETE
        When I send a "POST" request to "/countries.jsonld"
        Then the response status code should be 405
        When I send a "PUT" request to "/countries/1.jsonld"
        Then the response status code should be 405
        When I send a "DELETE" request to "/countries/1.jsonld"
        Then the response status code should be 405

    @emptyDatabase
    Scenario: GET Countries
        Given entities "AppBundle\Entity\Currency" :
            | name              | code |
            | Euro              | EUR  |
        Given entities "AppBundle\Entity\Country" :
            | name   | capitalName | codeAlpha3 | AppBundle\Entity\Currency:code | visaInformation | visaDuration | latitude   | longitude | priceAccommodation | priceLifeCost |
            | France | Paris       | FRA        | EUR                            | Visa gratuit    | 90 jours     | 45.864592  | 5.336492  | 28                 | 18            |
        Given entities "AppBundle\Entity\Destination" :
            | name  | AppBundle\Entity\Country:name | latitude   | longitude  | priceAccommodation | priceLifeCost | periodJanuary | periodFebruary |
            | Paris | France                        | 48.864592  | 2.336492   | 30                 | 20            | 1             | 2              |
            | Lyon  | France                        | 38.864592  | 1.336492   | 25                 | 15            | 1             | 3              |
        Given les destinations par défaut :
            | pays      | destination |
            | France    | Paris       |

        When I send a "GET" request to "/countries.jsonld"
        Then the response status code should be 200
        And the response should be in JSON
        And the header "Content-Type" should be equal to "application/ld+json; charset=utf-8"
        And the JSON should be equal to:
        """
        {
            "@context": "\/contexts\/Country",
            "@id": "\/countries",
            "@type": "hydra:Collection",
            "hydra:member": [
                {
                    "@id": "\/countries\/1",
                    "@type": "Country",
                    "id": 1,
                    "codeAlpha2": null,
                    "codeAlpha3": "FRA",
                    "name": "France",
                    "slug": "france",
                    "capitalName": "Paris",
                    "defaultDestination": {
                          "@id": "\/destinations\/1",
                          "@type": "Destination",
                          "id": 1,
                          "name": "Paris",
                          "slug": "paris",
                          "priceAccommodation": 30,
                          "priceLifeCost": 20,
                          "longitude": 2.336492,
                          "latitude": 48.864592
                        },
                    "visaInformation": "Visa gratuit",
                    "visaDuration": "90 jours",
                    "languages": null,
                    "population": null,
                    "destinations": [
                        {
                          "@id": "\/destinations\/1",
                          "@type": "Destination",
                          "id": 1,
                          "name": "Paris",
                          "slug": "paris",
                          "priceAccommodation": 30,
                          "priceLifeCost": 20,
                          "longitude": 2.336492,
                          "latitude": 48.864592
                        },{
                          "@id": "\/destinations\/2",
                          "@type": "Destination",
                          "id": 2,
                          "name": "Lyon",
                          "slug": "lyon",
                          "priceAccommodation": 25,
                          "priceLifeCost": 15,
                          "longitude": 1.336492,
                          "latitude": 38.864592
                        }
                    ],
                    "currency":  {
                        "name": "Euro",
                        "eurRate": null,
                        "code": "EUR"
                    },
                    "priceAccommodation": 28,
                    "priceLifeCost": 18,
                    "longitude": 5.336492,
                    "latitude": 45.864592
                }
            ],
            "hydra:totalItems": 1,
            "hydra:search": {
                "@type": "hydra:IriTemplate",
                "hydra:template": "\/countries.jsonld{?slug,slug[]}",
                "hydra:variableRepresentation": "BasicRepresentation",
                "hydra:mapping": [
                    {
                        "@type": "IriTemplateMapping",
                        "variable": "slug",
                        "property": "slug",
                        "required": false
                    },
                    {
                        "@type": "IriTemplateMapping",
                        "variable": "slug[]",
                        "property": "slug",
                        "required": false
                    }
                ]
            }
        }
        """
        When I send a "GET" request to "/countries/1.jsonld"
        Then the response status code should be 200
        And the response should be in JSON
        And the header "Content-Type" should be equal to "application/ld+json; charset=utf-8"
        And the JSON should be equal to:
        """
        {
            "@context": "\/contexts\/Country",
            "@id": "\/countries\/1",
            "@type": "Country",
            "id": 1,
            "codeAlpha2": null,
            "codeAlpha3": "FRA",
            "name": "France",
            "slug": "france",
            "capitalName": "Paris",
            "defaultDestination": {
                  "@id": "\/destinations\/1",
                  "@type": "Destination",
                  "id": 1,
                  "name": "Paris",
                  "slug": "paris",
                  "priceAccommodation": 30,
                  "priceLifeCost": 20,
                  "longitude": 2.336492,
                  "latitude": 48.864592
                },
            "visaInformation": "Visa gratuit",
            "visaDuration": "90 jours",
            "languages": null,
            "population": null,
            "destinations": [
                {
                  "@id": "\/destinations\/1",
                  "@type": "Destination",
                  "id": 1,
                  "name": "Paris",
                  "slug": "paris",
                  "priceAccommodation": 30,
                  "priceLifeCost": 20,
                  "longitude": 2.336492,
                  "latitude": 48.864592
                },{
                  "@id": "\/destinations\/2",
                  "@type": "Destination",
                  "id": 2,
                  "name": "Lyon",
                  "slug": "lyon",
                  "priceAccommodation": 25,
                  "priceLifeCost": 15,
                  "longitude": 1.336492,
                  "latitude": 38.864592
                }
            ],
            "currency":  {
                "name": "Euro",
                "eurRate": null,
                "code": "EUR"
            },
            "priceAccommodation": 28,
            "priceLifeCost": 18,
            "longitude": 5.336492,
            "latitude": 45.864592
        }
        """

        When I send a "GET" request to "/countries.jsonld?slug=france"
        Then the response status code should be 200
        And the response should be in JSON
        And the header "Content-Type" should be equal to "application/ld+json; charset=utf-8"
        And the JSON should be equal to:
        """
        {
            "@context": "\/contexts\/Country",
            "@id": "\/countries",
            "@type": "hydra:Collection",
            "hydra:member": [
                {
                    "@id": "\/countries\/1",
                    "@type": "Country",
                    "id": 1,
                    "codeAlpha2": null,
                    "codeAlpha3": "FRA",
                    "name": "France",
                    "slug": "france",
                    "capitalName": "Paris",
                    "defaultDestination": {
                          "@id": "\/destinations\/1",
                          "@type": "Destination",
                          "id": 1,
                          "name": "Paris",
                          "slug": "paris",
                          "priceAccommodation": 30,
                          "priceLifeCost": 20,
                          "longitude": 2.336492,
                          "latitude": 48.864592
                        },
                    "visaInformation": "Visa gratuit",
                    "visaDuration": "90 jours",
                    "languages": null,
                    "population": null,
                    "destinations": [
                        {
                          "@id": "\/destinations\/1",
                          "@type": "Destination",
                          "id": 1,
                          "name": "Paris",
                          "slug": "paris",
                          "priceAccommodation": 30,
                          "priceLifeCost": 20,
                          "longitude": 2.336492,
                          "latitude": 48.864592
                        },{
                          "@id": "\/destinations\/2",
                          "@type": "Destination",
                          "id": 2,
                          "name": "Lyon",
                          "slug": "lyon",
                          "priceAccommodation": 25,
                          "priceLifeCost": 15,
                          "longitude": 1.336492,
                          "latitude": 38.864592
                        }
                    ],
                    "currency":  {
                        "name": "Euro",
                        "eurRate": null,
                        "code": "EUR"
                    },
                    "priceAccommodation": 28,
                    "priceLifeCost": 18,
                    "longitude": 5.336492,
                    "latitude": 45.864592
                }
            ],
            "hydra:totalItems": 1,
            "hydra:view": {
                "@id": "\/countries.jsonld?slug=france",
                "@type": "hydra:PartialCollectionView"
            },
            "hydra:search": {
                "@type": "hydra:IriTemplate",
                "hydra:template": "\/countries.jsonld{?slug,slug[]}",
                "hydra:variableRepresentation": "BasicRepresentation",
                "hydra:mapping": [
                    {
                        "@type": "IriTemplateMapping",
                        "variable": "slug",
                        "property": "slug",
                        "required": false
                    },
                    {
                        "@type": "IriTemplateMapping",
                        "variable": "slug[]",
                        "property": "slug",
                        "required": false
                    }
                ]
            }
        }
        """

