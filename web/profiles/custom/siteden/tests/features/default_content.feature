Feature: Default Content on Installation
  So that content is already populated when I install a site
  As an Administrator to the site
  I should see default content that is already in a new site

  Background:
    Given I am logged in as a user with the "administrator" role

  @api
  Scenario: Check that the Article Type Vocabulary is pre-populated
    Given I am at "admin/structure/taxonomy/manage/sf_article_type/overview"
      Then I should see "News"

  @api
  Scenario: Check that the Units Vocabulary is pre-populated
    Given I am at "admin/structure/taxonomy/manage/sf_units/overview"
    Then I should see "People"
