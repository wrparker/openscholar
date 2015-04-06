Feature:
  Checking groups end point.

  @api @restful
  Scenario: Verify the endpoint permission.
    Given I can't visit "api/group"
     When I am logging in as "demo"
      And I can't visit "api/group"
     Then I am logging in as "admin"
      And I visit "api/group"
