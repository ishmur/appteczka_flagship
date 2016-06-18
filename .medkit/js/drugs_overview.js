'use strict';

// Delete selected drugs from db

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



// Edit selected drug info

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



// Show modal and AJAX call

var btnTakeArray = document.getElementsByClassName("btn-take");
var modalDiv = document.getElementsByClassName("modal")[0];
var modalContent = document.getElementsByClassName("modal-dialog")[0];
var modalClose = document.getElementById("modalCancelBtn");

function vertCenter(obj,objProperty) {
    obj.style[objProperty] = (window.innerHeight - obj.clientHeight)/2 + "px";
}

window.onload = function() {
    vertCenter(modalContent, 'margin-top');
}

window.addEventListener("resize", function() {
    vertCenter(modalContent, 'margin-top');
})

window.onclick = function(event) {
    // Hide modal when clicked outside
    if (event.target == modalDiv) {
        modalDiv.style.display = "none";
    }
}

modalClose.onclick = function() {
    modalDiv.style.display = "none";
}

for (index = 0; index < btnTakeArray.length; index++) {
    let button = btnTakeArray[index];

    button.onclick = function () {
        modalDiv.style.display = "block";
        vertCenter(modalContent, 'margin-top');
    }
}

