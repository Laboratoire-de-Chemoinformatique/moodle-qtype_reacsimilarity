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
 * Renderer class for the Reacsimilarity question type
 *
 * @package qtype
 * @subpackage  reacsimilarity
 * @copyright  2023 unistra  {@link http://unistra.fr}
 * @author Louis Plyer <louis.plyer@unistra.fr>, Céline Pervès <cperves@unistra.fr>, Alexandre Varnek <varnek@unistra.fr>,
 * Gilles Marcou <g.marcou@unistra.fr>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * Generates the output for reacsimilarity questions.
 *
 * @copyright  2023 unistra {@link http://unistra.fr}

 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

class qtype_reacsimilarity_renderer extends qtype_renderer {
    public function formulation_and_controls(question_attempt $qa,
            question_display_options $options): string {

        $question = $qa->get_question();
        $currentanswer = $qa->get_last_qt_var('answer');

        $inputname = $qa->get_qt_field_name('answer');
        $inputattributes = array(
                'id' => $inputname,
        );
        $toreplaceid = strtr($inputname, ":", "_") . "_cwc";

        if ($options->readonly) {
            $inputattributes['readonly'] = 'readonly';
        }

        if ($options->correctness) {
            $answer = $question->get_correct_answer();
            if ($answer) {
                $fraction = $answer->fraction;
            } else {
                $fraction = 0;
            }
            $inputattributes['class'] = $this->feedback_class($fraction);
        }

        $questiontext = $question->format_questiontext($qa);
        $placeholder = false;
        if (preg_match('/_____+/', $questiontext, $matches)) {
            $placeholder = $matches[0];
            $inputattributes['size'] = round(strlen($placeholder ?? '') * 1.1);
        }

        $input = html_writer::tag('div',
                html_writer::tag('canvas', "", array('id' => $toreplaceid)) . $this->hidden_fields($qa));

        if ($placeholder) {
            $inputinplace = html_writer::tag('label', get_string('answer'),
                    array('for' => $inputattributes['id'], 'class' => 'accesshide'));
            $inputinplace .= $input;
            $questiontext = substr_replace($questiontext ?? '', $inputinplace,
                    strpos($questiontext, $placeholder), strlen($placeholder ?? ''));
        }

        $result = html_writer::tag('div', $questiontext, array('class' => 'qtext'));

        if (!$placeholder) {
            $result .= html_writer::start_tag('div', array('class' => 'ablock'));
            $result .= html_writer::tag('label', get_string('answer', 'qtype_reacsimilarity',
                    html_writer::tag('span', $input, array('class' => 'answer'))),
                    array('for' => $inputattributes['id']));
            $result .= html_writer::end_tag('div');
        }

        if ($qa->get_state() == question_state::$invalid) {
            $result .= html_writer::nonempty_tag('div',
                    $question->get_validation_error(array('answer' => $currentanswer)),
                    array('class' => 'validationerror'));
        }
        $this->require_js($toreplaceid, $options->readonly, $options->correctness, $inputname, $question->id);

        return $result;
    }

    /**
     * Return any HTML that needs to be included in the page's <head> when this
     * question is used.
     *
     * @param $qa the question attempt that will be displayed on the page.
     * @return string HTML fragment.
     */
    public function head_code(question_attempt $qa) {
        $this->page->requires->css
        ("/question/type/reacsimilarity/chemdoodle/ChemDoodleWeb-9.4.0/install/ChemDoodleWeb.css");
        $this->page->requires->css
        ("/question/type/reacsimilarity/chemdoodle/ChemDoodleWeb-9.4.0/install/uis/jquery-ui-1.11.4.css");
        //$this->page->requires->js_call_amd("qtype_reacsimilarity/api_helper");
        $this->page->requires->js("/question/type/reacsimilarity/chemdoodle/ChemDoodleWeb-9.4.0/install/ChemDoodleWeb-min.js",
                true);
        $this->page->requires->js
        ("/question/type/reacsimilarity/chemdoodle/ChemDoodleWeb-9.4.0/install/uis/ChemDoodleWeb-uis-min.js", true);
        $this->page->requires->js_init_code('window.ChemDoodleVar = ChemDoodle;');
        parent::head_code($qa);
    }

    protected function require_js($toreplaceid, $readonly, $correctness, $inputname, $questionid) {
        $name = $toreplaceid . '_editor';
        $this->page->requires->js_call_amd(
            'qtype_reacsimilarity/chemdoodle_canvas',
                'insert_cwc',
                    array(
                        $toreplaceid,
                        $name,
                        str_replace(':', '_', $inputname),
                        $readonly,
                        $questionid),
                );
        $this->page->requires->js_init_code(
            '
                if(document.querySelector(".ChemDoodleWebComponent").length){
                    document.querySelector("#techinfo_inner :nth-child(6n)").css("white-space","pre"); // For the preview.
                }'
            , true);
    }

    protected function hidden_fields(question_attempt $qa): string {
        $question = $qa->get_question();

        $hiddenfieldshtml = '';
        $inputids = new stdClass();
        $responsefields = array_keys($question->get_expected_data());
        foreach ($responsefields as $responsefield) {
            $hiddenfieldshtml .= $this->hidden_field_for_qt_var($qa, $responsefield);
        }
        return $hiddenfieldshtml;
    }

    protected function hidden_field_for_qt_var(question_attempt $qa, $varname): string {
        $value = $qa->get_last_qt_var($varname, '');
        $fieldname = $qa->get_qt_field_name($varname);
        $attributes = array('type' => 'hidden',
                'id' => str_replace(':', '_', $fieldname),
                'class' => $varname,
                'name' => $fieldname,
                'value' => $value);
        return html_writer::empty_tag('input', $attributes);
    }

    public function specific_feedback(question_attempt $qa): string {
        $question = $qa->get_question();

        $answer = $question->get_correct_answer();
        $state = $qa->get_state();
        if (!$answer || !$answer->feedback) {
            return '';
        }

        if ($state != question_state::$gradedright  ) {
            return $question->format_text($answer->feedback, $answer->feedbackformat,
                    $qa, 'question', 'answerfeedback', $answer->id);
        } else {
            return '';
        }
    }

    public function correct_response(question_attempt $qa) {
        $question = $qa->get_question();
        $inputname = $qa->get_qt_field_name('answer');
        $toreplaceid = strtr($inputname, ":", "_") . "_cwc_correct_answer";
        $this->require_js_correct($toreplaceid, $question->id);
        return get_string('correctansweris', 'qtype_reacsimilarity') . html_writer::tag('canvas', "", array('id' => $toreplaceid));
    }

    protected function require_js_correct($toreplaceid, $questionid) {
        $name = $toreplaceid . '_editor';
        $this->page->requires->js_call_amd('qtype_reacsimilarity/chemdoodle_canvas','insert_good_answer',
                array($toreplaceid,
                        $name,
                        $questionid)
        );
    }
}
