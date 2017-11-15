Feature: Stages

    Scenario: Check authentication
        When I send a "GET" request to "/stages.jsonld"
        Then the response status code should be 401
        When I send a "POST" request to "/stages.jsonld"
        Then the response status code should be 401
        When I send a "PUT" request to "/stages/1.jsonld"
        Then the response status code should be 401
        When I send a "DELETE" request to "/stages/1.jsonld"
        Then the response status code should be 401

    @emptyDatabase
    Scenario: Add Stage
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
        Given les destinations par défaut :
            | pays      | destination |
            | France    | Paris       |
        Given entities "AppBundle\Entity\AvailableJourney" :
            | fromDestination:AppBundle\Entity\Destination:name | toDestination:AppBundle\Entity\Destination:name | flyPrices | flyTime | trainPrices | trainTime | busPrices | busTime |
            | Paris                                             | Lyon                                            | 52        | 56      | 50          | 120       | 5         | 390     |
            | Lyon                                              | Marseille                                       | 207       | 211     | 66          | 212       | 24        | 280     |
            | Paris                                             | Sens                                            |           |         | 20          | 56        | 5         | 120     |
            | Sens                                              | Marseille                                       |           |         | 98          | 320       | 56        | 612     |
        Given les utilisateurs :
            | nom | mot de passe | email       | role      |
            | gui | gui          | gui@gui.gui | ROLE_USER |
            | usr | usr          | usr@usr.usr | ROLE_USER |
        Given entities "AppBundle\Entity\Voyage" :
            | name | startDate(\DateTime) | startDestination:AppBundle\Entity\Destination:name | AppBundle\Entity\User:username | token  |
            | TDM  | 2017-01-20           | Paris                                              | gui                            | TOKEN1 |
            | TDM  | 2017-01-20           | Marseille                                          | usr                            | TOKEN2 |

        Given I add "Content-Type" header equal to "application/json"
        Given I authenticate the user "gui"


##################################
#        POST error nbDays
##################################

        When I send a "POST" request to "/stages.jsonld" with body:
        """
        {
            "voyage": "/voyages/1",
            "destination": "/destinations/1",
            "position": 0
        }
        """
        Then the response status code should be 400


##################################
#        POST error position
##################################

        When I send a "POST" request to "/stages.jsonld" with body:
        """
        {
            "voyage": "/voyages/1",
            "nbDays":3,
            "destination": "/destinations/1"
        }
        """
        Then the response status code should be 400


##################################
#        POST error voyage
##################################

        When I send a "POST" request to "/stages.jsonld" with body:
        """
        {
            "nbDays":3,
            "destination": "/destinations/1",
            "position": 0
        }
        """
        Then the response status code should be 400


#############################################
#        POST error country AND destination
#############################################

        When I send a "POST" request to "/stages.jsonld" with body:
        """
        {
            "voyage": "/voyages/1",
            "nbDays":3,
            "destination": "/destinations/1",
            "country": "/countries/1",
            "position": 0
        }
        """
        Then the response status code should be 400


#######################################
#        POST error voyage other user
#######################################

        When I send a "POST" request to "/stages.jsonld" with body:
        """
        {
            "voyage": "/voyages/2",
            "nbDays":3,
            "destination": "/destinations/1"
            "position": 0
        }
        """
        Then the response status code should be 400


##############################
#        POST OK
##############################

        When I send a "POST" request to "/stages.jsonld" with body:
        """
        {
            "voyage": "/voyages/1",
            "nbDays":3,
            "destination": "/destinations/2",
            "position": 0
        }
        """
        Then the response status code should be 201
        And the response should be in JSON
        And the header "Content-Type" should be equal to "application/ld+json; charset=utf-8"
        And the JSON should be equal to:
        """
        {
            "@context": "\/contexts\/Stage",
            "@id": "\/stages\/1",
            "@type": "Stage",
            "id": 1,
            "nbDays": 3,
            "destination": {
                "@id": "\/destinations\/2",
                "@type": "Destination",
                "id": 2,
                "name": "Lyon",
                "slug": "lyon"
            },
            "country": null,
            "position": 0,
            "transportType": null,
            "availableJourney": null
        }
        """


