@qtype @qtype_reacsimilarity
Feature: Test creating a reacsimilarity question
  As a teacher
  In order to test my students
  I need to be able to create a reacsimilarity question

  Background:
    Given the following "users" exist:
      | username | firstname | lastname | email               |
      | teacher1 | T1        | Teacher1 | teacher1@moodle.com |
    And the following "courses" exist:
      | fullname | shortname | category |
      | Course 1 | C1        | 0        |
    And the following "course enrolments" exist:
      | user     | course | role           |
      | teacher1 | C1     | editingteacher |
    And I log in as "teacher1"
    And I am on "Course 1" course homepage
    And I navigate to "Question bank" in current page administration

  Scenario: Create a Reacsimilarity question
    When I add a "Reacsimilarity" question filling the form with:
      | Question name        | reacsimilarity-001                         |
      | Question text        | Draw the reaction between éthène and but-1,3-diène.  |
      | General feedback     | Simple as that |
      | Default mark         | 1                                         |
      | id_answer_0          | {"json":"{\"m\":[{\"a\":[{\"x\":137,\"y\":124.5,\"i\":\"a0\"},{\"x\":154.32050807568876,\"y\":114.5,\"i\":\"a1\"},{\"x\":137,\"y\":144.5,\"i\":\"a2\"},{\"x\":154.32050807568876,\"y\":154.5,\"i\":\"a3\"}],\"b\":[{\"b\":0,\"e\":1,\"i\":\"b0\",\"o\":2},{\"b\":0,\"e\":2,\"i\":\"b1\"},{\"b\":2,\"e\":3,\"i\":\"b2\",\"o\":2}]},{\"a\":[{\"x\":214,\"y\":123.5,\"i\":\"a4\"},{\"x\":214,\"y\":143.5,\"i\":\"a5\"}],\"b\":[{\"b\":0,\"e\":1,\"i\":\"b3\",\"o\":2}]},{\"a\":[{\"x\":322,\"y\":115.5,\"i\":\"a6\"},{\"x\":339.3205080756888,\"y\":125.5,\"i\":\"a7\"},{\"x\":339.3205080756888,\"y\":145.5,\"i\":\"a8\"},{\"x\":322.00000000000006,\"y\":155.50000000000003,\"i\":\"a9\"},{\"x\":304.67949192431126,\"y\":145.50000000000009,\"i\":\"a10\"},{\"x\":304.67949192431115,\"y\":125.50000000000009,\"i\":\"a11\"}],\"b\":[{\"b\":0,\"e\":1,\"i\":\"b4\"},{\"b\":1,\"e\":2,\"i\":\"b5\"},{\"b\":2,\"e\":3,\"i\":\"b6\"},{\"b\":3,\"e\":4,\"i\":\"b7\"},{\"b\":4,\"e\":5,\"i\":\"b8\",\"o\":2},{\"b\":5,\"e\":0,\"i\":\"b9\"}]}],\"s\":[{\"i\":\"s0\",\"t\":\"Line\",\"x1\":227,\"y1\":132.5,\"x2\":281.03702434442516,\"y2\":132.5,\"a\":\"synthetic\"},{\"i\":\"s1\",\"t\":\"AtomMapping\",\"a1\":\"a1\",\"a2\":\"a6\"},{\"i\":\"s2\",\"t\":\"AtomMapping\",\"a1\":\"a11\",\"a2\":\"a0\"},{\"i\":\"s3\",\"t\":\"AtomMapping\",\"a1\":\"a2\",\"a2\":\"a10\"},{\"i\":\"s4\",\"t\":\"AtomMapping\",\"a1\":\"a9\",\"a2\":\"a3\"},{\"i\":\"s5\",\"t\":\"AtomMapping\",\"a1\":\"a4\",\"a2\":\"a7\"},{\"i\":\"s6\",\"t\":\"AtomMapping\",\"a1\":\"a8\",\"a2\":\"a5\"}]}","mol_file":"$RXN\nReaction from ChemDoodle Web Components\n\nhttp://www.ichemlabs.com\n  2  1\n$MOL\nMolecule from ChemDoodle Web Components\n\nhttp://www.ichemlabs.com\n  4  3  0  0  0  0            999 V2000\n   -0.4330    0.5000    0.0000 C   0  0  0  0  0  0  0  0  0  2  0  0\n    0.4330    1.0000    0.0000 C   0  0  0  0  0  0  0  0  0  1  0  0\n   -0.4330   -0.5000    0.0000 C   0  0  0  0  0  0  0  0  0  3  0  0\n    0.4330   -1.0000    0.0000 C   0  0  0  0  0  0  0  0  0  4  0  0\n  1  2  2  0  0  0  0\n  1  3  1  0  0  0  0\n  3  4  2  0  0  0  0\nM  END\n$MOL\nMolecule from ChemDoodle Web Components\n\nhttp://www.ichemlabs.com\n  2  1  0  0  0  0            999 V2000\n    0.0000    0.5000    0.0000 C   0  0  0  0  0  0  0  0  0  5  0  0\n    0.0000   -0.5000    0.0000 C   0  0  0  0  0  0  0  0  0  6  0  0\n  1  2  2  0  0  0  0\nM  END\n$MOL\nMolecule from ChemDoodle Web Components\n\nhttp://www.ichemlabs.com\n  6  6  0  0  0  0            999 V2000\n    0.0000    1.0000    0.0000 C   0  0  0  0  0  0  0  0  0  1  0  0\n    0.8660    0.5000    0.0000 C   0  0  0  0  0  0  0  0  0  5  0  0\n    0.8660   -0.5000    0.0000 C   0  0  0  0  0  0  0  0  0  6  0  0\n    0.0000   -1.0000    0.0000 C   0  0  0  0  0  0  0  0  0  4  0  0\n   -0.8660   -0.5000    0.0000 C   0  0  0  0  0  0  0  0  0  3  0  0\n   -0.8660    0.5000    0.0000 C   0  0  0  0  0  0  0  0  0  2  0  0\n  1  2  1  0  0  0  0\n  2  3  1  0  0  0  0\n  3  4  1  0  0  0  0\n  4  5  1  0  0  0  0\n  5  6  2  0  0  0  0\n  6  1  1  0  0  0  0\nM  END\n"} |
      | id_fraction_0        | 100%                                      |
      | id_feedback_0        | Well done.             |
    Then I should see "reacsimilarity-001"
