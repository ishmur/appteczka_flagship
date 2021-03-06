'use strict';

var userInputsOverdue = document.querySelectorAll("input[name='overdue[]']");
var userButtonsOverdue = document.getElementsByClassName("btn-delete-overdue");

for (var index = 0; index < userButtonsOverdue.length; index++){
    let input = userInputsOverdue[index];
    let row = input.parentNode.parentNode.parentNode;
    let button = userButtonsOverdue[index];

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