Feature:
  Testing that the List of posts widget shows upcoming events.

  @api @widgets
  Scenario: Verify that the List of posts widget shows upcoming events.
    Given I am logging in as "john"
     When I visit "john/calendar"
     Then I should see "Upcoming events"

  @api @widgets
  Scenario: Verify that the List of posts widget shows a new upcoming event.
    Given I am logging in as "john"
     When I create a new event with title "New event"
      And I visit "john/calendar"
     Then I should see the event "New event" in the LOP

  @api @widgets
  Scenario: Verify that the List of posts widget shows a new repeating event
            and that repeats are displayed correctly.
    Given I am logging in as "john"
     When I create a new repeating event with title "Repeating event" that repeats "3" times
      And I visit "john/calendar"
     Then I should see the event "Repeating event" in the LOP
      And I visit "john/event/repeating-event?delta=2"
      And I should see the date of the "2" repeat of the event
      And I should see "Repeats every week 3 times."
