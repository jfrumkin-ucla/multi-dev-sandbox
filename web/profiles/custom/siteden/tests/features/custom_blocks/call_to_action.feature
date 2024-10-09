#Feature: A User should create a Call to Action custom block
#  In order for new call to action blocks to be placed on a page
#  As an Administrator
#  I want to be able to create a Call to Action.
#
#  Background:
#    Given I am logged in as a user with the "administrator" role
#    And I visit "block/add/sf_call_to_action"
#    And I fill in the following:
#      | Text          | Testing Call to Action |
#      | URL           | http://google.com      |
#      | Link text     | Button Text            |
#
#  @api
#  Scenario: Add a Call to Action custom block
#    And I press "Save"
#    Then I should see the success message "Call to Action CTA: Testing Call to Action has been created."
#    When I fill in "Title" with "Block Call to Action"
#    And I select "Full Width Bottom" from "Region"
#    And I press "Save block"
#    Then I should see the success message "The block configuration has been saved."
#    When I am on the homepage
#    Then I should see the link "Button Text" in the "Full Width Bottom Region"
#    And I should see the ".give-now" element in the "Full Width Bottom Region"
#    Given I delete the most recent custom block
#    Then I should see the success message "The custom block CTA: Testing Call to Action has been deleted."
