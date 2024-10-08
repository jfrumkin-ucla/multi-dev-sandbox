@local_files @javascript
Feature: Photo Gallery Content Type
  Makes sure that the photo gallery content type was created during installation.

  Background:
    Given I am logged in as a user with the "administrator" role
      And "sf_photo_gallery_categories" terms:
        | name          |
        | Test Category |
        | Second Term   |
    When I visit "node/add/sf_photo_gallery"
      And I fill in the following:
        | Title | Gallery Name |
      And I press "field_sf_m_primary_image-media-library-open-button"
      And I wait for AJAX to finish
    When I attach the file "test_16x9.png" to "files[upload]"
      And I wait for AJAX to finish
      And I fill in "Alternative text" with "test image"
      And I fill in "Caption" with "Test Image"
      And I fill in "media[0][fields][field_sf_media_category][0][target_id]" with "Test"
      And I click the Save and insert button
      And I scroll to the "#field_sf_m_primary_image-media-library-wrapper" element
      Then I should see "test_16x9"
    When I press "field_sf_m_photos-media-library-open-button"
      And I wait for AJAX to finish
    When I attach the file "test_16x9.png" to "files[upload][]"
      And I wait for AJAX to finish
      And I fill in "Alternative text" with "test image"
      And I fill in "Name" with "Test Gallery Image"
      And I fill in "Caption" with "Test Gallery Image"
      And I fill in "media[0][fields][field_sf_media_category][0][target_id]" with "Test"
      And I click the Save and insert button
      Then I should see "Test Gallery Image"
    And I select "Published" from "moderation_state[0][state]"
    When I press "Save"
      Then I should see "Photo Gallery Gallery Name has been created."

  @api
  Scenario: Make sure that the photo gallery type provided by SiteFarm at installation is present.
    Then I should see "Photo Gallery"

  @api
  Scenario: Social share buttons on Photo Gallery page
    Given "sf_photo_gallery" content:
      | title                |
      | Social Photo Gallery |
    When I visit "photo-galleries/social-photo-gallery"
    Then I should see a ".at-icon-facebook" element
      And I should see a ".at-icon-twitter" element
      And I should see a ".at-icon-email" element
      And I should see a ".at-icon-addthis" element

  @api
  Scenario: Ensure that the Promote to Front page option is hidden.
    Then I should not see a "input[name='promote[value]']" element

  @api @javascript
  Scenario: Ensure that the custom help text is shown.
    Given I click "Edit"
      And I scroll to the "#field_sf_m_photos-media-library-wrapper" element
      And I press "field_sf_m_photos-media-library-open-button"
      And I wait for AJAX to finish
    When I attach the file "test_16x9.png" to "files[upload][]"
      And I wait for AJAX to finish
    Then I should see "What's the plus sign for?"

  @api
  Scenario: Classify Galleries with a single Category taxonomy.
    Given I click "Edit"
    And the Administration Toolbar is hidden
    And I click the "#edit-categorizing summary.seven-details__summary" element
    When I select "Test Category" from "field_sf_gallery_category"
      And I press "Save"
    Then I should see the link "Test Category"

  @api
  Scenario: Photo Gallery Category filter block displays categories.
  Given a block "views_block:sf_photo_gallery_category_filter-block_1" is in the "sidebar_second" region
  When I visit "photo-galleries"
  Then I should see "Filter by Category" in the "Sidebar Second Region"
    And I should see the link "Test Category" in the "Sidebar Second Region"
    And I should see the link "Second Term" in the "Sidebar Second Region"

  @api
  Scenario: Photo Galleries poster displays
    When I visit "photo-galleries"
    Then I should see "Gallery Name" in the ".node--view-mode-poster" element

  @api
  Scenario: Slideshow Photo Gallery block added to a region
    Given I visit "admin/structure/block/add/slideshow_gallery_block/siteden_surface?region=sidebar_first"
      And I reference "node" "Gallery Name" in "Gallery Title"
      And the "settings[display][show_title]" checkbox should be checked
      And the "settings[display][slider_nav]" checkbox should not be checked
      And I press "Save block"
    When I am on the homepage
    Then I should see "Slideshow Photo Gallery" in the "Sidebar First Region"
      And the element ".slideshow" should exist
      And I should see "Gallery Name" in the "Sidebar First Region"
    # Edit
    When I visit "admin/structure/block/manage/slideshowphotogallery"
      And I uncheck the box "settings[label_display]"
      And I uncheck the box "settings[display][show_title]"
      And I check the box "settings[display][slider_nav]"
      And I press "Save block"
    When I am on the homepage
    Then I should not see "Slideshow Photo Gallery" in the "Sidebar First Region"
      And I should not see "Gallery Name" in the "Sidebar First Region"
      And the element ".slideshow" should exist
      And the element ".slider-nav" should exist
    # Delete the block
    When I visit "admin/structure/block/manage/slideshowphotogallery/delete"
      And I press "Remove"
    Then I should see "The block Slideshow Photo Gallery has been removed"

  @api
  Scenario: Make sure filter exists on landing and active class added when filtering
    Given a block "views_block:sf_photo_gallery_category_filter-block_1" is in the "sidebar_second" region
    When I visit "photo-galleries"
      And I should see "Filter by Category"
      And I follow "Test Category"
    Then I should see a ".category-filter__list-item--active" element

  @api
  Scenario: Make sure filter is not on Photo Gallery page
    Then I should not see an ".region-sidebar-first" element

  @api
  Scenario: Site name meta tag is set
    Given I set the site name to 'My Awesome Site'
      And I am on "/"
    When I am on "photo-galleries/gallery-name"
    Then I should see an "meta[property='og:site_name']" element
      And I should see an "meta[content='My Awesome Site']" element

  @api
  Scenario: photo gallery publish and update meta tags exist
    Given I set the published date to "10/11/12" for the node with the title "Gallery Name"
      And I set the last updated date to "9/10/11" for the node with the title "Gallery Name"
      And I am on "/"
    When I am on "photo-galleries/gallery-name"
    # Published Time
    Then I should see an "meta[property='article:published_time']" element
      And I should see an "meta[content='2012-10-11T00:00:00-07:00']" element
      # Modified Time same as Updated Time
      And I should see an "meta[property='article:modified_time']" element
      And I should see an "meta[content='2011-09-10T00:00:00-07:00']" element

  @api @javascript
  Scenario: Default meta tags are set for photo galleries
    Given I am on the node edit page for the node with the title "Gallery Name"
      And the Administration Toolbar is hidden
      And I scroll to the "#edit-categorizing" element
      And I press "Categorizing"
      And I fill in "field_sf_tags[target_id]" with "Tag Test, Tag Test 2"
      And I select "Test Category" from "field_sf_gallery_category"
      And I put "This is some body text" into CKEditor
      And I press "Save"
    Then I should see an "title" element
      # Page title, desc., and keywords
      And I should see an "meta[name='description']" element
      And I should see an "meta[content='This is some body text']" element
      And I should see an "meta[name='keywords']" element
      And I should see an "meta[content='Tag Test, Tag Test 2']" element
      # Start of OG meta tags
      # Type
      And I should see an "meta[property='og:type']" element
      And I should see an "meta[content='photo gallery']" element
      # URL
      And I am on "photo-galleries/gallery-name"
      And I should see an "meta[property='og:url']" element
      And I should see an "meta[content$='/photo-galleries/gallery-name']" element
      # Title
      And I should see an "meta[property='og:title']" element
      And I should see an "meta[content='Gallery Name']" element
      # Image
      And I should see an "meta[property='og:image']" element
      # Image URL
      And I should see an "meta[property='og:image:url']" element
