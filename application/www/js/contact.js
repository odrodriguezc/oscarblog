'use strict';
/////////////////////////////////////////////////////////////////////////////////////////
// DATA
/////////////////////////////////////////////////////////////////////////////////////////




/////////////////////////////////////////////////////////////////////////////////////////
// FONCTIONS                                                                           //
/////////////////////////////////////////////////////////////////////////////////////////

/**
 * formValidate
 * 
 * @param event
 * @author ODRC
 */
function formValidate() {

    event.preventDefault();
    const contact = {
        name: document.querySelector('input[name="name"]').value,
        email: document.querySelector('input[name="email"]').value,
        message: document.querySelector('#message').value
    }

    if (contact.email === '' || contact.message === '') {
        alert(`Veillez remplir les champs email et message s'il vous plait`);
        return;
    } else {
        if (!validateEmail(contact.email)) {
            alert(`votre adresse mail n'est pas conforme`);
            return;
        } else {
            $('form#contact').submit();
            alert(`Votre message a été transmit.`);
        }
    }
}

/**
 * Determine si une chaine de caracters est un email 
 * @param {string} mail 
 * @returns {boolean}
 * @author {ODRC}
 */
function validateEmail(mail) {
    return (/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(mail)) ? true : false;
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

    document.querySelector('#sendMessage').addEventListener('click', formValidate);
    //cancel submit via enter
    document.querySelector('input[name="name"]').addEventListener('keydown', cancelSubmit);
    document.querySelector('input[name="name"]').addEventListener('keydown', cancelSubmit);


});