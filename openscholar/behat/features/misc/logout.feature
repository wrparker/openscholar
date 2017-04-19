Feature:
  Testing the logout link.

  @api @misc_first @javascript
  Scenario: Verify that when logging out the user gets redirected to the page
            he were in.
    Given I am logging in as "john"
     When I visit "john/classes"
      And I visit "john/user/logout"
     Then I should verify i am at "john/classes"
