<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Unit tests for the reacsimilarity question definition class.
 *
 * @package qtype
 * @subpackage  reacsimilarity
 * @copyright  2023 unistra  {@link http://unistra.fr}
 * @author Louis Plyer <louis.plyer@unistra.fr>, Céline Pervès <cperves@unistra.fr>, Alexandre Varnek <varnek@unistra.fr>,
 * Gilles Marcou <g.marcou@unistra.fr>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace qtype_reacsimilarity;
use advanced_testcase;
use question_attempt_step;
use question_state;
use test_question_maker;

defined('MOODLE_INTERNAL') || die();

global $CFG;
require_once($CFG->dirroot . '/question/type/questionbase.php');
require_once($CFG->dirroot . '/question/engine/tests/helpers.php');
require_once($CFG->dirroot . '/question/type/reacsimilarity/question.php');
/**
 * Unit tests for the reacsimilarity question definition class.
 *
 * @package qtype
 * @subpackage  reacsimilarity
 * @copyright  2023 unistra  {@link http://unistra.fr}
 * @author Louis Plyer <louis.plyer@unistra.fr>, Céline Pervès <cperves@unistra.fr>, Alexandre Varnek <varnek@unistra.fr>,
 * Gilles Marcou <g.marcou@unistra.fr>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class question_test extends advanced_testcase {

    protected function setUp(): void {
        $this->resetAfterTest();
        parent::setUp();
        $this->set_isida_url();
    }

    private function set_isida_url(): void {
        global $CFG;
        $configtestfile = $CFG->dirroot . '/question/type/reacsimilarity/config-test.php';
        if (file_exists($configtestfile)) {
            require($configtestfile);
        }
    }

    public function test_get_expected_data() {
        $question = test_question_maker::make_question('reacsimilarity', 'dielsalder');
        $this->assertEquals(array('answer' => PARAM_RAW), $question->get_expected_data());
    }

    public function test_is_complete_response() {
        $question = test_question_maker::make_question('reacsimilarity', 'dielsalder');
        $this->assertFalse($question->is_complete_response(array()));
        $this->assertFalse($question->is_complete_response(array('answer' => '')));
        $this->assertTrue($question->is_complete_response(array('answer' => '{}')));
    }

    public function test_get_correct_response() {
        $question = test_question_maker::make_question('reacsimilarity', 'dielsalder');

        $answer = '{"json":"{\\"m\\":[{\\"a\\":[{\\"x\\":137,\\"y\\":124.5,\\"i\\":\\"a0\\"},{\\"x\\":154.32050807568876,';
        $answer .= '\\"y\\":114.5,\\"i\\":\\"a1\\"},{\\"x\\":137,\\"y\\":144.5,\\"i\\":\\"a2\\"},{\\"x\\":154.32050807568876,';
        $answer .= '\\"y\\":154.5,\\"i\\":\\"a3\\"}],\\"b\\":[{\\"b\\":0,\\"e\\":1,\\"i\\":\\"b0\\",\\"o\\":2},';
        $answer .= '{\\"b\\":0,\\"e\\":2,\\"i\\":\\"b1\\"},{\\"b\\":2,\\"e\\":3,\\"i\\":\\"b2\\",\\"o\\":2}]},';
        $answer .= '{\\"a\\":[{\\"x\\":214,\\"y\\":123.5,\\"i\\":\\"a4\\"},{\\"x\\":214,\\"y\\":143.5,\\"i\\":\\"a5\\"}],';
        $answer .= '\\"b\\":[{\\"b\\":0,\\"e\\":1,\\"i\\":\\"b3\\",\\"o\\":2}]},{\\"a\\":[{\\"x\\":322,\\"y\\":115.5,\\"i\\":';
        $answer .= '\\"a6\\"},{\\"x\\":339.3205080756888,\\"y\\":125.5,\\"i\\":\\"a7\\"},{\\"x\\":339.3205080756888,\\"y\\":145.5,';
        $answer .= '\\"i\\":\\"a8\\"},{\\"x\\":322.00000000000006,\\"y\\":155.50000000000003,\\"i\\":\\"a9\\"},';
        $answer .= '{\\"x\\":304.67949192431126,\\"y\\":145.50000000000009,\\"i\\":\\"a10\\"},{\\"x\\":304.67949192431115,';
        $answer .= '\\"y\\":125.50000000000009,\\"i\\":\\"a11\\"}],\\"b\\":[{\\"b\\":0,\\"e\\":1,\\"i\\":\\"b4\\"},';
        $answer .= '{\\"b\\":1,\\"e\\":2,\\"i\\":\\"b5\\"},{\\"b\\":2,\\"e\\":3,\\"i\\":\\"b6\\"},{\\"b\\":3,';
        $answer .= '\\"e\\":4,\\"i\\":\\"b7\\"},{\\"b\\":4,\\"e\\":5,\\"i\\":\\"b8\\",\\"o\\":2},';
        $answer .= '{\\"b\\":5,\\"e\\":0,\\"i\\":\\"b9\\"}]}],\\"s\\":[{\\"i\\":\\"s0\\",\\"t\\":\\"Line\\",\\"x1\\":227,';
        $answer .= '\\"y1\\":132.5,\\"x2\\":281.03702434442516,\\"y2\\":132.5,\\"a\\":\\"synthetic\\"},{\\"i\\":\\"s1\\",\\"t\\":';
        $answer .= '\\"AtomMapping\\",\\"a1\\":\\"a1\\",\\"a2\\":\\"a6\\"},{\\"i\\":\\"s2\\",\\"t\\":\\"AtomMapping\\",';
        $answer .= '\\"a1\\":\\"a11\\",\\"a2\\":\\"a0\\"},{\\"i\\":\\"s3\\",\\"t\\":\\"AtomMapping\\",\\"a1\\":\\"a2\\",\\"a2\\":';
        $answer .= '\\"a10\\"},{\\"i\\":\\"s4\\",\\"t\\":\\"AtomMapping\\",\\"a1\\":\\"a9\\",\\"a2\\":\\"a3\\"},{\\"i\\":\\"s5\\",';
        $answer .= '\\"t\\":\\"AtomMapping\\",\\"a1\\":\\"a4\\",\\"a2\\":\\"a7\\"},{\\"i\\":\\"s6\\",\\"t\\":\\"AtomMapping\\",';
        $answer .= '\\"a1\\":\\"a8\\",\\"a2\\":\\"a5\\"}]}","mol_file":"$RXN\\nReaction from ChemDoodle Web Components\\n\\n';
        $answer .= 'http://www.ichemlabs.com\\n  2  1\\n$MOL\\nMolecule from ChemDoodle Web Components\\n\\nhttp://www.ichemlabs';
        $answer .= '.com\\n  4  3  0  0  0  0            999 V2000\\n   -0.4330    0.5000    0.0000 C   0  0  0  0  0  0  0';
        $answer .= '  0  0  2  0  0\\n    0.4330    1.0000    0.0000 C   0  0  0  0  0  0  0  0  0  1  0  0\\n   -0.4330   -0.5000';
        $answer .= '    0.0000 C   0  0  0  0  0  0  0  0  0  3  0  0\\n    0.4330   -1.0000    0.0000 C   0  0  0  0  0  0  0  0';
        $answer .= '  0  4  0  0\\n  1  2  2  0  0  0  0\\n  1  3  1  0  0  0  0\\n  3  4  2  0  0  0  0\\nM  END\\n$MOL\\n';
        $answer .= 'Molecule';
        $answer .= ' from ChemDoodle Web Components\\n\\nhttp://www.ichemlabs.com\\n  2  1  0  0  0  0            999 V2000\\n    ';
        $answer .= '0.0000    0.5000    0.0000 C   0  0  0  0  0  0  0  0  0  5  0  0\\n    0.0000   -0.5000    0.0000 C   0  0  ';
        $answer .= '0  0  0  0  0  0  0  6  0  0\\n  1  2  2  0  0  0  0\\nM  END\\n$MOL\\nMolecule from ChemDoodle Web Components';
        $answer .= '\\n\\nhttp://www.ichemlabs.com\\n  6  6  0  0  0  0            999 V2000\\n    0.0000    1.0000    0.0000 C   ';
        $answer .= '0  0  0  0  0  0  0  0  0  1  0  0\\n    0.8660    0.5000    0.0000 C   0  0  0  0  0  0  0  0  0  5  0  0\\n';
        $answer .= '    0.8660   -0.5000    0.0000 C   0  0  0  0  0  0  0  0  0  6  0  0\\n    0.0000   -1.0000    0.0000 C   0  ';
        $answer .= '0  0  0  0  0  0  0  0  4  0  0\\n   -0.8660   -0.5000    0.0000 C   0  0  0  0  0  0  0  0  0  3  0  0\\n   ';
        $answer .= '-0.8660    0.5000    0.0000 C   0  0  0  0  0  0  0  0  0  2  0  0\\n  1  2  1  0  0  0  0\\n  2  3  1  0  0';
        $answer .= '  0  0\\n  3  4  1  0  0  0  0\\n  4  5  1  0  0  0  0\\n  5  6  2  0  0  0  0\\n  6  1  1  0  0  0  0\\nM  ';
        $answer .= 'END\\n"}';
        $this->assertEquals(array('answer' => $answer), $question->get_correct_response());
    }

    public function test_get_question_summary() {
        $sa = test_question_maker::make_question('reacsimilarity', 'dielsalder');
        $qsummary = $sa->get_question_summary();
        $this->assertEquals('Draw the reaction between éthène and but-1,3-diène.', $qsummary);
    }

    public function test_summarise_response() {
        $sa = test_question_maker::make_question('reacsimilarity', 'dielsalder');

        $answer = '{"json":"{\\"m\\":[{\\"a\\":[{\\"x\\":137,\\"y\\":124.5,\\"i\\":\\"a0\\"},{\\"x\\":154.32050807568876,';
        $answer .= '\\"y\\":114.5,\\"i\\":\\"a1\\"},{\\"x\\":137,\\"y\\":144.5,\\"i\\":\\"a2\\"},{\\"x\\":154.32050807568876,';
        $answer .= '\\"y\\":154.5,\\"i\\":\\"a3\\"}],\\"b\\":[{\\"b\\":0,\\"e\\":1,\\"i\\":\\"b0\\",\\"o\\":2},';
        $answer .= '{\\"b\\":0,\\"e\\":2,\\"i\\":\\"b1\\"},{\\"b\\":2,\\"e\\":3,\\"i\\":\\"b2\\",\\"o\\":2}]},';
        $answer .= '{\\"a\\":[{\\"x\\":214,\\"y\\":123.5,\\"i\\":\\"a4\\"},{\\"x\\":214,\\"y\\":143.5,\\"i\\":\\"a5\\"}],';
        $answer .= '\\"b\\":[{\\"b\\":0,\\"e\\":1,\\"i\\":\\"b3\\",\\"o\\":2}]},{\\"a\\":[{\\"x\\":322,\\"y\\":115.5,\\"i\\":';
        $answer .= '\\"a6\\"},{\\"x\\":339.3205080756888,\\"y\\":125.5,\\"i\\":\\"a7\\"},{\\"x\\":339.3205080756888,\\"y\\":145.5,';
        $answer .= '\\"i\\":\\"a8\\"},{\\"x\\":322.00000000000006,\\"y\\":155.50000000000003,\\"i\\":\\"a9\\"},';
        $answer .= '{\\"x\\":304.67949192431126,\\"y\\":145.50000000000009,\\"i\\":\\"a10\\"},{\\"x\\":304.67949192431115,';
        $answer .= '\\"y\\":125.50000000000009,\\"i\\":\\"a11\\"}],\\"b\\":[{\\"b\\":0,\\"e\\":1,\\"i\\":\\"b4\\"},';
        $answer .= '{\\"b\\":1,\\"e\\":2,\\"i\\":\\"b5\\"},{\\"b\\":2,\\"e\\":3,\\"i\\":\\"b6\\"},{\\"b\\":3,';
        $answer .= '\\"e\\":4,\\"i\\":\\"b7\\"},{\\"b\\":4,\\"e\\":5,\\"i\\":\\"b8\\",\\"o\\":2},';
        $answer .= '{\\"b\\":5,\\"e\\":0,\\"i\\":\\"b9\\"}]}],\\"s\\":[{\\"i\\":\\"s0\\",\\"t\\":\\"Line\\",\\"x1\\":227,';
        $answer .= '\\"y1\\":132.5,\\"x2\\":281.03702434442516,\\"y2\\":132.5,\\"a\\":\\"synthetic\\"},{\\"i\\":\\"s1\\",\\"t\\":';
        $answer .= '\\"AtomMapping\\",\\"a1\\":\\"a1\\",\\"a2\\":\\"a6\\"},{\\"i\\":\\"s2\\",\\"t\\":\\"AtomMapping\\",';
        $answer .= '\\"a1\\":\\"a11\\",\\"a2\\":\\"a0\\"},{\\"i\\":\\"s3\\",\\"t\\":\\"AtomMapping\\",\\"a1\\":\\"a2\\",\\"a2\\":';
        $answer .= '\\"a10\\"},{\\"i\\":\\"s4\\",\\"t\\":\\"AtomMapping\\",\\"a1\\":\\"a9\\",\\"a2\\":\\"a3\\"},{\\"i\\":\\"s5\\",';
        $answer .= '\\"t\\":\\"AtomMapping\\",\\"a1\\":\\"a4\\",\\"a2\\":\\"a7\\"},{\\"i\\":\\"s6\\",\\"t\\":\\"AtomMapping\\",';
        $answer .= '\\"a1\\":\\"a8\\",\\"a2\\":\\"a5\\"}]}","mol_file":"$RXN\\nReaction from ChemDoodle Web Components\\n\\n';
        $answer .= 'http://www.ichemlabs.com\\n  2  1\\n$MOL\\nMolecule from ChemDoodle Web Components\\n\\nhttp://www.ichemlabs';
        $answer .= '.com\\n  4  3  0  0  0  0            999 V2000\\n   -0.4330    0.5000    0.0000 C   0  0  0  0  0  0  0';
        $answer .= '  0  0  2  0  0\\n    0.4330    1.0000    0.0000 C   0  0  0  0  0  0  0  0  0  1  0  0\\n   -0.4330   -0.5000';
        $answer .= '    0.0000 C   0  0  0  0  0  0  0  0  0  3  0  0\\n    0.4330   -1.0000    0.0000 C   0  0  0  0  0  0  0  0';
        $answer .= '  0  4  0  0\\n  1  2  2  0  0  0  0\\n  1  3  1  0  0  0  0\\n  3  4  2  0  0  0  0\\nM  END\\n$MOL\\n';
        $answer .= 'Molecule';
        $answer .= ' from ChemDoodle Web Components\\n\\nhttp://www.ichemlabs.com\\n  2  1  0  0  0  0            999 V2000\\n    ';
        $answer .= '0.0000    0.5000    0.0000 C   0  0  0  0  0  0  0  0  0  5  0  0\\n    0.0000   -0.5000    0.0000 C   0  0  ';
        $answer .= '0  0  0  0  0  0  0  6  0  0\\n  1  2  2  0  0  0  0\\nM  END\\n$MOL\\nMolecule from ChemDoodle Web Components';
        $answer .= '\\n\\nhttp://www.ichemlabs.com\\n  6  6  0  0  0  0            999 V2000\\n    0.0000    1.0000    0.0000 C   ';
        $answer .= '0  0  0  0  0  0  0  0  0  1  0  0\\n    0.8660    0.5000    0.0000 C   0  0  0  0  0  0  0  0  0  5  0  0\\n';
        $answer .= '    0.8660   -0.5000    0.0000 C   0  0  0  0  0  0  0  0  0  6  0  0\\n    0.0000   -1.0000    0.0000 C   0  ';
        $answer .= '0  0  0  0  0  0  0  0  4  0  0\\n   -0.8660   -0.5000    0.0000 C   0  0  0  0  0  0  0  0  0  3  0  0\\n   ';
        $answer .= '-0.8660    0.5000    0.0000 C   0  0  0  0  0  0  0  0  0  2  0  0\\n  1  2  1  0  0  0  0\\n  2  3  1  0  0';
        $answer .= '  0  0\\n  3  4  1  0  0  0  0\\n  4  5  1  0  0  0  0\\n  5  6  2  0  0  0  0\\n  6  1  1  0  0  0  0\\nM  ';
        $answer .= 'END\\n"}';
        $summary = $sa->summarise_response(array('answer' => $answer));
        $molfile = json_decode($answer)->{"mol_file"};
        $this->assertEquals($molfile, $summary);
    }

    public function test_get_validation_error() {
        $question = test_question_maker::make_question('reacsimilarity', 'dielsalder');
        $this->assertEquals(get_string('pleaseenterananswer', 'qtype_reacsimilarity'),
                $question->get_validation_error(array()));
        $this->assertNotEquals(get_string('pleaseenterananswer', 'qtype_reacsimilarity'),
                $question->get_validation_error(array('answer' => '{}')));
    }

    public function test_compute_final_grade() {
        $sa = test_question_maker::make_question('reacsimilarity', 'dielsalder');
        $sa->start_attempt(new question_attempt_step(), 1); // Needed ?
        $nulmol = '{"json":"{\"m\":[{\"a\":[]}]}","mol_file":""}';
        $molnolp = '{"json":"{\"m\":[{\"a\":[{\"x\":260.75,\"y\":169,\"i\":\"a0\",\"l\":\"O\"},';
        $molnolp .= '{\"x\":278.0705080756888,\"y\":159,\"i\":\"a1\"},{\"x\":295.3910161513775,\"y\":169.00000000000003,\"i\":\"a2';
        $molnolp .= '\"}],\"b\":[{\"b\":0,\"e\":1,\"i\":\"b0\"},{\"b\":1,\"e\":2,\"i\":\"b1\"}]}]}","mol_file":"Molecule from';
        $molnolp .= ' ChemDoodle Web Components\n\nhttp://www.ichemlabs.com\n  3  2  0  0  0  0            999 V2000\n   -0.8660';
        $molnolp .= '   -0.2500    0.0000 O   0  0  0  0  0  0\n    0.0000    0.2500    0.0000 C   0  0  0  0  0  0\n';
        $molnolp .= '    0.8660   -0.2500    0.0000 C   0  0  0  0  0  0\n  1  2  1  0  0  0  0\n  2  3  1  0  0  0  0\nM  END"}';
        $this->assertEquals(array(0, question_state::graded_state_for_fraction(0)),
                $sa->grade_response(array('answer' => $nulmol)));
        $this->assertEquals(array(1, question_state::graded_state_for_fraction(1)),
                $sa->grade_response($sa->get_correct_response()));
        $this->assertNotEquals(array(1, question_state::graded_state_for_fraction(1)),
                $sa->grade_response(array('answer' => $nulmol)));
        $this->assertNotEquals(array(0, question_state::graded_state_for_fraction(0)),
                $sa->grade_response(array('answer' => $molnolp)));
        $this->assertNotEquals(array(1, question_state::graded_state_for_fraction(1)),
                $sa->grade_response(array('answer' => $molnolp)));
    }
}
