Feature: A User should create a Branding block
  In order for new branding blocks to be placed on a page
  As an Administrator
  I want to be able to create a Branding block.

  Background:
    Given I am logged in as a user with the "administrator" role
    And I visit "media/add/sf_svg_media_type"
    And I fill in the following:
      | Name | Site Logo SVG |

  @api @javascript @local_files
  Scenario: Add a Branding block to the header of the site
    Given I attach the file "test.svg" to "files[field_media_svg_0]"
      And I wait for AJAX to finish
      And I fill in "field_media_svg[0][alt]" with "alt text"
      And I press "Save"
    When I visit "block/add/sf_branding_block"
      And I fill in the following:
        | Site Title    | Welcome to Your New SiteDen Site |
        | SVG Logo Link | http://google.com                |
      And the Administration Toolbar is hidden
      And I press "Add media"
      And I wait for AJAX to finish
      And I check the box "media_library_select_form[0]"
      And I click the Insert selected button
      And I press "Save"
      And I select "Header" from "Region"
      And I press "Save block"
      And I visit "/"
    Then I should see "Welcome to Your New SiteDen Site" in the ".branding" element
      And I should see an ".branding .branding__figure a img" element
    Then I delete the most recent custom block
      And I visit "admin/content/media"
      And I click "Site Logo SVG"
      And I click "Delete"
      And I press "Delete"
      And I should see "The media item Site Logo SVG has been deleted."

