Feature: Event Content Type
  Makes sure that the event content type was created during installation.

  Background:
    Given I am logged in as a user with the "administrator" role
    When I visit "node/add/sf_event"
      And I fill in the following:
        | Title                               | Testing title |
        | field_sf_dates[0][value][date]      | 2030-06-01    |
        | field_sf_dates[0][value][time]      | 05:06:22      |
        | field_sf_dates[0][end_value][date]  | 2030-06-01    |
        | field_sf_dates[0][end_value][time]  | 06:06:22      |

  @api
  Scenario: Make sure that the Event content type provided by SiteFarm at installation is present.
    Then I should see "Event"

  @api
  Scenario: Events should not able to go into the Main Menu
    Then I should not see the link "Menu settings"

  @api
  Scenario: Ensure that the event Promote to Front page option is hidden.
    Then I should not see a "input[name='promote[value]']" element

  @api
  Scenario: Ensure that the event Create New Revision is checked.
    When I press "Save"
      And I click "Edit"
    Then the "revision" checkbox should be checked

  @api
  Scenario: Ensure that meta tag fields are present.
    Then I should see a "input[name='field_sf_meta_tags[0][basic][title]']" element
    And I should see a "textarea[name='field_sf_meta_tags[0][basic][description]']" element

  @api
  Scenario: A url alias should be auto generated for Events.
    When I press "Save"
    Then I should see "Testing title" in the "Page Title" region
    And I should be on "events/testing-title"

  @api @javascript
  Scenario: Ensure that the WYSIWYG editor is present.
    Then CKEditor "edit-body-0-value" should exist

