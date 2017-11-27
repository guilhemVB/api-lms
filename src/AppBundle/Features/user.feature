Feature: User CRUD

    Scenario: Check authentication
        When I send a "GET" request to "/users"
        Then the response status code should be 401
        When I send a "GET" request to "/users/1"
        Then the response status code should be 401
        When I send a "PUT" request to "/users/1"
        Then the response status code should be 401
        When I send a "DELETE" request to "/users/1"
        Then the response status code should be 401

    @emptyDatabase
    Scenario: CRU user
        Given I add "Content-Type" header equal to "application/json"

############################################
#        POST OK
############################################

        When I send a "POST" request to "/users.jsonld" with body:
        """
        {
            "email": "test@test.com",
            "username": "usr",
            "plainPassword": "pwd12",
            "fullname": "usrName"
        }
        """
        Then the response status code should be 201
        And the response should be in JSON
        And the header "Content-Type" should be equal to "application/ld+json; charset=utf-8"
        And the JSON should be equal to:
        """
        {
            "@context": "\/contexts\/User",
            "@id": "\/users\/1",
            "@type": "User",
            "email": "test@test.com",
            "username": "usr",
            "fullname": "usrName"
        }
        """


############################################
#        POST KO, email already exist
############################################

        When I send a "POST" request to "/users.jsonld" with body:
        """
        {
            "email": "test@test.com",
            "username": "toto",
            "plainPassword": "toto",
            "fullname": "toto"
        }
        """
        Then the response status code should be 400
        And the JSON node "violations[0]->propertyPath" should be equal to "email"
        And the JSON node "violations[0]->message" should be equal to "This value is already used."



############################################
#        POST KO, username already exist
############################################

        When I send a "POST" request to "/users.jsonld" with body:
        """
        {
            "email": "test123@test.com",
            "username": "usr",
            "plainPassword": "toto",
            "fullname": "toto"
        }
        """
        Then the response status code should be 400
        And the JSON node "violations[0]->propertyPath" should be equal to "username"
        And the JSON node "violations[0]->message" should be equal to "This value is already used."


############################################
#        GET OK
############################################

        Given I authenticate the user "usr"

        When I send a "GET" request to "/users/1.jsonld"
        Then the response status code should be 200
        And the response should be in JSON
        And the header "Content-Type" should be equal to "application/ld+json; charset=utf-8"
        And the JSON should be equal to:
        """
        {
            "@context": "\/contexts\/User",
            "@id": "\/users\/1",
            "@type": "User",
            "email": "test@test.com",
            "username": "usr",
            "fullname": "usrName"
        }
        """


############################################
#        PUT OK
############################################

        When I send a "PUT" request to "/users/1.jsonld" with body:
        """
        {
            "email": "test2@test.com",
            "username": "usr2",
            "plainPassword": "12345",
            "fullname": "usrName2"
        }
        """
        Then the response status code should be 200
        And the response should be in JSON
        And the header "Content-Type" should be equal to "application/ld+json; charset=utf-8"
        And the JSON should be equal to:
        """
        {
            "@context": "\/contexts\/User",
            "@id": "\/users\/1",
            "@type": "User",
            "email": "test2@test.com",
            "username": "usr2",
            "fullname": "usrName2"
        }
        """



    @emptyDatabase
    Scenario: Delete user with voyage
        Given I add "Content-Type" header equal to "application/json"

        When I send a "POST" request to "/users.jsonld" with body:
        """
        {
            "email": "test@test.com",
            "username": "usr",
            "plainPassword": "pwd12",
            "fullname": "usrName"
        }
        """
        Then the response status code should be 201

        Given entities "AppBundle\Entity\Currency" :
            | name              | code |
            | Euro              | EUR  |
        Given entities "AppBundle\Entity\Country" :
            | name      | capitalName | codeAlpha3 | AppBundle\Entity\Currency:code | visaInformation | visaDuration | priceAccommodation | priceLifeCost |
            | France    | Paris       | FRA        | EUR                            | Visa gratuit    | 90 jours     |                    |               |
        Given entities "AppBundle\Entity\Destination" :
            | name      | AppBundle\Entity\Country:name | latitude   | longitude  | priceAccommodation | priceLifeCost |
            | Paris     | France                        | 48.864592  | 2.336492   | 30                 | 20            |
        Given entities "AppBundle\Entity\Voyage" :
            | name | startDate(\DateTime) | startDestination:AppBundle\Entity\Destination:name | AppBundle\Entity\User:username | token  |
            | TDM  | 2017-01-20           | Paris                                              | usr                            | TOKEN1 |


        Given I authenticate the user "usr"

        When I send a "DELETE" request to "/users/1.jsonld"
        Then the response status code should be 204
