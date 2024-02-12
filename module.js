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

        function style () {
        window[name].styles.atoms_useJMOLColors = true;
        window[name].styles.bonds_clearOverlaps_2D = true;
        ChemDoodle.ELEMENT['H'].jmolColor = 'black';
        ChemDoodle.ELEMENT['S'].jmolColor = '#B9A130';
        }

        if (readonly){
            window[name] = new ChemDoodle.ViewerCanvas(toreplaceid, 400, 300);
            window[name].emptyMessage = 'No data loaded';
            style();
        }

        else{
            window[name] = new ChemDoodle.SketcherCanvas(toreplaceid, 550, 300, {useServices:false, oneMolecule:false});
            style();

            let half_bond = document.getElementById(toreplaceid + '_button_bond_half_label');
            half_bond.remove(); // Removing the "halfbond".

            let initmol = ChemDoodle.readJSON("{\"m\":[{\"a\":[]}]}");
            let meth = ChemDoodle.readJSON("{\"m\":[{\"a\":[{\"x\":236.75,\"y\":134,\"i\":\"a0\"}]}]}");

            window[name].loadMolecule(initmol['molecules'][0]);
            window[name].click = initcanvas;

            function initcanvas(){
                let json_data = JSON.stringify(new ChemDoodle.io.JSONInterpreter().contentTo(window[name].molecules, window[name].shapes));
                if (json_data === '{\"m\":[{\"a\":[]}]}') {
                    window[name].loadMolecule(meth['molecules'][0]);
                }
            }

            const moodleform = document.getElementById("responseform");
            moodleform.addEventListener("submit", function (event) {
                let json_data = JSON.stringify(new ChemDoodle.io.JSONInterpreter().contentTo(window[name].molecules, window[name].shapes));
                // We check if there is an answer, if not, we send an empty json, which can be loaded into the canvas.
                if (json_data !== '{\"m\":[{\"a\":[]}]}') {
                    let reac = ChemDoodle.writeRXN(window[name].getMolecules(), window[name].shapes);
                    //if (reac == "") {reac = ChemDoodle.writeMOL(window[name].getMolecule())};
                    generateOutputChemDoodle(reac, json_data, location_isida);
                }
                return true;
            });
        }

        let lastmol = document.getElementById(inputname).value;
        window[name].doChecks = true;
        if(lastmol.length > 0) {
            window[name].doChecks = true;
            let cmcmol = ChemDoodle.readJSON(JSON.parse(lastmol).json);
            window[name].loadContent(cmcmol['molecules'], cmcmol['shapes']);
            window[name].repaint();
        }
        else if (scaffold !== '' && scaffold) {
            window[name].doChecks = true;
            let cmcmol = JSON.parse(scaffold);
            let data = ChemDoodle.readJSON(cmcmol.json);
            if (data.shapes.length !== 0) {
                window[name].loadContent(data['molecules'], data['shapes']);
            } else {
                window[name].loadMolecule(data['molecules'][0]);
            }
        }
        else { // Case ketcher not instantiated, we use the empty "mol".
            let initmol = ChemDoodle.readJSON("{\"m\":[{\"a\":[]}]}");
            window[name].loadMolecule(initmol['molecules'][0]);
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

            window[id] = new ChemDoodle.ViewerCanvas(id, 250, 125);
            window[id].emptyMessage = 'No data loaded';
            window[id].styles.atoms_useJMOLColors = true;
            window[id].styles.bonds_clearOverlaps_2D = true;

            // Locate the area of the answer.
            let parent = $(sketcherlist[i].parentNode.parentNode.parentNode);
            let box = get_source_preview(parent);

            // We initiate the viewer canvas with a value.
            if (box.val()) {
                let val = JSON.parse(box.val());
                let data = ChemDoodle.readJSON(val.json);
                window[id].clear();
                window[id].doChecks = true;
                window[id].loadContent(data['molecules'], data['shapes']);
            } else {
                window[id].clear();
            }
            let boxval = box.val();

            // Loop each 5s and update the preview if the answer was modified.
            setInterval(function () {
                    if (boxval !== box.val() ) {
                        boxval = box.val();
                        if (boxval === "") {
                            window[id].clear();
                        } else {
                            let val = JSON.parse(box.val());
                            let data = ChemDoodle.readJSON(val.json);
                            window[id].clear();
                            window[id].doChecks = true;
                            window[id].loadContent(data['molecules'], data['shapes']);
                            window[id].repaint();
                        }
                    }
            }, 5000)
        }
    },
    insert_good_answer : function (Y, toreplaceid, name, correct_data) {
        window[name] = new ChemDoodle.ViewerCanvas(toreplaceid, 400, 300);

        window[name].emptyMessage = 'No data loaded';
        window[name].styles.atoms_useJMOLColors = true;
        window[name].styles.bonds_clearOverlaps_2D = true;

        let correct_mol = correct_data;

        if (correct_mol.length > 0) {
            let cmcmol = ChemDoodle.readJSON(correct_mol);
            window[name].doChecks = true;
            window[name].loadContent(cmcmol['molecules'], cmcmol['shapes']);
        }
        },
    insert_scaffold : function (Y, dirroot) {
        let nameS = "sketcherScaffold";
        let dirr = JSON.parse(dirroot).dirrMoodle;

        window[nameS] = new ChemDoodle.SketcherCanvas(nameS, 550, 300, {useServices:false, oneMolecule:false});

        window[nameS].emptyMessage = 'No data loaded';
        window[nameS].styles.atoms_useJMOLColors = true;
        window[nameS].styles.bonds_clearOverlaps_2D = true;
        window[nameS].repaint();

        let scaffoldarea = $("input[name=scaffold]");
        let scaffoldData = scaffoldarea.val();

        if (scaffoldData.length > 0) {
            window[nameS].doChecks = true;
            let cmcmol = JSON.parse(scaffoldData);
            let data = ChemDoodle.readJSON(cmcmol.json);

            if (data.shapes.length !== 0) {
                window[nameS].loadContent(data['molecules'], data['shapes']);
            } else {
                window[nameS].loadMolecule(data['molecules'][0]);
            }
        }

        const moodleform = $("form[data-qtype=reacsimilarity]");
        moodleform[0].addEventListener("submit", function (event) {
            var json_data = JSON.stringify(new ChemDoodle.io.JSONInterpreter().contentTo(window[nameS].molecules));
            // We check if there is an answer, if not, we send an empty json, which can be loaded into the canvas.
            if (json_data !== '{}') {
                if (window[nameS].shapes.length !== 0) {
                    var json_data = JSON.stringify(new ChemDoodle.io.JSONInterpreter().contentTo(window[nameS].molecules, window[nameS].shapes));
                    var molscaf = ChemDoodle.writeRXN(window[nameS].getMolecules(), window[nameS].shapes);
                } else {
                    var molscaf = ChemDoodle.writeMOL(window[nameS].getMolecule());
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
