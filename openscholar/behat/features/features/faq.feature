Feature:
  Testing the faq app.

  @api @features_first
  Scenario: Testing the migration of FAQ
    Given I am logging in as "john"
      And I visit "john/faq"
      And I should see "What does JFK stands for?"
     When I click "What does JFK stands for?"
     Then I should see "JFK stands for: John Fitzgerald Kennedy."

  @api @features_first
  Scenario: Testing the migration of FAQ
    Given I am logging in as "john"
      And I visit "john/faq"
      And I click "Add New"
      And I click "FAQ"
      And I fill "edit-title" with random text
      And I press "Save"
     Then I should see the random string

  @api @features_first
  Scenario: Verify that the FAQ's body in the LOP is collapsed by default.
    Given I am logging in as "john"
      And the widget "List of posts" is set in the "FAQ" page with the following <settings>:
          | Content Type               | FAQ         | select list |
          | Display style              | Teaser      | select list |
     When I visit "john/faq"
     Then I should see that the "faq" in the "LOP" are collapsed

