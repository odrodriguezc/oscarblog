"use strict";
/////////////////////////////////////////////////////////////////////////////////////////
// DATA                                                                                //
/////////////////////////////////////////////////////////////////////////////////////////
let delUserConfirm;
let delUsername;



/////////////////////////////////////////////////////////////////////////////////////////
// FONCTIONS                                                                           //
/////////////////////////////////////////////////////////////////////////////////////////


/**
 * takeDataset
 * - capture l'id stocké dans le dataset.id et en le rajoutant à l'atribut href du lien delConfim de la modal
 * @param {} event 
 */
function takeDataset(event)
{
    delUserConfirm.href = `${getRequestUrl()}/admin/users/del/?id=${this.dataset.id}`;
    delUsername.textContent = `" ${this.dataset.username} "`;

}




/////////////////////////////////////////////////////////////////////////////////////////
// CODE PRINCIPAL                                                                      //
/////////////////////////////////////////////////////////////////////////////////////////
document.addEventListener('DOMContentLoaded',function(){    

    //propagation de l'evennement takeDataset sur tous les liens de la table
    document.querySelectorAll('.delLink').forEach(item =>{item.addEventListener("click", takeDataset);});
    delUserConfirm = document.querySelector('#delUserConfirm');
    delUsername = document.querySelector('#delUsername');

    
});
