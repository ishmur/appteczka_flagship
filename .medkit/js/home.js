'use strict';

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

modalClose.onclick = function() {
    modalDiv.style.display = "none";
}