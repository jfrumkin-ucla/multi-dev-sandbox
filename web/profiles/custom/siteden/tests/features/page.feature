@local
Feature: Basic Page Content Type
  Makes sure that the basic page content type was created during installation.

  Background:
    Given I am logged in as a user with the "administrator" role
    When I visit "node/add/sf_page"
      And I fill in the following:
        | Title | Testing title |

  @api
  Scenario: Make sure that the basic page provided by SiteFarm at installation is present.
    Then I should see "Basic page"

  @api @javascript
  Scenario: Ensure that the WYSIWYG editor is present.
    Then CKEditor "edit-body-0-value" should exist

  @api
  Scenario: Ensure that the page Promote to Front page option is hidden.
    Then I should not see a "input[name='promote[value]']" element

  @api
  Scenario: Ensure that the page Create New Revision is checked.
    When I press "Save"
      And I click "Edit"
    Then the "revision" checkbox should be checked

  @api
  Scenario: Ensure that meta tag fields are present.
    Then I should see a "input[name='field_sf_meta_tags[0][basic][title]']" element
      And I should see a "textarea[name='field_sf_meta_tags[0][basic][description]']" element

  @api
  Scenario: A url alias should be auto generated for Basic Pages.
    When I press "Save"
    Then I should see "Testing title" in the "Page Title" region
      And I should be on "/testing-title"

  @api @javascript @local_files
  Scenario: A Primary image should be available to upload.
    When I press "field_sf_featured_media-media-library-open-button"
      And I wait for AJAX to finish
    When I attach the file "test_16x9.png" to "files[upload]"
      And I wait for AJAX to finish
      And I should see "What's the plus sign for?"
      And I fill in "Alternative text" with "test image"
      And I click the Save and insert button
      And I wait for AJAX to finish
    When I press "Save"
    Then I should see "Basic Page Testing title has been created." in the ".alert" element

  @api @javascript
  Scenario: Basic pages should be able to go into the Main Menu
    Given the Administration Toolbar is hidden
    When I press "Menu settings"
      And I check the box "Provide a menu link"
      And I select "<Main navigation>" from "menu[menu_parent]"
      And I press "Save"
    Then I should see "Testing title" in the "Primary Menu Region"

  @api @javascript
  Scenario: Ensure Related Links field is present
    Then I should see a "input[name='field_sf_related_links[0][title]']" element
      And I should see a "input[name='field_sf_related_links[0][uri]']" element

  @api @javascript
  Scenario: Sub Pages should show up in a sidebar sub-nav menu
    Given the Administration Toolbar is hidden
      And a block "system_menu_block:main" is in the "sidebar_second" region
    When I press "Menu settings"
      And I check the box "Provide a menu link"
      And I select "<Main navigation>" from "menu[menu_parent]"
      And I press "Save"
      And I visit "node/add/sf_page"
      And the Administration Toolbar is hidden
      And I fill in the following:
        | Title | Sub Page |
      And I press "Menu settings"
      And I check the box "Provide a menu link"
      And I select "-- Testing title" from "menu[menu_parent]"
      And I press "Save"
    Then I should see the link "Sub Page" in the "Sidebar First Region" region

  @api @javascript
  Scenario: Main menu should not show a menu weight field
    Given the Administration Toolbar is hidden
    When I press "Menu settings"
      And I check the box "Provide a menu link"
    Then I should see a ".form-item-menu-menu-parent" element
      And I should not see a ".form-item-menu-weight" element

  @api
  Scenario: Tags added to an Page
    When I fill in "field_sf_tags[target_id]" with "Tag Test, Tag Test 2"
      And I press "Save"
    Then I should see the link "Tag Test"
    When I click "Edit"
    Then the "field_sf_tags[target_id]" autocomplete field should contain "Tag Test, Tag Test 2"

