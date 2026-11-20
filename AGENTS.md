# AGENTS.md · Ventas SDI

Instrucciones para agentes de código (ChatGPT Codex, Claude Code u otros) que trabajen en este repositorio.
**Objetivo: que cualquier cambio respete el diseño actual del sistema.** Si una regla de este archivo
choca con lo que pide una tarea, pregunta antes de romperla.

- Idioma: interfaz, textos, comentarios y mensajes de commit **en español**.
- Proyecto: Laravel (Blade) servido con WAMP. Vistas en `resources/views`, estilos en `public/css/main.css`,
  JS propio en `public/js`.

---

## 1. Cómo está armado el diseño (leer antes de tocar estilos)

| Pieza | Detalle |
|---|---|
| CSS base | `public/css/bootstrap.min.css` es **Bootstrap 4 (tema Lumen)**. No existen `form-select`, `gap-*`, `ms-*`, `fw-*` de Bootstrap 5 salvo las que `main.css` define a mano. |
| JS | Bootstrap **5.3** (bundle por CDN), jQuery, Select2, DataTables, SweetAlert2. |
| Estilos propios | **Todo** va en `public/css/main.css`, que carga al final y sobrescribe a Bootstrap. |
| Layout | `resources/views/maestra.blade.php`. **No tiene `@stack('styles')`**: un `@push('styles')` en una vista **no se carga nunca**. |
| Fuente | **DM Sans local** (`public/fonts/dm-sans`), variable 100–1000. Se usa con `var(--sdi-font)`. |
| Íconos | **Bootstrap Icons** (`bi bi-*`), estilo lineal. No mezclar con Font Awesome en código nuevo. |

### Reglas de oro

1. **No** agregues estilos en línea (`style="..."`) ni bloques `<style>` / `@push('styles')` en las vistas.
   Si hace falta un estilo nuevo, créalo como clase en `main.css`, en una sección con título comentado.
2. **No** cargues fuentes desde Google Fonts ni otro CDN. La única fuente es DM Sans local.
3. **No** cambies colores sueltos: usa las variables de `:root` en `main.css`
   (`--sdi-blue`, `--sdi-blue-dark`, `--sdi-blue-soft`, `--sdi-text`, `--sdi-border`, `--sdi-radius`, `--sdi-focus`,
   `--sdi-control-h`).
4. **No** edites `bootstrap.min.css` salvo para quitar cargas externas.
5. Reutiliza los componentes de la sección 2 antes de inventar uno nuevo.
6. Todo debe verse bien a **ancho de celular** (sin scroll horizontal).

---

## 2. Componentes que ya existen (usarlos tal cual)

### Barra de filtros (label arriba, todo en una fila)
```blade
<form method="get" class="barra-filtros">
    <div class="form-group">
        <label for="campo">Etiqueta</label>
        <select class="form-select" id="campo" name="campo" data-no-select2>…</select>
    </div>
    <div class="barra-filtros__acciones">
        <button type="submit" class="btn btn-catalogo-accion">Filtrar</button>
        <a class="btn btn-outline-secondary" href="…">Limpiar filtros</a>
    </div>
</form>
```
- Filtro por línea/público: `@include('partials.filtro_segmento')`.
- En filtros usa `form-select` + `data-no-select2` (si no, Select2 lo transforma y cambia la altura).

### Estado vacío (lista sin registros)
```blade
@include('partials.estado_vacio', [
    'titulo' => 'Aún no hay planes registrados',
    'mensaje' => 'Cuando crees un plan, aparecerá en este listado.',
    'accionUrl' => route('planes.create'),   // opcional
    'accionTexto' => 'Crear plan',           // opcional
    // 'accionIcono' => null, 'accionClase' => 'btn-outline-secondary'  (opcionales)
])
```
- Siempre centrado y con el ícono de alerta. Dentro de una tabla: `<td colspan="N" class="p-0">@include(...)</td>`.
- Distingue "no hay registros" de "la búsqueda no encontró nada" (ver `clientes/clientes_index.blade.php`).

### Tablas
- Clase `table` (con `mb-0` si va dentro de una card) y `<thead class="table-light">`.
- **Sin hover**: no uses `table-hover` ni agregues efectos al pasar el mouse.
- No uses `table-bordered` ni `table-striped` en tablas nuevas.

### Botones
| Uso | Clase |
|---|---|
| Acción principal | `btn btn-primary` o `btn btn-catalogo-accion` |
| Secundario | `btn btn-outline-primary` |
| Neutro / limpiar / cancelar | `btn btn-outline-secondary` |
| Solo ícono (tablas) | `btn btn-icon btn-…` |

Botones de alternar (seleccionar una opción) usan `aria-pressed="true|false"`.

### Formularios
- Inputs y textareas: `form-control`; selects: `form-select`. Son **planos** (sin sombra) y comparten altura.
- Label siempre **arriba** del campo y unido con `for`/`id`.
- Separa grupos con margen (`mb-3` / `margin-bottom`), nunca dejes botones pegados a un campo.

### Tipografía
- Títulos `h1`–`h6` (o clases `.h1`–`.h6`) ya tienen peso: 800, 750, 700, 700, 650, 600
  (variables `--sdi-peso-h1` … `--sdi-peso-h6`). No les pongas `font-weight` en línea.
- Clases de peso disponibles: `fw-light`, `fw-normal`, `fw-medium`, `fw-semibold`, `fw-bold`, `fw-bolder`.

### Demostración práctica (videos para vendedores)
- Vista `vender/demostracion_video.blade.php` + `public/js/demostraciones-videos.js` + sección
  "DEMOSTRACIÓN PRÁCTICA" de `main.css`.
- Flujo en 3 pasos numerados (`.demo-paso`, `.demo-paso__numero`). El JS depende de los atributos `data-demo-*`:
  no los renombres ni los quites.

---

## 3. Antes de terminar un cambio visual

1. Revisa que no quedaron `style=""`, `<style>` ni `@push('styles')` nuevos en las vistas.
2. Revisa que no agregaste clases de Bootstrap 5 que no existen en Bootstrap 4 (si necesitas una, defínela en
   la sección "Utilidades Bootstrap 5" de `main.css`).
3. Prueba la pantalla en escritorio y a ~375 px de ancho.
4. Si cambiaste `main.css`, recarga con Ctrl+F5 (caché del navegador).
5. No subas archivos de prueba (`public/_prueba*.html`) ni capturas.
