'use strict';

/////////////////////////////////////////////////////////////////////////////////////////
// FONCTIONS                                                                           //
/////////////////////////////////////////////////////////////////////////////////////////
let currentCol;



/**
 * showBigPhoto
 * 
 * Afiche la modal avec l'image en grand
 * @author odrc
 */
function showBigPhoto() {
    let target = document.querySelector('#imgTarget');
    const control = document.querySelectorAll('.control');

    control.forEach(element => {
        element.dataset.id = this.dataset.id;
    });
    target.src = this.dataset.src;
    photoModal.classList.toggle('show');

}

/**
 * closeModal
 * 
 * fait disparaitre la modal
 * @author ODRC
 */
function closeModal() {
    photoModal.classList.toggle('show');
}

function found(id) {
    let arr = Array.from(currentCol);
    return arr.findIndex(item => item.dataset.id === id);
}


function next() {
    if (currentCol.length <= 1) {
        return;
    }

    let index = found(this.dataset.id);

    if (currentCol.length > (index + 1)) {
        index++;
        document.querySelector('#imgTarget').src = currentCol[index].dataset.src;
        document.querySelector('#imgTarget').dataset.id = this.dataset.id;
        document.querySelector('#next').dataset.id = currentCol[index].dataset.id;
        document.querySelector('#back').dataset.id = currentCol[index].dataset.id;

    }
    return
}

function back() {
    if (currentCol.length <= 1) {
        return;
    }

    let index = found(this.dataset.id);


    if (index > 0) {
        index--;
        document.querySelector('#imgTarget').src = currentCol[index].dataset.src;
        document.querySelector('#imgTarget').dataset.id = currentCol[index].dataset.id;
        document.querySelector('#back').dataset.id = currentCol[index].dataset.id;
        document.querySelector('#next').dataset.id = currentCol[index].dataset.id;
    }
    return
}

function randomPic() {
    if (currentCol.length <= 1) {
        return;
    }

    const index = getRandomIntInclusive(0, (currentCol.length - 1));

    document.querySelector('#imgTarget').src = currentCol[index].dataset.src;
    document.querySelector('#imgTarget').dataset.id = currentCol[index].dataset.id;
    document.querySelector('#back').dataset.id = currentCol[index].dataset.id;
    document.querySelector('#next').dataset.id = currentCol[index].dataset.id;

    return
}




/////////////////////////////////////////////////////////////////////////////////////////
// CODE PRINCIPAL                                                                      //
/////////////////////////////////////////////////////////////////////////////////////////
document.addEventListener('DOMContentLoaded', function () {

    currentCol = document.querySelectorAll('.picLink');
    document.querySelectorAll('.picLink').forEach(element => {
        element.addEventListener('click', showBigPhoto);
    });

    document.querySelector('#closeModal').addEventListener('click', closeModal);
    const photoModal = document.querySelector('#photoModal');

    document.querySelector('#next').addEventListener('click', next);
    document.querySelector('#back').addEventListener('click', back);
    document.querySelector('#random').addEventListener('click', randomPic);


});