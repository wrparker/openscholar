Feature: foo
  Testing the Media Browser

  @media_browser @javascript
  Scenario: Invoke the browser from the standard media field
    Given I am logging in as "john"
      And I wait for page actions to complete
      And I edit the entity "node" with title "About"
     When I click on the "Upload" control
      And I wait "1 second" for the media browser to open
     Then I should see "Select files to Add"

  @media_browser @javascript
  Scenario: Navigate through tabs
    Given I am logging in as "john"
      And I wait for page actions to complete
      And I edit the entity "node" with title "About"
     When I click on the "Upload" control
      And I wait "1 second" for the media browser to open
      And I should see "Drag and drop files here."
     When I click on the tab "Previously uploaded files"
      And I should see "Filename"
     When I click on the tab "Embed from the web"
      And I should see "URL or HTML:"

  @media_browser @javascript
  Scenario: Verify files show up in the "Previously uploaded files" tab
    Given I am logging in as "john"
      And I wait for page actions to complete
      And I edit the node "About" in the group "john"
     When I click on the "Upload" control
      And I wait "1 second" for the media browser to open
     When I click on "Previously uploaded files" button in the media browser
     Then I should see "slideshow1.jpg"

  @media_browser @javascript
  Scenario: Test the file upload work flow for a single, valid file
    Given I am logging in as "john"
      And I wait for page actions to complete
      And I edit the node "About" in the group "john"
     When I click on the "Upload" control
      And I wait "1 second" for the media browser to open
      And I drop the file "kitten-2.jpg" onto the "Drag and drop files here." area
      And I should wait for "File Edit" directive to "appear"
     When I click on the "Save" control
     Then I should see the media browser "Previously uploaded files" tab is active
      And I should see "kitten-2.jpg" in the "div.media-row.new" element