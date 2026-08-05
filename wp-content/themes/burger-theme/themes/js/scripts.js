jQuery(document).ready(function($) {

    $(window).scroll(function() {

        if ($(window).scrollTop() > $(window).height() / 4) {
            $("#fixedNav").css({
                "background-color": "var(--dark)",
            });
            //$("#logo-dark").addClass('d-none');
            //$("#logo-light").removeClass('d-none');
        } else {
            $("#fixedNav").css({
                "background-color": "transparent",
            });
            //$("#logo-dark").removeClass('d-none');
            //$("#logo-light").addClass('d-none');
        }

    });

});

function openNav() {
    document.getElementById("mySidenav").style.width = "250px";
}

function closeNav() {
    document.getElementById("mySidenav").style.width = "0";
}