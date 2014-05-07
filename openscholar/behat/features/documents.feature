Feature:
  Testing the documents tab.

  @api @first
  Scenario: Test the Documents tab
    Given I visit "john"
     When I click "Documents"
     Then I should see "All about nodes"
