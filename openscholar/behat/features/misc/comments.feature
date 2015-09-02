Feature:
  Testing the comment publishing for a blog entry.

  @api @misc_first @comments
  Scenario: Check that a user can create a new blog post with Private comments setting
    Given I am logging in as "john"
     When I visit "john/blog"
      And I click "First blog"
      And I add a comment "Private comment -- lorem ipsum john doe" using the comment form
     Then I should see the text "Private comment -- lorem" under "comment-title"
      And I should see the text "Private comment -- lorem" under "view-os-blog-comments"

  @api @misc_first @comments
  Scenario: Check that a user can set up a blog to use facebook comments
    Given I am logging in as "john"
     When I visit "john/cp/build/features/os_blog"
      And I select the radio button named "comment_sources_blog" with value "fb_social:Facebook Comments"
      And I press "Save configuration"
      And I create a new "blog" entry with the name "Facebook Comments Blog"
      And I logout
      And I visit "john/blog/facebook-comments-blog"
     Then I should see "div" element with the class "fb-social-comments-plugin"