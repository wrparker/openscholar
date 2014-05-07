Feature: Testing the creation of the a new site.

  @api @first
  Scenario: Test the creation of a new site and verify that we don't get JS alert.
    Given I am logging in as "admin"
     When I visit "/"
      And I click "Create a site"
      And I fill "edit-domain" with random text
      And I press "edit-submit"
      And I visit the site "random"
     Then I should see "Your site's front page is set to display your bio by default."

  @api @last
  Scenario: Make sure all types of a site are presented to an authenticated user.
    Given I am logging in as "michelle"
     When I visit "site/register"
     Then I should see the options "Department Site,Personal Site,Project Site" under "bundle"

  # @todo add "@javascript" tag after selenium 2 is configured
  #    @fixme requires selenium for javascript support.
  #    @see waitForPageActionsToComplete()
  #    And I wait for page actions to complete
  #    Then I should see "Success! The new site has been created."
