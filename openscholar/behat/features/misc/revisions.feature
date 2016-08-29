Feature: Revisions functionality testing.

  @misc_second @api @revisions
  Scenario: Create and View revisions of a blog
      Given I am logging in as "john"
        And I create a new "blog" with title "My New Blog Post" in the site "john"
        And I create a revision of "My New Blog Post" where I change the "title" to "My New Revised Blog Post"
       Then I should be able to see "2" revisions for "My New Revised Blog Post"

  @misc_second @api @revisions
  Scenario: Create and View revisions of a book
      Given I am logging in as "john"
        And I create a new "book" with title "My New Book Page" in the site "john"
        And I create a revision of "My New Book Page" where I change the "title" to "My New Revised Book Page"
       Then I should be able to see "2" revisions for "My New Revised Book Page"

  @misc_second @api @revisions
  Scenario: Create and View revisions of a faq
      Given I am logging in as "john"
        And I create a new "faq" with title "My New FAQ" in the site "john"
        And I create a revision of "My New FAQ" where I change the "title" to "My New Revised FAQ"
       Then I should be able to see "2" revisions for "My New Revised FAQ"

  @misc_second @api @revisions
  Scenario: Create and View revisions of a link
      Given I am logging in as "john"
        And I create a new "link" with title "My New Link" in the site "john"
        And I create a revision of "My New Link" where I change the "title" to "My New Revised Link"
       Then I should be able to see "2" revisions for "My New Revised Link"

  @misc_second @api @revisions
  Scenario: Create and View revisions of a news item
      Given I am logging in as "john"
        And I create a new "news" with title "My New News" in the site "john"
        And I create a revision of "My New News" where I change the "title" to "My New Revised News"
       Then I should be able to see "2" revisions for "My New Revised News"

  @misc_second @api @revisions
  Scenario: Create and View revisions of a page
      Given I am logging in as "john"
        And I create a new "page" with title "My New Page" in the site "john"
        And I create a revision of "My New Page" where I change the "title" to "My New Revised page"
       Then I should be able to see "2" revisions for "My New Revised page"

  @misc_second @api @revisions
  Scenario: Create and View revisions of a profile
      Given I am logging in as "john"
        And I create a new "person" with title "Person Test" in the site "john"
        And I create a revision of "Person Test" where I change the "title" to "Person Test Revised"
       Then I should be able to see "2" revisions for "Person Test Revised"

  @misc_second @api @revisions
  Scenario: Create and View revisions of a publication
      Given I am logging in as "john"
        And I create a new "biblio" with title "My New Publication" in the site "john"
        And I create a revision of "My New Publication" where I change the "title" to "My New Revised Publication"
       Then I should be able to see "2" revisions for "My New Revised Publication"

