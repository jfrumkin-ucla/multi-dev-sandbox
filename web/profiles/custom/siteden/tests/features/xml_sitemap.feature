Feature: As a Search Engine Bot I should see an XML sitemap
  For search engines to easily navigate the site as a whole
  As an Administrator user
  I want to be able to see that XML sitemap configuration is correct.

  Background:
    Given I am logged in as a user with the "administrator" role

  @api
  Scenario: XML Sitemap Entity support is enabled
    Given I visit "admin/config/search/simplesitemap/entities"
    Then the "entity_types[node]" checkbox should be checked
      And the "entity_types[menu_link_content]" checkbox should be checked
      And the "entity_types[taxonomy_term]" checkbox should be checked

  @api
  Scenario: XML Sitemap Nodes should be enabled
    When I visit "node/add/sf_article"
    Then the "Index this Article entity in sitemap Default (default)" checkbox should be checked
    When I visit "node/add/sf_event"
    Then the "Index this Event entity in sitemap Default (default)" checkbox should be checked
    When I visit "node/add/sf_page"
    Then the "Index this Basic Page entity in sitemap Default (default)" checkbox should be checked
    When I visit "node/add/sf_person"
    Then the "Index this Person entity in sitemap Default (default)" checkbox should be checked
    When I visit "node/add/sf_photo_gallery"
    Then the "Index this Photo Gallery entity in sitemap Default (default)" checkbox should be checked

  @api
  Scenario: XML Sitemap Taxonomy should be enabled
    When I visit "admin/structure/taxonomy/manage/sf_article_category/add"
    Then the "Index this Article Categories entity in sitemap Default (default)" checkbox should be checked
    When I visit "admin/structure/taxonomy/manage/sf_article_type/add"
    Then the "Index this Article Type entity in sitemap Default" checkbox should be checked
    When I visit "admin/structure/taxonomy/manage/sf_event_type/add"
    Then the "Index this Event Category entity in sitemap Default" checkbox should be checked
    When I visit "admin/structure/taxonomy/manage/sf_person_type/add"
    Then the "Index this Person Type entity in sitemap Default" checkbox should be checked
    When I visit "admin/structure/taxonomy/manage/sf_photo_gallery_categories/add"
    Then the "Index this Photo Gallery Categories entity in sitemap Default" checkbox should be checked
    When I visit "admin/structure/taxonomy/manage/sf_tags/add"
    Then the "Index this Tags entity in sitemap Default" checkbox should be checked
#TODO: Figure out where these settings should come from.
#  @api
#  Scenario: XML Sitemap custom links are enabled
#    Given I visit "admin/config/search/simplesitemap/custom"
#    Then the "Relative Drupal paths" field should contain """
#/ 1.0
#/blog 0.9
#/news 0.9
#/events 0.9
#/people 0.7
#/photo-galleries 0.7
#    """
