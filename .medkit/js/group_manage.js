'use strict';

var userInputsKick = document.querySelectorAll("input[name='kickUsers[]']");
var userButtonsKick = document.getElementsByClassName("btn-delete-kick");

for (var index = 0; index < userButtonsKick.length; index++){
    let input = userInputsKick[index];
    let row = input.parentNode.parentNode.parentNode;
    let button = userButtonsKick[index];

    button.addEventListener('click', function(){
        if(input.checked) {
            input.checked = false;
            row.classList.remove("danger");
            button.innerHTML = "Zaznacz";
        } else {
            input.checked = true;
            row.classList.add("danger");
            button.innerHTML = "Odznacz";
        }
    });
}

