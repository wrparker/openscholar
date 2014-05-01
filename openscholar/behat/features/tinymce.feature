Feature:
  Testing the tiny MCE is enabled.

  @javascript @shushu
  Scenario: Verify the tiny MCE is enabled.
    Given I visit "user/logout"
      And I am logging in as "admin"
     When I visit "john/node/add/blog"
     Then I should see tineMCE in "Body"
