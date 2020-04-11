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
        ajaxResponse.text(`<p> l'image selectionée est déjà presente dans la collection ${selectedCol[0].textContent}</p>`);
        $('#ajaxResponseModal').modal('show');

    } else {
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
            ajaxResponse.text(`<p> ${response}</p>`);
            $('#ajaxResponseModal').modal('show');
            
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

            }
            
        })
    
        .fail(function(error){
            ajaxResponse.text(`<p>Votre demande n'a pas été processé  ${error}</p>`);
            $('#ajaxResponseModal').modal('show');
        })
    
        .always(function(){
    
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



/////////////////////////////////////////////////////////////////////////////////////////
// CODE PRINCIPAL                                                                      //
/////////////////////////////////////////////////////////////////////////////////////////
document.addEventListener('DOMContentLoaded',function(){    
    
    $('#formCollectionsSubmit').on("click",addPicToCollection);
    $('.popOff').on("click", popOffPic);
    
});
