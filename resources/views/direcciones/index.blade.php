@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Ingrese su Dirección</h5>
                    <form id="direccionForm">
                        @csrf
                        <div class="mb-3">
                            <input type="text" id="direccion" class="form-control" placeholder="Escriba su dirección" required autocomplete="off">
                        </div>
                        <input type="hidden" id="latitud" name="latitud">
                        <input type="hidden" id="longitud" name="longitud">
                        <button type="submit" class="btn btn-primary w-100">Guardar Dirección</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function initAutocomplete() {
        let input = document.getElementById("direccion");
        let autocomplete = new google.maps.places.Autocomplete(input, {
            componentRestrictions: { country: "PE" }, // Restringir a Perú
            fields: ["geometry", "formatted_address", "address_components"],
            types: ["geocode"], // Filtrar solo direcciones
        });

        autocomplete.addListener("place_changed", function () {
            let place = autocomplete.getPlace();
            if (!place.geometry) {
                alert("No se encontró una ubicación válida.");
                return;
            }

            // Verificar si la dirección es en Lima
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

            // Asignar dirección completa y coordenadas
            input.value = place.formatted_address;
            document.getElementById("latitud").value = place.geometry.location.lat();
            document.getElementById("longitud").value = place.geometry.location.lng();
        });
    }

    document.addEventListener("DOMContentLoaded", () => {
        document.getElementById("direccionForm").addEventListener("submit", function(event) {
            event.preventDefault();

            let direccion = document.getElementById("direccion").value;
            let latitud = document.getElementById("latitud").value;
            let longitud = document.getElementById("longitud").value;

            if (!direccion || !latitud || !longitud) {
                alert("Por favor, ingrese una dirección válida.");
                return;
            }

            fetch("{{ route('direcciones.store') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content")
                },
                body: JSON.stringify({
                    direccion: direccion,
                    latitud: latitud,
                    longitud: longitud
                })
            })
            .then(response => response.json())
            .then(data => {
                alert(data.message);
            })
            .catch(error => {
                console.error("Error:", error);
            });
        });
    });
</script>

<!-- Cargar Google Maps API -->
<script async defer 
    src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google_maps.api_key') }}&libraries=places&callback=initAutocomplete">
</script>

@endsection
