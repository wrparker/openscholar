Feature:
  Testing the OS Reports for sites feature

  @api @admin @features_second
  Scenario: Trying to view the report form without the proper access
    Given I am logging in as a user who cannot "access os reports"
          I can't visit "/admin/reports/site"

  @api @admin @features_second
  Scenario: Running a site report with all available optional columns
    Given I am logging in as a user who can "access os reports"
      And I run the "site" report with "Optional Columns" <checkboxes> checked:
          | Creation date               |
          | Created by                  |
          | Date of last content update |
          | Privacy level               |
          | Custom domain               |
          | Site type/preset            |
     Then I will see a report with content in the following <columns>:
          | site title           | populated    |
          | site owner email     | populated    |
          | owner subdomain      | may be blank |
          | os install           | populated    |
          | site created         | populated    |
          | content last updated | populated    |
          | site created by      | populated    |
          | site privacy setting | populated    |
          | custom domain        | populated    |
          | site type (preset)   | populated    |
          
    @api @admin @features_second
    Scenario: Running a site report that searches user email addresses for a keyword
    Given I am logging in as a user who can "access os reports"
      And I run the "site" report with "keyword" set to "gov" and these <checkboxes> checked:
          | email |
     Then I will see a "site" report with the following <rows>:
          | John   | jfk@whitehouse.gov |  |
          | Tesla  | jfk@whitehouse.gov |  |
          | Edison | alexander@bell.gov |  |
          
    @api @admin @features_second
    Scenario: Running a site report that searches user username for a keyword
    Given I am logging in as a user who can "access os reports"
      And I run the "site" report with "keyword" set to "john" and these <checkboxes> checked:
          | username |
     Then I will see a "site" report with the following <rows>:
          | John   | jfk@whitehouse.gov |  |
          | Tesla  | jfk@whitehouse.gov |  |
          
    @api @admin @features_second
    Scenario: Running a site report that searches user site name for a keyword
    Given I am logging in as a user who can "access os reports"
      And I run the "site" report with "keyword" set to "John" and these <checkboxes> checked:
          | site name |
     Then I will see a "site" report with the following <rows>:
          | John   | jfk@whitehouse.gov |  |
          
    @api @admin @features_second
    Scenario: Running a site report that searches user site name for a keyword
    Given I am logging in as a user who can "access os reports"
      And I run the "site" report with "keyword" set to "Rumpelstiltskin" and these <checkboxes> checked:
         | email     |
         | username  |
         | site name |
    Then I will see a "site" report with no results

    @api @admin @features_second
    Scenario: Running a site report limited by content last updated dates
    Given I am logging in as a user who can "access os reports"
      And I run the "site" report with "Content last updated before" set to the "beginning" of this "year"
     Then I will see a "site" report with "Content last updated before" values "greater than or equal" to the "beginning" of this "year"
          
    @api @admin @features_second
    Scenario: Running a site report limited by site creation date start
    Given I am logging in as a user who can "access os reports"
      And I run the "site" report with "creation start" set to the "beginning" of this "year"
     Then I will see a "site" report with "site created" values "greater than or equal" to the "beginning" of this "year"

    @api @admin @features_second
    Scenario: Running a site report limited by site creation date start
    Given I am logging in as a user who can "access os reports"
      And I run the "site" report with "creation start" set to the "end" of this "year"
     Then I will see a "site" report with no results
          
    @api @admin @features_second
    Scenario: Running a site report limited by site creation date end
    Given I am logging in as a user who can "access os reports"
      And I run the "site" report with "creation start" set to the "end" of this "year"
     Then I will see a "site" report with "site created" values "less than or equal" to the "end" of this "year"
          
    @api @admin @features_second
    Scenario: Running a site report limited by site creation date end
    Given I am logging in as a user who can "access os reports"
      And I run the "site" report with "creation start" set to the "beginning" of this "year"
     Then I will see a "site" report with no results

