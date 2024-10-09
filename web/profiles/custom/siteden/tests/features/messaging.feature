Feature: Messaging System
  Make sure messaging system is styled and has region.

  Background:
    Given I am logged in as a user with the "administrator" role
    Given I visit "node/add/sf_page"
    And I fill in the following:
      | Title | Testing title |
    When I press "Save"

  @api
  Scenario: Message region exists
    Then I should see an ".region-message" element

  @api
  Scenario: Status message is styled and in Message region
    Then I should see the ".alert--success" element in the "Message Region" region
