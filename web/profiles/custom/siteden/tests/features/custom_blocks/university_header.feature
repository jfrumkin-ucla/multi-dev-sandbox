Feature: A User should create a University Header custom block
  In order for new University Header block to be placed on a page
  As an Administrator
  I want to be able to create a University Header.

  Background:
    Given I am logged in as a user with the "administrator" role
      And I visit "block/add/sf_university_header"

  @api @javascript @local_files
  Scenario: Add a University Header to the header region
    When I fill in the following:
      | Block description | Test University Header |
      And I fill in "field_sf_departmental_breadcrumb[0][uri]" with "<front>"
      And I fill in "field_sf_departmental_breadcrumb[0][title]" with "Department Name"
      And I press "Save"
    Then I should see the success message "University Header Test University Header has been created."
    When I fill in "Title" with "Custom University Header"
      And I select "University Header" from "Region"
      And I press "Save block"
    Then I should see the success message "The block configuration has been saved."
    When I am on the homepage
    Then I should see "Department Name" in the ".university-header__text" element
    Given I delete the most recent custom block
    Then I should see the success message "The custom block Test University Header has been deleted."