##############################
#        GET OK
##############################

        When I send a "GET" request to "/stages/1.jsonld"
        Then the response status code should be 200
        And the response should be in JSON
        And the header "Content-Type" should be equal to "application/ld+json; charset=utf-8"
        And the JSON should be equal to:
        """
        {
            "@context": "\/contexts\/Stage",
            "@id": "\/stages\/1",
            "@type": "Stage",
            "id": 1,
            "nbDays": 3,
            "destination": {
                "@id": "\/destinations\/2",
                "@type": "Destination",
                "id": 2,
                "name": "Lyon",
                "slug": "lyon"
            },
            "country": null,
            "position": 0,
            "transportType": null,
            "availableJourney": null
        }
        """


############################################
#        POST OK
############################################

        When I send a "POST" request to "/stages.jsonld" with body:
        """
        {
            "voyage": "/voyages/1",
            "nbDays":2,
            "destination": "/destinations/3",
            "position": 1
        }
        """
        Then the response status code should be 201
        And the response should be in JSON
        And the header "Content-Type" should be equal to "application/ld+json; charset=utf-8"
        And the JSON should be equal to:
        """
        {
            "@context": "\/contexts\/Stage",
            "@id": "\/stages\/2",
            "@type": "Stage",
            "id": 2,
            "nbDays": 2,
            "destination": {
                "@id": "\/destinations\/3",
                "@type": "Destination",
                "id": 3,
                "name": "Marseille",
                "slug": "marseille"
            },
            "country": null,
            "position": 1,
            "transportType": null,
            "availableJourney": null
        }
        """


