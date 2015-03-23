Feature:
  Testing that the List of posts widget shows upcoming events.

  @api @widgets
  Scenario: Verify that the List of posts widget shows upcoming events.
    Given I am logging in as "john"
     When I visit "john/calendar"
     Then I should see "Upcoming events"
