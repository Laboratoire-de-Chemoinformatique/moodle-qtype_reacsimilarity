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
 * Form definition for the reacsimilarity question type
 *
 * @package qtype
 * @subpackage  reacsimilarity
 * @copyright  2023 unistra  {@link http://unistra.fr}
 * @author Louis Plyer <louis.plyer@unistra.fr>, Céline Pervès <cperves@unistra.fr>, Alexandre Varnek <varnek@unistra.fr>,
 * Gilles Marcou <g.marcou@unistra.fr>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */


defined('MOODLE_INTERNAL') || die();

global $CFG;
require_once($CFG->dirroot . '/question/type/shortanswer/edit_shortanswer_form.php');

class qtype_reacsimilarity_edit_form extends qtype_shortanswer_edit_form {

    /**
     * reacsimilarity question editing form definition.
     *
     * @param $mform
     * @throws coding_exception
     * @copyright  2023 unistra {@link http://unistra.fr}
     * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
     */
    protected function definition_inner($mform) {
        global $PAGE, $CFG;
        $PAGE->requires->css("/question/type/reacsimilarity/chemdoodle/ChemDoodleWeb-9.4.0/install/ChemDoodleWeb.css");
        $PAGE->requires->css("/question/type/reacsimilarity/chemdoodle/ChemDoodleWeb-9.4.0/install/uis/jquery-ui-1.11.4.css");
        $PAGE->requires->js("/question/type/reacsimilarity/chemdoodle/ChemDoodleWeb-9.4.0/install/ChemDoodleWeb-min.js", true);
        $PAGE->requires->js("/question/type/reacsimilarity/chemdoodle/ChemDoodleWeb-9.4.0/install/uis/ChemDoodleWeb-uis-min.js",
                true);
        $PAGE->requires->js("/question/type/reacsimilarity/javascript/jquery-1.11.3.min.js", true);
        $PAGE->requires->js("/question/type/reacsimilarity/utils.js");

        $menustereo = array(
                get_string('caseno', 'qtype_reacsimilarity'),
                get_string('caseyes', 'qtype_reacsimilarity')
        );

        $menuthreshold = question_bank::fraction_options();

        $menualpha = array(
                '1.0000000' => '1',
                '10.0000000' => '10',
                '5.0000000' => '5',
                '3.0000000' => '3',
                '2.5000000' => '2,5',
                '2.0000000' => '2',
                '1.2500000' => '1,25',
                '0.8000000' => '0,8',
                '0.6000000' => '0,6',
                '0.5000000' => '0,5',
                '0.4000000' => '0,4',
                '0.2000000' => '0,2',
                '0.1000000' => '0,1');

        $mform->addElement('select', 'threshold',
                get_string('threshold', 'qtype_reacsimilarity'), $menuthreshold);
        $mform->addHelpButton('threshold', 'threshold', 'qtype_reacsimilarity');

        $mform->addElement('select', 'alpha',
                get_string('alphaselection', 'qtype_reacsimilarity'), $menualpha);
        $mform->addHelpButton('alpha', 'alphaselection', 'qtype_reacsimilarity');

        $mform->addElement('select', 'stereobool',
                get_string('stereoselection', 'qtype_reacsimilarity'), $menustereo);

        $mform->addElement('static', 'scaffoldinstruct',
                get_string('scaffoldselection', 'qtype_reacsimilarity'));
        $mform->addHelpButton('scaffoldinstruct', 'scaffoldselection', 'qtype_reacsimilarity');

        $mform->addElement('html', '<canvas id="sketcherScaffold" style="padding-left: 0;padding-right: 0;margin-left: auto;
                margin-right: auto;display: block;"></canvas>');
        $mform->addElement('hidden', 'scaffold');
        $mform->setType('scaffold', PARAM_RAW);
        $mform->addElement('html', html_writer::empty_tag('br'));

        $this->require_js_scaffold();

        $mform->addElement('static', 'answersinstruct',
                get_string('correctanswers', 'qtype_reacsimilarity'),
                get_string('filloutoneanswer', 'qtype_reacsimilarity'));
        $mform->addHelpButton('answersinstruct', 'insertfrom', 'qtype_reacsimilarity');

        $mform->closeHeaderBefore('answerinstruct');

        // Add the iframe element which contains the ketcher instance.
        $mform->addElement('html', '<canvas id="sketcher" style="padding-left: 0;padding-right: 0;margin-left: auto;
                margin-right: auto;display: block;"></canvas>');
        $this->require_js();
        $mform->addElement('html', html_writer::empty_tag('br'));
        $answernumber = get_string('answernumber', 'qtype_reacsimilarity', '{no}');

        $this->add_per_answer_fields($mform, $answernumber, array('1.0' => '100%', '0.0' =>
                get_string('clearanswer', 'qtype_reacsimilarity')), $minoptions = 1,
                $addoptions = 1); // Grade only needed to be 1, in order to be sure to get the right specific feedback.
    }

    protected function require_js() {
        global $PAGE, $CFG;
        $jsmodule = array(
                'name'     => 'qtype_reacsimilarity',
                'fullpath' => '/question/type/reacsimilarity/module.js',
                'requires' => array(),
                'strings' => array()
        );
        $directory = json_encode(array("dirrMoodle" => $CFG->wwwroot), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
        $PAGE->requires->js_init_call('M.qtype_reacsimilarity.insert_form',
                array($directory),
                true,
                $jsmodule);
    }

    protected function require_js_preview() {
        global $PAGE, $CFG;
        $jsmodule = array(
                'name'     => 'qtype_reacsimilarity',
                'fullpath' => '/question/type/reacsimilarity/module.js',
                'requires' => array(),
                'strings' => array()
        );
        $PAGE->requires->js_init_call('M.qtype_reacsimilarity.insert_form_preview',
                null,
                true,
                $jsmodule);
    }

    protected function require_js_scaffold() {
        global $PAGE, $CFG;
        $jsmodule = array(
                'name'     => 'qtype_reacsimilarity',
                'fullpath' => '/question/type/reacsimilarity/module.js',
                'requires' => array(),
                'strings' => array()
        );
        $directory = json_encode(array("dirrMoodle" => $CFG->wwwroot), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
        $PAGE->requires->js_init_call('M.qtype_reacsimilarity.insert_scaffold',
                array($directory),
                true,
                $jsmodule);
    }

    protected function get_per_answer_fields($mform, $label, $gradeoptions, &$repeatedoptions, &$answersoption): array {
        global $CFG;
        $repeated = array();
        $answeroptions = array();
        $previewgroup = array();
        $insertchamp = array();

        $answeroptions[] = $mform->createElement('textarea', 'answer',
                $label, array('row' => 1, 'style' => 'display: none;'));
        $answeroptions[] = $mform->createElement('select', 'fraction',
                get_string('gradenoun'), $gradeoptions);

        $previewgroup[] = $mform->createElement('html', '<canvas id="sketcher_preview" name="test_preview"></canvas>');

        $answeroptions[] = $mform->createElement('group', 'previewgroup',
                get_string('formtest', 'qtype_reacsimilarity'), $previewgroup, null, false);

        $repeated[] = $mform->createElement('group', 'answeroptions', $label, $answeroptions, null, false);

        $fromketcher = 'classo = set-molfile';
        $toketcher = 'classo = load-molfile';
        $clearanswer = 'classo = clear_answer';
        $molempt = 'classo = mol_empty';
        $needreac = 'classo = needreac';

        $insertchamp[] = $mform->createElement('button', 'insertfrom',
                get_string('insertfromeditor', 'qtype_reacsimilarity'), $fromketcher);
        $insertchamp[] = $mform->createElement('html', '<div '. $molempt . ' display= inline-block'. '>' .
                get_string('moleculeempty', 'qtype_reacsimilarity') . '</div>');
        $insertchamp[] = $mform->createElement('html', '<div '. $needreac . ' display= inline-block, style = "display: none"'. '>' .
                get_string('errorreac', 'qtype_reacsimilarity') . '</div>');

        $repeated[] = $mform->createElement('group', 'insert_group', ' ', $insertchamp, null, false);

        $repeated[] = $mform->createElement('button', 'insertto',
                get_string('inserttoeditor', 'qtype_reacsimilarity'), $toketcher);
        $repeated[] = $mform->createElement('button', 'clearanswer',
                get_string('clearanswer', 'qtype_reacsimilarity'), $clearanswer);
        $repeated[] = $mform->createElement('editor', 'feedback',
                get_string('feedback', 'question'), array('rows' => 5), $this->editoroptions);

        $repeatedoptions['answer']['type'] = PARAM_RAW;
        $repeatedoptions['fraction']['default'] = 1;
        $answersoption = 'answers';
        $this->require_js_preview();

        return $repeated;
    }

    protected function data_preprocessing($question): object {
        $question = parent::data_preprocessing($question);
        return $question;
    }

    public function qtype() {
        return 'reacsimilarity';
    }
}
