@php
    $versionVideo = is_file(public_path($video->video)) ? filemtime(public_path($video->video)) : $video->updated_at?->timestamp;
    $urlVideo = asset($video->video).'?v='.$versionVideo;
    $versionSubtitulos = $video->subtitulos && is_file(public_path($video->subtitulos))
        ? filemtime(public_path($video->subtitulos)) : $versionVideo;
@endphp
<video class="w-100 rounded bg-dark" controls playsinline preload="metadata"
       @if($video->portada) poster="{{ asset($video->portada) }}" @endif aria-label="{{ $video->titulo }}">
    <source src="{{ $urlVideo }}" type="video/mp4">
    @if($video->subtitulos)
        <track kind="captions" label="Español" srclang="es" src="{{ asset($video->subtitulos).'?v='.$versionSubtitulos }}">
    @endif
    <a href="{{ $urlVideo }}">Abrir video</a>
</video>
