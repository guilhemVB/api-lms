Feature: CRUD Destinations

    Scenario: Check unknown routes POST - PUT - DELETE
        When I send a "POST" request to "/destinations.jsonld"
        Then the response status code should be 405
        When I send a "PUT" request to "/destinations/1.jsonld"
        Then the response status code should be 405
        When I send a "DELETE" request to "/destinations/1.jsonld"
        Then the response status code should be 405

    @emptyDatabase
    Scenario: GET Destinations
        Given entities "AppBundle\Entity\Currency" :
            | name              | code |
            | Euro              | EUR  |
        Given entities "AppBundle\Entity\Country" :
            | name   | capitalName | codeAlpha3 | AppBundle\Entity\Currency:code | visaInformation | visaDuration | priceAccommodation | priceLifeCost |
            | France | Paris       | FRA        | EUR                            | Visa gratuit    | 90 jours     |                    |               |
        Given entities "AppBundle\Entity\Destination" :
            | name  | AppBundle\Entity\Country:name | latitude   | longitude  | priceAccommodation | priceLifeCost | periodJanuary | periodFebruary |
            | Paris | France                        | 48.864592  | 2.336492   | 30                 | 20            | 1             | 2              |
            | Lyon  | France                        | 38.864592  | 1.336492   | 25                 | 15            | 1             | 3              |

        When I send a "GET" request to "/destinations.jsonld"
        Then the response status code should be 200
        And the response should be in JSON
        And the header "Content-Type" should be equal to "application/ld+json; charset=utf-8"
        And the JSON should be equal to:
        """
        {
            "@context": "\/contexts\/Destination",
            "@id": "\/destinations",
            "@type": "hydra:Collection",
            "hydra:member": [
                {
                    "@id": "\/destinations\/1",
                    "@type": "Destination",
                    "id": 1,
                    "name": "Paris",
                    "slug": "paris",
                    "description": null,
                    "tips": null,
                    "country": {
                        "@id": "\/countries\/1",
                        "@type": "Country",
                        "id": 1,
                        "name": "France",
                        "slug": "france",
                        "priceAccommodation": null,
                        "priceLifeCost": null,
                        "longitude": null,
                        "latitude": null
                    },
                    "priceAccommodation": 30,
                    "priceLifeCost": 20,
                    "periodJanuary": 1,
                    "periodFebruary": 2,
                    "periodMarch": null,
                    "periodApril": null,
                    "periodMay": null,
                    "periodJune": null,
                    "periodJuly": null,
                    "periodAugust": null,
                    "periodSeptember": null,
                    "periodOctober": null,
                    "periodNovember": null,
                    "periodDecember": null,
                    "longitude": 2.336492,
                    "latitude": 48.864592
                },{
                    "@id": "\/destinations\/2",
                    "@type": "Destination",
                    "id": 2,
                    "name": "Lyon",
                    "slug": "lyon",
                    "description": null,
                    "tips": null,
                    "country": {
                        "@id": "\/countries\/1",
                        "@type": "Country",
                        "id": 1,
                        "name": "France",
                        "slug": "france",
                        "priceAccommodation": null,
                        "priceLifeCost": null,
                        "longitude": null,
                        "latitude": null
                    },
                    "priceAccommodation": 25,
                    "priceLifeCost": 15,
                    "periodJanuary": 1,
                    "periodFebruary": 3,
                    "periodMarch": null,
                    "periodApril": null,
                    "periodMay": null,
                    "periodJune": null,
                    "periodJuly": null,
                    "periodAugust": null,
                    "periodSeptember": null,
                    "periodOctober": null,
                    "periodNovember": null,
                    "periodDecember": null,
                    "longitude": 1.336492,
                    "latitude": 38.864592
                }
            ],
            "hydra:totalItems": 2,
            "hydra:search": {
                "@type": "hydra:IriTemplate",
                "hydra:template": "\/destinations.jsonld{?slug,slug[],country.slug,country.slug[]}",
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
                    },
                    {
                        "@type": "IriTemplateMapping",
                        "variable": "country.slug",
                        "property": "country.slug",
                        "required": false
                    },
                    {
                        "@type": "IriTemplateMapping",
                        "variable": "country.slug[]",
                        "property": "country.slug",
                        "required": false
                    }
                ]
            }
        }
        """

        When I send a "GET" request to "/destinations.jsonld?country.slug=france"
        Then the response status code should be 200
        And the response should be in JSON
        And the header "Content-Type" should be equal to "application/ld+json; charset=utf-8"
        And the JSON should be equal to:
        """
        {
            "@context": "\/contexts\/Destination",
            "@id": "\/destinations",
            "@type": "hydra:Collection",
            "hydra:member": [
                {
                    "@id": "\/destinations\/1",
                    "@type": "Destination",
                    "id": 1,
                    "name": "Paris",
                    "slug": "paris",
                    "description": null,
                    "tips": null,
                    "country": {
                        "@id": "\/countries\/1",
                        "@type": "Country",
                        "id": 1,
                        "name": "France",
                        "slug": "france",
                        "priceAccommodation": null,
                        "priceLifeCost": null,
                        "longitude": null,
                        "latitude": null
                    },
                    "priceAccommodation": 30,
                    "priceLifeCost": 20,
                    "periodJanuary": 1,
                    "periodFebruary": 2,
                    "periodMarch": null,
                    "periodApril": null,
                    "periodMay": null,
                    "periodJune": null,
                    "periodJuly": null,
                    "periodAugust": null,
                    "periodSeptember": null,
                    "periodOctober": null,
                    "periodNovember": null,
                    "periodDecember": null,
                    "longitude": 2.336492,
                    "latitude": 48.864592
                },{
                    "@id": "\/destinations\/2",
                    "@type": "Destination",
                    "id": 2,
                    "name": "Lyon",
                    "slug": "lyon",
                    "description": null,
                    "tips": null,
                    "country": {
                        "@id": "\/countries\/1",
                        "@type": "Country",
                        "id": 1,
                        "name": "France",
                        "slug": "france",
                        "priceAccommodation": null,
                        "priceLifeCost": null,
                        "longitude": null,
                        "latitude": null
                    },
                    "priceAccommodation": 25,
                    "priceLifeCost": 15,
                    "periodJanuary": 1,
                    "periodFebruary": 3,
                    "periodMarch": null,
                    "periodApril": null,
                    "periodMay": null,
                    "periodJune": null,
                    "periodJuly": null,
                    "periodAugust": null,
                    "periodSeptember": null,
                    "periodOctober": null,
                    "periodNovember": null,
                    "periodDecember": null,
                    "longitude": 1.336492,
                    "latitude": 38.864592
                }
            ],
            "hydra:totalItems": 2,
            "hydra:view": {
                "@id": "/destinations.jsonld?country.slug=france",
                "@type": "hydra:PartialCollectionView"
            },
            "hydra:search": {
                "@type": "hydra:IriTemplate",
                "hydra:template": "\/destinations.jsonld{?slug,slug[],country.slug,country.slug[]}",
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
                    },
                    {
                        "@type": "IriTemplateMapping",
                        "variable": "country.slug",
                        "property": "country.slug",
                        "required": false
                    },
                    {
                        "@type": "IriTemplateMapping",
                        "variable": "country.slug[]",
                        "property": "country.slug",
                        "required": false
                    }
                ]
            }
        }
        """



