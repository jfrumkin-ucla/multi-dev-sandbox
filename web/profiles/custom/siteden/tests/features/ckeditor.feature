Feature: CkEditor buttons and functionality
  Ensure that ckeditor is acting correctly and presenting content as intended

  Background:
    Given I am logged in as a user with the "administrator" role
    When I visit "node/add/sf_page"
      And I fill in the following:
        | Title | Testing title |
      And I wait for AJAX to finish

  @api @javascript
  Scenario: CKEditor's Basic HTML filter should have all needed buttons
    Then I should see a ".cke_button__bold" element
      And I should see a ".cke_button__italic" element
      And I should see a ".cke_button__strike" element
      And I should see a ".cke_button__removeformat" element
      And I should see a ".cke_button__link" element
      And I should see a ".cke_button__unlink" element
      And I should see a ".cke_button__anchor" element
      And I should see a ".cke_button__bulletedlist" element
      And I should see a ".cke_button__numberedlist" element
      And I should see a ".cke_button__justifyleft" element
      And I should see a ".cke_button__justifycenter" element
      And I should see a ".cke_button__justifyright" element
      And I should see a ".cke_button__outdent_icon" element
      And I should see a ".cke_button__indent_icon" element
      And I should see a ".cke_button__descriptionlist_icon" element
      And I should see a ".cke_button__descriptionterm_icon" element
      And I should see a ".cke_button__descriptionvalue_icon" element
      And I should see a ".cke_button__undo" element
      And I should see a ".cke_button__redo" element
      And I should see a ".cke_button__source" element
      And I should see a ".cke_button__maximize" element
      And I should see a ".cke_combo__styles" element
      And I should see a ".cke_button__drupalmedialibrary_icon" element
      And I should see a ".cke_button__horizontalrule" element
      And I should see a ".cke_button__blockquote" element
      And I should see a ".cke_button__table" element
      And I should see a ".cke_button__specialchar" element
      And I should see a ".cke_button__media_link" element
      And I should see a ".cke_button__feature_block" element

  @api @javascript
  Scenario: The AutoSave plugin should save content after 10 seconds
    When I put "This is some body text" into CKEditor
      And I wait 11 seconds
      And I visit "node/add/sf_page"
      And I wait 2 seconds
    Then I should see "An auto-saved version" in popup
