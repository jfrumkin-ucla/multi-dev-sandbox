Feature: Standard workflow enables Draft and Review process for content Contributors
and allows Site Manager to publish content.

  @api
  Scenario Outline: Contributors and Editors can create a new Draft.
    Given I am logged in as a user with the <role> role
    Then I visit "node/add/sf_article"
    Then I should see "Draft" in the "#edit-moderation-state-0-state" element

    Examples:
      | role        |
      | contributor |
      | editor      |

#  @api
#  Scenario Outline: Contributors and Editors can move their own content from Published to Draft.
#    Given users:
#    | name  | mail  | roles |
#    | <name> | contrib@example.com | <role> |
#    Given I am logged in as <name>
#    Then I visit "node/add/sf_article"
#      And I fill in the following:
#      | Title | Testing title |
#      And I select "In Review" from "moderation_state[0][state]"
#      And I press "Save"
#    Given I am logged in as a user with the "site_manager" role
#      And I visit "admin/content"
#      And I click "Testing title"
#    When I click "Edit"
#      And I select "Published" from "moderation_state[0][state]"
#      And I press "Save"
#    Given I am logged in as <name>
#      And I visit "admin/content"
#      And I click "Testing title"
#    When I click "Edit"
#    Then I should see "Draft" in the "#edit-moderation-state-0-state" element
#
#    Examples:
#      | name    | role        |
#      | contrib | contributor |
#      | ed      | editor      |

  @api
  Scenario Outline: Contributors and Editors can move their Draft to In Review.
    Given I am logged in as a user with the <role> role
    Then I visit "node/add/sf_article"
    Then I should see "In Review" in the "#edit-moderation-state-0-state" element

    Examples:
      | role        |
      | contributor |
      | editor      |

  @api
  Scenario Outline: Contributors and Editors cannot move content to Published.
    Given I am logged in as a user with the <role> role
    Then I visit "node/add/sf_article"
    Then I should not see "Published" in the "#edit-moderation-state-0-state" element

    Examples:
      | role        |
      | contributor |
      | editor      |

#  @api
#  Scenario: Site Managers can move content from Draft to Published.
#    Given I am logged in as a user with the "site_manager" role
#    And I am viewing an "sf_article" with the title "Test Article Title"
#    When I click "Edit"
#    Then I should see "Published" in the "#edit-moderation-state-0-state" element
#
#  @api
#  Scenario: Site Managers can move content from In Review to Published.
#    Given I am logged in as a user with the "contributor" role
#    Then I visit "node/add/sf_article"
#      And I fill in the following:
#        | Title | Testing title |
#      And I select "In Review" from "moderation_state[0][state]"
#      And I press "Save"
#    Given I am logged in as a user with the "site_manager" role
#      And I visit "admin/content"
#      And I click "Testing title"
#    When I click "Edit"
#    Then I should see "Published" in the "#edit-moderation-state-0-state" element

#  @api
#  Scenario: Site Managers can move content from In Review to Draft.
#    Given I am logged in as a user with the "contributor" role
#    Then I visit "node/add/sf_article"
#      And I fill in the following:
#        | Title | Testing title |
#      And I select "In Review" from "moderation_state[0][state]"
#      And I press "Save"
#    Given I am logged in as a user with the "site_manager" role
#      And I visit "admin/content"
#      And I click "Testing title"
#    When I click "Edit"
#    Then I should see "Draft" in the "#edit-moderation-state-0-state" element

#  @api
#  Scenario: Site Managers can move content from Published to In Review.
#    Given I am logged in as a user with the "site_manager" role
#      And I am viewing an "sf_article" with the title "Test Article Title"
#    When I click "Edit"
#    Then I should see "In Review" in the "#edit-moderation-state-0-state" element

#  @api
#  Scenario: Site Managers can move content from Published to Draft.
#    Given I am logged in as a user with the "site_manager" role
#      And I am viewing an "sf_article" with the title "Test Article Title"
#    When I click "Edit"
#    Then I should see "Draft" in the "#edit-moderation-state-0-state" element

#  @api
#  Scenario: Site Managers can move content from Published to Published.
#    Given I am logged in as a user with the "site_manager" role
#    And I am viewing an "sf_article" with the title "Test Article Title"
#    When I click "Edit"
#    Then I should see "Published" in the "#edit-moderation-state-0-state" element

#  @api
#  Scenario: Site Managers can compare diff of revisions.
#    Given I am logged in as a user with the "site_manager" role
#    And I am viewing an "sf_article" with the title "Test Article Title"
#    When I click "Edit"
#      And I fill in "body[0][value]" with "This is a test of diff."
#      And I press "Save"
#    When I click "Revisions"
#    Then I should see an "input.diff-button" element

  @api
  Scenario: There are email templates.
    Given I am logged in as a user with the "administrator" role
      And I visit "admin/structure/workbench-moderation/workbench-email-template"
    Then I should see "Anything to Published"
      And I should see "Draft to Review"
      And I should see "Review to Draft"

  @api
  Scenario: Email templates not attached for create new draft transition.
    Given I am logged in as a user with the "administrator" role
    And I visit "admin/config/workflow/workflows/manage/standard_workflow/transition/create_new_draft"
    Then the checkbox "workbench_email_templates[anything_to_published]" should be unchecked
    And the checkbox "workbench_email_templates[draft_to_review]" should be unchecked
    And the checkbox "workbench_email_templates[review_to_draft]" should be unchecked

  @api
  Scenario: Email templates not attached for publish transition.
    Given I am logged in as a user with the "administrator" role
    And I visit "admin/config/workflow/workflows/manage/standard_workflow/transition/publish"
    Then the checkbox "workbench_email_templates[anything_to_published]" should be checked
    And the checkbox "workbench_email_templates[draft_to_review]" should be unchecked
    And the checkbox "workbench_email_templates[review_to_draft]" should be unchecked

  @api
  Scenario: Email templates not attached for review transition.
    Given I am logged in as a user with the "administrator" role
    And I visit "admin/config/workflow/workflows/manage/standard_workflow/transition/review"
    Then the checkbox "workbench_email_templates[anything_to_published]" should be unchecked
    And the checkbox "workbench_email_templates[draft_to_review]" should be checked
    And the checkbox "workbench_email_templates[review_to_draft]" should be unchecked

  @api
  Scenario: Email templates not attached for reject transition.
    Given I am logged in as a user with the "administrator" role
    And I visit "admin/config/workflow/workflows/manage/standard_workflow/transition/reject"
    Then the checkbox "workbench_email_templates[anything_to_published]" should be unchecked
    And the checkbox "workbench_email_templates[draft_to_review]" should be unchecked
    And the checkbox "workbench_email_templates[review_to_draft]" should be checked