#  @api
#  Scenario: A default Layout Builder layout should be applied
#    When I fill in the following:
#      | Body              | Body Test                |
#      | menu[enabled]     | 1                        |
#      | menu[title]       | Test                     |
#      | menu[menu_parent] | main:standard.front_page |
#      And I press "Save"
#    Then I should see "Testing title" in the "Title Region"
#      And I should see "Home" in the "Sidebar First Region" region
#      And I should see "Body Test" in the "Content Column Region" region

#  @api
#  Scenario: Each Basic page can have a unique layout via Layout Builder
#    When I press "Save"
#    Then I should see the link "Layout"

#  @api @javascript
#  Scenario: Remote video used in the Fetured Media field should show the video when viewed.
#    When I press "field_sf_featured_media-media-library-open-button"
#    And I wait for AJAX to finish
#    When I click "Video"
#    And I wait for AJAX to finish
#    And I fill in "Add Video via URL" with "https://www.youtube.com/watch?v=g1e11lsrSvw"
#    And I press "Add"
#    And I wait for AJAX to finish
#    And I click the Save and insert button
#    And I wait for AJAX to finish
#    And I select "Published" from "moderation_state[0][state]"
#    When I press "Save"
#    Then I should see "Basic Page Testing title has been created." in the ".alert--success" element
#    When I click "Layout"
#    And I click "Add block "
#    And I wait for AJAX to finish
#    And I click "Featured Media"
#    And I wait for AJAX to finish
#    And I select "Rendered entity" from "settings[formatter][type]"
#    And I wait for AJAX to finish
#    And I press "Add block"
#    And I wait for AJAX to finish
#    Given the Administration Toolbar is hidden
#    And I press "Save layout"
#    Then I should see a ".media--type-sf-video-media-type" element

  @api @javascript
  Scenario: Default meta tags are set for page
    Given I press "field_sf_featured_media-media-library-open-button"
      And I wait for AJAX to finish
      And I attach the file "test_16x9.png" to "files[upload]"
      And I wait for AJAX to finish
      And I fill in "Alternative text" with "test image"
      And I fill in "Caption" with "Test Image"
      And I fill in "media[0][fields][field_sf_media_category][0][target_id]" with "Test"
      And I click the Save and insert button
      And I wait for AJAX to finish
      And I put "This is some body text" into CKEditor
      And I press "Categorizing"
      And I fill in "field_sf_tags[target_id]" with "Tag Test, Tag Test 2"
      And I press "Save"
      And I set the published date to "10/11/12" for the node with the title "Testing title"
      And I set the last updated date to "9/10/11" for the node with the title "Testing title"
      And I set the site name to 'My Awesome Site'
      And I am on "/"
    When I am on "testing-title"
    Then I should see an "title" element
      # Page title, desc., and keywords
      And I should see an "meta[name='description']" element
      And I should see an "meta[content='This is some body text']" element
      And I should see an "meta[name='keywords']" element
      And I should see an "meta[content='Tag Test, Tag Test 2']" element
      # Start of OG meta tags
      # Site name
      And I should see an "meta[property='og:site_name']" element
      And I should see an "meta[content='My Awesome Site']" element
      # Type
      And I should see an "meta[property='og:type']" element
      And I should see an "meta[content='website']" element
      # URL
      And I should see an "meta[property='og:url']" element
      And I should see an "meta[content$='/testing-title']" element
      # Title
      And I should see an "meta[property='og:title']" element
      And I should see an "meta[content='Testing title']" element
      # Image
      And I should see an "meta[property='og:image']" element
      # Image URL
      And I should see an "meta[property='og:image:url']" element
      # Published Time
      And I should see an "meta[property='article:published_time']" element
      And I should see an "meta[content='2012-10-11T00:00:00-07:00']" element
      # Modified Time same as Updated Time
      And I should see an "meta[property='article:modified_time']" element
      And I should see an "meta[content='2011-09-10T00:00:00-07:00']" element
