'use strict';

var divTopNav = document.getElementById("myNavbar");
var btnTopNav = document.getElementsByClassName("navbar-toggle")[0];

var modalDiv = document.getElementsByClassName("modal")[0];
var modalContent = document.getElementsByClassName("modal-dialog")[0];
var modalClose = document.getElementById("modalCancelBtn");

function climbUpDOM(elem, selector) {
// Build path from DOM 'elem' up and return true if it includes 'selector'.
// Based on jquery closest() function.
    var firstChar = selector.charAt(0);
    // Get closest match
    for ( ; elem && elem !== document; elem = elem.parentNode ) {
        // If selector is a class
        if ( firstChar === '.' ) {
            if ( elem.classList.contains( selector.substr(1) ) ) {
                return elem;
            }
        }
        // If selector is an ID
        if ( firstChar === '#' ) {
            if ( elem.id === selector.substr(1) ) {
                return elem;
            }
        }
        // If selector is a data attribute
        if ( firstChar === '[' ) {
            if ( elem.hasAttribute( selector.substr(1, selector.length - 2) ) ) {
                return elem;
            }
        }
        // If selector is a tag
        if ( elem.tagName.toLowerCase() === selector ) {
            return elem;
        }
    }
    return false;
};

function vertCenter(obj,objProperty) {
	obj.style[objProperty] = (window.innerHeight - obj.clientHeight)/2 + "px";
}

btnTopNav.addEventListener('click', function(event) {
	divTopNav.classList.toggle("show");
});

window.onload = function() {
	vertCenter(modalContent, 'margin-top');
}

window.addEventListener("resize", function() {
	vertCenter(modalContent, 'margin-top');
})

modalClose.onclick = function() {
    modalDiv.style.display = "none";
}

window.onclick = function(event) {
	// Function hiding the dropdown menu when clicked outside of its area
	if (!climbUpDOM(event.target, "#myNavbar") && !climbUpDOM(event.target, ".navbar-toggle")) {
		divTopNav.classList.remove("show");
	}
}
