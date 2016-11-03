Feature:
  Testing ability to subscribe as support team for privileged users,
  that creates an expirable membership.

  @api @vsite @javascript
  Scenario: Test subscribe for user with permission
    Given I am logging in as "bill"
    When I visit "obama"
    And I open the user menu
    And I click "Support this site"
    And the overlay opens
    And I press "Join"
    And the overlay closes
    And I open the user menu
    Then I should see "Un-Subscribe from site"

  @api @vsite @javascript
  Scenario: Test user is still member after cron runs
    Given I am logging in as "bill"
    When I visit "obama"
    And I execute vsite cron
    And I open the user menu
    Then I should not see "Support this site"

  @api @vsite @javascript
  Scenario: Test expiring membership on cron, of an existing member
    Given I am logging in as "bill"
    When I visit "obama"
    And I set the variable "vsite_support_expire" to "1 sec"
    And I execute vsite cron
    And I visit "obama"
    And I open the user menu
    Then I should see "Support this site"

  @api @vsite @javascript
  Scenario: Test subscribe for user without permission
    Given I am logging in as "michelle"
    When I visit "obama"
     And I open the user menu
    Then I should not see "Support this site"
