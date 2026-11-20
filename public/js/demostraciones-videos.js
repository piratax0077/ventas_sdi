document.querySelectorAll('[data-demostracion-video]').forEach((demostracion) => {
    const botones = Array.from(demostracion.querySelectorAll('[data-demo-paso]'));
    const panel = demostracion.querySelector('[data-demo-panel]');
    const icono = demostracion.querySelector('[data-demo-icono]');
    const titulo = demostracion.querySelector('[data-demo-titulo]');
    const detalle = demostracion.querySelector('[data-demo-detalle]');
    const beneficio = demostracion.querySelector('[data-demo-beneficio]');
    const ventaja = demostracion.querySelector('[data-demo-ventaja]');
    const botonEjemplo = demostracion.querySelector('[data-demo-ejemplo]');
    const ayuda = demostracion.querySelector('[data-demo-ayuda]');
    const cajaArgumento = demostracion.querySelector('[data-demo-argumento-caja]');
    const argumento = demostracion.querySelector('[data-demo-argumento]');
    const propuesta = demostracion.querySelector('[data-demo-propuesta]');
    let pasoActual = null;

    const marcarBotones = (seleccionado) => {
        botones.forEach((opcion) => {
            const activa = opcion === seleccionado;
            opcion.setAttribute('aria-pressed', String(activa));
            opcion.classList.toggle('btn-primary', activa);
            opcion.classList.toggle('btn-outline-primary', !activa);
        });
    };

    const ocultarArgumento = () => {
        argumento.textContent = '';
        cajaArgumento.hidden = true;
    };

    botones.forEach((boton) => {
        boton.addEventListener('click', () => {
            pasoActual = boton;
            marcarBotones(boton);
            panel.dataset.estado = 'activo';
            icono.className = 'bi bi-check-circle demo-practica__panel-icono';
            titulo.textContent = boton.dataset.titulo;
            detalle.textContent = boton.dataset.detalle;
            ventaja.textContent = boton.dataset.ventaja;
            beneficio.hidden = false;
            botonEjemplo.disabled = false;
            ayuda.hidden = true;
            ocultarArgumento();
        });
    });

    botonEjemplo.addEventListener('click', () => {
        if (!pasoActual) return;
        argumento.textContent = 'Puedes explicarlo así. ' + pasoActual.dataset.ventaja
            + ' ¿Te gustaría que lo revisemos con un ejemplo de tu día a día?';
        cajaArgumento.hidden = false;
    });

    demostracion.querySelector('[data-demo-reiniciar]').addEventListener('click', () => {
        pasoActual = null;
        marcarBotones(null);
        panel.dataset.estado = 'vacio';
        icono.className = 'bi bi-hand-index demo-practica__panel-icono';
        titulo.textContent = titulo.dataset.inicial;
        detalle.textContent = detalle.dataset.inicial;
        ventaja.textContent = '';
        beneficio.hidden = true;
        botonEjemplo.disabled = true;
        ayuda.hidden = false;
        propuesta.value = '';
        ocultarArgumento();
    });
});
