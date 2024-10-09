Feature: Taxonomy Terms
  Check that taxonomy terms behave correctly

  Background:
    Given I am logged in as a user with the "administrator" role
    When I visit "admin/structure/taxonomy/manage/sf_article_category/add"
      And I fill in the following:
        | Name | Testing Term |

  @api
  Scenario: Branding classes are being applied to Article Category term page.
    When I press "Save"
      And I visit "article-category/testing-term"
    Then I should see "Testing Term" in the "Page Title" region
      And I should see an ".category-brand--ucla-blue" element
      And I should see an ".category-brand__title" element

  @api @javascript
  Scenario: Ensure that the WYSIWYG editor is present.
    Then CKEditor "edit-description-0-value" should exist

  @api
  Scenario: A url alias should be auto generated for Terms.
    When I press "Save"
      And I visit "article-category/testing-term"
    Then I should see "Testing Term" in the "Page Title" region
    # Clean up by deleting the term
    When I click "Edit"
      And I click "Delete"
      And I press "Delete"
    Then I should see "Deleted term Testing Term"

  @api @javascript @local_files
  Scenario: A Primary image and Brand color should be available to upload and update.
    When I press "field_sf_m_tax_primary_image-media-library-open-button"
      And I wait for AJAX to finish
    When I attach the file "test_16x9.png" to "files[upload]"
      And I wait for AJAX to finish
      And I fill in "Alternative text" with "test image"
      And I click the Save and insert button
      And I wait for AJAX to finish
      And I select "UCLA Blue" from "Brand Color"
      And I press "Save"
    Then I should see the success message "Testing Term"
    When I click "Testing Term"
    Then I should see an image in the "Content Region"
    # Clean up by deleting the term
    When I visit "article-category/testing-term"
      And I click "Edit"
      And I click "Delete"
      And I press "Delete"
    Then I should see "Deleted term Testing Term"

  @api
  Scenario: All category colors are present in configuration
    And I select "UCLA Blue" from "Brand Color"
    And I select "UCLA Gold" from "Brand Color"
    And I select "Basic Gray" from "Brand Color"
    And I select "Darkest Blue" from "Brand Color"
    And I select "Darker Blue" from "Brand Color"
    And I select "Lighter Blue" from "Brand Color"
    And I select "Lightest Blue" from "Brand Color"
    And I select "Darker Gold" from "Brand Color"
    And I select "Darkest Gold" from "Brand Color"
