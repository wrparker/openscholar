Feature:
  Testing the creation of the a new site.

  @api @javascript
  Scenario: Test the creation of a new site and verify that we don't get JS alert.
    Given I am logging in as "admin"
      And I wait for page actions to complete
     When I visit "/"
      And I click "Create a site"
      And I fill "edit-domain" with random text
      And I press "edit-submit"
      And I wait for page actions to complete
      And I should print page
     Then I should see "Success! The new site has been created."
      And I visit the site "random"
      And I should see "Your site's front page is set to display your bio by default."
