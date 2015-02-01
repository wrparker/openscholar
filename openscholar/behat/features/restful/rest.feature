Feature:
  Testing rest endpoint

  @api @rest
  Scenario: Testing the layout endpoint
    Given I test the exposed resources:
    """
    api
    api/v1.0/layouts
    api/v1.0/boxes
    api/v1.0/variables
    """

  @api @rest @now
  Scenario: Creating a box.
    Given I create a "box" as "john" with the settings:
      | Site    | Widget  | Description  |
      | john    | Terms   | Terms        |
