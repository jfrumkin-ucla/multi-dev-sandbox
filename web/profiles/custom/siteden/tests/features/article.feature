Feature: A User should create an article
  In order for new articles to be published
  As an Administrator
  I want to be able to create Article content.

  Background:
    Given I am logged in as a user with the "administrator" role
    Given "sf_article_type" terms:
      | name          |
      | Test Category |
      | Second Term   |
    Then I visit "node/add/sf_article"
      And I fill in the following:
        | Title | Testing title         |

  @api
  Scenario: Make sure that the article type provided by SiteFarm at installation is present.
    Then I should see "Article"

  @api @javascript
  Scenario: Ensure that the WYSIWYG editor is present.
    Then CKEditor "edit-body-0-value" should exist

  @api
  Scenario: Ensure that the article Promote to Front page option is hidden.
    Then I should not see a "input[name='promote[value]']" element

#  @api
#  Scenario: Ensure that the article Create New Revision is checked.
#    Given I select "News" from "field_sf_article_type"
#    When I press "Save"
#    And I click "Edit"
#    Then the "revision" checkbox should be checked

  @api
  Scenario: Ensure that meta tag fields are present on Articles.
    Then I should see a "input[name='field_sf_meta_tags[0][basic][title]']" element
    And I should see a "textarea[name='field_sf_meta_tags[0][basic][description]']" element

#  @api
#  Scenario: A url alias should be auto generated for Articles.
#    Given I select "News" from "field_sf_article_type"
#    When I press "Save"
#    Then I should see "Testing title" in the "Content" region
#      And I should be on "news/testing-title"

  @api @javascript @local_files
  Scenario: A Primary image should be available to upload.
    When I press "field_sf_featured_media-media-library-open-button"
      And I wait for AJAX to finish
    When I attach the file "test_16x9.png" to "files[upload]"
      And I wait for AJAX to finish
      And I fill in "Alternative text" with "test image"
      And I fill in "Caption" with "Test Image"
      And I fill in "media[0][fields][field_sf_media_category][0][target_id]" with "Test"
      And I click the Save and insert button
      And I wait for AJAX to finish
    Then I press "Categorizing"
      And I select "News" from "field_sf_article_type"
      And I press "Save"
    Then I should see an image in the "Content" region
      And I should see the image alt "test image" in the "Content" region

  @api
  Scenario: Tags added to an Article
    When I fill in "field_sf_tags[target_id]" with "Tag Test, Tag Test 2"
      And I select "News" from "field_sf_article_type"
      And I press "Save"
    Then I should see the link "Tag Test" in the "Content" region
      And I should see the link "Tag Test 2" in the "Content" region

  @api
  Scenario: Classify Articles with a single Category taxonomy
    Given "sf_article_category" terms:
      | name          |
      | Test Category |
      | Second Term   |
    When I visit "node/add/sf_article"
      And I fill in the following:
        | Title | Testing title |
      And I select "News" from "field_sf_article_type"
      And I select "Test Category" from "field_sf_article_category"
      And I press "Save"
    Then I should see the link "Test Category"

  @api @javascript
  Scenario: Social share buttons on an Article
    Given I press "Categorizing"
      And I select "News" from "field_sf_article_type"
    When I press "Save"
    Then I should see a ".at-icon-facebook" element
      And I should see a ".at-icon-twitter" element
      And I should see a ".at-icon-email" element
      And I should see a ".at-icon-addthis" element

  @api @javascript
  Scenario: Article teasers should strip html from the body summary
    Given the Administration Toolbar is hidden
    When I execute the "feature_block" command in CKEditor
      And I wait for AJAX to finish
      And I wait 1 seconds
      And I select the radio button "Align Right"
      And I press "OK"
      And I press "Categorizing"
      And I select "News" from "field_sf_article_type"
      And I press "Save"
    Then I should see "Title" in the ".wysiwyg-feature-block .wysiwyg-feature-block__title" element in the "Content" region
    When I visit "articles/news/"
    Then I should not see the ".wysiwyg-feature-block__body" element in the "Content" region

  @api
  Scenario: Category filter should be on type page and active state should be applied to filtered page filter item
    Given "sf_article_category" terms:
      | name          |
      | Test Category |
      | Second Term   |
      And I visit "node/add/sf_article"
      And I fill in the following:
        | Title | Testing title |
      And I select "News" from "field_sf_article_type"
      And I select "Test Category" from "field_sf_article_category"
      And I select "Published" from "moderation_state[0][state]"
      And I press "Save"
    When I visit "articles/news"
      And I should see "Filter By News Category"
      And I visit "articles/news/test-category"
    Then I should see a ".category-filter__list-item--active" element

  @api
  Scenario: Keyword and Created On filters should be on news pages.
    Given "sf_article_category" terms:
      | name          |
      | Test Category |
      | Second Term   |
      And I visit "node/add/sf_article"
      And I fill in the following:
        | Title | Testing title |
      And I select "News" from "field_sf_article_type"
      And I select "Test Category" from "field_sf_article_category"
      And I select "Published" from "moderation_state[0][state]"
      And I press "Save"
    When I visit "articles/news"
    Then I should see "Filter Articles" in the ".region-sidebar-first" element
      And I should see "Created On or After" in the ".region-sidebar-first" element
      And I should see "Keywords" in the ".region-sidebar-first" element
    When I visit "articles/news/test-category"
    Then I should see "Filter Articles" in the ".region-sidebar-first" element
      And I should see "Created On or After" in the ".region-sidebar-first" element
      And I should see "Keywords" in the ".region-sidebar-first" element
    When I visit "article-category/test-category"
    Then I should see "Filter Articles" in the ".region-sidebar-first" element
      And I should see "Created On or After" in the ".region-sidebar-first" element
      And I should see "Keywords" in the ".region-sidebar-first" element

  @api
  Scenario: Category filter should not be on article page and if no sidebar items there should be no sidebar
    Given "sf_article_category" terms:
      | name          |
      | Test Category |
      | Second Term   |
      And I visit "node/add/sf_article"
      And I fill in the following:
        | Title | Testing title |
      And I select "News" from "field_sf_article_type"
      And I select "Test Category" from "field_sf_article_category"
    When I press "Save"
    Then I should not see an ".region-sidebar-first" element

