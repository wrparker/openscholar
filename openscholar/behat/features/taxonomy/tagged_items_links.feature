Feature:
  Testing that content types should show tagged taxonomy terms on their node view page.

  @api @taxonomy
  Scenario: Testing tagged term links for content type bio
     Given I am logging in as "john"
      When I create the vocabulary "color" in the group "einstein" assigned to bundle "bio"
       And I visit "einstein/cp/build/taxonomy/color/add"
       And I fill in "Name" with "Red"
       And I press "edit-submit"
       And I create a new "bio" entry with the name "Albert" in the group "einstein"
       And I assign the node "Albert" with the type "bio" to the term "Red"
       And I visit "einstein/einstein"
      Then I should see "Red"

  @api @taxonomy
  Scenario: Testing that removed tagged term don't show as links for content type bio
    Given I am logging in as "john"
      And I unassign the node "Albert" with the type "bio" from the term "Red"
      And I visit "einstein/einstein"
     Then I should not see the text "Red" under "node-content"

