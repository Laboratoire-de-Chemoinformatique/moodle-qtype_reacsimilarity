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
 * Plugin administration for the Reacsimilarity question type
 *
 * @package qtype
 * @subpackage  reacsimilarity
 * @copyright  2023 unistra  {@link http://unistra.fr}
 * @author Louis Plyer <louis.plyer@unistra.fr>, Céline Pervès <cperves@unistra.fr>, Alexandre Varnek <varnek@unistra.fr>,
 * Gilles Marcou <g.marcou@unistra.fr>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();
global $DB;

// Redefine the reacsimilarity admin menu entry to be expandable.
$plugin = core_plugin_manager::instance()->get_plugin_info('qtype_reacsimilarity');
$qtypereacsimilarityfolder = new admin_category('qtypereacsimilarityfolder',
        new lang_string('pluginname', 'qtype_reacsimilarity'), $plugin->is_enabled() === false);

// Add the Settings admin menu entry.
$ADMIN->add('qtypesettings', $qtypereacsimilarityfolder);
$settings->visiblename = new lang_string('settings', 'qtype_reacsimilarity');

// Add the Libraries admin menu entry.
$ADMIN->add('qtypereacsimilarityfolder', $settings);

$ADMIN->add('qtypereacsimilarityfolder', new admin_externalpage('reacsimilarityconnectiontest',
        new lang_string('testconnection', 'qtype_reacsimilarity'),
        new moodle_url('/question/type/reacsimilarity/test_connection.php')));

if ($hassiteconfig) {
    $settings->add(
            new admin_setting_configtext('qtype_reacsimilarity/isidaurl',
                    get_string('isidaurl', 'qtype_reacsimilarity'),
                    get_string('isidaurl_desc', 'qtype_reacsimilarity'),
                    'localhost:9090')
    );
    $settings->add(
            new admin_setting_configtext('qtype_reacsimilarity/isidaKEY',
                    get_string('isidaKEY', 'qtype_reacsimilarity'),
                    get_string('isidaKEY_desc', 'qtype_reacsimilarity'),
                    'PutYourOwnKeyHere')
    );
    $settings->add(
            new qtype_reacsimilarity_test_connection('qtype_reacsimilarity/testconnectionout', '', '')
    );
}

$settings = null; // Prevent Moodle from adding settings block in standard location.
