Feature:
  Testing boxes.

  @api @restful
  Scenario: Testing group content type cosuming
    Given I define "john" as a "private" group
     When I consume "api/blog/10" as "demo"
     Then I verify the request "failed"
