Feature: Person Content Type
  Makes sure that the person content type was created during installation.

  Background:
    Given I am logged in as a user with the "administrator" role
    When I visit "node/add/sf_person"
      And I fill in the following:
        | Name Prefix | Dr   |
        | First Name  | John |
        | Last Name   | Doe  |
        | Credentials | Jr.  |

  @api
  Scenario: Make sure that the Person provided by SiteFarm at installation is present.
    Then I should see "Person"

  @api @javascript
  Scenario: Ensure that the WYSIWYG editor is present for the Bio field.
    Then CKEditor "edit-body-0-value" should exist

  @api
  Scenario: Ensure that the person Promote to Front page option is hidden.
    Then I should not see a "input[name='promote[value]']" element

  @api
  Scenario: Ensure that the person Create New Revision is checked.
    When I press "Save"
      And I click "Edit"
    Then the "revision" checkbox should be checked

  @api
  Scenario: Ensure that meta tag fields are present.
    Then I should see a "input[name='field_sf_meta_tags[0][basic][title]']" element
      And I should see a "textarea[name='field_sf_meta_tags[0][basic][description]']" element

  @api
  Scenario: The Person title should be hidden and auto-generated using Prefix, First Name, Last Name, and Credentials
    Then I should not see "Display Name"
    When I press "Save"
    Then I should see "Dr John Doe Jr." in the "Page Title" region

  @api
  Scenario: A url alias should be auto generated for Persons.
    When I press "Save"
    Then I should see "Dr John Doe Jr." in the "Page Title" region
      And I should be on "/people/john-doe"

  @api @javascript @local_files
  Scenario: A Primary image should be available to upload.
    When I press "field_sf_m_primary_image-media-library-open-button"
      And I wait for AJAX to finish
    When I attach the file "test_16x9.png" to "files[upload]"
      And I wait for AJAX to finish
      And I fill in "Alternative text" with "alt text"
      And I click the Save and insert button
      And I wait for AJAX to finish
      And I select "Published" from "moderation_state[0][state]"
    When I press "Save"
    Then I should see an image in the "Content" region
      And I should see the image alt "alt text" in the "Content" region

  @api
  Scenario: Persons should not able to go into the Main Menu
    Then I should not see the link "Menu settings"

  @api
  Scenario: Classify Persons with a single Person Type taxonomy
    Given "sf_person_type" terms:
      | name         |
      | Student Type |
    When I visit "node/add/sf_person"
    And I fill in the following:
      | First Name   | John |
      | Last Name    | Doe  |
    And I select "Student Type" from "field_sf_person_type"
    And I press "Save"
