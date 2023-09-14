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
 * Serve question type files for the Reacsimilarity question type
 *
 * @package qtype
 * @subpackage  reacsimilarity
 * @copyright  2023 unistra  {@link http://unistra.fr}
 * @author Louis Plyer <louis.plyer@unistra.fr>, Céline Pervès <cperves@unistra.fr>, Alexandre Varnek <varnek@unistra.fr>,
 * Gilles Marcou <g.marcou@unistra.fr>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */


defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/question/type/reacsimilarity/classes/ReacsimilarityException.php');
/**
 * Checks file access for reacsimilarity questions.
 * @package  qtype_Reacsimilarity
 * @category files
 * @param stdClass $course course object
 * @param stdClass $cm course module object
 * @param stdClass $context context object
 * @param string $filearea file area
 * @param array $args extra arguments
 * @param bool $forcedownload whether or not force download
 * @param array $options additional options affecting the file serving
 * @return bool
 */
function qtype_reacsimilarity_pluginfile($course, $cm, $context, $filearea, $args, $forcedownload, array $options=array()) {
    global $DB, $CFG;
    require_once($CFG->libdir . '/questionlib.php');
    question_pluginfile($course, $context, 'qtype_reacsimilarity', $filearea, $args, $forcedownload, $options);
}

function qtype_reacsimilarity_test_api_server() {
    $isidaurl = get_config('qtype_reacsimilarity', 'isidaurl');
    $curl = new curl();
    $response = $curl->post($isidaurl . "/time");
    if ($curl->get_info()['http_code'] == 200) {
        return $response;
    } else {
        throw new ReacsimilarityException($curl);
    }
}
