Feature: Testing the Publication form.

  @javascript
  Scenario: Testing the "Year of Publication" validation.
    Given I am logging in as "john"
      And I wait for page actions to complete
     When I visit "/john/node/add/biblio"
      And I fill in "biblio_year" with "200"
     Then I should see "Input must be in the form YYYY. Only numerical digits are allowed."
      And I fill in "biblio_year" with "2001"
      And I should not see "Input must be in the form YYYY. Only numerical digits are allowed."

  @javascript
  Scenario: Testing the "Publication Details" section.
    Given I am logging in as "john"
      And I wait for page actions to complete
     When I visit "/john/node/add/biblio"
      And I click "Publication Details"
      And I wait for page actions to complete
     Then I should see "Publication Image"
      And I should see "Extra fields"

  @javascript
  Scenario: Testing the "Publication Image" section when hovering over the
            drag&drop area help icon.
    Given I am logging in as "john"
      And I wait for page actions to complete
     When I visit "/john/node/add/biblio"
      And I click "Publication Details"
      And I wait for page actions to complete
     Then I hover over the element "os-publications-image-help" under "image-widget"
      And I should see "Journal of Statistical Software"

  @javascript
  Scenario: Testing the "Publication Image" section when hovering over the
            description help icon.
    Given I am logging in as "john"
      And I wait for page actions to complete
     When I visit "/john/node/add/biblio"
      And I click "Publication Details"
      And I wait for page actions to complete
     Then I hover over the element "os-publications-image-help" under "description"
      And I should see "png gif jpg jpeg"
