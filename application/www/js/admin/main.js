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





/////////////////////////////////////////////////////////////////////////////////////////
// CODE PRINCIPAL                                                                      //
/////////////////////////////////////////////////////////////////////////////////////////

$(document).ready(function(){
    $("#editBtn").on("click", swithUpdateView);

});