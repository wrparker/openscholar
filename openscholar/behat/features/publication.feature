Feature:
  Testing the publication tab and application.

  @api @first
  Scenario: Test the Publication tab
    Given I visit "john"
     When I click "Publications"
      And I click "The Little Prince"
     Then I should see "The Little Prince. United States; 1943."

  @api @first
  Scenario: Test the Publication tab allows caching of anonymous user
    Given cache is "enabled" for anonymous users
     When I visit "john/publications"
     Then I should get a "200" HTTP response
      And I visit "john/publications"
     Then response header "x-drupal-cache" should be "HIT"
      And cache is "disabled" for anonymous users

  @api @first
  Scenario: Test the Authors field in Publication form
    Given I am logging in as "john"
     When I edit the node "The Little Prince"
     Then I should see "Author"
      And I should see "Add person"

  @api @first
  Scenario: Verify publications are sorted by the creation date of the node.
    Given I am logging in as "john"
     When I visit "john/publications"
     Then I should see the publication "Goblet of Fire" comes before "Prisoner of Azkaban"
      And I should see the publication "Prisoner of Azkaban" comes before "Chamber of Secrets"
      And I should see the publication "Chamber of Secrets" comes before "Philosophers Stone"

  @api @first
  Scenario: Verify sticky publications appear first on each section.
    Given I am logging in as "john"
      And I make the node "Philosophers Stone" sticky
     When I visit "john/publications"
      And I should see the publication "Philosophers Stone" comes before "Goblet of Fire"

  @api @last
  Scenario: Verify anonymous users can't export publications using the main
            export link in the "publications" page but only through the link for
            a single publication.
    Given I visit "john/publications/john-f-kennedy-biography"
     When I click "BibTex"
     Then I should get a "200" HTTP response
      And I visit "john/publications"
     Then I should not see "Export"
      And I go to "john/publications/export/bibtex"
    Then I should get a "403" HTTP response

  @api @first
  Scenario: Verify authors page is not available
    Given I go to "/publications/authors"
     Then I should get a "403" HTTP response
      And I go to "john/publications/authors"
    Then I should get a "403" HTTP response

  @javascript-wip @first @foo
  Scenario: Verify user can create a publication.
    Given I am logging in as "admin"
     When I visit "/john/node/add/biblio"
#     And I fill in "title_field[und][0][value]" with "Journal publication"
      And I fill in "biblio_year" with "2013"
      And I press "Save"
     Then I should see "Journal publication"

  @api @first @foo
  Scenario: Test the Publication form year validation when submitting the form.
    Given I am logging in as "john"
      And I visit "john/node/add/biblio"
      And I fill in "title" with "Example title"
      And I fill in "edit-biblio-year" with "199"
     When I click "Save"
     Then I should see "Year value must be in a YYYY format."

  @javascript-wip @first @foo
  Scenario: Test the JS Publication form year validation.
    Given I am logging in as "john"
      And I visit "john/node/add/biblio"
      And I fill in "title" with "Example title"
     When I fill in "edit-biblio-year" with "199"
     Then I should see "Input must be in the form YYYY. Only numerical digits are allowed."

  @javascript @first
  Scenario: Verify date picker for posting date
    Given I am logging in as "john"
      And I visit "/john/publications/goblet-fire"
      And I edit current node
      And I sleep for "1"
     When I click "Post Created/Edited By"
      And I click "Menu options"
      And I sleep for "1"
     Then I should see "Posted on"
      And I select another month on "edit-date"
      And I sleep for "1"
      And I should see "Posted on"
      And I press "Save"

  @javascript @first
  Scenario: Verify tooltip hoover works
    Given I am logging in as "john"
      And I visit "/john/publications/goblet-fire"
      And I sleep for "1"
     When I edit current node
      And I check "Wand" under "image-widget" is not visible
      And I click "Publication Details"
     Then I should see the text "Help" under "image-widget"
      And I put mouse over "Help" under "image-widget"
      And I check "Wand" under "image-widget" is not visible

  @javascript @first
  Scenario: Verify changing biblio types changes fields
    Given I am logging in as "admin"
     When I visit "/john/node/add/biblio"
     Then I should see "Title of the Journal"
      And I should not see "Secondary Title"
      And I select "Artwork" from "edit-biblio-type"
     Then I should see "Secondary Title"
      And I should not see "Title of the Journal"

  @javascript @first @shushu2
  Scenario: Verify year element
    Given I am logging in as "john"
      And I visit "/john/publications/goblet-fire"
      And I edit current node
      And I sleep for "1"
     When text field "biblio_year" value should be "1997"
      And I should not see "Input must be in the form"
      And I choose id "edit-biblio-year-coded-10040" from radio id "edit-biblio-year-coded"
      And text field "biblio_year" value should not be "1997"
      And I fill in "biblio_year" with "123"
     Then I should see "Input must be in the form"
      And I fill in "biblio_year" with "1999"
      And I should not see "Input must be in the form"
