<?php

namespace App\Http\Controllers;

use App\ProductoSoftware;
use App\CatalogoSegmentos;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProductosSoftwareController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $productosSoftware = CatalogoSegmentos::filtrar(ProductoSoftware::query(), $request->query('segmento'))->get();
        return view('productos_software.index', ['productosSoftware' => $productosSoftware]);
    }

    public function catalogo(Request $request)
    {
        $consulta = ProductoSoftware::withCount([
            'planes as planes_disponibles_count' => fn ($planes) => $planes->disponible(),
        ]);
        $software = CatalogoSegmentos::filtrar($consulta, $request->query('segmento'))
            ->orderByDesc('estado')->orderBy('nombre')->orderBy('id')->paginate(24)->withQueryString();

        return view('vender.productos_catalogo', compact('software'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('productos_software.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->normalizarTipos($request);
        $validated = $request->validate([
            'nombre' => 'required|string|max:255|unique:productos_software',
            'descripcion' => 'required|string',
            'segmento_catalogo' => CatalogoSegmentos::reglas(),
            'versión' => 'nullable|string|max:50',
            'tipos' => 'required|list|min:1',
            'tipos.*' => ['required', 'string', 'distinct', Rule::in(array_keys(ProductoSoftware::TIPOS))],
            'url_descarga' => 'nullable|url',
            'estado' => 'nullable|boolean'
        ]);

        $validated['estado'] = $request->boolean('estado');
        $validated['tipo'] = $validated['tipos'][0];

        ProductoSoftware::create($validated);

        return redirect()->route('productos-software.index')
            ->with('mensaje', 'Producto software creado exitosamente');
    }

    /**
     * Display the specified resource.
     *
     * @param  ProductoSoftware  $productoSoftware
     * @return \Illuminate\Http\Response
     */
    public function show(ProductoSoftware $productoSoftware)
    {
        return view('productos_software.show', ['productoSoftware' => $productoSoftware]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  ProductoSoftware  $productoSoftware
     * @return \Illuminate\Http\Response
     */
    public function edit(ProductoSoftware $productoSoftware)
    {
        return view('productos_software.edit', ['productoSoftware' => $productoSoftware]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  ProductoSoftware  $productoSoftware
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ProductoSoftware $productoSoftware)
    {
        $this->normalizarTipos($request);
        $validated = $request->validate([
            'nombre' => 'required|string|max:255|unique:productos_software,nombre,' . $productoSoftware->id,
            'descripcion' => 'required|string',
            'segmento_catalogo' => CatalogoSegmentos::reglas(),
            'versión' => 'nullable|string|max:50',
            'tipos' => 'required|list|min:1',
            'tipos.*' => ['required', 'string', 'distinct', Rule::in(array_keys(ProductoSoftware::TIPOS))],
            'url_descarga' => 'nullable|url',
            'estado' => 'nullable|boolean'
        ]);

        $validated['estado'] = $request->boolean('estado');
        $validated['tipo'] = $validated['tipos'][0];

        $productoSoftware->update($validated);

        return redirect()->route('productos-software.index')
            ->with('mensaje', 'Producto software actualizado exitosamente');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  ProductoSoftware  $productoSoftware
     * @return \Illuminate\Http\Response
     */
    public function destroy(ProductoSoftware $productoSoftware)
    {
        $productoSoftware->delete();

        return redirect()->route('productos-software.index')
            ->with('mensaje', 'Producto software eliminado exitosamente');
    }

    private function normalizarTipos(Request $request): void
    {
        if (!$request->has('tipos')) {
            $request->merge(['tipos' => $request->has('tipo') ? [$request->input('tipo')] : []]);
        }
    }
}
