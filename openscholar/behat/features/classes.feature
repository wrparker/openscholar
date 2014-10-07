Feature:
  Testing the classes tab.
  As a user visiting different content-type tabs
  I should be able to filter by terms
  And see nodes of the content-type that are also attached to the selected term.

  @api @first
  Scenario: Test the Classes tab
    Given I visit "john"
      And I click "Classes"
      And I click "John F. Kennedy"
     When I should see the link "Wikipedia page on JFK"
     Then I should see the link "Who was JFK?"

  @api @first @now
  Scenario: Test the order of the classes order
    Given I visit "john"
     When I visit "john/classes"
     Then I verify the class "JFK 202" is before "JFK 101"
      And I verify the class "JFK 101" is before "JFK 303"
      And I verify the class "JFK 303" is before "JFK 404"
      And I verify the class "JFK 404" is before "JFK 505"
