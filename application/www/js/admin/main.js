'use strict';

/////////////////////////////////////////////////////////////////////////////////////////
// FONCTIONS                                                                           //
/////////////////////////////////////////////////////////////////////////////////////////

/**
 * @function swithUpdateView 
 * change the input atributes to make them editables
 * show the hidden select and submit et cancel button
 * hide the edit button 
 */
function swithUpdateView(){
    $("#userRole, #passwordGroup, #confirmPasswordGroup, #btnUpdateGroup").removeClass("d-none");
    $("#editBtn, #roleLabel").addClass("d-none");
    $(".readonly").removeAttr("readonly");
    $("#updateBtn").removeAttr("disabled");
};

/**
 * @function cancelUpdate
 * Revert the swithUpdateView function effects
 */
function cancelUpdate() {
    $("#userRole, #passwordGroup, #confirmPasswordGroup, #btnUpdateGroup").addClass("d-none");
    $("#editBtn, #roleLabel").removeClass("d-none");
    $(".readonly").addAttr("readonly");
    $("#updateBtn").addAttr("disabled");
}



/////////////////////////////////////////////////////////////////////////////////////////
// CODE PRINCIPAL                                                                      //
/////////////////////////////////////////////////////////////////////////////////////////

$(document).ready(function(){
    $("#editBtn").on("click", swithUpdateView);
    $("#cancelBtn").on("click", cancelUpdate);

});