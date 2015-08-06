Feature: foo
  Testing the Media Browser

  @media_browser @javascript
  Scenario: Invoke the browser from the standard media field
    Given I am logging in as "john"
      And I wait for page actions to complete
      And I edit the entity "node" with title "John doe biography"
     When I click on the "Upload" control
      And I wait "1 second" for the media browser to open
     Then I should see "Select files to Add"

  @media_browser @javascript
  Scenario: Navigate through tabs
    Given I am logging in as "john"
      And I wait for page actions to complete
      And I edit the entity "node" with title "John doe biography"
     When I click on the "Upload" control
      And I wait "1 second" for the media browser to open
      And I should see "Drag and drop files here."
     When I click on the tab "Previously uploaded files"
      And I should see "No files to show."
     When I click on the tab "Embed from the web"
      And I should see "Enter a URL"

  @javascript
  Scenario: Verify files show up in the "Previously uploaded files" tab
    Given I am logging in as "john"
      And I wait for page actions to complete
      And I edit the node "John doe biography" in the group "john"
     When I click on the "Upload" control
      And I wait "1 second" for the media browser to open
     When I click on "Previously uploaded files" button in the media browser
     Then I should see "kitty.jpg"
