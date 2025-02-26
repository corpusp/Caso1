@extends('layouts.app')

@section('content')
    <h1>Detalles de la Reserva</h1>

    <p><strong>Usuario:</strong> {{ $reserva->usuario->nombre }}</p>
    <p><strong>Tour:</strong> {{ $reserva->tour->nombre }}</p>
    <p><strong>Horario:</strong> {{ $reserva->horario->hora_salida }}</p>
    <p><strong>Fecha:</strong> {{ $reserva->fecha_reserva }}</p>

    <div id="map" style="height: 500px; width: 100%;"></div>

    <script>
        function initMap() {
    const centroLima = { lat: -12.0464, lng: -77.0428 }; // Centro de Lima
    const parqueAguas = { lat: -12.0681, lng: -77.0330 }; // Parque de las Aguas

    const map = new google.maps.Map(document.getElementById("map"), {
        zoom: 13,
        center: centroLima
    });

    const waypoints = [];

    @foreach ($usuarios as $usuario)
        waypoints.push({
            location: new google.maps.LatLng({{ $usuario->latitud }}, {{ $usuario->longitud }}),
            stopover: true
        });

        // Agregar marcador para cada usuario
        new google.maps.Marker({
            position: { lat: {{ $usuario->latitud }}, lng: {{ $usuario->longitud }} },
            map: map,
            title: "{{ $usuario->nombre }}",
            icon: "{{ Auth::id() === $usuario->id ? 'https://maps.google.com/mapfiles/ms/icons/blue-dot.png' : '' }}"
        });
    @endforeach

    const directionsService = new google.maps.DirectionsService();
    const directionsRenderer = new google.maps.DirectionsRenderer({
        map: map,
        suppressMarkers: true // 🔴 Evita los marcadores automáticos de Google
    });

    directionsService.route({
        origin: centroLima,
        destination: parqueAguas,
        waypoints: waypoints,
        optimizeWaypoints: true,
        travelMode: google.maps.TravelMode.DRIVING
    }, function(response, status) {
        if (status === "OK") {
            directionsRenderer.setDirections(response);
        } else {
            console.error("Error al calcular la ruta:", status);
        }
    });

    // Agregar marcador para el origen (Centro de Lima)
    new google.maps.Marker({
        position: centroLima,
        map: map,
        title: "Centro de Lima",
        icon: "https://maps.google.com/mapfiles/ms/icons/green-dot.png" // 🟢 Marcador verde
    });

    // Agregar marcador para el destino (Parque de las Aguas)
    new google.maps.Marker({
        position: parqueAguas,
        map: map,
        title: "Parque de las Aguas",
        icon: "https://maps.google.com/mapfiles/ms/icons/red-dot.png" // 🔴 Marcador rojo
    });
}

    </script>

    <script src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google_maps.api_key') }}&callback=initMap" async defer></script>
@endsection
