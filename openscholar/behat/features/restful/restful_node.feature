Feature:
  Checking node management via restful

  @restful @now
  Scenario: Creating nodes via rest.
    Given I create a new node of "biblio" as "john" with the settings:
      | Label       | Body                  | vsite | type  |
      | Rest biblio | This is a test Biblio | john  | 101   |
