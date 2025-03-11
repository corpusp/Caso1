@extends('layouts.app')

@section('content')
    <h1>Gestión de Turistas y Rutas</h1>
    
    <!-- Formulario para añadir turistas -->
    <form id="addTouristForm">
        <input type="text" id="nombre" placeholder="Nombre" required>
        <input type="text" id="direccion" placeholder="Dirección" required>
        <input type="text" id="telefono" placeholder="Teléfono" required>
        <button type="submit">Añadir Turista</button>
    </form>
    
    <!-- Lista de turistas -->
    <h2>Lista de Turistas</h2>
    <ul id="touristList"></ul>
    
    <!-- Botón para calcular ruta -->
    <button id="calculateRoute">Calcular Ruta</button>
    
    <!-- Contenedor del mapa -->
    <div id="map" style="height: 500px; width: 100%;"></div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const map = new google.maps.Map(document.getElementById("map"), {
                zoom: 13,
                center: { lat: -12.0464, lng: -77.0428 }
            });

            const directionsService = new google.maps.DirectionsService();
            const directionsRenderer = new google.maps.DirectionsRenderer({ map: map });
            const touristList = document.getElementById("touristList");

            function loadTourists() {
                fetch("/api/tourists")
                    .then(response => response.json())
                    .then(data => {
                        touristList.innerHTML = "";
                        data.forEach(tourist => {
                            const li = document.createElement("li");
                            li.textContent = `${tourist.nombre} - ${tourist.direccion} - ${tourist.telefono}`;
                            const deleteBtn = document.createElement("button");
                            deleteBtn.textContent = "Eliminar";
                            deleteBtn.onclick = () => deleteTourist(tourist.id);
                            li.appendChild(deleteBtn);
                            touristList.appendChild(li);
                        });
                    });
            }

            function addTourist(event) {
                event.preventDefault();
                const nombre = document.getElementById("nombre").value;
                const direccion = document.getElementById("direccion").value;
                const telefono = document.getElementById("telefono").value;

                fetch("/api/tourists", {
                    method: "POST",
                    headers: { "Content-Type": "application/json" },
                    body: JSON.stringify({ nombre, direccion, telefono })
                }).then(() => {
                    loadTourists();
                    document.getElementById("addTouristForm").reset();
                });
            }

            function deleteTourist(id) {
                fetch(`/api/tourists/${id}`, { method: "DELETE" }).then(loadTourists);
            }

            function calculateRoute() {
                fetch("/api/calculate-route")
                    .then(response => response.json())
                    .then(routeData => {
                        directionsRenderer.setDirections(routeData);
                    });
            }

            document.getElementById("addTouristForm").addEventListener("submit", addTourist);
            document.getElementById("calculateRoute").addEventListener("click", calculateRoute);

            loadTourists();
        });
    </script>

    <script src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google_maps.api_key') }}&callback=initMap" async defer></script>

@endsection
