Feature:
  Testing the media dialog page

  @api @javascript @foo
  Scenario: Verify the "Upload" button is displayed when opening the media
            dialog page.
    Given I am logging in as "john"
     When I edit the node "First blog"
      And I click "Browse"
      And I wait for page actions to complete
     Then I should see "Upload"
