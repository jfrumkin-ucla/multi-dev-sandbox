Feature: Units with related Persons
  Makes sure that the Units Taxonomy term view with related people works.

  Background:
    Given I am logged in as a user with the "administrator" role
    Given "sf_person" content:
      | field_sf_first_name | field_sf_last_name |
      | Jane                | Test               |
#    Given "sf_units" terms:
#      | name     | field_sf_unit_leaders | parent   | field_sf_unit_leaders_title |
#      | Unit One |                       |          |                             |
#      | Unit Two | Jane Test             | Unit One | Boss                        |
    Given "sf_units" terms:
      | name     | field_sf_unit_leaders | field_sf_unit_leaders_title |
      | Unit One |                       |                             |
      | Unit Two | Jane Test             | Boss                        |

  @api
  Scenario: I should see sub units.
    When I visit "units/unit-one"
#    Then I should see "Unit Two" in the "Content Region" region

  @api
  Scenario: I should se the related Unit Leader at the top of the poeple in a unit.
    When I visit "units/unit-two"
    Then I should see "Boss" in the "Content Region" region
    And I should see "Jane Test" in the "Content Region" region