#    Now check that the alert does not come after saving
    When I cancel the popup
      And I fill in the following:
        | Title | Testing title |
      And I put "This is some body text" into CKEditor
      And I press "Save"
      And I click "Edit"
    Then I should not see an alert popup

  @api @javascript
  Scenario: Word and Character count should appear while typing
    Then I should see "Words: 0, Characters: 0" in the ".cke_wordcount" element
    When I put "This is some body text" into CKEditor
    And I wait 1 seconds
    Then I should see "Words: 5, Characters: 18" in the ".cke_wordcount" element

  @api @javascript
  Scenario: Heading Titles should have styles
    Given the Administration Toolbar is hidden
    When I click "Heading 2" in the CKEditor style panel
      And I put "Heading 2" into CKEditor
      And I execute the "enter" command in CKEditor
    When I click "Heading 3" in the CKEditor style panel
      And I put "Heading 3" into CKEditor
      And I execute the "enter" command in CKEditor
    When I click "Heading 4" in the CKEditor style panel
      And I put "Heading 4" into CKEditor
      And I execute the "enter" command in CKEditor
    When I click "Heading 5" in the CKEditor style panel
      And I put "Heading 5" into CKEditor
        And I execute the "enter" command in CKEditor
    When I click "Heading 2 Bar" in the CKEditor style panel
      And I put "Heading 2 Bar" into CKEditor
        And I execute the "enter" command in CKEditor
    When I click "Heading 2 Highlight" in the CKEditor style panel
      And I put "Heading 2 Highlight" into CKEditor
        And I execute the "enter" command in CKEditor
    When I click "Heading 2 Ribbon Highlight" in the CKEditor style panel
      And I put "Heading 2 Ribbon Highlight" into CKEditor
    When I press "Save"
    Then I should see "Heading 2" in the "h2" element in the "Content" region
      And I should see "Heading 3" in the "h3" element in the "Content" region
      And I should see "Heading 4" in the "h4" element in the "Content" region
      And I should see "Heading 5" in the "h5" element in the "Content" region
      And I should see "Heading 2 Bar" in the "h2.heading--bar" element in the "Content" region
      And I should see "Heading 2 Highlight" in the "h2.heading--highlight" element in the "Content" region
      And I should see "Heading 2 Ribbon Highlight" in the "h2.heading--ribbon-highlight" element in the "Content" region

  @api @javascript
  Scenario: Block paragraph styles for CKEditor
    Given the Administration Toolbar is hidden
    When I click "Heading 2" in the CKEditor style panel
      And I put "Normal" into CKEditor
      And I click "Normal" in the CKEditor style panel
        And I execute the "enter" command in CKEditor
    When I click "Clear Aligns" in the CKEditor style panel
      And I put "Clear Aligns" into CKEditor
        And I execute the "enter" command in CKEditor
    When I click "Lead Copy" in the CKEditor style panel
      And I put "Lead Copy" into CKEditor
    When I press "Save"
    Then I should see "Normal" in the "p" element in the "Content" region
      And I should see "Clear Aligns" in the "p.u-clear" element in the "Content" region
      And I should see "Lead Copy" in the "p.lead" element in the "Content" region


  @api @javascript
  Scenario: Alert message styles for CKEditor
    Given the Administration Toolbar is hidden
    When I click "Alert" in the CKEditor style panel
      And I put "Alert Base" into CKEditor
      And I execute the "enter" command in CKEditor
    When I click "Alert - Success" in the CKEditor style panel
      And I put "Alert - Success" into CKEditor
      And I execute the "enter" command in CKEditor
    When I click "Alert - Warning" in the CKEditor style panel
      And I put "Alert - Warning" into CKEditor
      And I execute the "enter" command in CKEditor
    When I click "Alert - Error" in the CKEditor style panel
      And I put "Alert - Error" into CKEditor
    When I press "Save"
    Then I should see "Alert Base" in the ".alert" element in the "Content" region
      And I should see "Alert - Success" in the ".alert.alert--success" element in the "Content" region
      And I should see "Alert - Warning" in the ".alert.alert--warning" element in the "Content" region
      And I should see "Alert - Error" in the ".alert.alert--error" element in the "Content" region

  @api @javascript
  Scenario: Pullquote styles for the Blockquote element in CKEditor
    Given the Administration Toolbar is hidden
    When I execute the "blockquote" command in CKEditor
      And I click "Pullquote" in the CKEditor style panel
      And I put "Pullquote Base" into CKEditor
      And I execute the "enter" command in CKEditor "2" times
    When I execute the "blockquote" command in CKEditor
      And I click "Pullquote - Left" in the CKEditor style panel
      And I put "Pullquote - Left" into CKEditor
      And I execute the "enter" command in CKEditor "2" times
    When I execute the "blockquote" command in CKEditor
      And I click "Pullquote - Right" in the CKEditor style panel
      And I put "Pullquote - Right" into CKEditor
    When I press "Save"
    Then I should see "Pullquote Base" in the ".pullquote" element in the "Content" region
      And I should see "Pullquote - Left" in the ".pullquote.u-align--left.u-width--half" element in the "Content" region
      And I should see "Pullquote - Right" in the ".pullquote.u-align--right.u-width--half" element in the "Content" region

  @api @javascript
  Scenario: Table styles for the Table element in CKEditor
    Given the Administration Toolbar is hidden
    When I execute the "table" command in CKEditor
      And I wait 1 seconds
      And I press "OK"
      And I click "Data Table Striped Rows" in the CKEditor style panel
      And I put "Data Table Striped Rows" into CKEditor
    Given The cursor is at the end of CKEditor
    When I execute the "table" command in CKEditor
      And I wait 1 seconds
      And I press "OK"
      And I click "Data Table Plain" in the CKEditor style panel
      And I put "Data Table Plain" into CKEditor
    When I press "Save"
    Then I should see "Data Table Striped Rows" in the ".table tbody tr td" element in the "Content" region
      And I should see "Data Table Plain" in the ".table--plain tbody tr td" element in the "Content" region


  @api @javascript
  Scenario: Unordered List styles for CKEditor
    Given the Administration Toolbar is hidden
    When I execute the "bulletedlist" command in CKEditor
      And I click "Flush List" in the CKEditor style panel
      And I put "Flush List" into CKEditor
      And I execute the "enter" command in CKEditor "3" times
    When I execute the "bulletedlist" command in CKEditor
      And I click "Link List" in the CKEditor style panel
      And I put "Link List" into CKEditor
      And I execute the "enter" command in CKEditor "3" times
    When I execute the "bulletedlist" command in CKEditor
      And I click "Link List with Icons" in the CKEditor style panel
      And I put "Link List with Icons" into CKEditor
      And I execute the "enter" command in CKEditor "3" times
    When I execute the "bulletedlist" command in CKEditor
      And I click "Pipe List" in the CKEditor style panel
      And I put "Pipe List" into CKEditor
      And I execute the "enter" command in CKEditor "3" times
    When I execute the "bulletedlist" command in CKEditor
      And I click "Wraparound List (Bulleted)" in the CKEditor style panel
      And I put "Wraparound List (Bulleted)" into CKEditor
      And I execute the "enter" command in CKEditor "3" times
    When I press "Save"
    Then I should see "Flush List" in the "ul.list--flush" element in the "Content" region
      And I should see "Link List" in the "ul.list--link" element in the "Content" region
      And I should see "Link List with Icons" in the "ul.list--link-icon" element in the "Content" region
      And I should see "Pipe List" in the "ul.list--pipe" element in the "Content" region
      And I should see "Wraparound List (Bulleted)" in the "ul.list--wraparound" element in the "Content" region

  @api @javascript
  Scenario: Ordered List styles for CKEditor
    Given the Administration Toolbar is hidden
    When I execute the "numberedlist" command in CKEditor
      And I click "Upper Roman List" in the CKEditor style panel
      And I put "Upper Roman List" into CKEditor
      And I execute the "enter" command in CKEditor "3" times
    When I execute the "numberedlist" command in CKEditor
      And I click "Upper Alpha List" in the CKEditor style panel
      And I put "Upper Alpha List" into CKEditor
      And I execute the "enter" command in CKEditor "3" times
    When I execute the "numberedlist" command in CKEditor
      And I click "Lower Roman List" in the CKEditor style panel
      And I put "Lower Roman List" into CKEditor
      And I execute the "enter" command in CKEditor "3" times
    When I execute the "numberedlist" command in CKEditor
      And I click "Lower Alpha List" in the CKEditor style panel
      And I put "Lower Alpha List" into CKEditor
      And I execute the "enter" command in CKEditor "3" times
    When I execute the "numberedlist" command in CKEditor
      And I click "No Marker List" in the CKEditor style panel
      And I put "No Marker List" into CKEditor
      And I execute the "enter" command in CKEditor "3" times
    When I execute the "numberedlist" command in CKEditor
      And I click "Wraparound List (Numbered)" in the CKEditor style panel
      And I put "Wraparound List (Numbered)" into CKEditor
      And I execute the "enter" command in CKEditor "3" times
    When I press "Save"
    Then I should see "Upper Roman List" in the "ol.list--upper-roman" element in the "Content" region
      And I should see "Upper Alpha List" in the "ol.list--upper-alpha" element in the "Content" region
      And I should see "Lower Roman List" in the "ol.list--lower-roman" element in the "Content" region
      And I should see "Lower Alpha List" in the "ol.list--lower-alpha" element in the "Content" region
      And I should see "No Marker List" in the "ol.list--none" element in the "Content" region
      And I should see "Wraparound List (Numbered)" in the "ol.list--wraparound" element in the "Content" region

  @api @javascript
  Scenario: Link and Button styles for CKEditor
    Given the Administration Toolbar is hidden
    When I execute the "link" command in CKEditor
      And I fill the CKEditor dialog "URL" with "button-primary.edu"
      And I click "OK"
      And I click "Button Primary" in the CKEditor style panel
      And The cursor is at the end of CKEditor
      And I execute the "enter" command in CKEditor
    When I execute the "link" command in CKEditor
      And I wait for AJAX to finish
    And I fill the CKEditor dialog "URL" with "button-secondary.edu"
      And I click "OK"
      And I click "Button Secondary" in the CKEditor style panel
      And The cursor is at the end of CKEditor
      And I execute the "enter" command in CKEditor
    When I execute the "link" command in CKEditor
      And I fill the CKEditor dialog "URL" with "button-inverted.edu"
      And I click "OK"
      And I click "Button Inverted" in the CKEditor style panel
      And The cursor is at the end of CKEditor
      And I execute the "enter" command in CKEditor
    When I execute the "link" command in CKEditor
      And I fill the CKEditor dialog "URL" with "arrowlink.edu"
      And I click "OK"
      And I click "Arrow Link" in the CKEditor style panel
      And The cursor is at the end of CKEditor
      And I execute the "enter" command in CKEditor
    When I execute the "link" command in CKEditor
      And I fill the CKEditor dialog "URL" with "downloadlink.edu"
      And I click "OK"
      And I click "Download Link" in the CKEditor style panel
      And The cursor is at the end of CKEditor
      And I execute the "enter" command in CKEditor
    When I execute the "link" command in CKEditor
      And I fill the CKEditor dialog "URL" with "externallink.edu"
      And I click "OK"
      And I click "External Link" in the CKEditor style panel
      And The cursor is at the end of CKEditor
      And I execute the "enter" command in CKEditor
    When I press "Save"
    Then I should see "http://button-primary.edu" in the "a.btn.btn--primary" element in the "Content" region
      And I should see "http://button-secondary.edu" in the "a.btn.btn--secondary" element in the "Content" region
      And I should see "http://button-inverted.edu" in the "a.btn.btn--invert" element in the "Content" region
      And I should see "http://arrowlink.edu" in the "a.icon-link.icon-link--internal" element in the "Content" region
      And I should see "http://downloadlink.edu" in the "a.icon-link.icon-link--download" element in the "Content" region
      And I should see "http://externallink.edu" in the "a.icon-link.icon-link--external" element in the "Content" region

  @api @javascript
  Scenario: Media Link button adds a widget and filters to wrap with a link
    Given the Administration Toolbar is hidden
    When I execute the "media_link" command in CKEditor
      And I wait for AJAX to finish
      And I fill in "Link URL" with "http://example.com"
      And I press "OK"
      And I press "Save"
    Then I should see "Title" in the "a.media-link .media-link__title" element in the "Content" region
      And I should see "Content" in the ".media-link__container p" element in the "Content" region
      And I should see "Add an Image" in the ".media-link__figure p" element in the "Content" region

  @api @javascript
  Scenario: Feature Block button adds a widget to CKEditor
