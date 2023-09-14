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
 * Test helpers for the reacsimilarity question type.
 *
 * @package qtype
 * @subpackage  reacsimilarity
 * @copyright  2023 unistra  {@link http://unistra.fr}
 * @author Louis Plyer <louis.plyer@unistra.fr>, Céline Pervès <cperves@unistra.fr>, Alexandre Varnek <varnek@unistra.fr>,
 * Gilles Marcou <g.marcou@unistra.fr>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * Test helper class for the reacsimilarity question type.
 *
 * @package qtype
 * @subpackage  reacsimilarity
 * @copyright  2023 unistra  {@link http://unistra.fr}
 * @author Louis Plyer <louis.plyer@unistra.fr>, Céline Pervès <cperves@unistra.fr>, Alexandre Varnek <varnek@unistra.fr>,
 * Gilles Marcou <g.marcou@unistra.fr>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class qtype_reacsimilarity_test_helper extends question_test_helper {
    public function get_test_questions() {
        return array('dielsalder');
    }

    public function make_reacsimilarity_question_dielsalder() {
        question_bank::load_question_definition_classes('reacsimilarity');
        $sa = new qtype_reacsimilarity_question();
        test_question_maker::initialise_a_question($sa);
        $sa->name = 'Diels Alder';
        $sa->questiontext = 'Draw the reaction between éthène and but-1,3-diène.';
        $sa->stereobool = 0;
        $sa->threshold = 0;
        $sa->alpha = 1;
        $sa->scaffold = '';
        $sa->generalfeedback = 'Generalfeedback: Be sure to add the atom mapping!';
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
        $answer .= '  0  4  0  0\\n  1  2  2  0  0  0  0\\n  1  3  1  0  0  0  0\\n  3  4  2  0  0  0  0\\nM  END\\n$MOL\\nMolecule';
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
        $sa->answers = array(
               1 => new question_answer(1, $answer, 1.0,
                       'Think about the reaction arrows to help you with atom mapping.', 1)
        );
        $sa->qtype = question_bank::get_qtype('reacsimilarity');
        return $sa;
    }

    public function make_reacsimilarity_question_s1aminoethanol() {
        question_bank::load_question_definition_classes('reacsimilarity');
        $sa = new qtype_reacsimilarity_question();
        test_question_maker::initialise_a_question($sa);
        $sa->name = 'reacsimilarity question';
        $sa->questiontext = 'Draw the lewis structure of a molecule of ethanol.';
        $sa->stereobool = 1;
        $sa->threshold = 0;
        $sa->alpha = 1;
        $sa->scaffold = '';
        $sa->generalfeedback = 'Generalfeedback: ethanol should not be confused with methanol.';
        $answer = '{"json":"{\"m\":[{\"a\":[{\"x\":221.75,\"y\":156,\"i\":\"a0\",\"l\":\"N\"},{\"x\":239.07050807568876,\"y\":146,';
        $answer .= '\"i\":\"a1\"},{\"x\":256.3910161513775,\"y\":156,\"i\":\"a2\"},{\"x\":239.07050807568876,\"y\":126,\"i\":\"a3';
        $answer .= '\",\"l\":\"O\"},{\"x\":259.07050807568874,\"y\":146,\"i\":\"a4\",\"l\":\"H\"}],\"b\":[{\"b\":0,\"e\":1,\"i\":';
        $answer .= '\"b0\"},{\"b\":1,\"e\":2,\"i\":\"b1\",\"s\":\"protruding\"},{\"b\":1,\"e\":3,\"i\":\"b2\"},{\"b\":1,\"e\":4,';
        $answer .= '\"i\":\"b3\",\"s\":\"recessed\"}]}]}","mol_file":"Molecule from ChemDoodle Web Components\n\nhttp://www.icheml';
        $answer .= 'abs.com\n  5  4  0  0  0  0            999 V2000\n   -0.9330   -0.7500    0.0000 N   0  0  0  0  0  0';
        $answer .= '\n   -0.0670   -0.2500    0.0000 C   0  0  0  0  0  0\n    0.7990   -0.7500    0.0000 C   0  0  0  0  0  0';
        $answer .= '\n   -0.0670    0.7500    0.0000 O   0  0  0  0  0  0\n    0.9330   -0.2500    0.0000 H   0  0  0  0  0  0';
        $answer .= '\n  1  2  1  0  0  0  0\n  2  3  1  1  0  0  0\n  2  4  1  0  0  0  0\n  2  5  1  6  0  0  0\nM  END"}';
        $sa->answers = array(
                1 => new question_answer(1, $answer, 1.0, 'Watchout for the stereochemistry !',
                        1)
        );
        $sa->qtype = question_bank::get_qtype('reacsimilarity');
        return $sa;
    }

    public function get_reacsimilarity_question_data_dielsalder() {
        $qdata = new stdClass();
        test_question_maker::initialise_question_data($qdata);

        $qdata->qtype = 'reacsimilarity';
        $qdata->name = 'reacsimilarity question';
        $qdata->questiontext = 'Draw the reaction between éthène and but-1,3-diène.';
        $qdata->generalfeedback = 'Generalfeedback: Be sure to add the atom mapping!';

        $qdata->options = new stdClass();
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
        $answer .= '  0  4  0  0\\n  1  2  2  0  0  0  0\\n  1  3  1  0  0  0  0\\n  3  4  2  0  0  0  0\\nM  END\\n$MOL\\nMolecule';
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
        $qdata->options->answers = array(
                1 => new question_answer(1, $answer, 1.0,
                        'Think about the reaction arrows to help you with atom mapping.', 1)
        );
        return $qdata;
    }

    public function get_reacsimilarity_question_form_data_dielsalder() {
        $form = new stdClass();

        $form->name = 'reacsimilarity question';
        $form->questiontext = array('text' => 'Draw the reaction between éthène and but-1,3-diène.', 'format' => FORMAT_HTML);
        $form->defaultmark = 1.0;
        $form->generalfeedback = array('text' => 'Be sure to add the atom mapping!', 'format' => FORMAT_HTML);
        $form->stereobool = 0;
        $form->threshold = 0;
        $form->alpha = 1;
        $form->scaffold = '';
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
        $answer .= '  0  4  0  0\\n  1  2  2  0  0  0  0\\n  1  3  1  0  0  0  0\\n  3  4  2  0  0  0  0\\nM  END\\n$MOL\\nMolecule';
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
        $form->answer = array($answer);
        $form->fraction = array('1.0');
        $form->feedback = array(array('text' => 'Think about the reaction arrows to help you with atom mapping.', 'format' => FORMAT_HTML));

        return $form;
    }
}
