"use strict";
/////////////////////////////////////////////////////////////////////////////////////////
// DATA                                                                                //
/////////////////////////////////////////////////////////////////////////////////////////
let delArticleConfirm;
let delArticleTitle;



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
    delArticleConfirm.href = `${getRequestUrl()}/admin/articles/del/?id=${this.dataset.id}`;
    delArticleTitle.textContent = `" ${this.dataset.title} "`;

}





/////////////////////////////////////////////////////////////////////////////////////////
// CODE PRINCIPAL                                                                      //
/////////////////////////////////////////////////////////////////////////////////////////
document.addEventListener('DOMContentLoaded',function(){    

    //propagation de l'evennement takeDataset sur tous les liens de la table
    document.querySelectorAll('.delLink').forEach(item =>{item.addEventListener("click", takeDataset);});
    delArticleConfirm = document.querySelector('#delArticleConfirm');
    delArticleTitle = document.querySelector('#delArticleTitle');

    
});
