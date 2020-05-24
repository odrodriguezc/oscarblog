"use strict";
/////////////////////////////////////////////////////////////////////////////////////////
// DATA                                                                                //
/////////////////////////////////////////////////////////////////////////////////////////




/////////////////////////////////////////////////////////////////////////////////////////
// FONCTIONS                                                                           //
/////////////////////////////////////////////////////////////////////////////////////////
let httpRequest;


function subscribe() {
    event.preventDefault();
    email = document.querySelector('input[name="email"]').value;

    httpRequest = new XMLHttpRequest();

    if (!httpRequest) {
        alert('Abandon :( Impossible de créer une instance de XMLHTTP');
        return false;
    }
    httpRequest.onreadystatechange = alertContents;
    httpRequest.open('GET', `${getRequestUrl()}/contact?email=${email}`);
    httpRequest.send();
}

function alertContents() {
    if (httpRequest.readyState === XMLHttpRequest.DONE) {
        if (httpRequest.status === 200) {
            alert(httpRequest.responseText);
        } else {
            alert('Il y a eu un problème avec la requête.');
        }
    }
}





/////////////////////////////////////////////////////////////////////////////////////////
// CODE PRINCIPAL                                                                      //
/////////////////////////////////////////////////////////////////////////////////////////
document.addEventListener('DOMContentLoaded', function () {

    document.querySelector('#subscribeToNewsletter').addEventListener('click', subscribe);


});