"use strict";
/////////////////////////////////////////////////////////////////////////////////////////
// DATA                                                                                //
/////////////////////////////////////////////////////////////////////////////////////////



/////////////////////////////////////////////////////////////////////////////////////////
// FONCTIONS                                                                           //
/////////////////////////////////////////////////////////////////////////////////////////

function validateUpload(e) {
    console.log(this.files);
    clearList();
    if (this.files.length >= 10) {
        document.querySelector('#uploadSubmit').disabled = true;
        alert("Max 10 fichier par téléversement")
    }
    let size = 0;
    for (let i = 0; i < this.files.length; i++) {
        size += this.files[i].size;
        showFile(this.files[i], i);
    }

    showTotal(this.files.length, size);

    if (size > 10000000) {
        document.querySelector('#uploadSubmit').disabled = true;
        alert("Vous avez excedé la taille max (10Mo)");
    }
    console.log(size);

}

function showFile(file, index) {

    $('#fileList').append(`<li>No. ${index} Nom:  ${file.name} Taille: ${(file.size/1000000).toFixed(2)}</li>`);
}

function showTotal(files, size) {
    $('#fileList').append(`<li>Total: Files: ${files} Taille: ${(size/1000000).toFixed(2)}</li>`);
}

function clearList() {
    $('#fileList').empty();
}




/////////////////////////////////////////////////////////////////////////////////////////
// CODE PRINCIPAL                                                                      //
/////////////////////////////////////////////////////////////////////////////////////////
document.addEventListener('DOMContentLoaded', function () {

    $('#fileupload').on("change", validateUpload);


});