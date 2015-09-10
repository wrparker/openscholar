Feature:
  Testing the publication tab and application.

  @api @features_second
  Scenario: Test the Publication tab
    Given I visit "john"
     When I click "Publications"
      And I click "The Little Prince"
     Then I should see "The Little Prince. United States; 1943."

  @api @features_second
  Scenario: Test the Publication tab allows caching of anonymous user
    Given cache is "enabled" for anonymous users
     When I visit "john/publications"
     Then I should get a "200" HTTP response
      And I visit "john/publications"
     Then response header "X-Drupal-Cache" should be "HIT"
      And cache is "disabled" for anonymous users

  @api @features_second
  Scenario: Test the Authors field in Publication form
    Given I am logging in as "john"
     When I edit the node "The Little Prince"
     Then I should see "Author"
      And I should see "Add person"

  @api @features_second
  Scenario: Verify publications are sorted by the creation date of the node.
    Given I am logging in as "john"
     When I visit "john/publications"
     Then I should see the publication "Goblet of Fire" comes before "Prisoner of Azkaban"
      And I should see the publication "Prisoner of Azkaban" comes before "Chamber of Secrets"
      And I should see the publication "Chamber of Secrets" comes before "Philosophers Stone"

  @api @features_second
  Scenario: Verify sticky publications appear first on each section.
    Given I am logging in as "john"
      And I make the node "Philosophers Stone" sticky
     When I visit "john/publications"
      And I should see the publication "Philosophers Stone" comes before "Goblet of Fire"

  @api @features_second
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

  @api @features_second
  Scenario: Verify authors page is not available
    Given I go to "/publications/authors"
     Then I should get a "403" HTTP response
      And I go to "john/publications/authors"
     Then I should get a "403" HTTP response

  @api @features_second
  Scenario: Verify the "Cancel" button for confirm delete for a Publication
            redirects to the edit form of that node.
    Given I am logging in as "john"
      And I edit the node "The Little Prince"
     When I click "Delete this biblio"
      And I click "Cancel"
     Then I should see "Delete this biblio"

  @api @features_second
  Scenario: Test that Conference Papers using the Chicago-Author-Date style
            print 'In' correctly and doesn't capitalize unneeded words.
    Given I am logging in as "john"
     When I go to the "os_publications" app settings in the vsite "john"
      And I select the radio button named "biblio_citeproc_style" with value "chicago-author-date.csl"
      And I press "Save configuration"
      And I visit "john/publications/confpapers-tests"
     Then I should not see "ConfPapers tests, in"
      And I should see "Confpapers Tests.” In"
      And I visit "john/publications/journal-article-title"
      And I should see "Journal of Publications"
      And I should not find the text "Journal Of Publications"

  @api @features_second
  Scenario: Verify that the publication citations contain the indent CSS class
            when format is Chicago Author-Date style.
    Given I am logging in as "john"
      And I visit "john/cp/build/features/os_publications"
     Then I should see "div" element with the class "bib-neg-indent"

  @js @features_second
  Scenario: Verify that the publication citations display author F. Middle Last correctly
            when format is Chicago Author-Date style.
    Given I am logging in as "john"
      And I edit the node "Philosophers Stone"
      And I fill in "edit-biblio-contributors-0-auth-type" with "1"
      And I fill in "edit-biblio-contributors-0-name" with "Rowling, J. Kathleen"
      And I fill in "edit-biblio-contributors-0-rank" with "0"
      And I press "edit-submit"
    Then I should see "Rowling, J. Kathleen"

  @js @features_second
  Scenario: Verify that the publication citations display author F. Middle Last correctly
            when format is Chicago Author-Date style.
    Given I am logging in as "john"
      And I edit the node "Philosophers Stone"
      And I fill in "edit-biblio-contributors-0-auth-type" with "1"
      And I fill in "edit-biblio-contributors-0-name" with "J. Kathleen Rowling"
      And I fill in "edit-biblio-contributors-0-rank" with "0"
      And I press "edit-submit"
    Then I should see "Rowling, J. Kathleen"

  @js @features_second
  Scenario: Verify that the publication citations display author F. M. Last correctly
            when format is Chicago Author-Date style.
    Given I am logging in as "john"
      And I edit the node "Philosophers Stone"
      And I fill in "edit-biblio-contributors-0-auth-type" with "1"
      And I fill in "edit-biblio-contributors-0-name" with "Rowling, J. K."
      And I fill in "edit-biblio-contributors-0-rank" with "0"
      And I press "edit-submit"
    Then I should see "Rowling, J. K."

  @js @features_second
  Scenario: Verify that the publication citations display author F. M. Last correctly
            when format is Chicago Author-Date style.
    Given I am logging in as "john"
      And I edit the node "Philosophers Stone"
      And I fill in "edit-biblio-contributors-0-auth-type" with "1"
      And I fill in "edit-biblio-contributors-0-name" with "J. K. Rowling"
      And I fill in "edit-biblio-contributors-0-rank" with "0"
      And I press "edit-submit"
    Then I should see "Rowling, J. K."

  @api @features_second
  Scenario: Verify the user can see message the the publication won't display
            in the publication form.
    Given I am logging in as "john"
      And I visit "john/cp/build/features/os_publications"
      And I uncheck the box "os_publications_filter_publication_types[102]"
      And I press "edit-submit"
      And I visit "john/node/add/biblio"
     Then I should see "Note: The publication type Journal Article is not currently shown in publication lists."

  @api @features_second
  Scenario: Verify that when filtering publications with a taxonomy term, the
            title of the publication list is the term name.
    Given I am logging in as "john"
      And I visit "john/publications/science/air"
     Then I should see "Air"
      And I should see the link "The Little Prince"

  @api @features_second
  Scenario: Verify that when filtering publications by publication type, the
            title of the publication list is the publication type.
    Given I am logging in as "john"
      And I visit "john/publications/type/book"
     Then I should see "Publications by Type: Book"

  @api @features_second
  Scenario: Verify that when filtering publications by year, the
            title of the publication list is given year.
    Given I am logging in as "john"
      And I visit "john/publications/year/1943"
     Then I should see "Publications by Year: 1943"
