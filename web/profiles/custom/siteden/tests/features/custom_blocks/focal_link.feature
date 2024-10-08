Feature: A User should create a Focal Link custom block
  In order for new focal link blocks to be placed on a page
  As an Administrator
  I want to be able to create a Focal Link.

  Background:
    Given I am logged in as a user with the "administrator" role
      And I visit "block/add/sf_focal_link"

#  @api
#  Scenario: Add a Focal link
#    When I fill in the following:
#      | Title | Focal Link        |
#      | Link  | http://google.com |
#    Given the "Use Default Icons" checkbox should be checked
#    When I press "Save"
#    Then I should see the success message "Focal Link FL: Focal Link has been created"
#    When I fill in "Title" with "Block Focal Link"
#      And I select "First Sidebar" from "Region"
#      And I press "Save block"
#    Then I should see the success message "The block configuration has been saved."
#    When I am on the homepage
#    Then I should see the link "Focal Link" in the "Sidebar First Region"
#    Given I delete the most recent custom block
#    Then I should see the success message "The custom block FL: Focal Link has been deleted."

  @api @javascript @local_files
  Scenario: Add a Focal link with an uploaded photo for the icon
    When I fill in the following:
      | Title | Focal Link 2        |
      | Link  | http://google.com 2 |
    Given the Administration Toolbar is hidden
    Given the "Use Default Icons" checkbox should be checked
      And I should not see "Custom Image" in the ".field--name-field-sf-m-block-primary-img" element
      And I uncheck the box "Use Default Icons"
      And I wait for AJAX to finish
    When I press "field_sf_m_block_primary_img-media-library-open-button"
      And I wait for AJAX to finish
    When I attach the file "test_16x9.png" to "files[upload]"
      And I wait for AJAX to finish
      And I fill in "Alternative text" with "test image"
      And I fill in "Caption" with "Test Image"
      And I fill in "media[0][fields][field_sf_media_category][0][target_id]" with "Test"
      And I click the Save and insert button
      And I wait for AJAX to finish
      And I press "Save"
    Then I should see the success message "Focal Link FL: Focal Link 2 has been created"
    When I fill in "Title" with "Block Focal Link 2"
      And I select "Second Sidebar" from "Region"
      And I press "Save block"
    Then I should see the success message "The block configuration has been saved."
    When I am on the homepage
    Then I should see the link "Focal Link" in the "Sidebar Second Region"
      And I should not see the ".focal-link__icon" element in the "Sidebar Second Region"
      And I should see an image in the "Sidebar Second Region"
    Given I delete the most recent custom block
    Then I should see the success message "The custom block FL: Focal Link 2 has been deleted."
    Given I visit "admin/content/media"
      And I click "test_16x9.png"
      And I click "Delete"
      And I press "Delete"
    Then I should see "The media item test_16x9.png has been deleted."

#  @api
#  Scenario: Add a Material Icon
#    Given I fill in the following:
#      | Title | Focal Link 3        |
#      | Link  | http://google.com 3 |
#      And the "Use Default Icons" checkbox should be checked
#      And I select "add" from "Icons"
#      And I press "Save"
#      And I should see the success message "Focal Link FL: Focal Link 3 has been created"
#    When I fill in "Title" with "Block Focal Link 3"
#      And I select "Second Sidebar" from "Region"
#      And I press "Save block"
#      And I should see the success message "The block configuration has been saved."
#      And I am on the homepage
#    Then I should see "Focal Link 3"
#      And I should see an "span.material-icons" element
#      And the ".layout__sidebar-second span.material-icons" element should contain "add"
#      And I delete the most recent custom block
#      And I should see the success message "The custom block FL: Focal Link 3 has been deleted."
