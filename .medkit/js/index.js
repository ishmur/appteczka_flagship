'use strict';

var contentContainer = document.getElementById("contentContainer");
var btnLogin = document.getElementById("btnLogin");
var btnCancelLogin = document.getElementById("modalCancelBtn");
var modalDiv = document.getElementsByClassName("modal")[0];
var modalDialog = document.getElementsByClassName("modal-dialog")[0];
var modalClose = document.getElementsByClassName("close")[0];

function vertCenter(obj,objProperty) {
	obj.style[objProperty] = (window.innerHeight - obj.clientHeight)/2 + "px";
}

window.addEventListener("resize", function() {
	vertCenter(modalDialog, 'margin-top');
	vertCenter(contentContainer, 'padding-top');
})

btnLogin.onclick = function() {
    modalDiv.style.display = "block";
    vertCenter(modalDialog, 'margin-top');
}

modalClose.onclick = function() {
    modalDiv.style.display = "none";
}

btnCancelLogin.onclick = function() {
    modalDiv.style.display = "none";
}

window.onclick = function(event) {
	// Hide modal when clicked outside
    if (event.target == modalDiv) {
        modalDiv.style.display = "none";
    }
}

window.onload = function() {
	vertCenter(modalDialog, 'margin-top');
}
