@extends('layouts.app')

@section('content')
<h2>Añadir Usuario</h2>
<form id="addUserForm">
    @csrf
    <input type="text" id="nombre" placeholder="Nombre" required>
    <input type="text" id="direccion" placeholder="Dirección" required autocomplete="off"> <!-- 🔥 Evita autocompletado del navegador -->
    <input type="text" id="telefono" placeholder="Teléfono" required>
    <input type="hidden" id="latitud">
    <input type="hidden" id="longitud">
    <input type="hidden" id="email">
    <button type="submit">Añadir Usuario</button>
</form>

<h2>Lista de Usuarios</h2>
<ul id="userList"></ul>

<h2>Mapa de Ubicación</h2>
<div id="map" style="width: 100%; height: 500px;"></div>
<button id="calculateRoute">Calcular Ruta Óptima</button>

<script>
    let map, directionsService, directionsRenderer, autocomplete;
    const origin = { lat: -12.0464, lng: -77.0428 };
    const destination = { lat: -12.0681, lng: -77.0330 };
    let markers = [];

    function initMap() {
        map = new google.maps.Map(document.getElementById("map"), {
            center: origin,
            zoom: 13,
            disableDefaultUI: true
        });

        directionsService = new google.maps.DirectionsService();
        directionsRenderer = new google.maps.DirectionsRenderer({
            suppressMarkers: true // 🔥 Elimina los marcadores automáticos
        });

        directionsRenderer.setMap(map);

        addMarker(origin, "Inicio (Centro de Lima)", "green");
        addMarker(destination, "Destino (Parque de las Aguas)", "blue");

        loadUsers();
        initAutocomplete(); // 🔥 Aseguramos que se inicialice después de que el mapa esté listo
    }

    function initAutocomplete() {
        const input = document.getElementById("direccion");
        autocomplete = new google.maps.places.Autocomplete(input, {
            types: ["geocode"], // 🔥 Filtrar solo direcciones
            componentRestrictions: { country: "PE" } // Opcional: restringir a Perú
        });

        autocomplete.addListener("place_changed", function () {
            const place = autocomplete.getPlace();
            if (place.geometry) {
                document.getElementById("latitud").value = place.geometry.location.lat();
                document.getElementById("longitud").value = place.geometry.location.lng();
                console.log("Ubicación seleccionada:", place.formatted_address);
            } else {
                console.warn("No se encontró una ubicación válida.");
            }
        });
    }

    function addMarker(position, title, color = "red") {
        const marker = new google.maps.Marker({
            position,
            map,
            title,
            icon: { url: `http://maps.google.com/mapfiles/ms/icons/${color}-dot.png` }
        });
        markers.push(marker);
    }

    function loadUsers() {
        fetch("/users")
            .then(response => response.json())
            .then(data => {
                document.getElementById("userList").innerHTML = "";
                markers.forEach(marker => marker.setMap(null));
                markers = [];

                addMarker(origin, "Inicio (Centro de Lima)", "green");
                addMarker(destination, "Destino (Parque de las Aguas)", "blue");

                data.forEach(user => {
                    const li = document.createElement("li");
                    li.innerHTML = `${user.nombre} - ${user.telefono} 
                                    <button onclick="deleteUser(${user.id})">Eliminar</button>`;
                    document.getElementById("userList").appendChild(li);

                    if (user.latitud && user.longitud) {
                        addMarker({ lat: parseFloat(user.latitud), lng: parseFloat(user.longitud) }, user.nombre);
                    }
                });
            })
            .catch(error => console.error("Error al cargar usuarios:", error));
    }

    function addUser(event) {
        event.preventDefault();
        const nombre = document.getElementById("nombre").value;
        const direccion = document.getElementById("direccion").value;
        const telefono = document.getElementById("telefono").value;
        const email = `${nombre.replace(/\s+/g, '').toLowerCase()}@gmail.com`;
        const latitud = document.getElementById("latitud").value;
        const longitud = document.getElementById("longitud").value;

        if (!latitud || !longitud) {
            alert("Por favor, selecciona una dirección válida del autocompletado.");
            return;
        }

        fetch("/users", {
            method: "POST",
            headers: { 
                "Content-Type": "application/json", 
                "X-CSRF-TOKEN": "{{ csrf_token() }}" 
            },
            body: JSON.stringify({ nombre, email, telefono, direccion, latitud, longitud })
        })
        .then(response => response.json())
        .then(() => {
            loadUsers();
            document.getElementById("addUserForm").reset();
        })
        .catch(error => console.error("Error al añadir usuario:", error));
    }

    function deleteUser(userId) {
        fetch(`/users/${userId}`, {
            method: "DELETE",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content")
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error("Error al eliminar usuario");
            }
            return response.json();
        })
        .then(() => loadUsers())
        .catch(error => console.error("Error al eliminar usuario:", error));
    }


    document.getElementById("addUserForm").addEventListener("submit", addUser);

    

    function calculateOptimalRoute() {
        fetch("/users")
            .then(response => response.json())
            .then(data => {
                const waypoints = data.map(user => ({
                    location: new google.maps.LatLng(user.latitud, user.longitud),
                    stopover: true
                }));

                const request = {
                    origin: origin,
                    destination: destination,
                    waypoints: waypoints,
                    travelMode: google.maps.TravelMode.DRIVING,
                    optimizeWaypoints: true
                };

                directionsService.route(request, function(result, status) {
                    if (status === google.maps.DirectionsStatus.OK) {
                        directionsRenderer.setDirections(result);
                    } else {
                        console.error("No se pudo calcular la ruta:", status);
                    }
                });
            })
            .catch(error => console.error("Error al obtener usuarios para la ruta:", error));
    }

    document.getElementById("calculateRoute").addEventListener("click", calculateOptimalRoute);
</script>

<script src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google_maps.api_key') }}&libraries=places&callback=initMap" async defer></script>
@endsection
