"use strict";
/////////////////////////////////////////////////////////////////////////////////////////
// DATA                                                                                //
/////////////////////////////////////////////////////////////////////////////////////////



/////////////////////////////////////////////////////////////////////////////////////////
// FONCTIONS                                                                           //
/////////////////////////////////////////////////////////////////////////////////////////

function tooglePass() {
    const group = document.querySelector('#passwordSection');
    const currentPass = document.querySelector('input[name=currentPassword]');
    const password = document.querySelector('input[name=password]');
    const confirmPassword = document.querySelector('input[name=confirmPassword]');
    
    if (this.checked == true){
            group.classList.remove('hide');
            currentPass.disabled = false;
            password.disabled = false;
            confirmPassword.disabled = false;
    } else{
        group.classList.add('hide');
        currentPass.disabled = true;
        password.disabled = true;
        confirmPassword.disabled = true;
    }
}



/////////////////////////////////////////////////////////////////////////////////////////
// CODE PRINCIPAL                                                                      //
/////////////////////////////////////////////////////////////////////////////////////////
document.addEventListener('DOMContentLoaded',function(){    

 document.querySelector('input[name=changePassword]').addEventListener('change', tooglePass);


    
});