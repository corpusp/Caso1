@extends('layouts.app')

@section('content')
    <h1>Crear Nueva Reserva</h1>

    <form action="{{ route('reservas.store') }}" method="POST" id="formReserva">
        @csrf

        <input type="hidden" name="usuario_id" value="{{ auth()->user()->id }}">

        <label for="tour_id">Tour:</label>
        <select name="tour_id" required>
            @foreach($tours as $tour)
                <option value="{{ $tour->id }}">{{ $tour->nombre }}</option>
            @endforeach
        </select>

        <label for="horario_id">Horario:</label>
        <select name="horario_id" required>
            @foreach($horarios as $horario)
                <option value="{{ $horario->id }}">{{ $horario->hora_salida }}</option>
            @endforeach
        </select>

        <label for="fecha_reserva">Fecha de Reserva:</label>
        <input type="date" name="fecha_reserva" required>

        <hr>

        <label for="direccion">Dirección:</label>
        <input type="text" id="direccion" name="direccion" class="form-control" placeholder="Escriba su dirección" required autocomplete="off">

        <input type="hidden" id="latitud" name="latitud">
        <input type="hidden" id="longitud" name="longitud">

        <button type="submit" class="btn btn-success mt-3">Guardar Reserva</button>
        <div id="mapaDireccion" style="height: 300px; width: 100%; margin-top: 15px;"></div>

    </form>

    <script>
        let map, marker;
    
        function initAutocomplete() {
            const input = document.getElementById("direccion");
    
            // Autocompletado de Google Places
            const autocomplete = new google.maps.places.Autocomplete(input, {
                componentRestrictions: { country: "PE" },
                fields: ["geometry", "formatted_address", "address_components", "types"],
                types: ["establishment"],  // Filtra solo establecimientos como mercados, tiendas, etc.
            });
    
            // Inicializa el mapa en Lima por defecto
            map = new google.maps.Map(document.getElementById("mapaDireccion"), {
                center: { lat: -12.0464, lng: -77.0428 }, // Centro de Lima
                zoom: 12
            });
    
            marker = new google.maps.Marker({
                map: map,
                draggable: false
            });
    
            // Evento cuando se selecciona un lugar
            autocomplete.addListener("place_changed", function () {
                const place = autocomplete.getPlace();
                if (!place.geometry) {
                    alert("No se encontró una ubicación válida.");
                    return;
                }
    
                let esLima = false;
                place.address_components.forEach(component => {
                    if (component.types.includes("administrative_area_level_1") && component.long_name.includes("Lima")) {
                        esLima = true;
                    }
                });
    
                if (!esLima) {
                    alert("Solo se permiten direcciones dentro de Lima.");
                    input.value = "";
                    return;
                }
    
                const location = place.geometry.location;
    
                document.getElementById("direccion").value = place.formatted_address;
                document.getElementById("latitud").value = location.lat();
                document.getElementById("longitud").value = location.lng();
    
                // Centra el mapa y coloca el marcador
                map.setCenter(location);
                map.setZoom(15);
                marker.setPosition(location);
            });
        }
    </script>
    
    <script async defer 
        src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google_maps.api_key') }}&libraries=places&callback=initAutocomplete">
    </script>
    
@endsection
