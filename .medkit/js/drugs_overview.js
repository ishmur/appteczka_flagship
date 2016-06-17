'use strict';

var userInputs = document.querySelectorAll("input[name='drugs[]']");
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

var userInputsEdit = document.querySelectorAll("input[name='drugs_edit[]']");
var userButtonsEdit = document.getElementsByClassName("btn-edit");
var editForm = document.forms["edit_drugs"];

for (index = 0; index < userButtonsEdit.length; index++){
    let input = userInputsEdit[index];
    let button = userButtonsEdit[index];

    button.addEventListener('click', function(){
        input.checked = true;
        editForm.submit();
    });
}