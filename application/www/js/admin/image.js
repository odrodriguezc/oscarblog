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
    
    if (selectedCol[0].dataset.assigned === 'true' )
    {
        ajaxResponse.text(`<p> l'image selectionée est déjà presente dans la collection ${selectedCol[0].textContent}</p>`);
        $('#ajaxResponseModal').modal('show');


    return ;

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
            
            if (code === '1'){
                let str = `<span>
                <a class="inCollection" data-collection-id = "${picId}"  href="${getRequestUrl()}/admin/gallery/gallery/collections/col/?id=${picId}">${selectedCol[0].textContent}
                </a>
                <a class="ml-1" href="${getRequestUrl()}/admin/gallery/gallery/collections/pop/?col=${selectedCol[0].value}&pic=${picId}"><i class="far fa-trash-alt">
                </i>
                </a>
                </span>`
                $('#inCollectionGroup').append(str);
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





/////////////////////////////////////////////////////////////////////////////////////////
// CODE PRINCIPAL                                                                      //
/////////////////////////////////////////////////////////////////////////////////////////
document.addEventListener('DOMContentLoaded',function(){    
    
    $('#formCollectionsSubmit').on("click",addPicToCollection);
    
});
