import {generateOutputChemDoodle, retrieveCorrectDatas, retrieveScafold} from "./api_helper";

export const insert_cwc = (toreplaceid, name, inputname, readonly, questionid) => {
  // Retrieve scaffold
  const scaffold = retrieveScafold(questionid);
  let ChemDoodle = window.ChemDoodleVar;
  let location_isida = document.querySelector('#' + inputname);
  let sketcher = document.querySelector('#'+name);

  if (readonly){
    sketcher = new ChemDoodle.ViewerCanvas(toreplaceid, 400, 300);
    sketcher.emptyMessage = 'No data loaded';
    style(sketcher);
  }
  else {
    sketcher = new ChemDoodle.SketcherCanvas(toreplaceid, 550, 300, {useServices:false, oneMolecule:false});
    style(sketcher);

    let half_bond = document.getElementById(toreplaceid + '_button_bond_half_label');
    half_bond.remove(); // Removing the "halfbond".

    let initmol = ChemDoodle.readJSON("{\"m\":[{\"a\":[]}]}");
    sketcher.doChecks = true;
    sketcher.loadMolecule(initmol['molecules'][0]);

    const moodleform = document.getElementById("responseform");
    moodleform.addEventListener("submit", function () {
      let json_data = JSON.stringify(new ChemDoodle.io.JSONInterpreter().contentTo(sketcher.molecules, sketcher.shapes));
      // We check if there is an answer, if not, we send an empty json, which can be loaded into the canvas.
      if (json_data !== '{\"m\":[{\"a\":[]}]}') {
        let reac = ChemDoodle.writeRXN(sketcher.getMolecules(), sketcher.shapes);
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
};

const style =  (sketcher)  => {
  let ChemDoodle = window.ChemDoodleVar;
  sketcher.styles.atoms_useJMOLColors = true;
  sketcher.styles.bonds_clearOverlaps_2D = true;
  ChemDoodle.ELEMENT['H'].jmolColor = 'black';
  ChemDoodle.ELEMENT['S'].jmolColor = '#B9A130';
};

const get_source =  (parent) => {
  let buttonname = parent.getAttribute('name');
  let textfieldid = 'id_answer_' + buttonname.substr(buttonname.length - 2, 1);
  let source = document.querySelector(`#${textfieldid}`);
  return source;
};

export const insert_form  = () => {
  let ChemDoodle = window.ChemDoodleVar;
  ChemDoodle.ELEMENT['H'].jmolColor = 'black';
  ChemDoodle.ELEMENT['S'].jmolColor = '#B9A130';
  let sketcher = new ChemDoodle.SketcherCanvas('sketcher', 550, 300, {useServices:false, oneMolecule:false});
  sketcher.styles.atoms_useJMOLColors = true;
  sketcher.styles.bonds_clearOverlaps_2D = true;

  let half_bond = document.getElementById('sketcher_button_bond_half_label');
  half_bond.remove(); // removing the "halfbond"

  document.querySelector('[classo=set-molfile]').addEventListener('click', function () {
    let parent = this;
    if (sketcher.shapes.length !== 0) {
      let molfile = ChemDoodle.writeRXN(sketcher.getMolecules(), sketcher.shapes);
      let json_data = JSON.stringify(new ChemDoodle.io.JSONInterpreter().contentTo(sketcher.molecules, sketcher.shapes));
      window.console.log(json_data);
      window.console.log(ChemDoodle.readJSON(json_data));
      let box = get_source(parent);
      generateOutputChemDoodle(molfile, json_data, box);
      if (box.value.length >= 1) {
        document.querySelector('[classo = mol_empty]').style.display = 'none';
        document.querySelector('[classo = needreac]').style.display = 'none';
      }
    } else {

      let neereacElt = document.querySelector('[classo = needreac]');
      if (neereacElt.style.display == 'none') {
        neereacElt.style.display = 'block';
      } else {
        neereacElt.style.display = 'none';
      }
    }
  });

  document.querySelector('[classo=load-molfile]').addEventListener('click', function () {
    let $parent = this;
    let box = get_source($parent);
    if (box.value){
      let val = JSON.parse(box.value);
      let data = ChemDoodle.readJSON(val.json);
      // avant de load, clear le canvas
      sketcher.doChecks = true;
      sketcher.loadContent(data['molecules'], data['shapes']);
      document.querySelector('[classo = mol_empty]').style.display = 'none';
    }
  });
  document.querySelector('[classo=clear_answer]').addEventListener('click', function () {
    let $parent = this;
    let box = get_source($parent);
    if (box.value){
      box.value = "";
    }
  });
};

const get_source_preview = (parent) => {
  let groupname = parent.getAttribute('data-groupname');
  let textfieldid = 'id_answer_' + groupname.substr(groupname.length - 2, 1);
  let source = document.querySelector(`#${textfieldid}`);
  return source;
};

export const insert_form_preview = () => {
  let ChemDoodle = window.ChemDoodleVar;
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
    // TODO better selector
    let parent = sketcherlist[i].parentNode.parentNode.parentNode;
    let box = get_source_preview(parent);
    // We initiate the viewer canvas with a value.
    if (box.value) {
      let val = JSON.parse(box.value);
      let data = ChemDoodle.readJSON(val.json);
      sketcher.clear();
      sketcher.doChecks = true;
      sketcher.loadContent(data['molecules'], data['shapes']);
    } else {
      sketcher.clear();
    }
    let boxval = box.value;

    // Loop each 5s and update the preview if the answer was modified.
    setInterval(function () {
      if (boxval !== box.value ) {
        boxval = box.value;
        if (boxval === "") {
          sketcher.clear();
        } else {
          let val = JSON.parse(box.value);
          let data = ChemDoodle.readJSON(val.json);
          sketcher.clear();
          sketcher.doChecks = true;
          sketcher.loadContent(data['molecules'], data['shapes']);
          sketcher.repaint();
        }
      }
    }, 5000);
  }
};
export const insert_good_answer = (toreplaceid, name, questionid) => {
  let ChemDoodle = window.ChemDoodleVar;
  let sketcher = document.querySelector('#'+name);
  sketcher = new ChemDoodle.ViewerCanvas(toreplaceid, 400, 300);

  sketcher.emptyMessage = 'No data loaded';
  sketcher.styles.atoms_useJMOLColors = true;
  sketcher.styles.bonds_clearOverlaps_2D = true;

  let correct_mol = retrieveCorrectDatas(questionid);

  if (correct_mol.length > 0) {
    let cmcmol = ChemDoodle.readJSON(correct_mol);
    sketcher.doChecks = true;
    sketcher.loadContent(cmcmol['molecules'], cmcmol['shapes']);
  }
};

export const insert_scaffold = () => {
  let ChemDoodle = window.ChemDoodleVar;
  let nameS = "sketcherScaffold";
  let sketcherS = document.querySelector('#'+nameS);
  sketcherS = new ChemDoodle.SketcherCanvas(nameS, 550, 300, {useServices:false, oneMolecule:false});

  sketcherS.emptyMessage = 'No data loaded';
  sketcherS.styles.atoms_useJMOLColors = true;
  sketcherS.styles.bonds_clearOverlaps_2D = true;
  sketcherS.repaint();

  let scaffoldarea = document.querySelector("input[name=scaffold]");
  let scaffoldData = scaffoldarea.value;

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

  const moodleform = document.querySelector("form[data-qtype=reacsimilarity]");
  moodleform.addEventListener("submit", function () {
    var json_data = JSON.stringify(new ChemDoodle.io.JSONInterpreter().contentTo(sketcherS.molecules));
    // We check if there is an answer, if not, we send an empty json, which can be loaded into the canvas.
    if (json_data !== '{}') {
      if (sketcherS.shapes.length !== 0) {
        json_data = JSON.stringify(new ChemDoodle.io.JSONInterpreter().contentTo(sketcherS.molecules, sketcherS.shapes));
        var molscaf = ChemDoodle.writeRXN(sketcherS.getMolecules(), sketcherS.shapes);
      } else {
        var molscaf = ChemDoodle.writeMOL(sketcherS.getMolecule());
      }
      if (molscaf !==
        'Molecule from ChemDoodle Web Components\n\n'
        +'http://www.ichemlabs.com\n  1  0  0  0  0  0            999 V2000\n'
        +'    0.0000    0.0000    0.0000 C   0  0  0  0  0  0\nM  END'
      ) {
        generateOutputChemDoodle(molscaf, json_data, scaffoldarea);
      }
    } else {
      scaffoldarea.value = '';
    }
    return true;
  });
};