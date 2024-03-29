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
 * Testing the connection for the Reacsimilarity question type
 *
 * @package qtype
 * @subpackage  reacsimilarity
 * @copyright  2023 unistra  {@link http://unistra.fr}
 * @author Louis Plyer <louis.plyer@unistra.fr>, Céline Pervès <cperves@unistra.fr>, Alexandre Varnek <varnek@unistra.fr>,
 * Gilles Marcou <g.marcou@unistra.fr>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once('../../../config.php');
require_once($CFG->libdir.'/adminlib.php');


require_login();
require_capability('moodle/site:config', context_system::instance());
admin_externalpage_setup('reacsimilarityconnectiontest');
$PAGE->set_context(context_system::instance());
$PAGE->set_url(new moodle_url('/question/type/reacsimilarity/test_reacsimilarity_connection.php'));
$PAGE->set_title(get_string('testconnection', 'qtype_reacsimilarity'));
$PAGE->set_heading(get_string('testconnection', 'qtype_reacsimilarity'));
$PAGE->set_pagelayout('admin');


echo $OUTPUT->header();
echo $OUTPUT->container_start('center');

$test = new qtype_reacsimilarity_test_connection('qtype_reacsimilarity/testconnectionout',
        get_string('connectiontestresult', 'qtype_reacsimilarity'), '');
echo $test->output_html('', '');

echo $OUTPUT->container_end();
echo $OUTPUT->footer();
