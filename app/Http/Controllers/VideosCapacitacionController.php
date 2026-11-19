<?php

namespace App\Http\Controllers;

use App\VideoCapacitacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Throwable;

class VideosCapacitacionController extends Controller
{
    public function catalogo(Request $request)
    {
        $linea = $this->lineaSeleccionada($request);
        $videos = VideoCapacitacion::where('linea', $linea)->orderBy('orden')->orderBy('id')->get();
        return view('vender.videos', compact('videos', 'linea'));
    }

    public function index(Request $request)
    {
        $linea = $this->lineaSeleccionada($request);
        $videos = VideoCapacitacion::where('linea', $linea)->orderBy('orden')->orderBy('id')->paginate(20)->withQueryString();
        return view('administracion.videos.index', compact('videos', 'linea'));
    }

    private function lineaSeleccionada(Request $request): string
    {
        return $request->query('linea') === 'veterchile' ? 'veterchile' : 'medichile';
    }

    public function show(VideoCapacitacion $video)
    {
        return view('administracion.videos.show', compact('video'));
    }

    public function edit(VideoCapacitacion $video)
    {
        return view('administracion.videos.edit', compact('video'));
    }

    public function update(Request $request, VideoCapacitacion $video)
    {
        $datos = $request->validate([
            'titulo' => 'required|string|max:160',
            'descripcion' => 'nullable|string|max:2000',
            'video' => 'nullable|file|mimetypes:video/mp4|max:51200',
            'portada' => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
            'subtitulos' => 'nullable|file|extensions:vtt|max:256',
        ]);

        if ($request->hasFile('subtitulos')) {
            $encabezado = file_get_contents($request->file('subtitulos')->getRealPath(), false, null, 0, 32);
            if (!preg_match('/^(?:\xEF\xBB\xBF)?WEBVTT(?:[ \t\r\n]|$)/', $encabezado ?: '')) {
                throw ValidationException::withMessages(['subtitulos' => 'El archivo debe tener el formato WebVTT.']);
            }
        }

        $nuevos = [];
        try {
            foreach (['video', 'portada', 'subtitulos'] as $campo) {
                if (!$request->hasFile($campo)) {
                    continue;
                }
                $archivo = $request->file($campo);
                $extension = match ($campo) {
                    'video' => 'mp4',
                    'subtitulos' => 'vtt',
                    default => $archivo->extension(),
                };
                $ruta = $archivo->storeAs('videos-capacitacion', Str::uuid().'.'.$extension, 'public');
                if (!$ruta) {
                    throw ValidationException::withMessages([$campo => 'No se pudo guardar el archivo.']);
                }
                $nuevos[$campo] = 'storage/'.$ruta;
            }

            $reemplazados = DB::transaction(function () use ($video, $datos, $nuevos) {
                $actual = VideoCapacitacion::lockForUpdate()->findOrFail($video->id);
                $archivos = array_key_exists('video', $nuevos)
                    ? $nuevos + ['portada' => null, 'subtitulos' => null]
                    : $nuevos;
                $anteriores = array_intersect_key($actual->getAttributes(), $archivos);
                $actual->update(['titulo' => $datos['titulo'], 'descripcion' => $datos['descripcion'] ?? null] + $archivos);

                return $anteriores;
            });
        } catch (Throwable $error) {
            $this->borrarCargas($nuevos);
            throw $error;
        }
        $this->borrarCargas($reemplazados);

        return redirect()->route('administracion.videos.show', $video)->with('mensaje', 'Video actualizado.');
    }

    public function destroy(VideoCapacitacion $video)
    {
        $eliminados = DB::transaction(function () use ($video) {
            $actual = VideoCapacitacion::lockForUpdate()->findOrFail($video->id);
            $archivos = [$actual->video, $actual->portada, $actual->subtitulos];
            $actual->delete();

            return $archivos;
        });
        $this->borrarCargas($eliminados);

        return redirect()->route('administracion.videos.index', $video->linea === 'veterchile' ? ['linea' => 'veterchile'] : [])
            ->with('mensaje', 'Video retirado de la capacitación.');
    }

    private function borrarCargas(array $archivos): void
    {
        foreach ($archivos as $ruta) {
            if (is_string($ruta) && str_starts_with($ruta, 'storage/videos-capacitacion/')) {
                Storage::disk('public')->delete(Str::after($ruta, 'storage/'));
            }
        }
    }
}
