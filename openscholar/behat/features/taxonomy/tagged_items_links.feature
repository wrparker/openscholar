Feature:
  Testing that content types should show tagged taxonomy terms on their node view page.

  @api @taxonomy @bio @links
  Scenario: Testing tagged term links for content type bio
     Given I am logging in as "john"
      When I create the vocabulary "color" in the group "einstein" assigned to bundle "bio"
       And I visit "einstein/cp/build/taxonomy/color/add"
       And I fill in "Name" with "Magenta"
       And I press "edit-submit"
       And I create a new "bio" entry with the name "Albert" in the group "einstein"
       And I assign the node "Albert" with the type "bio" to the term "Magenta"
       And I visit "einstein/einstein"
      Then I should see "Magenta"

  @api @taxonomy @bio @links
  Scenario: Testing that removed tagged term don't show as links for content type bio
     Given I am logging in as "john"
       And I unassign the node "Albert" with the type "bio" from the term "Magenta"
       And I visit "einstein/einstein"
      Then I should not see the text "Magenta" under "node-content"

  @api @taxonomy @blog @links
  Scenario: Testing tagged term links for content type blog
     Given I am logging in as "john"
      When I bind the content type "blog" with "color"
       And I create a new "blog" entry with the name "Bulletin" in the group "einstein"
       And I assign the node "Bulletin" with the type "blog" to the term "Magenta"
       And I visit "einstein/blog/bulletin"
      Then I should see "Magenta"

  @api @taxonomy @blog @links
  Scenario: Testing that removed tagged term don't show as links for content type blog
     Given I am logging in as "john"
       And I unassign the node "Bulletin" with the type "blog" from the term "Magenta"
       And I visit "einstein/blog/bulletin"
      Then I should not see the text "Magenta" under "node-content"

  @api @taxonomy @book @links
  Scenario: Testing tagged term links for content type book
     Given I am logging in as "john"
      When I bind the content type "book" with "color"
       And I create a new "book" entry with the name "Chapter" in the group "einstein"
       And I assign the node "Chapter" with the type "book" to the term "Magenta"
       And I visit "einstein/book/chapter"
      Then I should see "Magenta"

  @api @taxonomy @book @links
  Scenario: Testing that removed tagged term don't show as links for content type book
     Given I am logging in as "john"
       And I unassign the node "Chapter" with the type "book" from the term "Magenta"
       And I visit "einstein/book/chapter"
      Then I should not see the text "Magenta" under "node-content"

  @api @taxonomy @cv @links
  Scenario: Testing tagged term links for content type cv
     Given I am logging in as "john"
      When I bind the content type "cv" with "color"
       And I create a new "cv" entry with the name "Dynasty" in the group "einstein"
       And I assign the node "Dynasty" with the type "cv" to the term "Magenta"
       And I visit "einstein/biocv/cv"
      Then I should see "Magenta"

  @api @taxonomy @cv @links
  Scenario: Testing that removed tagged term don't show as links for content type cv
     Given I am logging in as "john"
       And I unassign the node "Dynasty" with the type "cv" from the term "Magenta"
       And I visit "einstein/biocv/cv"
      Then I should not see the text "Magenta" under "node-content"

  @api @taxonomy @event @links
  Scenario: Testing tagged term links for content type event
     Given I am logging in as "john"
      When I bind the content type "event" with "color"
       And I create a new "event" entry with the name "Fair" in the group "einstein"
       And I assign the node "Fair" with the type "event" to the term "Magenta"
       And I visit "einstein/event/fair"
      Then I should see "Magenta"

  @api @taxonomy @event @links
  Scenario: Testing that removed tagged term don't show as links for content type event
     Given I am logging in as "john"
       And I unassign the node "Fair" with the type "event" from the term "Magenta"
       And I visit "einstein/event/fair"
      Then I should not see the text "Magenta" under "node-content"

  @api @taxonomy @faq @links
  Scenario: Testing tagged term links for content type faq
     Given I am logging in as "john"
      When I bind the content type "faq" with "color"
       And I visit "einstein/node/add/faq"
       And I fill in "Question" with "Guideline"
       And I press "edit-submit"
       And I assign the node "Guideline" with the type "faq" to the term "Magenta"
       And I visit "einstein/faq/guideline"
      Then I should see "Magenta"

  @api @taxonomy @faq @links
  Scenario: Testing that removed tagged term don't show as links for content type faq
     Given I am logging in as "john"
       And I unassign the node "Guideline" with the type "faq" from the term "Magenta"
       And I visit "einstein/faq/guideline"
      Then I should not see the text "Magenta" under "node-content"

  @api @taxonomy @feed @links
  Scenario: Testing tagged term links for content type feed
     Given I am logging in as "john"
      When I bind the content type "feed" with "color"
       And I visit "einstein/node/add/feed"
       And I fill in "Title" with "Health"
       And I fill in "edit-field-url-und-0-url" with "http://feeds.boston.com/boston/lifestyle/health/health_stew/index"
       And I press "edit-submit"
       And I assign the node "Health" with the type "feed" to the term "Magenta"
       And I visit "einstein/feeds/health"
      Then I should see "Magenta"

  @api @taxonomy @feed @links
  Scenario: Testing that removed tagged term don't show as links for content type feed
     Given I am logging in as "john"
       And I unassign the node "Health" with the type "feed" from the term "Magenta"
       And I visit "einstein/feeds/health"
      Then I should not see the text "Magenta" under "node-content"

  @api @taxonomy @news @links
  Scenario: Testing tagged term links for content type news
     Given I am logging in as "john"
      When I bind the content type "news" with "color"
       And I visit "einstein/node/add/news"
       And I fill in "Title" with "Internets"
       And I fill in "Date" with "Jan 1 2015"
       And I press "edit-submit"
       And I assign the node "Internets" with the type "news" to the term "Magenta"
       And I visit "einstein/news/internets"
      Then I should see "Magenta"

  @api @taxonomy @news @links
  Scenario: Testing that removed tagged term don't show as links for content type news
     Given I am logging in as "john"
       And I unassign the node "Internets" with the type "news" from the term "Magenta"
       And I visit "einstein/news/internets"
      Then I should not see the text "Magenta" under "node-content"

  @api @taxonomy @page @links
  Scenario: Testing tagged term links for content type page
     Given I am logging in as "john"
      When I bind the content type "link" with "color"
       And I create a new "page" entry with the name "Journey" in the group "einstein"
       And I assign the node "Journey" with the type "page" to the term "Magenta"
       And I visit "einstein/journey"
      Then I should see "Magenta"

  @api @taxonomy @page @links
  Scenario: Testing that removed tagged term don't show as links for content type page
     Given I am logging in as "john"
       And I unassign the node "Journey" with the type "page" from the term "Magenta"
       And I visit "einstein/journey"
      Then I should not see the text "Magenta" under "node-content"

  @api @taxonomy @person @links
  Scenario: Testing tagged term links for content type person
     Given I am logging in as "john"
      When I bind the content type "person" with "color"
       And I create a new "person" entry with the name "Kate" in the group "einstein"
       And I assign the node "Kate" with the type "person" to the term "Magenta"
       And I visit "einstein/people/kate-doe"
      Then I should see "Magenta"

  @api @taxonomy @person @links
  Scenario: Testing that removed tagged term don't show as links for content type person
     Given I am logging in as "john"
       And I visit "einstein/node/add/person"
       And I fill in "First Name" with "Kate"
       And I fill in "Last Name" with "Doe"
       And I press "edit-submit"
       And I unassign the node "Kate" with the type "person" from the term "Magenta"
       And I visit "einstein/people/kate-doe"
      Then I should not see the text "Magenta" under "node-content"

  @api @taxonomy @presentation @links
  Scenario: Testing tagged term links for content type presentation
     Given I am logging in as "john"
      When I bind the content type "presentation" with "color"
       And I create a new "presentation" entry with the name "Lessons" in the group "einstein"
       And I assign the node "Lessons" with the type "presentation" to the term "Magenta"
       And I visit "einstein/presentations/lessons"
      Then I should see "Magenta"

  @api @taxonomy @presentation @links
  Scenario: Testing that removed tagged term don't show as links for content type presentation
     Given I am logging in as "john"
       And I unassign the node "Lessons" with the type "presentation" from the term "Magenta"
       And I visit "einstein/presentations/lessons"
      Then I should not see the text "Magenta" under "node-content"

  @api @taxonomy @publication @links
  Scenario: Testing tagged term links for content type publication
     Given I am logging in as "john"
      When I bind the content type "biblio" with "color"
       And I visit "einstein/node/add/biblio"
       And I fill in "Title" with "Manual"
       And I fill in "edit-biblio-year" with "2015"
       And I press "edit-submit"
       And I assign the node "Manual" with the type "biblio" to the term "Magenta"
       And I visit "einstein/publications/manual"
      Then I should see "Magenta"

  @api @taxonomy @publication @links
  Scenario: Testing that removed tagged term don't show as links for content type publication
     Given I am logging in as "john"
       And I unassign the node "Manual" with the type "biblio" from the term "Magenta"
       And I visit "einstein/publications/manual"
      Then I should not see the text "Magenta" under "node-content"

  @api @taxonomy @software @links
  Scenario: Testing tagged term links for content type software project
     Given I am logging in as "john"
      When I bind the content type "software_project" with "color"
       And I visit "einstein/node/add/software-project"
       And I fill in "Project" with "Notes"
       And I press "edit-submit"
       And I assign the node "Notes" with the type "software_project" to the term "Magenta"
       And I visit "einstein/software/notes"
      Then I should see "Magenta"

  @api @taxonomy @software @links
  Scenario: Testing that removed tagged term don't show as links for content type software project
     Given I am logging in as "john"
       And I unassign the node "Notes" with the type "software_project" from the term "Magenta"
       And I visit "einstein/software/notes"
      Then I should not see the text "Magenta" under "node-content"