#    Then I should see the link "Student Type"
    When I click "Edit"
    Then the "field_sf_person_type" select should be set to "Student Type"

  @api
  Scenario: Add Persons to Units
    Given "sf_units" terms:
      | name                   |
      | Information Technology |
      | Administration         |
    When I visit "node/add/sf_person"
    And I fill in the following:
      | First Name   | John |
      | Last Name    | Doe  |
    And I select "Information Technology" from "Units"
    And I additionally select "Administration" from "Units"
    And I press "Save"
    Then I should see the link "Information Technology"
    And I should see the link "Administration"
    When I click "Edit"
    Then the "Units" select should be set to "Information Technology"
    Then the "Units" select should be set to "Administration"

  @api @javascript
  Scenario: Address field should show US fields when selected
    Then the "field_sf_address[0][address][country_code]" select should be set to "- None -"
    When I select "United States" from "field_sf_address[0][address][country_code]"
      And I wait for AJAX to finish
    Then I should see a "input[name='field_sf_address[0][address][address_line1]']" element
      And I should see a "input[name='field_sf_address[0][address][locality]']" element
      And I should see a "select[name='field_sf_address[0][address][administrative_area]']" element
      And I should see a "input[name='field_sf_address[0][address][postal_code]']" element
    When I select "- None -" from "field_sf_address[0][address][country_code]"
      And I wait for AJAX to finish
      And I select "United States" from "field_sf_address[0][address][country_code]"
      And I wait for AJAX to finish
    Then I should see a "input[name='field_sf_address[0][address][address_line1]']" element

  @api
  Scenario: Person Directory shows group titles
    Given "sf_person_type" terms:
      | name         |
      | Student Type |
    When I visit "node/add/sf_person"
      And I fill in the following:
        | First Name   | John |
        | Last Name    | Doe  |
      And I select "Student Type" from "field_sf_person_type"
      And I select "Published" from "moderation_state[0][state]"
      And I press "Save"
    And I visit "people"
    Then I should see "Student Type" in the "Content" region

  @api
  Scenario: Add Person Button (link) is on the Person(s) admin view
    Given I am logged in as a user with the "site_manager" role
    When I visit "admin/content/person"
    Then I should see the link "Add Person"

  @api @javascript
  Scenario: Contact info does not appear in the sidebar
    When the Administration Toolbar is hidden
      And I press "field_sf_m_primary_image-media-library-open-button"
      And I wait for AJAX to finish
      And I attach the file "test_16x9.png" to "files[upload]"
      And I wait for AJAX to finish
      And I fill in "Alternative text" with "alt text"
      And I click the Save and insert button
      And I wait for AJAX to finish
      And I select "Published" from "moderation_state[0][state]"
      And I press "Save"
    Then I should not see an ".views-field-nothing-1" element
      And I should see an "img.image-style-sf-profile" element

  @api @javascript
  Scenario: Contact info appears in the sidebar
    Given I fill in the following:
      | Email                                             | test@example.com |
      | Phone Number                                      | 555-555-5555     |
      | Office Location                                   | Some Building    |
      | Country                                           | US               |
    And I wait for AJAX to finish
    And I fill in the following:
      | field_sf_address[0][address][organization]        | UCLA               |
      | field_sf_address[0][address][address_line1]       | 757 Westwood Plaza |
      | field_sf_address[0][address][locality]            | Los Angeles        |
      | field_sf_address[0][address][administrative_area] | CA                 |
      | field_sf_address[0][address][postal_code]         | 90095              |
    When the Administration Toolbar is hidden
      And I press "field_sf_m_primary_image-media-library-open-button"
      And I wait for AJAX to finish
      And I attach the file "test_16x9.png" to "files[upload]"
      And I wait for AJAX to finish
      And I fill in "Alternative text" with "alt text"
      And I click the Save and insert button
      And I wait for AJAX to finish
      And I select "Published" from "moderation_state[0][state]"
      And I press "Save"
    Then I should see an ".views-field-nothing-1" element
      And I should see an "img.image-style-sf-profile" element
      And I should see an ".views-field-field-sf-phone-numbers" element
      And I should see an ".views-field-field-sf-emails" element
      And I should see an ".views-field-field-sf-office-location" element
      And I should see an ".views-field-field-sf-address" element

  @api @javascript
  Scenario: Default meta tags are set for person
    Given I press "field_sf_m_primary_image-media-library-open-button"
      And I wait for AJAX to finish
      And I attach the file "test_16x9.png" to "files[upload]"
      And I wait for AJAX to finish
      And I fill in "Alternative text" with "test image"
      And I fill in "Caption" with "Test Image"
      And I fill in "media[0][fields][field_sf_media_category][0][target_id]" with "Test"
      And I click the Save and insert button
      And I wait for AJAX to finish
      And I put "This is some body text" into CKEditor
      And the Administration Toolbar is hidden
      And I scroll to the "#edit-categorizing" element
      And I press "Categorizing"
      And I fill in "field_sf_tags[target_id]" with "Tag Test, Tag Test 2"
      And I press "Save"
      And I set the published date to "10/11/12" for the node with the title "Dr John Doe Jr."
      And I set the last updated date to "9/10/11" for the node with the title "Dr John Doe Jr."
      And I set the site name to 'My Awesome Site'
      And I am on "/"
    When I am on "/people/john-doe"
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
      And I should see an "meta[content='person']" element
      # URL
      And I should see an "meta[property='og:url']" element
      And I should see an "meta[content$='/people/john-doe']" element
      # Title
      And I should see an "meta[property='og:title']" element
      And I should see an "meta[content='Dr John Doe Jr.']" element
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
