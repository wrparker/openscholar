Feature:
  Testing access.

  @api @restful
  Scenario: Testing group content type cosuming
    Given I define "john" as a "private group"
     When I consume "api/blog/12" as "demo"
     Then I verify the request "failed"

  @api @restful @now2
  Scenario: Testing OG audience field population restrictions
    Given I try to post a "blog" as "alexander" to "john"
