var today = new Date();
var dd = today.getDate();
var mm = today.getMonth()+1;
var yyyy = today.getFullYear();

if(dd<10){
    dd='0'+dd
}
if(mm<10){
    mm='0'+mm
}

today = yyyy+'-'+mm+'-'+dd;

document.getElementById("utd-t").setAttribute("max", today);
document.getElementById("utd-f").setAttribute("max", today);
document.getElementById("tout-t").setAttribute("min", today);
document.getElementById("tout-f").setAttribute("min", today);