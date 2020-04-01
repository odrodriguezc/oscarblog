"use strict";
/////////////////////////////////////////////////////////////////////////////////////////
// DATA                                                                                //
/////////////////////////////////////////////////////////////////////////////////////////
let delCatConfirm;
let delCatTitle;



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
    delCatConfirm.href = `${getRequestUrl()}/admin/categories/del/?id=${this.dataset.id}&pId=${this.dataset.parent}`;
    delCatTitle.textContent = `" ${this.dataset.title} "`;

}




/////////////////////////////////////////////////////////////////////////////////////////
// CODE PRINCIPAL                                                                      //
/////////////////////////////////////////////////////////////////////////////////////////
document.addEventListener('DOMContentLoaded',function(){    

    //propagation de l'evennement takeDataset sur tous les liens de la table
    document.querySelectorAll('.delLink').forEach(item =>{item.addEventListener("click", takeDataset);});
    delCatConfirm = document.querySelector('#delCatConfirm');
    delCatTitle = document.querySelector('#delCatTitle');

    
});
