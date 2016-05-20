'use strict';

window.onclick = function(event) {
	// Function hiding the dropdown menu when clicked outside of its area
    if ((!climbUpDOM(event.target, "#myNavbar") && !climbUpDOM(event.target, ".navbar-toggle")) &&
        ((!climbUpDOM(event.target, ".dropdown-content") && !climbUpDOM(event.target, ".dropdown-toggle")))) {
        divTopNav.classList.remove("show");
        divSideNavSettings.classList.remove("show");
    }
}
