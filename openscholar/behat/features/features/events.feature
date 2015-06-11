Feature:
  Testing the events (calendar) tab.

  @api @features_first
  Scenario: Test the Management of registrations for an event
    Given I am logging in as "john"
     When I create a new registration event with title "Registration event"
      And I sign up "Foo bar" with email "foo@example.com" to the event "Registration event"
      And I manage registrations for the event "Registration event"
     Then I should see "Simple view"
      And I should see "CSV"
