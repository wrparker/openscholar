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

#  @javascript @first @foo
#  Scenario: Verify user can create a publication.
#    Given I am logging in as "admin"
#     When I visit "/john/node/add/biblio"
##     And I fill in "title_field[und][0][value]" with "Journal publication"
#      And I fill in "biblio_year" with "2013"
#      And I press "Save"
#     Then I should see "Journal publication"

#  @api @first @foo
#  Scenario: Test the Publication form year validation when submitting the form.
#    Given I am logging in as "john"
#      And I visit "john/node/add/biblio"
#      And I fill in "title" with "Example title"
#      And I fill in "edit-biblio-year" with "199"
#     When I click "Save"
#     Then I should see "Year value must be in a YYYY format."

#  @javascript @first @foo
#  Scenario: Test the JS Publication form year validation.
#    Given I am logging in as "john"
#      And I visit "john/node/add/biblio"
#      And I fill in "title" with "Example title"
#     When I fill in "edit-biblio-year" with "199"
#     Then I should see "Input must be in the form YYYY. Only numerical digits are allowed."


  @javascript @first
  Scenario: Test the JS Publication form year validation.
    Given I am logging in as "john"
     When I set the widget of vocabulary "food" to "tree"
      And I sleep for "15"
      And I visit "john/node/add/biblio"
      And I click "Publication Details"
      And I should not see "Burger"
      And I sleep for "5"
     Then I click "Expand Terms"
      And I should see "Burger"
