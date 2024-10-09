Feature: General Media and Media Library setup.

  @api
  Scenario: The taxonomy vocabulary for Media Categories should be present.
    Given I am logged in as a user with the "administrator" role
    When I visit "admin/structure/taxonomy"
    Then I should see "Media Categories"

  @api
  Scenario: Administrators can add media categories
    Given I am logged in as a user with the "administrator" role
    Then I visit "admin/structure/taxonomy/manage/sf_media_categories/add"
    And I fill in the following:
      | Name | Test Media Category |
    And I press "Save"
    Then I should see "Created new term Test Media Category."

  @api
  Scenario: As an adminsitrator I should be able to edit Media Categories
    Given I am logged in as a user with the "administrator" role
    And I visit "media-categories/test-media-category"
    And I click "Edit"
    And I fill in "Name" with "Test Category"
    And I press "Save"
    Then I should see "Updated term Test Category."

  @api
  Scenario: As an adminsitrator I should be able to delete Media Categories
    Given I am logged in as a user with the "administrator" role
    And I visit "media-categories/test-media-category"
    And I click "Edit"
    And I click "Delete"
    Then I press "Delete"
    Then I should see "Deleted term Test Category."

  @api
  Scenario Outline: As a Site Manager, Site Builder, and Editor I should be able to add Media Categories
    Given I am logged in as a user with the <role> role
    Then I visit "admin/structure/taxonomy/manage/sf_media_categories/add"
    And I fill in the following:
      | Name | <name> |
    And I press "Save"
    Then I should see "Created new term <name>"

    Examples:
      | role         | name   |
      | site_manager | sm     |
      | site_builder | sb     |
      | editor       | ed     |

  @api
  Scenario Outline: As a Site Manager, Site Builder, and Editor I should be able to edit Media Categories
    Given I am logged in as a user with the <role> role
    And I visit "media-categories/<name>"
    And I click "Edit"
    And I fill in "Name" with "new<name>"
    And I press "Save"
    Then I should see "Updated term new<name>."

    Examples:
      | role         | name |
      | site_manager | sm   |
      | site_builder | sb   |
      | editor       | ed   |

  @api
  Scenario Outline: As a Site Manager, Site Builder, and Editor I should be able to delete Media Categories
    Given I am logged in as a user with the <role> role
    And I visit "media-categories/<name>"
    And I click "Edit"
    And I click "Delete"
    Then I press "Delete"
    Then I should see "Deleted term new<name>."

    Examples:
      | role         | name |
      | site_manager | sm   |
      | site_builder | sb   |
      | editor       | ed   |

  @api
  Scenario: The Category field should be exposed when using the Media Library table view.
    Given I am logged in as a user with the "administrator" role
      And I visit "/admin/content/media"
    Then I should see "Category" in the "Content Region" region
      And I should see a "input[name='name_1']" element

  @api
  Scenario: The Category field should be exposed when using the Media Library grid view.
    Given I am logged in as a user with the "administrator" role
      And I visit "/admin/content/media"
      And I click "Grid"
    Then I should see "Category" in the "Content Region" region
      And I should see a "input[name='name_1']" element
