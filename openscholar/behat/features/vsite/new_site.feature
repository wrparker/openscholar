Feature: Testing the creation of the a new site.

  @api @vsite @javascript
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

  @api @vsite
  Scenario: Make sure all types of a site are presented to an authenticated user.
    Given I am logging in as "michelle"
     When I visit "site/register"
     Then I should see the options "Department Site,Personal Site,Project Site" under "bundle"
