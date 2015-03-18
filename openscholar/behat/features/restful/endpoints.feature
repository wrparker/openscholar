Feature:
  Check enpoints.

  @api @restful
  Scenario: Testing the layout endpoint
    Given I test the exposed resources:
    """
    api
    api/v1.0/layouts
    api/v1.0/boxes
    api/v1.0/variables
    """
