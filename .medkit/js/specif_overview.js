'use strict';

var userInputs = document.querySelectorAll("input[name='specif[]']");

for (var index = 0; index < userInputs.length; index++){
    var input = userInputs[index];
    input.addEventListener('change',function(){
        switch(this.checked){

            case true:
                this.parentNode.parentNode.classList.add("danger");
                break;

            case false:
                this.parentNode.parentNode.classList.remove("danger");
                break;

        }
    });
}


