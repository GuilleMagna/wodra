jQuery(function($) {
        
    var options = {
    useEasing: true,
    useGrouping: true,
    separator: ",",
    };

    var count = $(".count");

    count.each(function (index) {

        var value = $(count[index]).html();

        var countAnimation = new CountUp(count[index], 0, value, 0, 5, options);
        countAnimation.start();
    });

 });