Feature:
  Testing the Media Browser

  @media_browser @javascript
  Scenario: Invoke the browser from the standard media field
    Given I am logging in as "john"
      And I visit "john/node/add/page"
     When I press "Choose File"
      And I wait "1 second" for media browser to open
