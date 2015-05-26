Feature:
  Testing the Media Browser

  @media_browser @javascript
  Scenario: Invoke the browser from the standard media field
    Given I am logging in as "john"
      And I wait for page actions to complete
      And I visit "john/node/add/page"
     When I click "Choose File"
      And I wait for page actions to complete
     Then I should see "Select files to Add"
