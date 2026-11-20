/**
 * Videos para vendedores: muestra un video a la vez.
 * A la izquierda hay una lista (en celular, un selector) y a la derecha el video elegido.
 */
(() => {
    const contenedor = document.querySelector('[data-videos-lista]');
    if (!contenedor) return;

    const botones = Array.from(contenedor.querySelectorAll('[data-videos-boton]'));
    const paneles = Array.from(contenedor.querySelectorAll('[data-videos-panel]'));
    const selector = contenedor.querySelector('[data-videos-selector]');

    const mostrar = (id, { enfocar = false, actualizarUrl = true } = {}) => {
        id = String(id);
        if (!paneles.some((panel) => panel.dataset.videosPanel === id)) return;

        for (const panel of paneles) {
            const activo = panel.dataset.videosPanel === id;
            if (!activo) {
                for (const video of panel.querySelectorAll('video')) video.pause();
            }
            panel.hidden = !activo;
        }
        for (const boton of botones) {
            boton.setAttribute('aria-pressed', boton.dataset.videosBoton === id ? 'true' : 'false');
        }
        if (selector) selector.value = id;
        if (actualizarUrl) history.replaceState(null, '', '#video-' + id);
        if (enfocar) {
            const titulo = document.getElementById('titulo-video-' + id);
            if (titulo) {
                titulo.setAttribute('tabindex', '-1');
                titulo.focus({ preventScroll: true });
            }
            contenedor.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
    };

    for (const boton of botones) {
        boton.addEventListener('click', () => mostrar(boton.dataset.videosBoton, { enfocar: true }));
    }
    if (selector) {
        selector.addEventListener('change', () => mostrar(selector.value, { enfocar: true }));
    }

    // Permite abrir un video directo con un enlace del tipo #video-12
    const desdeUrl = (location.hash.match(/^#video-(\d+)$/) || [])[1];
    if (desdeUrl) mostrar(desdeUrl, { actualizarUrl: false });
})();