#  Don't use javascript here due to an issue with the time field acting weird
  @api @local_files @javascript
  Scenario: A Primary image should be available to upload.
    Given the Administration Toolbar is hidden
    When I wait for AJAX to finish
      And I press "field_sf_featured_media-media-library-open-button"
      And I wait for AJAX to finish
    When I attach the file "test_16x9.png" to "files[upload]"
      And I wait for AJAX to finish
      And I fill in "Alternative text" with "alt text"
      And I click the Save and insert button
      And I wait for AJAX to finish
    When I fill in "field_sf_dates[0][value][date]" with "01/02/2030"
      And I fill in "field_sf_dates[0][value][time]" with "08:00AM"
      And I fill in "field_sf_dates[0][end_value][date]" with "01/02/2030"
      And I fill in "field_sf_dates[0][end_value][time]" with "12:00PM"
    When I press "Save"
    Then I should see an image in the "Content" region
      And I should see the image alt "alt text" in the "Content" region

  @api
  Scenario: Classify Events with a single Event Type taxonomy
    Given "sf_event_type" terms:
      | name                |
      | Event Test Category |
      | Second Event Term   |
    When I visit "node/add/sf_event"
      And I fill in the following:
        | Title                               | Testing title |
        | field_sf_dates[0][value][date]      | 2016-06-01    |
        | field_sf_dates[0][value][time]      | 05:06:22      |
        | field_sf_dates[0][end_value][date]  | 2016-06-01    |
        | field_sf_dates[0][end_value][time]  | 06:06:22      |
      And I select "Event Test Category" from "field_sf_event_type"
      And I press "Save"
    Then I should see the link "Event Test Category" in the "Content" region
    When I click "Edit"
    Then the "field_sf_event_type" select should be set to "Event Test Category"

  @api
  Scenario: Tags added to an Event
    When I fill in "field_sf_tags[target_id]" with "Tag Test, Tag Test 2"
      And I press "Save"
    Then I should see the link "Tag Test" in the "Content" region
      And I should see the link "Tag Test 2" in the "Content" region
    When I click "Edit"
    Then the "field_sf_tags[target_id]" autocomplete field should contain "Tag Test, Tag Test 2"

  @api
  Scenario: Locations on an Event
    When I fill in "field_sf_event_location[0][value]" with "My current Location"
      And I press "Save"
    Then I should see "My current Location" in the "Content" region

  @api
  Scenario: Map Location Link on an Event
    When I fill in "field_sf_event_map_link[0][uri]" with "http://campusmap.ucdavis.edu/?b=107"
      And I fill in "field_sf_event_map_link[0][title]" with "Location for the event"
      And I press "Save"
    Then I should see the link "Location for the event" in the "Content" region

  @api @javascript
  Scenario: Social share buttons on Event
    Given "sf_event" content:
      | title        | field_sf_dates:value | field_sf_dates:end_value |
      | Social Event | 2016-06-01T05:06:22  | 2016-06-01T06:06:22      |
    When I visit "events/social-event"
    Then I should see a ".at-icon-facebook" element
      And I should see a ".at-icon-twitter" element
      And I should see a ".at-icon-email" element
      And I should see a ".at-icon-addthis" element

  @api
  Scenario: Category filter events landing and active state should be applied to filtered page filter item
    Given "sf_event_type" terms:
      | name                |
      | Event Test Category |
      | Second Event Term   |
      And I visit "node/add/sf_event"
      And I fill in the following:
        | Title                               | Testing title |
        | field_sf_dates[0][value][date]      | 2016-06-01    |
        | field_sf_dates[0][value][time]      | 05:06:22      |
        | field_sf_dates[0][end_value][date]  | 2016-06-01    |
        | field_sf_dates[0][end_value][time]  | 06:06:22      |
      And I select "Event Test Category" from "field_sf_event_type"
      And I press "Save"
    When I visit "events"
      And I should see "Categories"
      And I follow "Event Test Category"
    Then I should see a ".category-filter__list-item--active" element

  @api
  Scenario: Category filter should not be on event page and if no sidebar items there should be no sidebar
    Given "sf_event_type" terms:
      | name                |
      | Event Test Category |
      | Second Event Term   |
    And I visit "node/add/sf_event"
    And I fill in the following:
      | Title                               | Testing title |
      | field_sf_dates[0][value][date]      | 2016-06-01    |
      | field_sf_dates[0][value][time]      | 05:06:22      |
      | field_sf_dates[0][end_value][date]  | 2016-06-01    |
      | field_sf_dates[0][end_value][time]  | 06:06:22      |
    And I select "Event Test Category" from "field_sf_event_type"
    When I press "Save"
    Then I should not see an ".region-sidebar-first" element

  @api
  Scenario: Site name meta tag is set
    Given I press "Save"
      And I set the site name to 'My Awesome Site'
      And I am on "/"
    When I am on "events/testing-title"
    Then I should see an "meta[property='og:site_name']" element
      And I should see an "meta[content='My Awesome Site']" element

  @api
  Scenario: Event publish and update meta tags exist
    Given I press "Save"
      And I set the published date to "10/11/12" for the node with the title "Testing title"
      And I set the last updated date to "9/10/11" for the node with the title "Testing title"
      And I am on "/"
    When I am on "events/testing-title"
      And I should see an "meta[property='article:published_time']" element
      And I should see an "meta[content='2012-10-11T00:00:00-07:00']" element
      And I should see an "meta[property='article:modified_time']" element
      And I should see an "meta[content='2011-09-10T00:00:00-07:00']" element

  @api @javascript
  Scenario: Default meta tags are set for event
    Given "sf_event" content:
      | title     | field_sf_dates:value | field_sf_dates:end_value |
      | New Event | 2016-06-01T05:06:22  | 2016-06-01T06:06:22      |
      And I am on the node edit page for the node with the title "New Event"
      And the Administration Toolbar is hidden
      And I scroll to the "#edit-categorizing" element
      And I press "Categorizing"
      And I fill in "field_sf_tags[target_id]" with "Tag Test, Tag Test 2"
      And I press "field_sf_featured_media-media-library-open-button"
      And I wait for AJAX to finish
      And I attach the file "test_16x9.png" to "files[upload]"
      And I wait for AJAX to finish
      And I fill in "Alternative text" with "test image"
      And I fill in "Caption" with "Test Image"
      And I fill in "media[0][fields][field_sf_media_category][0][target_id]" with "Test"
      And I click the Save and insert button
      And I wait for AJAX to finish
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
      And I should see an "meta[content='event']" element
      # URL
      And I should see an "meta[property='og:url']" element
      And I should see an "meta[content$='/events/new-event']" element
      # Title
      And I should see an "meta[property='og:title']" element
      And I should see an "meta[content='New Event']" element
      # Image
      And I should see an "meta[property='og:image']" element
      # Image URL
      And I should see an "meta[property='og:image:url']" element

  @api @javascript
  Scenario:
    Given "sf_event" content:
      | title        | field_sf_dates:value | field_sf_dates:end_value |
      | add | 2016-06-01T05:06:22  | 2016-06-01T06:06:22      |
    When I visit "events/add"
    Then I should see an ".dropdown" element
      And I should see an ".dropdown" element
      And I should see an ".dropdown__btn" element
      And I should see an ".dropdown__content" element
      And I should see an ".dropdown__item" element
