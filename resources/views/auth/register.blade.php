@extends('layouts.app')

@section('content')
    <h2>Registro</h2>

    <form action="{{ route('register') }}" method="POST">
        @csrf
        <label>Nombre:</label>
        <input type="text" name="nombre" required>

        <label>Email:</label>
        <input type="email" name="email" required>

        <label>Teléfono:</label>
        <input type="text" name="telefono" required>

        <label>Dirección:</label>
        <input type="text" id="direccion" name="direccion" required placeholder="Ingresa tu dirección...">

        <input type="hidden" id="latitud" name="latitud">
        <input type="hidden" id="longitud" name="longitud">

        <div id="map" style="height: 300px; width: 100%; margin-top: 10px;"></div>

        <label>Contraseña:</label>
        <input type="password" name="password" required>

        <label>Confirmar contraseña:</label>
        <input type="password" name="password_confirmation" required>

        <button type="submit">Registrarse</button>
    </form>

    <p>¿Ya tienes cuenta? <a href="{{ route('login') }}">Inicia sesión</a></p>

    {{-- Google Maps API Script --}}
    <script>
        let map, marker, autocomplete;

        function initMap() {
            const defaultLocation = { lat: -12.0464, lng: -77.0428 }; // Ubicación inicial (Lima, Perú)

            map = new google.maps.Map(document.getElementById("map"), {
                center: defaultLocation,
                zoom: 14,
            });

            marker = new google.maps.Marker({
                position: defaultLocation,
                map: map,
                draggable: true,
            });

            // Autocompletado de direcciones
            autocomplete = new google.maps.places.Autocomplete(document.getElementById('direccion'));
            autocomplete.bindTo('bounds', map);

            // Evento: Al seleccionar una dirección en el autocompletado
            autocomplete.addListener('place_changed', () => {
                const place = autocomplete.getPlace();
                if (!place.geometry) return;

                map.setCenter(place.geometry.location);
                map.setZoom(15);
                marker.setPosition(place.geometry.location);

                actualizarUbicacion(place.geometry.location.lat(), place.geometry.location.lng());
            });

            // Evento: Al mover el marcador manualmente
            marker.addListener('dragend', () => {
                const position = marker.getPosition();
                actualizarUbicacion(position.lat(), position.lng());
            });

            // Evento: Al hacer clic en el mapa, mover el marcador
            map.addListener('click', (e) => {
                marker.setPosition(e.latLng);
                actualizarUbicacion(e.latLng.lat(), e.latLng.lng());
            });
        }

        // Función para actualizar los valores de dirección y coordenadas
        function actualizarUbicacion(lat, lng) {
            document.getElementById('latitud').value = lat;
            document.getElementById('longitud').value = lng;

            // Obtener dirección con Geocoder
            const geocoder = new google.maps.Geocoder();
            geocoder.geocode({ location: { lat, lng } }, (results, status) => {
                if (status === 'OK' && results[0]) {
                    document.getElementById('direccion').value = results[0].formatted_address;
                }
            });
        }
    </script>

    {{-- Cargar Google Maps API con Places --}}
    <script src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google_maps.api_key') }}&libraries=places&callback=initMap" async defer></script>
@endsection
