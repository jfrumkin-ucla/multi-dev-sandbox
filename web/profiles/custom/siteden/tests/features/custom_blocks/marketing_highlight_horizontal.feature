Feature: A User should create a Marketing Highlight - Horizontal  custom block
  In order for new Marketing Highlight - Horizontal blocks to be placed on a page
  As an Administrator
  I want to be able to create a Marketing Highlight - Horizontal.

  Background:
    Given I am logged in as a user with the "administrator" role
      And I visit "block/add/sf_marketing_highlight_horizntl"
      And I fill in the following:
        | Title             | Marketing Highlight - Horizontal |
        | Call to Action    | My call to action                |
        | Link              | http://google.com                |

  @api @javascript @local_files
  Scenario: Add a standard Marketing Highlight - Horizontal custom block
    When I press "field_sf_m_block_primary_img-media-library-open-button"
      And I wait for AJAX to finish
    When I attach the file "test_16x9.png" to "files[upload]"
      And I wait for AJAX to finish
      And I fill in "Alternative text" with "test image"
      And I click the Save and insert button
      And I wait for AJAX to finish
      And I press "Save"
    Then I should see the success message "Marketing Highlight - Horizontal MHH: Marketing Highlight - Horizontal has been created"
    Given the Administration Toolbar is hidden
    When I fill in "Title" with "Block Marketing Highlight - Horizontal"
      And I select "First Sidebar" from "Region"
      And I press "Save block"
    Then I should see the success message "The block configuration has been saved."
    When I am on the homepage
    Then I should see the link "My call to action" in the "Sidebar First Region"
      And I should see the ".marketing-highlight-horizontal" element in the "Sidebar First Region"
    Given I delete the most recent custom block
    Then I should see the success message "The custom block MHH: Marketing Highlight - Horizontal has been deleted."
    Given I visit "admin/content/media"
      And I click "test_16x9.png"
      And I click "Delete"
      And I press "Delete"
    Then I should see "The media item test_16x9.png has been deleted."
