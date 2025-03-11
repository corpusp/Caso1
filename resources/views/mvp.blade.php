@extends('layouts.app')

@section('content')

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
                        li.textContent = `${user.nombre} - ${user.email}`;
                        userList.appendChild(li);
                    });
                })
                .catch(error => console.error("Error al cargar usuarios:", error));
        }

        loadUsers();
    });
</script>

@endsection
