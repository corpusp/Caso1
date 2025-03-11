@extends('layouts.app')

@section('content')
<h2>Añadir Usuario</h2>
<form id="addUserForm">
    @csrf
    <input type="text" id="nombre" placeholder="Nombre" required>
    <input type="email" id="email" placeholder="Email" required>
    <input type="text" id="telefono" placeholder="Telefono" required>
    <button type="submit">Añadir Usuario</button>
</form>

<h2>Lista de Usuarios</h2>
<ul id="userList"></ul>

<script>
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
                        li.textContent = `${user.nombre} - ${user.telefono}`;
                        userList.appendChild(li);
                    });
                })
                .catch(error => console.error("Error al cargar usuarios:", error));
        }

        function addUser(event) {
            event.preventDefault();
            const nombre = document.getElementById("nombre").value;
            const email = document.getElementById("email").value;
            const telefono = document.getElementById("telefono").value;

            fetch("/users", {
                method: "POST",
                headers: { 
                "Content-Type": "application/json", 
                "X-CSRF-TOKEN": "{{ csrf_token() }}"  // Laravel requiere esto
            },
                body: JSON.stringify({ nombre, email, telefono })
            })
            .then(response => response.json())
            .then(() => {
                loadUsers();
                document.getElementById("addUserForm").reset();
            })
            .catch(error => console.error("Error al añadir usuario:", error));
        }

        document.getElementById("addUserForm").addEventListener("submit", addUser);
        loadUsers();
    });
</script>

@endsection
