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

$messageproviders = array(
    // Notify that the reacsimilarity correction server is down.
        'reacsimilarity_down' => array(
                'defaults' => array(
                        'popup' => MESSAGE_PERMITTED + MESSAGE_DEFAULT_ENABLED,
                        'email' => MESSAGE_PERMITTED + MESSAGE_DEFAULT_ENABLED,
                ),
        ),
        'reacsimilarity_security' => array(
                'defaults' => array(
                        'popup' => MESSAGE_PERMITTED + MESSAGE_DEFAULT_ENABLED,
                        'email' => MESSAGE_PERMITTED + MESSAGE_DEFAULT_ENABLED,
                ),
        ),
);
