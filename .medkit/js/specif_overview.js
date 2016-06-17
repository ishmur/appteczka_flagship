'use strict';

var userInputs = document.querySelectorAll("input[name='specif[]']");
var userButtons = document.getElementsByClassName("btn-delete");

for (var index = 0; index < userButtons.length; index++){
    let input = userInputs[index];
    let row = input.parentNode.parentNode.parentNode;
    let button = userButtons[index];

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


