Feature: foo
  Testing the Media Browser

  @media_browser @javascript
  Scenario: Invoke the browser from the standard media field
    Given I am logging in as "john"
      And I wait for page actions to complete
      And I start creating a post of type "page" in site "john"
     When I click "Choose File"
      And I wait "1 second" for the media browser to open

  @media_browser @javascript
  Scenario: Invoke the browser from the wysiwyg
    Given I am logging in as "john"
      And I wait for page actions to complete
      And I visit "john/node/add/page"
     When I click "Choose File"
      And I wait for page actions to complete
     Then I should see "Select files to Add"

  @media_browser @javascript
  Scenario: Navigate through tabs
    Given I am logging in as "john"
      And I wait for page actions to complete
      And I start creating a post of type "page" in site "john"
      And I click "Choose File"
      And I wait "1 second" for the media browser to open
     Then I should see "Drag and drop files here."
     When I click on "Web" button in the media browser
     Then I should see "URL or HTML"
     When I click on "Library" button in the media browser
      And I should see "Type in a filename or label to filter list of files"

  @media_browser @javascript
  Scenario: Verify files show up in the "Previously uploaded files" tab
    Given I am logging in as "john"
      And I wait for page actions to complete
      And I edit the node "John doe biography" in the group "john"
      And I click "Choose File"
      And I wait "1 second" for the media browser to open
     When I click on "Previously uploaded files" button in the media browser
     Then I should see "kitty.jpg"

  @media_browser @javascript @wip
  Scenario: Upload a new file with no duplicates by pressing the button
    Given I am logging in as "john"
      And I wait for page actions to complete
      And I start creating a post of type "page" in site "john"
      And I click "Choose File"
      And I wait "1 second" for the media browser to open
     When I press "Upload"