#  @api
#  Scenario: Multiple Authors set as a Person content type reference
#    Given "sf_person" content:
#      | title     | field_sf_first_name | field_sf_last_name |
#      | John Test | John                | Test               |
#      | Jane Test | Jane                | Test               |
#    When I visit "node/add/sf_article"
#    And I fill in the following:
#      | Title                          | Testing title |
#      | field_sf_authors[0][target_id] | John Test     |
#    And I select "News" from "field_sf_article_type"
#    And I press "Save"
#    Then I should see "John Test" in the ".article__meta" element
#    When I click "Edit"
#    And I fill in the following:
#      | Title                          | Testing title |
#      | field_sf_authors[1][target_id] | Jane Test     |
#    And I press "Save"
#    Then I should see "John Test, Jane Test" in the ".article__meta" element

#  @api
#  Scenario: If users full name is filled out then it should appear in byline
#    Given I visit "user"
#    And I click "Edit"
#    And I fill in "field_sf_full_name[0][value]" with "Mr. Test User"
#    And I press "Save"
#    When I visit "node/add/sf_article"
#    And I fill in the following:
#      | Title | Testing title |
#    And I press "Save"
#    Then I should see "Mr. Test User" in the ".article__meta" element
#
#  @api
#  Scenario: If the Byline field is filled the value should appear in byline
#    Given I visit "node/add/sf_article"
#    And I fill in the following:
#      | Title  | Testing title |
#      | Byline | Test Author   |
#    And I press "Save"
#    Then I should see "Test Author" in the ".article__meta" element

  @api @javascript
  Scenario: Default meta tags are set for article
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
      And I select "News" from "field_sf_article_type"
      And I press "Save"
      And I set the published date to "10/11/12" for the node with the title "Testing title"
      And I set the last updated date to "9/10/11" for the node with the title "Testing title"
      And I set the site name to 'My Awesome Site'
      And I am on "/"
      And I am on "news/testing-title"
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
      And I should see an "meta[content='article']" element
      # URL
      And I should see an "meta[property='og:url']" element
      And I should see an "meta[content$='/news/testing-title']" element
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
