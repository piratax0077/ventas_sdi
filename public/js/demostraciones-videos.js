document.querySelectorAll('[data-demostracion-video]').forEach((demostracion) => {
    const botones = Array.from(demostracion.querySelectorAll('[data-demo-paso]'));
    const titulo = demostracion.querySelector('[data-demo-titulo]');
    const detalle = demostracion.querySelector('[data-demo-detalle]');
    const beneficio = demostracion.querySelector('[data-demo-beneficio]');
    const ventaja = demostracion.querySelector('[data-demo-ventaja]');
    const argumento = demostracion.querySelector('[data-demo-argumento]');
    const propuesta = demostracion.querySelector('[data-demo-propuesta]');
    let pasoActual = null;

    botones.forEach((boton) => {
        boton.addEventListener('click', () => {
            pasoActual = boton;
            botones.forEach((opcion) => {
                const activa = opcion === boton;
                opcion.setAttribute('aria-pressed', String(activa));
                opcion.classList.toggle('btn-primary', activa);
                opcion.classList.toggle('btn-outline-primary', !activa);
            });
            titulo.textContent = boton.dataset.titulo;
            detalle.textContent = boton.dataset.detalle;
            ventaja.textContent = boton.dataset.ventaja;
            beneficio.hidden = false;
            argumento.textContent = '';
        });
    });

    demostracion.querySelector('[data-demo-ejemplo]').addEventListener('click', () => {
        argumento.textContent = pasoActual
            ? 'Puedes explicarlo así. ' + pasoActual.dataset.ventaja + ' ¿Te gustaría que lo revisemos con un ejemplo de tu día a día?'
            : 'Elige primero una acción para ver un argumento relacionado con su ventaja.';
    });

    demostracion.querySelector('[data-demo-reiniciar]').addEventListener('click', () => {
        pasoActual = null;
        botones.forEach((boton) => {
            boton.setAttribute('aria-pressed', 'false');
            boton.classList.remove('btn-primary');
            boton.classList.add('btn-outline-primary');
        });
        titulo.textContent = 'Selecciona una acción para practicar';
        detalle.textContent = '';
        ventaja.textContent = '';
        beneficio.hidden = true;
        argumento.textContent = '';
        propuesta.value = '';
    });
});
