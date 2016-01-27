Feature:
  Testing the page metatags.

  @api @vsite
  Scenario: Make sure the canonical and shortlink metatags have the correct url.
    Given I am logging in as "john"
     When I visit "john/about"
     Then I validate the following metatags:
        | canonical |
        | shortlink |