#########################################
#        PUT OK -> change destination
#########################################

        When I send a "PUT" request to "/stages/1.jsonld" with body:
        """
        {
            "voyage": "/voyages/1",
            "nbDays":1,
            "destination": "/destinations/4",
            "position": 0
        }
        """
        Then the response status code should be 200
        And the response should be in JSON
        And the header "Content-Type" should be equal to "application/ld+json; charset=utf-8"
        And the JSON should be equal to:
        """
        {
            "@context": "\/contexts\/Stage",
            "@id": "\/stages\/1",
            "@type": "Stage",
            "id": 1,
            "nbDays": 1,
            "destination": {
                "@id": "\/destinations\/4",
                "@type": "Destination",
                "id": 4,
                "name": "Sens",
                "slug": "sens"
            },
            "country": null,
            "position": 0,
            "transportType": "BUS",
            "availableJourney": {
                "@id": "\/available_journeys\/4",
                "@type": "AvailableJourney",
                "id": 4,
                "fromDestination": {
                    "@id": "\/destinations\/4",
                    "@type": "Destination",
                    "id": 4,
                    "name": "Sens",
                    "slug": "sens"
                },
                "toDestination": {
                    "@id": "\/destinations\/3",
                    "@type": "Destination",
                    "id": 3,
                    "name": "Marseille",
                    "slug": "marseille"
                },
                "flyPrices": null,
                "flyTime": null,
                "busPrices": 56,
                "busTime": 612,
                "trainPrices": 98,
                "trainTime": 320
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
            "name": "TDM",
            "token": "TOKEN1",
            "urlMinified": null,
            "showPricesInPublic": true,
            "startDate": "2017-01-20",
            "startDestination": {
                "@id": "\/destinations\/1",
                "@type": "Destination",
                "id": 1,
                "name": "Paris",
                "slug": "paris"
            },
            "stages": [
                {
                    "@id": "\/stages\/1",
                    "@type": "Stage",
                    "id": 1,
                    "nbDays": 1,
                    "destination": {
                        "@id": "\/destinations\/4",
                        "@type": "Destination",
                        "id": 4,
                        "name": "Sens",
                        "slug": "sens"
                    },
                    "country": null,
                    "position": 0,
                    "transportType": "BUS",
                    "availableJourney": {
                        "@id": "\/available_journeys\/4",
                        "@type": "AvailableJourney",
                        "id": 4,
                        "fromDestination": {
                            "@id": "\/destinations\/4",
                            "@type": "Destination",
                            "id": 4,
                            "name": "Sens",
                            "slug": "sens"
                        },
                        "toDestination": {
                            "@id": "\/destinations\/3",
                            "@type": "Destination",
                            "id": 3,
                            "name": "Marseille",
                            "slug": "marseille"
                        },
                        "flyPrices": null,
                        "flyTime": null,
                        "busPrices": 56,
                        "busTime": 612,
                        "trainPrices": 98,
                        "trainTime": 320
                    }
                },
                {
                    "@id": "\/stages\/2",
                    "@type": "Stage",
                    "id": 2,
                    "nbDays": 2,
                    "destination": {
                        "@id": "\/destinations\/3",
                        "@type": "Destination",
                        "id": 3,
                        "name": "Marseille",
                        "slug": "marseille"
                    },
                    "country": null,
                    "position": 1,
                    "transportType": null,
                    "availableJourney": null
                }
            ],
            "transportType": "BUS",
            "availableJourney": {
                "@id": "\/available_journeys\/3",
                "@type": "AvailableJourney",
                "id": 3,
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
                    "name": "Sens",
                    "slug": "sens"
                },
                "flyPrices": null,
                "flyTime": null,
                "busPrices": 5,
                "busTime": 120,
                "trainPrices": 20,
                "trainTime": 56
            }
        }
        """


    @emptyDatabase
    Scenario: DELETE Stage
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
        Given les destinations par défaut :
            | pays      | destination |
            | France    | Paris       |
        Given entities "AppBundle\Entity\AvailableJourney" :
            | fromDestination:AppBundle\Entity\Destination:name | toDestination:AppBundle\Entity\Destination:name | flyPrices | flyTime | trainPrices | trainTime | busPrices | busTime |
            | Paris                                             | Lyon                                            | 52        | 56      | 50          | 120       | 5         | 390     |
            | Lyon                                              | Marseille                                       | 207       | 211     | 66          | 212       | 24        | 280     |
            | Marseille                                         | Sens                                            |           |         | 20          | 56        | 5         | 120     |
            | Lyon                                              | Sens                                            |           |         | 98          | 320       | 56        | 612     |
        Given les utilisateurs :
            | nom | mot de passe | email       | role      |
            | gui | gui          | gui@gui.gui | ROLE_USER |
        Given entities "AppBundle\Entity\Voyage" :
            | name | startDate(\DateTime) | startDestination:AppBundle\Entity\Destination:name | AppBundle\Entity\User:username | token  |
            | TDM  | 2017-01-20           | Paris                                              | gui                            | TOKEN1 |

        Given I add "Content-Type" header equal to "application/json"
        Given I authenticate the user "gui"

        When I send a "POST" request to "/stages.jsonld" with body:
        """
        {
            "voyage": "/voyages/1",
            "nbDays":1,
            "destination": "/destinations/2",
            "position": 0
        }
        """
        Then the response status code should be 201

        When I send a "POST" request to "/stages.jsonld" with body:
        """
        {
            "voyage": "/voyages/1",
            "nbDays":2,
            "destination": "/destinations/3",
            "position": 1
        }
        """
        Then the response status code should be 201

        When I send a "POST" request to "/stages.jsonld" with body:
        """
        {
            "voyage": "/voyages/1",
            "nbDays":3,
            "destination": "/destinations/4",
            "position": 2
        }
        """
        Then the response status code should be 201

        When I send a "DELETE" request to "/stages/2.jsonld"
        Then the response status code should be 204

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
            "name": "TDM",
            "token": "TOKEN1",
            "urlMinified": null,
            "showPricesInPublic": true,
            "startDate": "2017-01-20",
            "startDestination": {
                "@id": "\/destinations\/1",
                "@type": "Destination",
                "id": 1,
                "name": "Paris",
                "slug": "paris"
            },
            "stages": [
                {
                    "@id": "\/stages\/1",
                    "@type": "Stage",
                    "id": 1,
                    "nbDays": 1,
                    "destination": {
                        "@id": "\/destinations\/2",
                        "@type": "Destination",
                        "id": 2,
                        "name": "Lyon",
                        "slug": "lyon"
                    },
                    "country": null,
                    "position": 0,
                    "transportType": "BUS",
                    "availableJourney": {
                        "@id": "\/available_journeys\/4",
                        "@type": "AvailableJourney",
                        "id": 4,
                        "fromDestination": {
                            "@id": "\/destinations\/2",
                            "@type": "Destination",
                            "id": 2,
                            "name": "Lyon",
                            "slug": "lyon"
                        },
                        "toDestination": {
                            "@id": "\/destinations\/4",
                            "@type": "Destination",
                            "id": 4,
                            "name": "Sens",
                            "slug": "sens"
                        },
                        "flyPrices": null,
                        "flyTime": null,
                        "busPrices": 56,
                        "busTime": 612,
                        "trainPrices": 98,
                        "trainTime": 320
                    }
                },
                {
                    "@id": "\/stages\/3",
                    "@type": "Stage",
                    "id": 3,
                    "nbDays": 3,
                    "destination": {
                        "@id": "\/destinations\/4",
                        "@type": "Destination",
                        "id": 4,
                        "name": "Sens",
                        "slug": "sens"
                    },
                    "country": null,
                    "position": 1,
                    "transportType": null,
                    "availableJourney": null
                }
            ],
            "transportType": "BUS",
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


    @emptyDatabase
    Scenario: Changer l'ordre des étapes -> de 1 à 0
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
        Given les destinations par défaut :
            | pays      | destination |
            | France    | Paris       |
        Given entities "AppBundle\Entity\AvailableJourney" :
            | fromDestination:AppBundle\Entity\Destination:name | toDestination:AppBundle\Entity\Destination:name | flyPrices | flyTime | trainPrices | trainTime | busPrices | busTime |
            | Paris                                             | Lyon                                            | 52        | 56      | 50          | 120       | 5         | 390     |
            | Lyon                                              | Marseille                                       | 207       | 211     | 66          | 212       | 24        | 280     |
            | Marseille                                         | Sens                                            |           |         | 20          | 56        | 5         | 120     |
            | Lyon                                              | Sens                                            |           |         | 98          | 320       | 56        | 612     |
            | Marseille                                         | Lyon                                            |           |         | 65          | 120       | 15        | 415     |
            | Paris                                             | Marseille                                       | 1         | 2       | 3           | 4         | 5         | 6       |
        Given les utilisateurs :
            | nom | mot de passe | email       | role      |
            | gui | gui          | gui@gui.gui | ROLE_USER |
        Given entities "AppBundle\Entity\Voyage" :
            | name | startDate(\DateTime) | startDestination:AppBundle\Entity\Destination:name | AppBundle\Entity\User:username | token  |
            | TDM  | 2017-01-20           | Paris                                              | gui                            | TOKEN1 |

        Given I add "Content-Type" header equal to "application/json"
        Given I authenticate the user "gui"

        When I send a "POST" request to "/stages.jsonld" with body:
        """
        {
            "voyage": "/voyages/1",
            "nbDays":1,
            "destination": "/destinations/2",
            "position": 0
        }
        """
        Then the response status code should be 201

        When I send a "POST" request to "/stages.jsonld" with body:
        """
        {
            "voyage": "/voyages/1",
            "nbDays":2,
            "destination": "/destinations/3",
            "position": 1
        }
        """
        Then the response status code should be 201

        When I send a "POST" request to "/stages.jsonld" with body:
        """
        {
            "voyage": "/voyages/1",
            "nbDays":3,
            "destination": "/destinations/4",
            "position": 2
        }
        """
        Then the response status code should be 201

        When I send a "PUT" request to "/stages/2.jsonld" with body:
        """
        {
            "voyage": "/voyages/1",
            "nbDays":2,
            "destination": "/destinations/3",
            "position": 0
        }
        """
        Then the response status code should be 200
        And the response should be in JSON
        And the header "Content-Type" should be equal to "application/ld+json; charset=utf-8"

