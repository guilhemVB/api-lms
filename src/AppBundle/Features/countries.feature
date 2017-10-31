Feature: CRUD Countries

    Scenario: Check unknown routes
        When I send a "POST" request to "/countries.jsonld"
        Then the response status code should be 405
        When I send a "PUT" request to "/countries/1.jsonld"
        Then the response status code should be 405
        When I send a "DELETE" request to "/countries/1.jsonld"
        Then the response status code should be 405

    @emptyDatabase
    Scenario: CRUD Destinations
        Given entities "AppBundle\Entity\Currency" :
            | name              | code |
            | Euro              | EUR  |
        Given entities "AppBundle\Entity\Country" :
            | name      | capitalName | codeAlpha3 | AppBundle\Entity\Currency:code | visaInformation | visaDuration | priceAccommodation | priceLifeCost |
            | France    | Paris       | FRA        | EUR                            | Visa gratuit    | 90 jours     |                    |               |

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
                    "defaultDestination": null,
                    "visaInformation": "Visa gratuit",
                    "visaDuration": "90 jours",
                    "languages": null,
                    "population": null,
                    "destinations": [],
                    "currency": [],
                    "priceAccommodation": null,
                    "priceLifeCost": null,
                    "longitude": null,
                    "latitude": null
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

        When I send a "POST" request to "/countries.jsonld" with body:
        """
        {
            "name" : "Espagne",
            "codeAlpha3": "ESP"
        }
        """
        Then the response status code should be 405

        When I send a "DELETE" request to "/countries/1.jsonld"
        Then the response status code should be 405