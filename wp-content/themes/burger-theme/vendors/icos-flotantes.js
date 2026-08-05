function scrollFunction() {
	if (document.body.scrollTop > 50 || document.documentElement.scrollTop > 50) {
		document.getElementById("icos-flotantes").style.marginRight = "0px";

	} else {
		document.getElementById("icos-flotantes").style.marginRight = "-100px";
	}
}

window.onscroll = function() {scrollFunction()};