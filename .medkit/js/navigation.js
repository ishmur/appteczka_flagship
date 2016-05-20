'use strict';

var divTopNav = document.getElementById("NavbarTopMain");
var btnTopNav = document.getElementsByClassName("navbar-toggle")[0];

var divTopNavSettings = document.getElementById("NavbarTopSettings");
var btnTopNavSettings = document.getElementsByClassName("navbar-toggle")[1];

var divSideNavDrugs = document.getElementsByClassName("dropdown-content")[0];
var btnSideNavDrugs = document.getElementsByClassName("dropdown-toggle")[0];

var divSideNavSettings = document.getElementsByClassName("dropdown-content")[1];
var btnSideNavSettings = document.getElementsByClassName("dropdown-toggle")[1];


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
}

btnTopNav.addEventListener('click', function(event) {
    divTopNav.classList.toggle("show");
    divTopNavSettings.classList.remove("show");
});

btnTopNavSettings.addEventListener('click', function(event) {
    divTopNavSettings.classList.toggle("show");
    divTopNav.classList.remove("show");
});

btnSideNavDrugs.addEventListener('click', function(event) {
    divSideNavDrugs.classList.toggle("show");
});

btnSideNavSettings.addEventListener('click', function(event) {
    divSideNavSettings.classList.toggle("show");
});

window.onclick = function(event) {
    // Function hiding the dropdown menu when clicked outside of its area
    if ((!climbUpDOM(event.target, "#myNavbar") && !climbUpDOM(event.target, ".navbar-toggle")) &&
        ((!climbUpDOM(event.target, ".dropdown-content") && !climbUpDOM(event.target, ".dropdown-toggle")))) {
        divTopNav.classList.remove("show");
        divTopNavSettings.classList.remove("show");
        divSideNavDrugs.classList.remove("show");
        divSideNavSettings.classList.remove("show");
    }
}

