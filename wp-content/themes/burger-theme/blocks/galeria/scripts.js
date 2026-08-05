
function burgerInitGaleriaThumbs(section) {

    const mainImage = section.querySelector('.main-col .carousel-inner');
    const thumbsCol = section.querySelector('.thumbs-col');
    const container = section.querySelector('.thumbs-container');
    const thumbs = container ? container.querySelector('.carousel-indicators') : null;
    const btnUp = thumbsCol ? thumbsCol.querySelector('.thumb-nav-up') : null;
    const btnDown = thumbsCol ? thumbsCol.querySelector('.thumb-nav-down') : null;

    if (!mainImage || !container || !thumbs || !btnUp || !btnDown) {
        return;
    }

    let scrollPos = 0;
    const step = 100;

    function layout() {

        // Debajo de lg las miniaturas pasan a fila horizontal, sin flechas.
        if (window.innerWidth < 992) {
            btnUp.classList.add('thumb-nav-hidden');
            btnDown.classList.add('thumb-nav-hidden');
            container.style.maxHeight = '';
            thumbs.style.transform = '';
            return;
        }

        const availableHeight = mainImage.getBoundingClientRect().height - btnUp.offsetHeight - btnDown.offsetHeight;
        container.style.maxHeight = availableHeight + 'px';

        const maxScroll = Math.max(0, thumbs.scrollHeight - availableHeight);
        const needsScroll = maxScroll > 1;

        btnUp.classList.toggle('thumb-nav-hidden', !needsScroll);
        btnDown.classList.toggle('thumb-nav-hidden', !needsScroll);

        if (!needsScroll) {
            scrollPos = 0;
            thumbs.style.transform = 'translateY(0)';
            return;
        }

        scrollPos = Math.max(0, Math.min(scrollPos, maxScroll));
        thumbs.style.transform = `translateY(-${scrollPos}px)`;

        const atStart = scrollPos <= 0;
        const atEnd = scrollPos >= maxScroll;

        btnUp.classList.toggle('opacity-50', atStart);
        btnUp.disabled = atStart;

        btnDown.classList.toggle('opacity-50', atEnd);
        btnDown.disabled = atEnd;
    }

    btnUp.addEventListener('click', () => {
        scrollPos -= step;
        layout();
    });

    btnDown.addEventListener('click', () => {
        scrollPos += step;
        layout();
    });

    // Cálculo inicial siempre síncrono: el ResizeObserver no garantiza
    // disparar su callback de inmediato, así que no podemos depender solo de él.
    layout();

    // Además, vigila el tamaño REAL de la imagen principal (aspect-ratio,
    // carga de fuentes, cambio de breakpoint, lo que sea) y recalcula
    // apenas cambie — evita que la altura medida quede vieja.
    if (window.ResizeObserver) {
        new ResizeObserver(layout).observe(mainImage);
    } else {
        window.addEventListener('resize', layout);
    }
}

document.querySelectorAll('.galeria-block').forEach(burgerInitGaleriaThumbs);