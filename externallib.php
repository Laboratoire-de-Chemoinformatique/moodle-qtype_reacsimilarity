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
 * External Web Service for the reacsimilarity question type
 *
 * @package qtype
 * @subpackage  Reacsimilarity
 * @copyright  2023 unistra  {@link http://unistra.fr}
 * @author Louis Plyer <louis.plyer@unistra.fr>, Céline Pervès <cperves@unistra.fr>, Alexandre Varnek <varnek@unistra.fr>,
 * Gilles Marcou <g.marcou@unistra.fr>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once("$CFG->libdir/externallib.php");

class qtype_reacsimilarity_external extends external_api {
    public static function modify_rxnfile_parameters() {
        return new external_function_parameters(
                array(
                    'molfile' => new external_value(PARAM_TEXT, 'molfile', VALUE_REQUIRED),
                    'json_data' => new external_value(PARAM_TEXT,
                        'json_data containing info about the lone pairs and radicals and atom mapping',
                        VALUE_REQUIRED),
                    'sesskey' => new external_value(PARAM_ALPHANUMEXT, 'sesskey', VALUE_REQUIRED),
                )
        );
    }
    // From 2nd comment https://www.php.net/manual/fr/function.array-column.php .
    private static function reacsimilarity_array_column_ext($array, $columnkey, $indexkey = null): array {
        $result = array();
        foreach ($array as $subarray => $value) {
            if (array_key_exists($columnkey, $value)) {
                $val = $array[$subarray][$columnkey];
            } else if ($columnkey === null) {
                $val = $value;
            } else {
                continue;
            }

            if ($indexkey === null) {
                $result[] = $val;
            } else if ($indexkey == -1 || array_key_exists($indexkey, $value)) {
                $result[($indexkey == -1) ? $subarray : $array[$subarray][$indexkey]] = $val;
            }
        }
        return $result;
    }
    private static function reacsimilarity_modify_molfile($amolfile, $decoded, $molid) {

        // We look for the number of lone pairs and radicals in the initial json.
        $atominf = $decoded['m'][$molid]['a'];
        $lonepairs = self::reacsimilarity_array_column_ext($atominf, 'p', -1);
        $radicals = self::reacsimilarity_array_column_ext($atominf, 'r', -1);

        if (!$lonepairs & !$radicals) {
            $finalmol = implode("\n", $amolfile);
        } else {

            // Make a list of lines.
            // $lines = explode("\n", $molfile);
            // Get the count line and take the numbers from it.
            $countline = $amolfile[4];
            preg_match_all('!\d+!', $countline, $numbers);
            $nbatom = $numbers[0][0];
            $nbbonds = $numbers[0][1];
            // Get the different parts of the molfile.
            $header = array_slice($amolfile, 0, 4);
            $atomblock = array_slice($amolfile, 5, $nbatom);
            $bondblock = array_slice($amolfile, (5 + $nbatom), $nbbonds);
            $endfile = array_slice($amolfile, -1, 1);

            if ($lonepairs) {

                // Get the initial number of atoms and bonds.
                $nblonepairs = 0;
                $nbatomfinal = $nbatom;
                $nbbondsfinal = $nbbonds;
                $bondvanilla = '  1  0  0  0  0';

                foreach ($lonepairs as $key => $value) {
                    $numatom = $key + 1;
                    // Creating a line for the atom block.
                    $atomline = $atomblock[$key];
                    $atomline[31] = 'L';
                    $atomline[32] = 'P';
                    for ($i = 1; $i <= $value; $i++) {
                        $atomblock[$nbatomfinal] = $atomline;
                        $nbatomfinal++;

                        // Creating a line for the bond block.
                        $bondline = '';
                        $bondline .= sprintf("% 3d", $numatom);
                        $bondline .= sprintf("% 3d", $nbatomfinal);
                        $bondline .= $bondvanilla;
                        $bondblock[$nbbondsfinal] = $bondline;
                        $nblonepairs++;
                        $nbbondsfinal++;

                    }
                }
                // Change number of atoms and bonds to new one.
                $countlineedited = '';
                $countlineedited .= sprintf("% 3d", $nbatomfinal);
                $countlineedited .= sprintf("% 3d", $nbbondsfinal);
                $countlineedited .= substr($countline, 6);
                $countlinearray = explode('\n', $countlineedited);
            }

            if ($radicals) {
                $nbradicals = 0;
                $radline = 'M  RAD';
                $endradline = '';
                foreach ($radicals as $key => $value) {
                    $numatom = $key + 1;
                    $endradline .= sprintf("% 4d", $numatom);
                    $endradline .= sprintf("% 4d", 2);
                    $nbradicals ++;
                }
                $radline .= sprintf("% 3d", $nbradicals);
                $radline .= $endradline;
                $radlinearray = explode('\n', $radline);
                if (!$lonepairs) {
                    $countlinearray = explode('\n', $countline);
                }
            }

            // Rewrite the molfile.
            $finalmol = array();
            if ($radicals) {
                $finalmol = implode("\n", array_merge($finalmol, $header, $countlinearray,
                        $atomblock, $bondblock, $radlinearray, $endfile));
            } else {
                $finalmol = implode("\n", array_merge($finalmol, $header, $countlinearray, $atomblock, $bondblock, $endfile));
            }
        }
        return $finalmol;
    }

    public static function modify_rxnfile($molfile, $jsondata, $sesskey) {
        self::validate_parameters(self::modify_rxnfile_parameters(),
                    array('molfile' => $molfile, 'json_data' => $jsondata, 'sesskey' => $sesskey));

        // We look for the number of lone pairs and radicals in the initial json.
        $lines = explode("\n", $molfile);
        $decoded = json_decode($jsondata ?? '', true);

        // Redefine str_contains for php 7.4
        if (!function_exists('str_contains')) {
            function str_contains($haystack, $needle) {
                return $needle !== '' && mb_strpos($haystack, $needle) !== false;
            }
        }
        if (str_contains($lines[0], '$RXN')) {
            // If reaction, then we look for the number of molecules in it.
            $headerrxn = array_slice($lines, 0, 5);
            $countlinereac = $lines[4];
            preg_match_all('!\d+!', $countlinereac, $anumbermol);
            $numbermols = $anumbermol[0][0] + $anumbermol[0][1];

            // Modify each molecule to add Lone pairs and radicals.
            $endrxn = '';
            $j = 5;
            for ($i = 0; $i < $numbermols; $i++) {
                $countline = $lines[$j + 4];
                preg_match_all('!\d+!', $countline, $numbers);
                $molarray = array();
                $l = 0;

                while (!(str_contains($lines[$j], 'M  END'))) {
                    if ($l == 0) {
                        $l++;
                    } else {
                        $j++;
                    }
                    $molarray[] = $lines[$j];
                }
                $endrxn .= self::reacsimilarity_modify_molfile($molarray, $decoded, $i);
                $endrxn .= "\n";
                $j++;
            }
            $finalrxn = '';
            $finalrxn .= implode("\n", $headerrxn);
            $finalrxn .= "\n";
            $finalrxn .= $endrxn;
        } else {
            $finalrxn = self::reacsimilarity_modify_molfile($lines, $decoded, 0);
        }
        // A json is sent back including the json and the new molfile.
        return ['json' => $jsondata, 'mol_file' => $finalrxn];

    }
    public static function modify_rxnfile_returns() {
        return new external_single_structure(
                array(
                        'json'   => new external_value(PARAM_RAW, 'Backup Status'),
                        'mol_file' => new external_value(PARAM_RAW, 'Backup progress'),
                ), 'Backup completion status'
        );
    }
}
