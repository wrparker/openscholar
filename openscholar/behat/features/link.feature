Feature:
  Testing the link tab.

  @api @first
  Scenario: Test the Links tab
    Given I visit "john"
     When I click "Links"
     Then I should see "JFK wikipedia page"
