Feature:
  Testing the file deletion behavior.

  @api @misc_first
  Scenario: Verify that the "Used in" column is populated correctly in the
            "cp_files" view.
    Given I am logging in as "john"
     When I visit "john/cp/content/files"
      And I should see "Example gallery" in the "used in" column
      And I delete the node of type "media_gallery" named "Another Kittens gallery"
      And I visit "john/cp/content/files"
          # Verify that the files are not being deleted.
     Then I should see "slideshow7.jpg"
      And I should see "slideshow8.jpg"
      And I should see "slideshow9.jpg"