#        When I send a "GET" request to "/voyages/1.jsonld"
#        Then the response status code should be 200
#        And the response should be in JSON
#        And the header "Content-Type" should be equal to "application/ld+json; charset=utf-8"
#        And the JSON should be equal to:
#        """
#        {
#            "@context": "\/contexts\/Voyage",
#            "@id": "\/voyages\/1",
#            "@type": "Voyage",
#            "id": 1,
#            "name": "TDM",
#            "token": "TOKEN1",
#            "urlMinified": null,
#            "showPricesInPublic": true,
#            "startDate": "2017-01-20",
#            "startDestination": {
#                "@id": "\/destinations\/1",
#                "@type": "Destination",
#                "id": 1,
#                "name": "Paris",
#                "slug": "paris"
#            },
#            "stages": [
#                {
#                    "@id": "\/stages\/2",
#                    "@type": "Stage",
#                    "id": 2,
#                    "nbDays": 2,
#                    "destination": {
#                        "@id": "\/destinations\/3",
#                        "@type": "Destination",
#                        "id": 3,
#                        "name": "Marseille",
#                        "slug": "marseille"
#                    },
#                    "country": null,
#                    "position": 0,
#                    "transportType": "BUS",
#                    "availableJourney": {
#                        "@id": "\/available_journeys\/5",
#                        "@type": "AvailableJourney",
#                        "id": 5,
#                        "fromDestination": {
#                            "@id": "\/destinations\/3",
#                            "@type": "Destination",
#                            "id": 3,
#                            "name": "Marseille",
#                            "slug": "marseille"
#                        },
#                        "toDestination": {
#                            "@id": "\/destinations\/2",
#                            "@type": "Destination",
#                            "id": 2,
#                            "name": "Lyon",
#                            "slug": "lyon"
#                        },
#                        "flyPrices": null,
#                        "flyTime": null,
#                        "busPrices": 15,
#                        "busTime": 415,
#                        "trainPrices": 65,
#                        "trainTime": 120
#                    }
#                },
#                {
#                    "@id": "\/stages\/1",
#                    "@type": "Stage",
#                    "id": 1,
#                    "nbDays": 1,
#                    "destination": {
#                        "@id": "\/destinations\/2",
#                        "@type": "Destination",
#                        "id": 2,
#                        "name": "Lyon",
#                        "slug": "lyon"
#                    },
#                    "country": null,
#                    "position": 1,
#                    "transportType": null,
#                    "availableJourney": null
#                },
#                {
#                    "@id": "\/stages\/3",
#                    "@type": "Stage",
#                    "id": 3,
#                    "nbDays": 3,
#                    "destination": {
#                        "@id": "\/destinations\/4",
#                        "@type": "Destination",
#                        "id": 4,
#                        "name": "Sens",
#                        "slug": "sens"
#                    },
#                    "country": null,
#                    "position": 2,
#                    "transportType": null,
#                    "availableJourney": null
#                }
#            ],
#            "transportType": "FLY",
#            "availableJourney": {
#                "@id": "\/available_journeys\/6",
#                "@type": "AvailableJourney",
#                "id": 6,
#                "fromDestination": {
#                    "@id": "\/destinations\/1",
#                    "@type": "Destination",
#                    "id": 1,
#                    "name": "Paris",
#                    "slug": "paris"
#                },
#                "toDestination": {
#                    "@id": "\/destinations\/3",
#                    "@type": "Destination",
#                    "id": 3,
#                    "name": "Marseille",
#                    "slug": "marseille"
#                },
#                "flyPrices": 1,
#                "flyTime": 2,
#                "busPrices": 5,
#                "busTime": 6,
#                "trainPrices": 3,
#                "trainTime": 4
#            }
#        }
#        """


    @skip
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

