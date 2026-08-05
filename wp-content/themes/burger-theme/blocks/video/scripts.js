jQuery(document).ready(function($) {

    $('.video-container').on('click', function(ev) {

            var youtubeId = $(this).data('youtube');
            $('.video-overlay').fadeOut('slow');
            var iframe = document.createElement( "iframe" );
            iframe.setAttribute( "frameborder", "0" );
            iframe.setAttribute( "allowfullscreen", "" );
            iframe.setAttribute( "style", "width: 100%; height: 100%;" );
            iframe.setAttribute( "src", "https://www.youtube.com/embed/"+ youtubeId +"?rel=0&controls=1&showinfo=0&autoplay=1" );
            this.innerHTML = "";
            this.appendChild( iframe );
            
    });

});