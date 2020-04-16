'use strict';

/////////////////////////////////////////////////////////////////////////////////////////
// FONCTIONS                                                                           //
/////////////////////////////////////////////////////////////////////////////////////////

/**
 * addLikes
 * 
 * Rajoute un like (j'aime) à un article
 * - Recupere l'id de l'article 
 * - construit un object avec l'id et l'action (like)
 * - Transforme l'objet en json
 * - fait appel à la function makeRequest
 * @param {Event} event 
 * @author ODRC
 */
function addLikes(event) {
    event.preventDefault();
    let data = {
        id : this.dataset.id,
        action :'likes'
    };
    makeRequest('POST', 'articles/action/', JSON.stringify(data));

}

/**
 * addDislike
 * 
 * Rajoute un dislike (je n'aime pas) à un article
 * - Recupere l'id de l'article 
 * - construit un object avec l'id et l'action (like)
 * - Transforme l'objet en json
 * - fait appel à la function makeRequest
 * @param {Event} event 
 * @author ODRC
 */
function addDislikes(event) {
    event.preventDefault();
    let data = {
        id : this.dataset.id,
        action :'dislikes'
    };
    makeRequest('POST', 'articles/action/', JSON.stringify(data));
}

/**
 * share
 * 
 * Nous permet de passer les paramettres à la modal
 * Fait appel a la function addShare
 * @author ODRC
 * @todo faire l'appel de addShare apres avoir verifier que le user a partagé l'article
 */
function share() {
    const hostname = location.hostname;
    const id = this.dataset.id;
    const title = this.dataset.title.replace(" ","%20")

    let facebookStr = `https://www.facebook.com/sharer/sharer.php?u=${hostname}/article/?id=${id}`;
    document.querySelector('.share-facebook').href  = facebookStr;

    let twitterStr = `https://twitter.com/intent/tweet?text=${title}${hostname}/article/?id=${id}`
    document.querySelector('.share-twitter').href = twitterStr;

    let linkedinStr = `https://www.linkedin.com/shareArticle?mini=true&url=${hostname}/article/?id=${id}&title=${title}&source=${hostname}`;
    document.querySelector('.share-linkedin').href = linkedinStr;

    let emailStr = `mailto:?subject=${title}&body=${hostname}/article/?id=${id}`;
    document.querySelector('.share-email').href = emailStr;

    addShare(id);
    
}

/**
 * addShare
 * 
 * Rajoute un share à un article qui a été partagé
 * - Recupere l'id de l'article 
 * - construit un object avec l'id et l'action (like)
 * - Transforme l'objet en json
 * - fait appel à la function makeRequest
 * @param {Event} event 
 * @author ODRC
 */
function addShare(id) {
    let data = {
        id : id,
        action :'share'
    };
    makeRequest('POST', 'articles/action/', JSON.stringify(data));
}

/**
 * makeRequest
 * 
 * Renvoie une requette AJAX avec un JSON et recupere la reponse
 * @param {string} methode 
 * @param {string} url 
 * @param {JSON} data 
 * @author ODRC
 */
function makeRequest(methode, url, data) {
    var httpRequest = new XMLHttpRequest();

    if (!httpRequest) {
      alert('Abandon :( Impossible de créer une instance de XMLHTTP');
      return false;
    }
    httpRequest.onreadystatechange = ()=>{
        try {
            if (httpRequest.readyState === XMLHttpRequest.DONE) {
              if (httpRequest.status === 200) {
                  let response = JSON.parse(httpRequest.responseText);
                
                  //display response
                  document.querySelector(`span.likesCount[data-id="${response.id}"]`).textContent = response.likes;
                  document.querySelector(`span.dislikesCount[data-id="${response.id}"]`).textContent = response.dislikes;
                  document.querySelector(`span.shareCount[data-id="${response.id}"]`).textContent = response.share;
              } else {
                  alert("Un problème est survenu au cours de la requête.");
              }
            }
          }
          catch( e ) {
            alert("Une exception s’est produite : " + e.description);
          }
    };
    httpRequest.open(`${methode}`, `${getRequestUrl()}/${url}`);
    httpRequest.setRequestHeader('Content-Type', 'application/json');
    httpRequest.send(data);
}




/////////////////////////////////////////////////////////////////////////////////////////
// CODE PRINCIPAL                                                                      //
/////////////////////////////////////////////////////////////////////////////////////////
document.addEventListener('DOMContentLoaded',function(){    

    document.querySelectorAll('.likes').forEach(element => {
        element.addEventListener('click', addLikes);
    });
    document.querySelectorAll('.dislikes').forEach(element => {
        element.addEventListener('click', addDislikes);
    });
    document.querySelectorAll('.share').forEach(element => {
        element.addEventListener('click', share);
    });
    
});