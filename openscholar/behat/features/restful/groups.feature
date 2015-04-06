Feature:
  Checking groups end point.

  @api @restful
  Scenario: Verify the endpoint permission.
    Given I can't visit "api/group"
     When I am logging in as "demo"
      And I can't visit "api/group"
     Then I am logging in as "admin"
      And I visit "api/group"

  @api @restful
  Scenario: Create a new group via restful.
    Given I "create" a group as "admin":
      | label                     | purl    | type        |
      | everybody love raymond    | raymond | department  |
      | Courage the Cowardly Dog  | eustace | personal    |
      | king of the hill          | hank    | project     |
    And I verify vsite content:
      | purl    | text                      |
      | raymond | everybody love raymond    |
      | eustace | Courage the Cowardly Dog  |
      | hank    | king of the hill          |
