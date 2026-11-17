<video class="w-100 rounded bg-dark" controls playsinline preload="metadata"
       @if($video->portada) poster="{{ asset($video->portada) }}" @endif aria-label="{{ $video->titulo }}">
    <source src="{{ asset($video->video) }}" type="video/mp4">
    @if($video->subtitulos)
        <track kind="captions" label="Español" srclang="es" src="{{ asset($video->subtitulos) }}">
    @endif
    <a href="{{ asset($video->video) }}">Abrir video</a>
</video>
