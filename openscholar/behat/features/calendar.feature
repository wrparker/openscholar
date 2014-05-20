Feature: Testing OpenScholar calendar page.

  @api @last
  Scenario: Test the Calendar tab
    Given I visit "john"
     When I click "Calendar"
     Then I should see "Testing event"

  @api @last
  Scenario: Test the Calendar tab with month events
    Given I visit "john/calendar?month=2013-05&type=month"
     Then I should see "John F. Kennedy birthday"

  @api @last
  Scenario: Test the Calendar tab with day events filtered by terms.
    Given I visit "john/calendar?type=day&day=2013-05-29"
      And I should see the text "John F. Kennedy birthday" under "view-display-id-page_1"
     When I visit "john/calendar/authors/douglas-noël-adams?type=day&day=2013-05-29"
     Then I should not see the text "John F. Kennedy birthday" under "view-display-id-page_1"

  @api @last
  Scenario: Test the Calendar tab with week events filtered by terms.
    Given I visit "john/calendar/authors/stephen-william-hawking?week=2013-W22&type=week"
      And I should see the text "John F. Kennedy birthday" under "view-display-id-page_1"
     When I visit "john/calendar/authors/douglas-noël-adams?week=2013-W22&type=week"
     Then I should not see the text "John F. Kennedy birthday" under "view-display-id-page_1"

  @api @last
  Scenario: Test the Calendar tab with month events filtered by terms.
    Given I visit "john/calendar/authors/stephen-william-hawking?type=month&month=2013-05"
      And I should see the link "John F. Kennedy birthday" under "view-display-id-page_1"
     When I visit "john/calendar/authors/douglas-noël-adams?type=month&month=2013-05"
     Then I should not see the text "John F. Kennedy birthday" under "view-display-id-page_1"

  @api @last
  Scenario: Test the Calendar tab with year events filtered by terms.
    Given I visit "john/calendar/authors/stephen-william-hawking?type=year&year=2013"
      And I should see the link "29" under "has-events"
     When I visit "john/calendar/authors/douglas-noël-adams?type=year&year=2013"
     Then I should not see the link "29" under "view-display-id-page_1"

  @api @last
  Scenario: Testing the events export in iCal format.
    Given I visit "john/calendar/export.ics"
     Then I look for ".field_date.0@"

  @api @last
  Scenario: Test that site-wise calendar is disabled
     Given I go to "calendar"
      Then I should get a "403" HTTP response

  @api @wip
  Scenario: Test the week tab and
    Given I visit "john/calendar"
      And I click "Week"
      And I click "Navigate to next week"
     Then I should verify the next week calendar is displayed correctly

  @api @last @momo
  Scenario: Test list of posts widget with events
            Including ongoing and near future events.
    Given I am logging in as "john"
      And the widget "Simple view list" is set in the "Classes" page by the name "events" with the following <settings>:
          | Content Type                   | Event       | select list |
          | Sorted By                      | Event Date  | select list |
      And I visit "john/classes"
      And I should not see the text "My passed event 1" under "sv-list-event"
      And I should not see the text "My ongoing event 1" under "sv-list-event"
      And I should see the text "My ongoing event 2" under "sv-list-event"
      And I should see the text "My upcoming event 1" under "sv-list-event"
      And I should not see the text "My upcoming event 2" under "sv-list-event"
      And I should see the text "My upcoming event 3" under "sv-list-event"
      And I should see "My upcoming event 1" before "My upcoming event 3" and after "My ongoing event 2"