#    Right Aligned
    Given the Administration Toolbar is hidden
    When I execute the "feature_block" command in CKEditor
      And I wait for AJAX to finish
      And I select the radio button "Align Right"
      And I press "OK"
      And I press "Save"
    Then I should see "Title" in the ".wysiwyg-feature-block .wysiwyg-feature-block__title" element in the "Content" region
    And I should see "Content" in the ".wysiwyg-feature-block__body" element in the "Content" region
      And the element ".u-align--right" should exist
      And the element ".u-width--half" should exist
#    Left Aligned
    When I visit "node/add/sf_page"
      And I fill in the following:
        | Title | Testing title |
    Given the Administration Toolbar is hidden
    When I execute the "feature_block" command in CKEditor
      And I wait for AJAX to finish
      And I select the radio button "Align Left"
      And I press "OK"
      And I press "Save"
    Then I should see "Title" in the ".wysiwyg-feature-block__title" element in the "Content" region
      And the element ".u-align--left" should exist
      And the element ".u-width--half" should exist
#    No Alignment
    When I visit "node/add/sf_page"
      And I fill in the following:
        | Title | Testing title |
    Given the Administration Toolbar is hidden
    When I execute the "feature_block" command in CKEditor
      And I wait for AJAX to finish
      And I select the radio button "None"
      And I press "OK"
      And I press "Save"
    Then I should see "Title" in the ".wysiwyg-feature-block__title" element in the "Content" region
      And I should not see a ".u-align--left" element
      And I should not see a ".u-align--right" element
      And I should not see a ".u-align--half" element
