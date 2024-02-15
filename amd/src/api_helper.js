// This file is part of Moodle - https://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle. If not, see <https://www.gnu.org/licenses/>.


/**
 * Utils script to call the External Web Service for the Reacsimilarity question type
 * @subpackage reacsimilarity
 * @copyright 2024 unistra {@link http://unistra.fr}
 * @author Louis Plyer <louis.plyer@unistra.fr>, Céline Pervès <cperves@unistra.fr>, Alexandre Varnek <varnek@unistra.fr>,
 * Gilles Marcou <g.marcou@unistra.fr>
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

import Ajax from 'core/ajax';
import notification from "core/notification";

export const generateOutputChemDoodle = (molfile, json_data, component) => {
    Ajax.call([{
      methodname: 'qtype_reacsimilarity_modify_rxnfile',
      args: {
        molfile: molfile,
        json_data: json_data,
        sesskey: M.cfg.sesskey,
      },
      done : response =>  {
        window.console.log(JSON.stringify(response.json));
        window.console.log(response.molfile);
        component.value = JSON.stringify(response);
      },
      fail: notification.exception
    }], false);
};

export const retrieveScafold = (questionid) => {
  let scaffold='';
  Ajax.call([{
    methodname: 'qtype_reacsimilarity_retrieve_scaffold',
    args: {
      questionid: questionid,
    },
    done : response =>  {
      scaffold = response.scaffold;
    },
    fail: notification.exception
  }], false);
  return scaffold;
};

export const retrieveCorrectDatas = (questionid) => {
  let correctDatas='';
  Ajax.call([{
    methodname: 'qtype_reacsimilarity_retrieve_correctdatas',
    args: {
      questionid: questionid,
    },
    done : response =>  {
      correctDatas = response.correctDatas;
    },
    fail: notification.exception
  }], false);
  return correctDatas;
};