Feature: SiteFarm Site Builder Permissions
  To ensure that correct permissions are enabled
  As a Site Builder
  I should be restricted from some access and allowed for others

  Background:
    Given I am logged in as a user with the "Site Builder" role

  @api
  Scenario: Site Building pages that should be accessible by Site Builder
    Given I am on "admin/structure/block"
    Then I should get a "200" HTTP response
    Given I am on "admin/structure/types"
    Then I should get a "200" HTTP response
    Given I am on "admin/structure/display-modes"
    Then I should get a "200" HTTP response
    Given I am on "admin/structure/views"
    Then I should get a "200" HTTP response
    Given I am on "admin/structure/menu"
    Then I should get a "200" HTTP response
    Given I am on "admin/structure/taxonomy"
    Then I should get a "200" HTTP response

  @api
  Scenario: Configuration pages that should be accessible by Site Builder
    Given I am on "admin/config/sitefarm"
    Then I should get a "200" HTTP response
    Given I am on "admin/config/system/site-information"
    Then I should get a "200" HTTP response
    Given I am on "admin/config/system/cron"
    Then I should get a "200" HTTP response
    Given I am on "admin/config/development/performance"
    Then I should get a "200" HTTP response
    Given I am on "admin/config/development/logging"
    Then I should get a "200" HTTP response
    Given I am on "admin/config/development/maintenance"
    Then I should get a "200" HTTP response
    Given I am on "admin/config/search/pages"
    Then I should get a "200" HTTP response
    Given I am on "admin/config/search/path"
    Then I should get a "200" HTTP response
    Given I am on "admin/config/sitefarm/clearcache"
    Then I should get a "200" HTTP response
    Given I am on "admin/config/content/formats"
    Then I should get a "200" HTTP response
    Given I am on "admin/config/media/file-system"
    Then I should get a "200" HTTP response
    Given I am on "admin/config/media/image-styles"
    Then I should get a "200" HTTP response
    Given I am on "admin/config/media/image-toolkit"
    Then I should get a "200" HTTP response
    Given I am on "admin/config/regional/date-time"
    Then I should get a "200" HTTP response
    Given I am on "admin/config/services/rss-publishing"
    Then I should get a "200" HTTP response
    Given I am on "admin/config/user-interface/shortcut"
    Then I should get a "200" HTTP response
    Given I am on "admin/config/media/crop"
    Then I should get a "200" HTTP response
# TODO: See why this is failing on no-JS tests and re-enable.
#    Given I am on "admin/config/development/configuration"
#    Then I should get a "200" HTTP response

#  @api
#  Scenario: Admin pages that should be accessible by Site Builder
#    Given I am on "admin/appearance"
#    Then I should get a "200" HTTP response
#    Given I am on "admin/content"
#    Then I should get a "200" HTTP response
#    Given I am on "admin/help"
#    Then I should get a "200" HTTP response
#    Given I am on "admin/modules"
#    Then I should get a "200" HTTP response
#    Given I am on "admin/content/moderated"
#    Then I should get a "200" HTTP response

  @api
  Scenario: Site Builder can access robots.txt interface
    When I visit "admin/config/search/robotstxt"
    And I fill in "robotstxt_content" with "Allow: /"
    And I press "Save configuration"
    Then I should see "The configuration options have been saved."
