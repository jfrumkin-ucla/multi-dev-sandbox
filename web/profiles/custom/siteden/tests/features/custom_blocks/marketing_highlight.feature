Feature: A User should create a Marketing Highlight custom block
  In order for new marketing highlight blocks to be placed on a page
  As an Administrator
  I want to be able to create a Marketing Highlight.

  Background:
    Given I am logged in as a user with the "administrator" role
      And I visit "block/add/sf_marketing_highlight"
      And I fill in the following:
        | Title          | Marketing Highlight |
        | Link           | http://google.com   |
        | Description    | Short Description   |
        | Call to Action | Click This Block    |

  @api @javascript @local_files
  Scenario: Add a standard Marketing Highlight custom block
    When I press "field_sf_m_block_primary_img-media-library-open-button"
      And I wait for AJAX to finish
    When I attach the file "test_16x9.png" to "files[upload]"
      And I wait for AJAX to finish
      And I fill in "Alternative text" with "test image"
      And I click the Save and insert button
      And I wait for AJAX to finish
      And I press "Save"
    Then I should see the success message "Marketing Highlight MH: Marketing Highlight has been created"
    When I fill in "Title" with "Block Marketing Highlight"
      And I select "First Sidebar" from "Region"
      And I press "Save block"
    Then I should see the success message "The block configuration has been saved."
    When I am on the homepage
    Then I should see the link "Marketing Highlight" in the "Sidebar First Region"
      And I should see the ".marketing-highlight" element in the "Sidebar First Region"
      And I should not see the ".marketing-highlight__type" element in the "Sidebar First Region"
    Given I delete the most recent custom block
    Then I should see the success message "The custom block MH: Marketing Highlight has been deleted."
