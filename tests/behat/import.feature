@qtype @qtype_reacsimilarity
Feature: Test importing Reacsimilarity questions
  As a teacher
  In order to reuse my Reacsimilarity questions
  I need to import them

  Background:
    Given the following "users" exist:
      | username | firstname | lastname | email                |
      | teacher1 | T1        | Teacher1 | teacher1@example.com |
    And the following "courses" exist:
      | fullname | shortname |
      | Course 1 | C1        |
    And the following "course enrolments" exist:
      | user     | course | role           |
      | teacher1 | C1     | editingteacher |
    And I log in as "teacher1"
    And I am on "Course 1" course homepage

  @javascript @_file_upload
  Scenario: import reacsimilarity question.
    When I am on the "Course 1" "core_question > course question import" page
    And I set the field "id_format_xml" to "1"
    And I upload "question/type/reacsimilarity/tests/fixtures/testquestion.moodle.xml" file to "Import" filemanager
    And I press "id_submitbutton"
    Then I should see "Parsing questions from import file."
    And I should see "Importing 1 questions from file"
    And I should see "Draw the reaction between éthène and but-1,3-diène."
    And I press "Continue"
    And I should see "Diels Alder"
