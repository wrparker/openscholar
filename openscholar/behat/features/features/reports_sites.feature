Feature:
  Testing the OS Reports for sites feature

  @api @reports
  Scenario: Trying to view the report form without the proper access
      Given I am logging in as a user who "can't" "access os reports"
       Then I can't visit "/admin/reports/site"

  @api @reports
  Scenario: Trying to view the report form with the proper access
      Given I am logging in as a user who "can" "access os reports"
        And I go to "/admin/reports/site"
       Then I should see the text "Sites Report"
