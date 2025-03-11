@extends('layouts.app')

@section('content')

<div class="container mt-4">
    <div class="row">
        <!-- Sección Añadir Usuario -->
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Añadir Usuario</h5>
                    <form id="addUserForm">
                        @csrf
                        <div class="mb-2">
                            <input type="text" id="nombre" class="form-control" placeholder="Nombre" required>
                        </div>
                        <div class="mb-2">
                            <input type="text" id="direccion" class="form-control" placeholder="Dirección" required autocomplete="off">
                        </div>
                        <div class="mb-2">
                            <input type="text" id="telefono" class="form-control" placeholder="Teléfono" required>
                        </div>
                        <input type="hidden" id="latitud">
                        <input type="hidden" id="longitud">
                        <input type="hidden" id="email">
                        <button type="submit" class="btn btn-primary w-100">Añadir Usuario</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Sección Mapa -->
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Mapa de Ubicación</h5>
                    <div id="map" style="width: 100%; height: 300px;"></div>
                    <button id="calculateRoute" class="btn btn-success w-100 mt-3">Calcular Ruta Óptima</button>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-3">
        <!-- Sección Lista de Usuarios -->
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Lista de Usuarios</h5>
                    <ul id="userList" class="list-group"></ul>
                </div>
            </div>
        </div>

        <!-- Sección Resultados de la Ruta -->
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Resultados de la Ruta</h5>
                    <div id="routeResults">
                        <ul id="routeDetails" class="list-group"></ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


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
            types: ["establishment", "geocode"], // 🔥 Permite buscar direcciones y negocios
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
            const userList = document.getElementById("userList");
            userList.innerHTML = "";

            markers.forEach(marker => marker.setMap(null));
            markers = [];

            addMarker(origin, "Inicio (Centro de Lima)", "green");
            addMarker(destination, "Destino (Parque de las Aguas)", "blue");

            data.forEach(user => {
                const li = document.createElement("li");
                li.className = "list-group-item d-flex justify-content-between align-items-center";

                li.innerHTML = `
                    <div>
                        <strong>${user.nombre}</strong> <br>
                        📍 ${user.direccion} <br>
                        📞 ${user.telefono}
                    </div>
                    <button class="btn btn-danger btn-sm" onclick="deleteUser(${user.id})">
                        Eliminar
                    </button>
                `;
                userList.appendChild(li);

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
            if (data.length === 0) {
                alert("No hay usuarios registrados.");
                return;
            }

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
                    showRouteDetails(result, data);
                } else {
                    console.error("No se pudo calcular la ruta:", status);
                }
            });
        })
        .catch(error => console.error("Error al obtener usuarios para la ruta:", error));
}

function showRouteDetails(result, users) {
    const routeDetails = document.getElementById("routeDetails");
    routeDetails.innerHTML = "";

    const route = result.routes[0];
    let totalDistance = 0;
    let totalTime = 0;

    // Obtener el orden optimizado de los waypoints
    const waypointOrder = result.routes[0].waypoint_order;

    // Agregar inicio (Centro de Lima)
    const inicioLi = document.createElement("li");
    inicioLi.className = "list-group-item list-group-item-secondary text-center fw-bold";
    inicioLi.innerHTML = `<strong>🟢 Inicio: Centro de Lima</strong>`;
    routeDetails.appendChild(inicioLi);

    // Iterar sobre las etapas de la ruta y mostrar en el orden optimizado
    route.legs.forEach((leg, index) => {
        totalDistance += leg.distance.value;
        totalTime += leg.duration.value;

        let user = { nombre: "Destino Final", direccion: "Parque de las Aguas" };
        
        // Si es un punto intermedio (no el destino final)
        if (index < waypointOrder.length) {
            const userIndex = waypointOrder[index]; // Obtener el índice optimizado
            user = users[userIndex]; // Obtener el usuario correcto
        }

        const li = document.createElement("li");
        li.className = "list-group-item";

        li.innerHTML = `
            <strong>🚶 ${index + 1}. ${user.nombre}</strong> <br>
            📍 <em>${user.direccion}</em> <br>
            📏 Distancia: <strong>${leg.distance.text}</strong> <br>
            ⏳ Tiempo estimado: <strong>${leg.duration.text}</strong>
        `;
        routeDetails.appendChild(li);
    });

    // Agregar destino final
    const destinoLi = document.createElement("li");
    destinoLi.className = "list-group-item list-group-item-primary text-center fw-bold";
    destinoLi.innerHTML = `<strong>🔵 Destino: Parque de las Aguas</strong>`;
    routeDetails.appendChild(destinoLi);

    // Mostrar distancia y tiempo total
    const totalInfo = document.createElement("li");
    totalInfo.className = "list-group-item list-group-item-info text-center fw-bold";
    totalInfo.innerHTML = `
        <strong>📊 Total de la ruta:</strong> <br>
        📏 Distancia: ${(totalDistance / 1000).toFixed(2)} km <br>
        ⏳ Tiempo estimado: ${(totalTime / 60).toFixed(2)} minutos
    `;
    routeDetails.appendChild(totalInfo);
}


    document.getElementById("calculateRoute").addEventListener("click", calculateOptimalRoute);
</script>

<script src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google_maps.api_key') }}&libraries=places&callback=initMap" async defer></script>
@endsection
