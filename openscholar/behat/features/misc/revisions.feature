Feature: Revisions functionality testing.

  @misc_second @revisions
  Scenario: View revisions of a page
    Given I am logging in as a user who can "edit own page content"
      And I create a new "page" with title "My New Page" in the site "john"
      And I create a revision of "My New Page" where I change the "title" to "My New Revised page"
     Then I should see "2" revisions for "My New Revised page"

