jQuery(document).ready(function($) {

    $('form.wpcf7-form.invalid').find('.wpcf7-validates-as-required').attr('aria-invalid', 'true');

    const html = '<button type="submit" class="btn btn-secundario px-3">' +
                    'ENVIAR CONSULTA' +
                    '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">' +
                        '<path d="M11 8.5L14.5 12L11 15.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>' +
                        '<path d="M12 22C17.5228 22 22 17.5228 22 12C22 6.47715 17.5228 2 12 2C6.47715 2 2 6.47715 2 12C2 17.5228 6.47715 22 12 22Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>' +
                    '</svg>' +
                '</button>';

    $('.wpcf7-submit').replaceWith(html);

    document.addEventListener('wpcf7submit', function(event) {

        if (event.detail.apiResponse && event.detail.apiResponse.status === 'mail_sent') {
            var form = event.target;
            var nameInput = form.querySelector('[name="your-name"]');
            if (nameInput) {
                var name = encodeURIComponent(nameInput.value);
                window.location.href = '/gracias/?nombre=' + name;
            }
        }
        
    }, false);

});