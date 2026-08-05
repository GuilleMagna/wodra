jQuery(document).ready(function($) {

    const html = '<button class="btn btn-lg btn-outline-primary px-3" type="submit">' +
                    '<span class="text-btn-01 mx-3 text-white">Participar ahora</span>' +
                    '<svg width="20" height="20" viewBox="0 0 15 15" fill="none" xmlns="http://www.w3.org/2000/svg" class="icon-btn ms-2 mb-1">' + 
                        '<g clip-path="url(#clip0_4365_1021)">' +
                            '<path d="M6.875 5.3125L9.0625 7.5L6.875 9.6875" stroke="#25282A" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>' +
                            '<path d="M7.5 13.75C10.9517 13.75 13.75 10.9517 13.75 7.5C13.75 4.04822 10.9517 1.25 7.5 1.25C4.04822 1.25 1.25 4.04822 1.25 7.5C1.25 10.9517 4.04822 13.75 7.5 13.75Z" stroke="" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>' +
                        '</g>' +
                        '<defs>' +
                            '<clipPath id="clip0_4365_1021">' +
                                '<rect width="15" height="15" fill="white"/>' +
                            '</clipPath>' +
                        '</defs>' +
                    '</svg>' +
                '</button>';

    $('.wpcf7-submit.submit_archivo').replaceWith(html);

});