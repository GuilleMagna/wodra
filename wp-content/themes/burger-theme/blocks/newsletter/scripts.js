jQuery(document).ready(function($) {

    const html = '<button class="btn btn-primario mt-4 px-2 px-lg-3" type="submit">' +
                    'SUSCRIBIRME AL NEWS' +
                    '<svg class="icon-btn" width="24" height="25" viewBox="0 0 24 25" fill="none" xmlns="http://www.w3.org/2000/svg">'+
                        '<path d="M11 9L14.5 12.5L11 16" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>'+
                        '<path d="M12 22.5C17.5228 22.5 22 18.0228 22 12.5C22 6.97715 17.5228 2.5 12 2.5C6.47715 2.5 2 6.97715 2 12.5C2 18.0228 6.47715 22.5 12 22.5Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>'+
                    '</svg>' +
                '</button>';

    $('.contenido-news .wpcf7-submit.submit_newsletter').replaceWith(html);

});