@extends('layouts.app')

@section('content')
<h2>Añadir Usuario</h2>
<form id="addUserForm">
    @csrf
    <input type="text" id="nombre" placeholder="Nombre" required>
    <input type="text" id="direccion" placeholder="Dirección" required>
    <input type="text" id="telefono" placeholder="Telefono" required>
    <input type="hidden" id="latitud">
    <input type="hidden" id="longitud">
    <input type="hidden" id="email">
    <button type="submit">Añadir Usuario</button>
</form>

<h2>Lista de Usuarios</h2>
<ul id="userList"></ul>

<script>
    function initAutocomplete() {
        const input = document.getElementById("direccion");
        const autocomplete = new google.maps.places.Autocomplete(input);
        autocomplete.addListener("place_changed", function () {
            const place = autocomplete.getPlace();
            if (place.geometry) {
                document.getElementById("latitud").value = place.geometry.location.lat();
                document.getElementById("longitud").value = place.geometry.location.lng();
            }
        });
    }

    document.addEventListener("DOMContentLoaded", function() {
        function loadUsers() {
            fetch("/users")
                .then(response => response.json())
                .then(data => {
                    console.log(data); // Para depuración
                    const userList = document.getElementById("userList");
                    userList.innerHTML = "";
                    data.forEach(user => {
                        const li = document.createElement("li");
                        li.textContent = `${user.nombre} - ${user.telefono} `;

                        const deleteButton = document.createElement("button");
                        deleteButton.textContent = "Eliminar";
                        deleteButton.onclick = function () {
                            deleteUser(user.id);
                        };

                        li.appendChild(deleteButton);
                        userList.appendChild(li);
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
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                }
            })
            .then(response => response.json())
            .then(() => loadUsers())
            .catch(error => console.error("Error al eliminar usuario:", error));
        }

        document.getElementById("addUserForm").addEventListener("submit", addUser);
        loadUsers();
    });
</script>

<script src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google_maps.api_key') }}&libraries=places&callback=initAutocomplete" async defer></script>

@endsection
