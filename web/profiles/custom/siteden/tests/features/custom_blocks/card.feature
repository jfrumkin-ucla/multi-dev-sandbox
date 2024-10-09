Feature: A User should create a Card custom block
  In order for new Card blocks to be placed on a page
  As an Administrator
  I want to be able to create a Card.

  Background:
    Given I am logged in as a user with the "administrator" role
      And I visit "block/add/sf_card"

  @api @javascript
  Scenario: Add a Card to the content region
    When I fill in the following:
      | Title        | Card block type title   |
      And I put "This is a Card body" into CKEditor
      And I fill in "field_sf_card_link[0][uri]" with "http://ucdavis.edu"
      And I fill in "field_sf_card_link[0][title]" with "Call to Action"
    Given the Administration Toolbar is hidden
    When I press "field_sf_card_image-media-library-open-button"
      And I wait for AJAX to finish
    When I attach the file "test_16x9.png" to "files[upload]"
      And I wait for AJAX to finish
      And I fill in "Alternative text" with "test image"
      And I click the Save and insert button
      And I wait for AJAX to finish
      And I press "Save"
    Then I should see the success message "Card C: Card block type title has been created"
    When I fill in "Title" with "Block Card"
      And I select "White background" from "Choose a Card Style"
      And I select "Content" from "Region"
      And I press "Save block"
    Then I should see the success message "The block configuration has been saved."
    When I am on the homepage
    Then I should see "Card block type title" in the "Content"
      And I should see the ".field--name-field-sf-card-image" element in the "Content" region
      And I should see "This is a Card body" in the "Content" region
      And I should see the link "Call to Action" in the "Content" region
    Given I delete the most recent custom block
    Then I should see the success message "The custom block C: Card block type title has been deleted."
