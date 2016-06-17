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

var userInputsSoon = document.querySelectorAll("input[name='overdueSoon[]']");
var userButtonsSoon = document.getElementsByClassName("btn-delete-soon");

for (var index = 0; index < userButtonsSoon.length; index++){
    let input = userInputsSoon[index];
    let row = input.parentNode.parentNode.parentNode;
    let button = userButtonsSoon[index];

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