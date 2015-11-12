Feature: Testing OpenScholar layout.

  @javascript
  Scenario: Test the drag&drop for the layout.
    Given I am logging in as "admin"
     When I visit "/john/cp/build/layout/os_front?destination=home"
      And I drag&drop "boxes-site_logo" to "edit-layout-content-top"
      And I press "edit-submit"
     Then I verify the element "boxes-box-site_logo" under "columns"
      And no boxes display outside the site context

  @javascript @now2
  Scenario: Testing the remove link for menu in the menu section.
    Given I am logging in as "john"
     When I visit "/john"
      And I remove the menu from the menu section
      And I wait the text "This widget has been removed from this section" to appear
      And I click on the undo widget
     When I refresh the page
     Then I verify the menu exists
