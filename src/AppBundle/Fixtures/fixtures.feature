Feature: Fixtures

    Scenario: Fixtures User
        Given les utilisateurs :
            | nom   | mot de passe | email               | role             |
            | gui   | gui          | gimli.fr@hotmail.fr | ROLE_SUPER_ADMIN |
            | user  | user         | user@test.com       | ROLE_USER        |
            | admin | admin        | admin@test.com      | ROLE_ADMIN       |

    Scenario: Fixtures AvailableJourney
        Given entities "AppBundle\Entity\AvailableJourney" :
            | fromDestination:AppBundle\Entity\Destination:name | toDestination:AppBundle\Entity\Destination:name | flyPrices | flyTime | trainPrices | trainTime | busPrices | busTime |
            | Paris                                             | Lyon                                            | 136       | 269     | 82          | 152       | 21        | 452     |
            | Paris                                             | Londres                                         | 111       | 319     | 235         | 205       | 47        | 587     |
            | Paris                                             | New York                                        | 469       | 725     |             |           |           |         |
            | Lyon                                              | Paris                                           | 136       | 270     | 83          | 133       | 21        | 458     |
            | Lyon                                              | Londres                                         | 150       | 321     | 261         | 342       | 32        | 1081    |
            | Lyon                                              | New York                                        | 483       | 837     |             |           |           |         |
            | Lyon                                              | Nice                                            | 99        | 73      | 150         | 300       | 51        | 712     |
            | Londres                                           | Paris                                           | 114       | 311     | 235         | 201       | 52        | 616     |
            | Londres                                           | Lyon                                            | 153       | 294     | 253         | 407       | 38        | 988     |
            | Londres                                           | New York                                        | 496       | 681     |             |           |           |         |
            | Londres                                           | Dublin                                          | 89        | 102     |             |           | 35        | 440     |
            | New York                                          | Paris                                           | 469       | 622     |             |           |           |         |
            | New York                                          | Lyon                                            | 483       | 778     |             |           |           |         |
            | New York                                          | Londres                                         | 493       | 638     |             |           |           |         |

    Scenario: Fixtures Voyage Tour dè Frânce
        Given entities "AppBundle\Entity\Voyage" :
            | name           | AppBundle\Entity\User:username | startDate(\DateTime) | StartDestination:AppBundle\Entity\Destination:name | token  |
            | Tour dè Frânce | gui                            | 2017-10-12 20:30:54  | Paris                                              | fgT99j |
        Given entities "AppBundle\Entity\Stage" :
            | AppBundle\Entity\Voyage:name | AppBundle\Entity\Destination:name | AppBundle\Entity\Country:name | nbDays | position |
            | Tour dè Frânce               | Lyon                              |                               | 4      | 0        |
            | Tour dè Frânce               | Nice                              |                               | 3      | 1        |
            | Tour dè Frânce               | Bordeaux                          |                               | 5      | 2        |
            | Tour dè Frânce               | Paris                             |                               | 1      | 3        |

    Scenario: Fixtures Voyage Tour d'Eur@pe
        Given entities "AppBundle\Entity\Voyage" :
            | name          | AppBundle\Entity\User:username | startDate(\DateTime) | StartDestination:AppBundle\Entity\Destination:name | token  |
            | Tour d'Eur@pe | user                           | 2017-09-18 08:30:00  | Nice                                               | 12aed3 |
        Given entities "AppBundle\Entity\Stage" :
            | AppBundle\Entity\Voyage:name | AppBundle\Entity\Destination:name | AppBundle\Entity\Country:name | nbDays | position |
            | Tour d'Eur@pe                |                                   | France                        | 4      | 0        |
            | Tour d'Eur@pe                |                                   | Royaume-Uni                   | 3      | 1        |
            | Tour d'Eur@pe                |                                   | Irlande                       | 5      | 2        |
            | Tour d'Eur@pe                | Stockholm                         |                               | 1      | 3        |
            | Tour d'Eur@pe                | Amsterdam                         |                               | 3      | 4        |
            | Tour d'Eur@pe                |                                   | Danemark                      | 3      | 5        |
