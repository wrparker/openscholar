Feature: Testing OpenScholar calendar page.

  @api @features_first
  Scenario: Test the Calendar tab
    Given I visit "john"
     When I click "Calendar"
     Then I should see "Testing event"

  @api @features_first
  Scenario: Adding vocabulary for events content
    Given I am logging in as "john"
      And I create the vocabulary "event type" in the group "john" assigned to bundle "event"
      And I visit "john/cp/build/taxonomy/eventtype/add"
      And I fill in "Name" with "astronomy"
      And I check the box "Generate automatic URL alias"
     When I press "edit-submit"
     Then I verify the alias of term "astronomy" is "john/event-type/astronomy"

  @api @features_first
  Scenario: Adding term for events content
    Given I am logging in as "john"
      And I visit "john/cp/build/taxonomy/eventtype/add"
      And I fill in "Name" with "birthday"
      And I check the box "Generate automatic URL alias"
     When I press "edit-submit"
     Then I verify the alias of term "astronomy" is "john/event-type/astronomy"

  @api @features_first
  Scenario: Assigning events with terms
    Given I am logging in as "john"
      And I assign the node "John F. Kennedy birthday" with the type "event" to the term "birthday"
      And I assign the node "Halley's Comet" with the type "event" to the term "astronomy"
     Then I should get a "200" HTTP response

  @api @features_first
  Scenario: Test the Calendar tab with day events filtered by terms.
    Given I visit "john/calendar?type=day&day=2013-05-29"
      And I should see the text "John F. Kennedy birthday" under "view-display-id-page_1"
     When I visit "john/calendar/event-type/birthday?type=day&day=2013-05-29"
     Then I should see the text "John F. Kennedy birthday" under "view-display-id-page_1"
     When I visit "john/calendar/event-type/astronomy?type=day&day=2013-05-29"
     Then I should not see the text "John F. Kennedy birthday" under "view-display-id-page_1"

  @api @features_first
  Scenario: Test the Calendar tab with week events filtered by terms.
    Given I visit "john/calendar?week=2013-W22&type=week"
      And I should see the text "John F. Kennedy birthday" under "view-display-id-page_1"
     When I visit "john/calendar/event-type/birthday?week=2013-W22&type=week"
     Then I should see the text "John F. Kennedy birthday" under "view-display-id-page_1"
     When I visit "john/calendar/event-type/astronomy?week=2013-W22&type=week"
     Then I should not see the text "John F. Kennedy birthday" under "view-display-id-page_1"

  @api @features_first
  Scenario: Test the Calendar tab with month events filtered by terms.
    Given I visit "john/calendar?type=month&month=2013-05"
      And I should see the link "John F. Kennedy birthday" under "view-display-id-page_1"
     When I visit "john/calendar/event-type/birthday?type=month&month=2013-05"
     Then I should see the link "John F. Kennedy birthday" under "view-display-id-page_1"
     When I visit "john/calendar/event-type/astronomy?type=month&month=2013-05"
     Then I should not see the text "John F. Kennedy birthday" under "view-display-id-page_1"

  @api @features_first
  Scenario: Test the Calendar tab with year events filtered by terms.
    Given I visit "john/calendar?type=year&year=2013"
      And I should see the link "29" under "has-events"
     When I visit "john/calendar/event-type/birthday?type=year&year=2013"
     Then I should see the link "29" under "view-display-id-page_1"
     When I visit "john/calendar/event-type/astronomy?type=year&year=2013"
     Then I should not see the link "29" under "view-display-id-page_1"

  @api @features_first
  Scenario: Testing the upcoming events export in iCal format.
    Given I visit "john/calendar/upcoming/all/export.ics"
     Then I should find the text "SUMMARY:Registration event" in the file
      And I should find the text "SUMMARY:Halley's Comet" in the file
      And I should not find the text "Testing event" in the file

  @api @features_first
  Scenario: Test that site-wise calendar is disabled
     Given I go to "calendar"
      Then I should get a "403" HTTP response

  @api @features_first
  Scenario: Test the week tab and
    Given I visit "john/calendar"
      And I click "Week"
      And I click "Navigate to next week"
     Then I should verify the next week calendar is displayed correctly
