Feature: A User should create a Focus Box custom block
  In order for new Focus Box blocks to be placed on a page
  As an Administrator
  I want to be able to create a Focus Box.

  Background:
    Given I am logged in as a user with the "administrator" role
      And I visit "block/add/sf_focus_box"

  @api @javascript
  Scenario: Ensure that the WYSIWYG editor is present.
    Then CKEditor "edit-body-0-value" should exist

  @api @javascript @local_files
  Scenario: Add a Focus Box to the first sidebar region
    When I fill in the following:
      | Title             | Focus Box      |
      And I put "This is a body" into CKEditor
    Given the Administration Toolbar is hidden
    When I press "field_sf_m_block_primary_img-media-library-open-button"
      And I wait for AJAX to finish
    When I attach the file "test_16x9.png" to "files[upload]"
      And I wait for AJAX to finish
      And I fill in "Alternative text" with "test image"
      And I click the Save and insert button
      And I wait for AJAX to finish
      And I press "Save"
    Then I should see the success message "Focus Box FB: Focus Box has been created"
    When I fill in "Title" with "Block Focus Box"
      And I select "First Sidebar" from "Region"
      And I press "Save block"
    Then I should see the success message "The block configuration has been saved."
    When I am on the homepage
    Then I should see "Focus Box" in the "Sidebar First Region"
      And I should see the ".focus-box" element in the "Sidebar First Region"
      And I should see an image in the "Sidebar First Region"
      And I should see "This is a body" in the ".l-sidebar-first .focus-box .field--name-body" element
    Given I delete the most recent custom block
    Then I should see the success message "The custom block FB: Focus Box has been deleted."
