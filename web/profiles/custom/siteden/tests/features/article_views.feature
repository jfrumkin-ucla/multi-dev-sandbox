Feature: A User should see lists of articles
  In order to see articles
  As an Anonymous visitor
  I want to be able to see lists of articles on pages

  Background:
    Given I am an anonymous user
      And "sf_article_type" terms:
        | name |
        | News |
        | Views Type |
      And "sf_article_category" terms:
        | name           | field_sf_brand_color      |
        | Views Category | category-brand--ucla-blue |
        | Views Cat 2    | category-brand--ucla-gold |
      And "sf_tags" terms:
        | name        |
        | Views Tag   |
        | Views Tag 2 |
      And "sf_article" content:
        | title          | field_sf_article_category | field_sf_tags | field_sf_article_type | created           | moderation_state |
        | First Article  | Views Category            | Views Tag     | News                  | 2019-10-17 8:00am | published        |
        | Second Article | Views Cat 2               | Views Tag     | News                  | 2019-10-17 9:00am | published        |
        | Third Article  | Views Category            | Views Tag 2   | News                  | 2019-10-17 10:00am | published       |
        | Fourth Article | Views Category            | Views Tag 2   | Views Type            | 2019-10-17 11:00am | published       |

  @api
  Scenario: Related Articles should appear on articles when having categories in common
    Given a block "views_block:sf_articles_related-block_1" is in the "sidebar_second" region
      And I am viewing a "sf_article" content:
        | title                     | Current Article |
        | field_sf_article_category | Views Category  |
        | field_sf_article_type     | News            |
        | moderation_state          | published       |
    Then I should see "First Article" in the "Sidebar Second Region"
      And I should see "Third Article" in the "Sidebar Second Region"
      And I should not see "Second Article" in the "Sidebar Second Region"
      And I should not see "Current Article" in the "Sidebar Second Region"

  @api
  Scenario: Related Articles should appear on articles when having tags in common
    Given a block "views_block:sf_articles_related-block_1" is in the "sidebar_second" region
      And I am viewing a "sf_article" content:
        | title                 | Current Article |
        | field_sf_tags         | Views Tag       |
        | field_sf_article_type | News            |
        | moderation_state      | published       |
    Then I should see "First Article" in the "Sidebar Second Region"
      And I should see "Second Article" in the "Sidebar Second Region"
      And I should not see "Third Article" in the "Sidebar Second Region"
      And I should not see "Current Article" in the "Sidebar Second Region"

  @api
  Scenario: Articles are showing in the content region of the Articles page
    Given I am on "/articles"
    Then I should see "First Article" in the "Content" region
      And I should see "Second Article" in the "Content" region
      And I should see "Third Article" in the "Content" region
      And I should see the ".view-mode--teaser" element in the "Content" region

  @api
  Scenario: Articles filtered by type and category that have spaces in their names are showing in the content region
    Given I am on "/articles/views-type/views-category"
    Then I should see "Fourth Article" in the "Content" region
    And I should see the ".view-mode--teaser" element in the "Content" region

  @api @javascript
  Scenario: Articles filtered by type and category that have spaces in their names are available as an RSS feed
    When I am on "/articles/views-type/views-category/feed.rss"
    Then I should see "Fourth Article"

  @api @javascript
  Scenario: Latest articles are available as an RSS feed
    When I am on "articles.rss"
    Then I should see "First Article"
      And I should see "Second Article"
      And I should see "Third Article"

  @api
  Scenario: The title on the News page should be "Recent News Articles"
    Given I am an anonymous user
      And I visit "/articles/news"
    Then I should see the text "Recent News Articles" in the "Page Title" region

  @api
  Scenario: If an image is not uploaded no featured article block should show up on the News page
    Given I am logged in as a user with the "administrator" role
      And a block "views_block:sf_article_featured-sf_article_featured_block" is in the "content" region
      And I visit "node/add/sf_article"
      And I fill in the following:
        | Title | Testing Missing Featured View |
      And I select "News" from "field_sf_article_type"
      And I check the box "field_sf_featured_status[value]"
      And I press "Save"
      And I am an anonymous user
    When I visit "/articles/news"
    Then I should not see the ".block-views-blocksf-article-featured-sf-article-featured-block" element in the "Content" region

  @api @javascript
  Scenario: Single most recent featured article should appear to top of News page
    Given I am logged in as a user with the "administrator" role
      And a block "views_block:sf_article_featured-sf_article_featured_block" is in the "content" region
      And I visit "node/add/sf_article"
      And I fill in the following:
        | Title | Testing Featured View |
      And I wait 2 seconds
    When I press "field_sf_featured_media-media-library-open-button"
      And I wait 2 seconds
    When I attach the file "test_16x9.png" to "files[upload]"
      And I wait for AJAX to finish
      And I fill in "Alternative text" with "alt text"
      And I click the Save and insert button
      And I wait for AJAX to finish
    When I press "Categorizing"
      And I select "News" from "field_sf_article_type"
    When I press "Promotion options"
      And I check the box "field_sf_featured_status[value]"
    And I select "Published" from "moderation_state[0][state]"
      And I press "Save"
    Given I am an anonymous user
    When I visit "/news"
    Then I should see the ".block-views-blocksf-article-featured-sf-article-featured-block" element in the "Content" region

  @api
  Scenario: The news category filter should have branding
    Given I am an anonymous user
      And I visit "/articles/news"
    Then I should see the text "Filter by News Category" in the "Sidebar First Region" region
      And I should see an ".category-filter__list-item.category-brand--ucla-blue" element
      And I should see an ".category-filter__link.category-brand__filter-link" element
