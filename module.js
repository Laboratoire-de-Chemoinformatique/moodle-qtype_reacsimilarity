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
 * Module script to render the Chemdoodle sketcher for the Reacsimilarity question type
 *
 * @package qtype
 * @subpackage  reacsimilarity
 * @copyright  2023 unistra  {@link http://unistra.fr}
 * @author Louis Plyer <louis.plyer@unistra.fr>, Céline Pervès <cperves@unistra.fr>, Alexandre Varnek <varnek@unistra.fr>,
 * Gilles Marcou <g.marcou@unistra.fr>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

M.qtype_reacsimilarity={
    // Insert for the renderer
    insert_cwc : function(Y, toreplaceid, name, inputname, readonly, dirroot, scaffold){
        let dirr = JSON.parse(dirroot).dirrMoodle;
        let location_isida = '#' + inputname;

        let sketcher = document.querySelector('#'+name);
        function style (sketcher) {
            sketcher.styles.atoms_useJMOLColors = true;
            sketcher.styles.bonds_clearOverlaps_2D = true;
            ChemDoodle.ELEMENT['H'].jmolColor = 'black';
            ChemDoodle.ELEMENT['S'].jmolColor = '#B9A130';
        }

        if (readonly){
            sketcher = new ChemDoodle.ViewerCanvas(toreplaceid, 400, 300);
            sketcher.emptyMessage = 'No data loaded';
            style(sketcher);
        }

        else{
            sketcher = new ChemDoodle.SketcherCanvas(toreplaceid, 550, 300, {useServices:false, oneMolecule:false});
            style(sketcher);

            let half_bond = document.getElementById(toreplaceid + '_button_bond_half_label');
            half_bond.remove(); // Removing the "halfbond".

            let initmol = ChemDoodle.readJSON("{\"m\":[{\"a\":[]}]}");
            sketcher.doChecks = true;
            sketcher.loadMolecule(initmol['molecules'][0]);

            const moodleform = document.getElementById("responseform");
            moodleform.addEventListener("submit", function (event) {
                let json_data = JSON.stringify(new ChemDoodle.io.JSONInterpreter().contentTo(sketcher.molecules, sketcher.shapes));
                // We check if there is an answer, if not, we send an empty json, which can be loaded into the canvas.
                if (json_data !== '{\"m\":[{\"a\":[]}]}') {
                    let reac = ChemDoodle.writeRXN(sketcher.getMolecules(), sketcher.shapes);
                    //if (reac == "") {reac = ChemDoodle.writeMOL(sketcher.getMolecule())};
                    generateOutputChemDoodle(reac, json_data, location_isida);
                }
                return true;
            });
        }

        let lastmol = document.getElementById(inputname).value;
        sketcher.doChecks = true;
        if(lastmol.length > 0) {
            sketcher.doChecks = true;
            let cmcmol = ChemDoodle.readJSON(JSON.parse(lastmol).json);
            sketcher.loadContent(cmcmol['molecules'], cmcmol['shapes']);
            sketcher.repaint();
        }
        else if (scaffold !== '' && scaffold) {
            sketcher.doChecks = true;
            let cmcmol = JSON.parse(scaffold);
            let data = ChemDoodle.readJSON(cmcmol.json);
            if (data.shapes.length !== 0) {
                sketcher.loadContent(data['molecules'], data['shapes']);
            } else {
                sketcher.loadMolecule(data['molecules'][0]);
            }
        }
        else { // Case ketcher not instantiated, we use the empty "mol".
            let initmol = ChemDoodle.readJSON("{\"m\":[{\"a\":[]}]}");
            sketcher.loadMolecule(initmol['molecules'][0]);
        }
        $(function(){
            if($('.ChemDoodleWebComponent').length){
                $('#techinfo_inner :nth-child(6n)').css('white-space','pre'); // For the preview.
            }});
    },
    // Insert in the form
    insert_form : function (Y, dirroot) {
            ChemDoodle.ELEMENT['H'].jmolColor = 'black';
            ChemDoodle.ELEMENT['S'].jmolColor = '#B9A130';
            let sketcher = new ChemDoodle.SketcherCanvas('sketcher', 550, 300, {useServices:false, oneMolecule:false});
            sketcher.styles.atoms_useJMOLColors = true;
            sketcher.styles.bonds_clearOverlaps_2D = true;

            let half_bond = document.getElementById('sketcher_button_bond_half_label');
            half_bond.remove(); // removing the "halfbond"

            $().ready(function () {$('[classo=load-molfile]').first().trigger("click")});

            function get_source (parent) {
                    let buttonname = parent.attr('name');
                    let textfieldid = 'id_answer_' + buttonname.substr(buttonname.length - 2, 1);
                    let source = $(`#${textfieldid}`);
                    return source;
                }

            $('[classo=set-molfile]').click(function () {
                let dirr = JSON.parse(dirroot).dirrMoodle;
                let parent = $(this);
                if (sketcher.shapes.length !== 0) {
                    let molfile = ChemDoodle.writeRXN(sketcher.getMolecules(), sketcher.shapes);
                    let json_data = JSON.stringify(new ChemDoodle.io.JSONInterpreter().contentTo(sketcher.molecules, sketcher.shapes));
                    console.log(json_data);
                    console.log(ChemDoodle.readJSON(json_data));
                    let box = get_source(parent);
                    generateOutputChemDoodle(molfile, json_data, box);
                    if (box.val().length >= 1) {$('[classo = mol_empty]').hide();$('[classo = needreac]').hide();}
                } else {
                    $('[classo = needreac]').toggle();
                }
            });

            $('[classo=load-molfile]').click(function () {
                let $parent = $(this);
                let box = get_source($parent);
                if (box.val()){
                    let val = JSON.parse(box.val());
                    let data = ChemDoodle.readJSON(val.json);
                    // avant de load, clear le canvas
                    sketcher.doChecks = true;
                    sketcher.loadContent(data['molecules'], data['shapes']);
                    $('[classo = mol_empty]').hide();
                }
            });
            $('[classo=clear_answer]').click(function () {
                let $parent = $(this);
                let box = get_source($parent);
                if (box.val()){
                    box.val("");
                }
            });
            $(function(){
                    $('[id^="fitem_id_feedback_"]:not(:first)').css('display','none'); // Because we only need the first specific feedback
                });
        },
    insert_form_preview: function(Y) {

        function get_source_preview (parent) {
            let groupname = parent.attr('data-groupname');
            let textfieldid = 'id_answer_' + groupname.substr(groupname.length - 2, 1);
            let source = $(`#${textfieldid}`);
            return source;
        }

        // NodeList created.
        let sketcherlist = document.getElementsByName('test_preview');
        let listlength = sketcherlist.length;

        // Loop and initialize Ketchers, add 'eventlisteners'.

        for (let i = 0; i < listlength; ++i) {

            // Change the id of the canvas initiated by Moodle.
            let id = 'sketcher_preview';
            id += i;
            sketcherlist[i].id = id;

            let sketcher = document.querySelector('#'+id);
            sketcher = new ChemDoodle.ViewerCanvas(id, 250, 125);
            sketcher.emptyMessage = 'No data loaded';
            sketcher.styles.atoms_useJMOLColors = true;
            sketcher.styles.bonds_clearOverlaps_2D = true;

            // Locate the area of the answer.
            let parent = $(sketcherlist[i].parentNode.parentNode.parentNode);
            let box = get_source_preview(parent);

            // We initiate the viewer canvas with a value.
            if (box.val()) {
                let val = JSON.parse(box.val());
                let data = ChemDoodle.readJSON(val.json);
                sketcher.clear();
                sketcher.doChecks = true;
                sketcher.loadContent(data['molecules'], data['shapes']);
            } else {
                sketcher.clear();
            }
            let boxval = box.val();

            // Loop each 5s and update the preview if the answer was modified.
            setInterval(function () {
                    if (boxval !== box.val() ) {
                        boxval = box.val();
                        if (boxval === "") {
                            sketcher.clear();
                        } else {
                            let val = JSON.parse(box.val());
                            let data = ChemDoodle.readJSON(val.json);
                            sketcher.clear();
                            sketcher.doChecks = true;
                            sketcher.loadContent(data['molecules'], data['shapes']);
                            sketcher.repaint();
                        }
                    }
            }, 5000)
        }
    },
    insert_good_answer : function (Y, toreplaceid, name, correct_data) {
        let sketcher = document.querySelector('#'+name);
        sketcher = new ChemDoodle.ViewerCanvas(toreplaceid, 400, 300);

        sketcher.emptyMessage = 'No data loaded';
        sketcher.styles.atoms_useJMOLColors = true;
        sketcher.styles.bonds_clearOverlaps_2D = true;

        let correct_mol = correct_data;

        if (correct_mol.length > 0) {
            let cmcmol = ChemDoodle.readJSON(correct_mol);
            sketcher.doChecks = true;
            sketcher.loadContent(cmcmol['molecules'], cmcmol['shapes']);
        }
        },
    insert_scaffold : function (Y, dirroot) {
        let nameS = "sketcherScaffold";
        let dirr = JSON.parse(dirroot).dirrMoodle;
        let sketcherS = document.querySelector('#'+nameS);
        sketcherS = new ChemDoodle.SketcherCanvas(nameS, 550, 300, {useServices:false, oneMolecule:false});

        sketcherS.emptyMessage = 'No data loaded';
        sketcherS.styles.atoms_useJMOLColors = true;
        sketcherS.styles.bonds_clearOverlaps_2D = true;
        sketcherS.repaint();

        let scaffoldarea = $("input[name=scaffold]");
        let scaffoldData = scaffoldarea.val();

        if (scaffoldData.length > 0) {
            sketcherS.doChecks = true;
            let cmcmol = JSON.parse(scaffoldData);
            let data = ChemDoodle.readJSON(cmcmol.json);

            if (data.shapes.length !== 0) {
                sketcherS.loadContent(data['molecules'], data['shapes']);
            } else {
                sketcherS.loadMolecule(data['molecules'][0]);
            }
        }

        const moodleform = $("form[data-qtype=reacsimilarity]");
        moodleform[0].addEventListener("submit", function (event) {
            var json_data = JSON.stringify(new ChemDoodle.io.JSONInterpreter().contentTo(sketcherS.molecules));
            // We check if there is an answer, if not, we send an empty json, which can be loaded into the canvas.
            if (json_data !== '{}') {
                if (sketcherS.shapes.length !== 0) {
                    var json_data = JSON.stringify(new ChemDoodle.io.JSONInterpreter().contentTo(sketcherS.molecules, sketcherS.shapes));
                    var molscaf = ChemDoodle.writeRXN(sketcherS.getMolecules(), sketcherS.shapes);
                } else {
                    var molscaf = ChemDoodle.writeMOL(sketcherS.getMolecule());
                }
                if (molscaf !== 'Molecule from ChemDoodle Web Components\n\nhttp://www.ichemlabs.com\n  1  0  0  0  0  0            999 V2000\n    0.0000    0.0000    0.0000 C   0  0  0  0  0  0\nM  END') {
                    generateOutputChemDoodle(molscaf, json_data, scaffoldarea);
                }
            } else {
                scaffoldarea.val('');
            }
            return true;
        });
    },
    };

function generateOutputChemDoodle (molfile, json_data, location_isida){
    require(['qtype_reacsimilarity/api_helper'], function (app) {
        app.callApi(molfile, json_data).then(async data => {
            console.log(JSON.stringify(data));
            console.log(data);
            $(location_isida).val(JSON.stringify(data));
        });
    });
}
