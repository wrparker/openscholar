Feature:
  Checking the publication author date date picker.

  @javascript
  Scenario: Checking the publication author date date picker.
    Given I am logging in as "john"
      And I go to the "os_publications" app settings in the vsite "john"
      And I select the radio button named "biblio_citeproc_style" with value "harvard-chicago-author-date.csl"
      And I press "Save configuration"
     When I visit "john/node/add/biblio"
      And I am verifying the date picker behaviour
