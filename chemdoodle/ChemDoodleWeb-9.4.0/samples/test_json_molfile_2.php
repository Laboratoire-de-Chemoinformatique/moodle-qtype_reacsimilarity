<?php

$molfile = isset($_POST['molfile']) ? $_POST['molfile'] : null;
$json_doc = isset($_POST['json_data']) ? $_POST['json_data'] : null;

// from 2nd comment https://www.php.net/manual/fr/function.array-column.php
function molsimilarity_array_column_ext($array, $columnkey, $indexkey = null): array {
    $result = array();
    foreach ($array as $subarray => $value) {
        if (array_key_exists($columnkey,$value)) { $val = $array[$subarray][$columnkey]; }
        else if ($columnkey === null) { $val = $value; }
        else { continue; }

        if ($indexkey === null) { $result[] = $val; }
        elseif ($indexkey == -1 || array_key_exists($indexkey,$value)) {
            $result[($indexkey == -1)?$subarray:$array[$subarray][$indexkey]] = $val;
        }
    }
    return $result;
}

// On cherche le nombre de lone pairs et de radicaux dans le json

$decoded = json_decode($json_doc, true);
$atom_inf = $decoded['m'][0]['a'];
$lone_pairs = molsimilarity_array_column_ext($atom_inf,'p',-1);
$radicals = molsimilarity_array_column_ext($atom_inf, 'r', -1);

if (!$lone_pairs & !$radicals){
    // Si pas de doublets ou radicaux, on renvoie le molfile d'origine.
    $final_mol = $molfile;
}
else {

    // On prend une liste de lignes
    $lines = explode("\n", $molfile);
    // On récupère la ligne comptes puis on en sort les chiffres
    $count_line = $lines[3];
    preg_match_all('!\d+!', $count_line, $numbers);
    $nb_atom = $numbers[0][0];
    $nb_bonds = $numbers[0][1];
    // On récupère les différents blocks du molfile
    $header = array_slice($lines, 0, 3);
    $atom_block = array_slice($lines, 4, $nb_atom);
    $bond_block = array_slice($lines, (4 + $nb_atom), $nb_bonds);
    $end_file = array_slice($lines, -1, 1);



    if ($lone_pairs) {

        // Get the initial number of atoms and bonds
        $nb_lone_pairs = 0;
        $nb_atom_final = $nb_atom;
        $nb_bonds_final = $nb_bonds;
        $bond_vanilla = '  1  0  0  0  0';

        foreach ($lone_pairs as $key => $value) {
            $num_atom = $key + 1;
            //On créer une ligne pour l'atom block
            $atom_line = $atom_block[$key];
            $atom_line[31] = 'L';
            $atom_line[32] = 'P';
            for ($i = 1; $i <= $value; $i++) {
                $atom_block[$nb_atom_final] = $atom_line;
                $nb_atom_final++;

                // On créer une ligne pour le bond block
                $bond_line = '';
                $bond_line .= sprintf("% 3d", $num_atom);
                $bond_line .= sprintf("% 3d", $nb_atom_final);
                $bond_line .= $bond_vanilla;
                $bond_block[$nb_bonds_final] = $bond_line;
                $nb_lone_pairs++;
                $nb_bonds_final++;

            }
        }
        // Change number of atoms and bonds to new one
        $count_line_edited = '';
        $count_line_edited .= sprintf("% 3d", $nb_atom_final);
        $count_line_edited .= sprintf("% 3d", $nb_bonds_final);
        $count_line_edited .= substr($count_line, 6);
        $count_line_array = explode('\n', $count_line_edited);
    }

    if ($radicals) {
        $nb_radicals = 0;
        $rad_line = 'M  RAD';
        $end_rad_line = '';
        foreach ($radicals as $key => $value) {
            $num_atom = $key + 1;
            $end_rad_line .= sprintf("% 3d", $num_atom);
            $end_rad_line .= sprintf("% 3d", 2);
            $nb_radicals ++;
        }
        $rad_line .= sprintf("% 3d", $nb_radicals);
        $rad_line .= $end_rad_line;
        $rad_line_array = explode('\n', $rad_line);
        if (!$lone_pairs) {
            $count_line_array = explode('\n', $count_line);
        }
    }


    // Rewrite the molfile
    $final_mol = array();
    if ($radicals) {
        $final_mol = implode("\n", array_merge($final_mol, $header, $count_line_array, $atom_block, $bond_block, $rad_line_array, $end_file));
    }
    else {
        $final_mol = implode("\n", array_merge($final_mol, $header, $count_line_array, $atom_block, $bond_block, $end_file));
    }
}
// A json is sent back with the json and the new molfile.
$end_mol = ['json' => $json_doc, 'mol_file' => 	$final_mol];
echo (json_encode($end_mol, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

