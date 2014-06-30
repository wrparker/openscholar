Feature:
  Testing the creation of a site from a profile.

  @api @first
  Scenario: Test creation of a site from a profile.
    Given I am logging in as "admin"
      And I visit "john/people/norma-jeane-mortenson"
      And I click "Create a personal website from this profile"
      And I fill in "URL" with "norma-jeane"
      And I press "Submit"
     When I visit "norma-jeane/biocv"
     Then I should see "AKA Marilyn Monroe"
      And I verify that the profile "Norma Jeane Mortenson" has a child site named "Norma Jeane Mortenson"
      And I visit "john/people/norma-jeane-mortenson"
      And I should not "Create a personal website from this profile"
