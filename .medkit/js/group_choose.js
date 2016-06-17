'use strict';

var userInputsLeave = document.querySelectorAll("input[name='groups[]']");
var userButtonsLeave = document.getElementsByClassName("btn-delete");

for (var index = 0; index < userButtonsLeave.length; index++){
    let input = userInputsLeave[index];
    let row = input.parentNode.parentNode.parentNode;
    let button = userButtonsLeave[index];

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

var userInputsChange = document.querySelectorAll("input[name='group_change[]']");
var userButtonsChange = document.getElementsByClassName("btn-change");
var changeForm = document.forms["change_group"];

for (index = 0; index < userButtonsChange.length; index++){
    let input = userInputsChange[index];
    let button = userButtonsChange[index];

    button.addEventListener('click', function(){
        input.checked = true;
        changeForm.submit();
    });
}