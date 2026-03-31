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
 * Folder plugin version information
 *
 * @package qtype
 * @subpackage  reacsimilarity
 * @copyright  2023 unistra  {@link http://unistra.fr}
 * @author Louis Plyer <louis.plyer@unistra.fr>, Céline Pervès <cperves@unistra.fr>, Alexandre Varnek <varnek@unistra.fr>,
 * Gilles Marcou <g.marcou@unistra.fr>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/question/type/reacsimilarity/lib.php');

class qtype_reacsimilarity_test_connection extends  admin_setting_heading {

    /**
     * Returns an HTML string saying if the REST server is up and working or not
     *
     * @param string $data
     * @param string $query
     * @return string Returns an HTML string
     * @throws coding_exception
     */
    public function output_html($data, $query = '') {
        try {
            $isserverup = qtype_reacsimilarity_test_api_server();
        } catch (Exception $e) {
            return html_writer::div(get_string('errorintestwhilegconnection', 'qtype_reacsimilarity').
                    '<br>'.get_string('testerrorcode', 'qtype_reacsimilarity', $e->getCode()).'<br>'.
                    get_string('testerrormessage', 'qtype_reacsimilarity', $e->getMessage()), '', array(
                            'role'     => 'alert',
                            'style' => 'color: #712b29; background-color: #f7dddc; padding: .75rem 1.25rem; margin-bottom: 1rem;',
                    )
            );
        }
        if ($isserverup) {
            return html_writer::div(get_string('connectiontestresult', 'qtype_reacsimilarity').'<br>'.
                    get_string('connection-success', 'qtype_reacsimilarity'), '', array(
                            'role'     => 'alert',
                            'style' => 'color: #306030; background-color: #def1de; padding: .75rem 1.25rem; margin-bottom: 1rem;',
                    )
            );
        }
    }
}
