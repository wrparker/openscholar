Feature: Testing OpenScholar layout.

  @javascript
  Scenario: Test the drag&drop for the layout.
    Given I am logging in as "admin"
     When I visit "/john/cp/build/layout/os_front?destination=home"
      And I drag&drop "boxes-site_logo" to "edit-layout-content-top"
      And I press "edit-submit"
     Then I verify the element "boxes-box-site_logo" under "columns"
      And no boxes display outside the site context
    And  I should see the text "<string>"

  @javascript
  Scenario: Test the removal of items from the manual list.
    Given I am logging in as "admin"
     When the widget "Featured posts" is set in the "Blog" page
     Then I verify the manual list order is kept
