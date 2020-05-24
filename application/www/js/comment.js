'use strict';

/////////////////////////////////////////////////////////////////////////////////////////
// FONCTIONS                                                                           //
/////////////////////////////////////////////////////////////////////////////////////////

function validate() {

    event.preventDefault();

    const comment = {
        title: document.querySelector('input[name="title"]').value,
        content: document.querySelector('#content').value,
        postId: document.querySelector('input[name="postId"]').value,
    }

    if (comment.title === '' || comment.content === '') {
        alert(`Veillez donner un title et un contenu svp`);
        return;
    } else {
        $('form#commentForm').submit();
    }
}

/**
 * Annule la validation du formulaire en cas d'appui de la touche Enter
 * @param {Event} event 
 */
function cancelSubmit(event) {
    if (event.key === "Enter")
        event.preventDefault();
}




/////////////////////////////////////////////////////////////////////////////////////////
// CODE PRINCIPAL                                                                      //
/////////////////////////////////////////////////////////////////////////////////////////
document.addEventListener('DOMContentLoaded', function () {

    document.querySelector('#commentForm').addEventListener('submit', validate);
    document.querySelector('input[name="title"]').addEventListener('keydown', cancelSubmit);

});