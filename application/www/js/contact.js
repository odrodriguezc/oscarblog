'use strict';
/////////////////////////////////////////////////////////////////////////////////////////
// DATA
/////////////////////////////////////////////////////////////////////////////////////////
let formValues = {};



/////////////////////////////////////////////////////////////////////////////////////////
// FONCTIONS                                                                           //
/////////////////////////////////////////////////////////////////////////////////////////

/**
 * formValidate
 * 
 * @param event
 * @author ODRC
 */
function formValidate() 
{  
    
    event.preventDefault();
    if (formValues.email.value === '' || formValues.message.value === '')
    {
        alert(`Veillez remplir les champs email et message s'il vous plait`);
    } else {
        document.querySelector('form').submit();
        alert(`Votre message a été transmit`);
    }

}

/**
 * Annule la validation du formulaire en cas d'appui de la touche Enter
 * @param {Event} event 
 */
function cancelSubmit(event) 
{
    if (event.key === "Enter")
    event.preventDefault();
}




/////////////////////////////////////////////////////////////////////////////////////////
// CODE PRINCIPAL                                                                      //
/////////////////////////////////////////////////////////////////////////////////////////
document.addEventListener('DOMContentLoaded',function(){    

    document.querySelector('#sendMessage').addEventListener('click', formValidate);
    formValues.name = document.querySelector('input[name="name"]')
    formValues.email = document.querySelector('input[name = "email"')
    formValues.message = document.querySelector('input[name= "message"]');
    //cancel submit via enter
    formValues.name.addEventListener('keydown', cancelSubmit);
    formValues.email.addEventListener('keydown', cancelSubmit);

    
});