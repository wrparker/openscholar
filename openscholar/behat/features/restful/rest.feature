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

  @api @rest
  Scenario: Creating a box.
    Given I create a "box" with the settings:
      | Title       | vsite   | widget            | delta       | options[description]  |
      | Dummy box   | 2       | os_taxonomy_fbt   | 1419342352  | Terms                 |
