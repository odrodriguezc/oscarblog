'use strict';

/////////////////////////////////////////////////////////////////////////////////////////
// FONCTIONS                                                                           //
/////////////////////////////////////////////////////////////////////////////////////////
/**
 * showBigPhoto
 * 
 * Afiche la modal avec l'image en grand
 * @author odrc
 */
function showBigPhoto() 
{
    let target = document.querySelector('#imgTarget');

    target.src = this.dataset.src;
    photoModal.classList.toggle('show');

}

/**
 * closeModal
 * 
 * fait disparaitre la modal
 * @author ODRC
 */
function closeModal() 
{
    photoModal.classList.toggle('show');
}



/////////////////////////////////////////////////////////////////////////////////////////
// CODE PRINCIPAL                                                                      //
/////////////////////////////////////////////////////////////////////////////////////////
document.addEventListener('DOMContentLoaded',function(){    

    document.querySelectorAll('.picLink').forEach(element => {
        element.addEventListener('click', showBigPhoto);
    });
    
    document.querySelector('#closeModal').addEventListener('click', closeModal);
    const photoModal = document.querySelector('#photoModal');


    
});