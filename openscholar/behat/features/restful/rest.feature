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
    Given I create a "box" with the settings:
      | Site    | Widget            | Description  |
      | john    | os_taxonomy_fbt   | Terms        |
