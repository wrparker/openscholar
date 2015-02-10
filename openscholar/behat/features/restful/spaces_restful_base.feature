Feature:
  Testing boxes, layouts and variable end points.

  @api @restful
  Scenario: Testing the layout endpoint
    Given I test the exposed resources:
    """
    api
    api/v1.0/layouts
    api/v1.0/boxes
    api/v1.0/variables
    """

  @api @restful
  Scenario: CRUD-ind a box.
    Given I "create" a box as "john" with the settings:
      | Site    | Widget  | Description  |
      | john    | Terms   | Terms        |
    When I "update" a box as "john" with the settings:
      | Site    | Widget  | Description  | Delta |
      | john    | Terms   | Terms - new  | PREV  |
    Then I "delete" a box as "john" with the settings:
      | Site    | Widget  | Description  | Delta |
      | john    | Terms   | Terms - new  | PREV  |

  @api @restful
  Scenario: CRUD-ing a layout.
    Given I "create" a layout as "john" with the settings:
      | Site | Context  | Box |
      | john | os_front | Bio |
      And I visit "john"
      And I should see the text "Work in gizra inc." under "region-sidebar-second"
     When I "update" a layout as "john" with the settings:
      | Site | Context  | Box | Delta |
      | john | os_front | Bio | PREV  |
    And I visit "john"
    And I should see the text "gizra" under "region-sidebar-first"
    When I "delete" a layout as "john" with the settings:
      | Site | Context                  | Delta |
      | john | os_front:reaction:block  | PREV  |
    And I visit "john"
    And I should see the text "Work in gizra inc." under "region-content-top"
