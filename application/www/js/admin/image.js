"use strict";
/////////////////////////////////////////////////////////////////////////////////////////
// DATA                                                                                //
/////////////////////////////////////////////////////////////////////////////////////////



/////////////////////////////////////////////////////////////////////////////////////////
// FONCTIONS                                                                           //
/////////////////////////////////////////////////////////////////////////////////////////
/**
 * addPicToCollection
 * 
 * envoie une requette ajax pour ajouter la photo dans une collection
 * 
 * @param event 
 * @returns mixed 
 * @author ODRC
 */
function addPicToCollection() 
{
    event.preventDefault();
    
    const ajaxResponse = $('#ajaxResponse');
    const selectedCol = $('#formCollections option:selected');
    const picId = $('#picId').val();
    
    if (selectedCol.data('assigned') === 'true' )
    {
        alert(`l'image selectionée est déjà presente dans la collection ${selectedCol[0].textContent}`);

    } else if (selectedCol.val()==='0')
    {
        alert(`La collection selectionée n'est pas valable`)

    } else{    
        $.ajax({
            
            url: `${getRequestUrl()}/admin/gallery/collections/add/`,
    
            method: "POST",
    
            dataType: "html",
            
            data:   {   collection: selectedCol[0].value,
                        picId: picId
                    }
    
        })
        .done(function(response){
            let code = response.slice(-1);
            
            //succes
            if (code === '1'){
                const str = `<span class="inCollection">
                <a class="colLink" data-collectionid = "${picId}"  href="${getRequestUrl()}/admin/gallery/gallery/collections/col/?id=${picId}">${selectedCol[0].textContent}
                </a>
                <a class="ml-1 popOff" data-collectionid ="${picId}" href="${getRequestUrl()}/admin/gallery/gallery/collections/pop/"><i class="far fa-trash-alt"></i>
                </a>
                </span>`
                $('#inCollectionGroup').append(str);
                //ajouter evennement 
                $('.popOff').on("click", popOffPic);
                //data assigned
                $('#collectionSlt option:selected')[0].dataset.assigned = "true";
                
            }
            ajaxResponse.text(`<p> ${response}</p>`);
            
        })
    
        .fail(function(error){
            ajaxResponse.text(`<p>Votre demande n'a pas été processé  ${error}</p>`);
          
        })
    
        .always(function(){
            $('#ajaxResponseModal').modal('show');
        })

    }

}

/**
 * popOffPic
 * 
 * Fait sauter une photo d'une collection
 * @author ODRC
 */
function popOffPic(){
    
    event.preventDefault();
 
   
    const ajaxResponse = $('#ajaxResponse');
    const span = this.parentElement;
    const picId = parseInt($('#picId').val());
    const colId = parseInt(this.dataset.collectionid);

    if (Number.isInteger(picId) && Number.isInteger(colId))
    {
        $.ajax({
            
            url: `${getRequestUrl()}/admin/gallery/collections/pop/`,
    
            method: "POST",
    
            dataType: "html",
            
            data:   {   collection: colId,
                        picId: picId
                    }
    
        })
        .done(function(response){
            let code = response.slice(-1);
            
            //succes
            if (code === '1'){
                span.remove();
                //on change le data set assigned de l'option du select
                $(`#collectionSlt option[value=${colId}]`)[0].dataset.assigned ='false';
            }
            ajaxResponse.text(`<p> ${response}</p>`);
            $('#ajaxResponseModal').modal('show');
        })
    
        .fail(function(error){
            ajaxResponse.text(`<p>Votre demande n'a pas été processé  ${error}</p>`);
            $('#ajaxResponseModal').modal('show');
        })
    
        .always(function(){ 
    
        });
    } else {
        ajaxResponse.text(`<p> Requette non envoyé. Les valeurs passés en paramettres ne corresponden pas aux valeurs attendus</p>`);
        $('#ajaxResponseModal').modal('show');
    }
    
}

/**
 * newCollection
 * 
 * Ajouter une collection à la liste de collections
 * 
 * @param void
 * @author ODRC
 */
function newCollection() {
    event.preventDefault();
    
    let values ={};
    values.title = $('#colTitle').val();
    values.description = $('#colDescription').val();
    values.published = $('input[name=published]:checked').val();
    const colList = $('#collectionSlt');
    const ajaxResponse = $('#ajaxResponse');


    
    if (values.title == '') 
    {
        return alert(`Le champ title est obligatoire`);
    } 
    //supprimer les caracters speciaux sauf les (_ et -);
    values.title = values.title.replace(/[^a-zA-Z0-9 _-]/g, '');

    //verifier que la collection est vraiment nouvelle
    if ($('#collectionSlt').find(`option[data-title = '${values.title}']`).length > 0)
    {
        return alert(`La collection ${values.title} est déjà existante`);
    } else {
        $.ajax({
                
            url: `${getRequestUrl()}/admin/gallery/collections/new/`,
    
            method: "POST",
    
            dataType: "json",
            
            data:   {json: JSON.stringify(values)}
    
        })
        .done(function(response){
            console.log(response);
            if (response.hasOwnProperty('error')){
                ajaxResponse.text(`<p> ${response.error}</p>`);
            } else if (response.hasOwnProperty('succes'))
            {
                colList.append(`<option class="collectionOpt" value="${response.succes[0].id}" data-assigned="false" data-title="${response.succes[0].title}">${response.succes[0].title}</option>`);
            }
           
        })
    
        .fail(function(error){
            ajaxResponse.text(`<p>Votre demande n'a pas été processé  ${error}</p>`);
            console.log(error);
        })
    
        .always(function(){ 
            $('#newColModal').modal('hide');
            $('#ajaxResponseModal').modal('show');
        });
    }
}

/**
 * switchBtns
 * 
 * permet d'afficher le boutton new collection et de cacher le bouton ajouter 
 * @author ODRC
 */
function switchBtns (){
    if ($('#newColOpt')[0].selected == true)
    {
        $('#newColBtn').show();
        $('#formCollectionsSubmit').hide();
    } else{
        $('#newColBtn').hide();
        $('#formCollectionsSubmit').show();
    }
}



/////////////////////////////////////////////////////////////////////////////////////////
// CODE PRINCIPAL                                                                      //
/////////////////////////////////////////////////////////////////////////////////////////
document.addEventListener('DOMContentLoaded',function(){    
    
    $('#formCollectionsSubmit').on("click",addPicToCollection);
    $('.popOff').on("click", popOffPic);
    $('#newCollectionForm').on("submit", newCollection);
    $('#collectionSlt').on("change", switchBtns);
    
});
