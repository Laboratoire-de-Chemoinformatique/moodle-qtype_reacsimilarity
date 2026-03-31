function ajax_call (molfile, json_data, location_isida, dirroot ) {
    let request;
    console.log(molfile);
    console.log(json_data);
    console.log(location_isida);
    console.log(dirroot);
    if (request){
        request.abort();
    }
    let get_data = '';
    $.ajax({
        async: false,
        dataType: 'json',
        url: dirroot + '/question/type/molsimilarity/chemdoodle/ChemDoodleWeb-9.1.0/samples/test_json_molfile_2.php',
        type: 'post',
        methodname: 'qtype_molsimilarity_modify_molfile',
        data: {molfile: molfile, json_data: json_data}})
        .done(function (response) {
            console.log('Correction ongoing');
        })
        .fail(function (response){
            //alert('fail');
            console.error('The following error occurred : %o', response);
        })
        .always(function (response) {
            get_data = (response);
            // console.log(get_data);
            // console.log(response.json);
            $(location_isida).val(JSON.stringify(get_data));
            console.log(response.mol_file);
            console.log($(location_isida).val());
        });
}