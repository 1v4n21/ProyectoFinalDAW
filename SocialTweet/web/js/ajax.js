//Funcion al dar me gusta
function darLike(postId, userId, event) {
    fetch(`index.php?accion=darLike&postId=${postId}&userId=${userId}`, {
        method: 'POST',
        credentials: 'same-origin',
        headers: {
            'Content-Type': 'application/json',
        },
    })
        .then(response => response.json())
        .then(data => {
            // Actualiza la interfaz de usuario según la respuesta del servidor
            const likeIcon = event.target;
            const likeCountElement = likeIcon.nextElementSibling; // Obtiene el siguiente elemento (en este caso, el <span>)

            if (data.liked) {
                likeIcon.classList.remove('fa-regular');
                likeIcon.classList.add('fa-solid');
            } else {
                likeIcon.classList.remove('fa-solid');
                likeIcon.classList.add('fa-regular');
            }

            likeCountElement.textContent = data.likeCount;
        })
        .catch(error => console.error('Error al realizar la solicitud fetch:', error));
}

//Funcion al guardar la publicacion
function darGuardado(postId, userId, event) {
    fetch(`index.php?accion=darGuardado&postId=${postId}&userId=${userId}`, {
        method: 'POST',
        credentials: 'same-origin',
        headers: {
            'Content-Type': 'application/json',
        },
    })
        .then(response => response.json())
        .then(data => {
            // Actualiza la interfaz de usuario según la respuesta del servidor
            const saveIcon = event.target;
            const saveCountElement = saveIcon.nextElementSibling; // Obtiene el siguiente elemento (en este caso, el <span>)

            if (data.saved) {
                saveIcon.classList.remove('fa-regular');
                saveIcon.classList.add('fa-solid');
            } else {
                saveIcon.classList.remove('fa-solid');
                saveIcon.classList.add('fa-regular');
            }

            saveCountElement.textContent = data.saveCount;
        })
        .catch(error => console.error('Error al realizar la solicitud fetch:', error));
}

//Funcion para borrar publicación
function borrarPost(postId) {
    fetch(`borrarPost?postId=${postId}`, {
        method: 'POST',
        credentials: 'same-origin',
        headers: {
            'Content-Type': 'application/json',
        },
    })
        .then(response => response.json())
        .then(data => {
            // Verificamos el estado de la respuesta
            if (data.respuesta === "ok") {
                // Encuentra el contenedor del post por su ID y elimínalo
                var postContainer = document.querySelector(`div[data-id="${postId}"]`);
                if (postContainer) {
                    postContainer.remove();
                } else {
                    console.error("No se encontró el contenedor del post");
                }
            } else {
                // Algo salió mal
                console.error("Error al eliminar la publicación");
            }
        })
        .catch(error => console.error('Error al realizar la solicitud fetch:', error));
}

//Buscador de mensajes
document.addEventListener("DOMContentLoaded", function () {
    var searchInput = document.getElementById("searchInput");

    searchInput.addEventListener("input", function () {
        var username = searchInput.value;

        // Actualiza la URL para buscar publicaciones por nombre de usuario
        fetch(`buscarPublicaciones?username=${username}`, {
            method: 'POST',
            credentials: 'same-origin',
            headers: {
                'Content-Type': 'application/json',
            },
        })
            .then(response => response.json())
            .then(data => {
                // Manejar los resultados de la búsqueda de publicaciones
                console.log(data);

                // Actualizar la interfaz de usuario con los resultados
                const resultadosContainer = document.getElementById("resultadosContainer");

                // Limpiar resultados anteriores
                resultadosContainer.innerHTML = '';

                // Iterar sobre las publicaciones y crear elementos HTML
                data.publicaciones.forEach(publicacion => {
                    const postDiv = document.createElement("div");
                    postDiv.className = "post";
                    postDiv.dataset.id = publicacion.idPublicacion;

                    const postTitle = document.createElement("div");
                    postTitle.className = "post-title";
                    postTitle.textContent = `@${publicacion.nombreUsuario}`;

                    const postTime = document.createElement("small");
                    postTime.className = "text-muted";
                    postTime.textContent = publicacion.fecha;

                    const postContent = document.createElement("div");
                    postContent.className = "post-content";
                    postContent.textContent = publicacion.mensaje;

                    const postActions = document.createElement("div");
                    postActions.className = "post-actions";

                    // Icono de me gusta
                    const likeIcon = document.createElement("i");
                    likeIcon.className = publicacion.usuarioHaDadoMeGusta
                        ? "fa-solid fa-thumbs-up"
                        : "fa-regular fa-thumbs-up";
                    likeIcon.onclick = function () {
                        darLike(publicacion.idPublicacion, data.usuarioActual.id, event);
                    };

                    // Contador de me gustas
                    const likeCount = document.createElement("span");
                    likeCount.style.display = "inline";
                    likeCount.textContent = publicacion.megustas;

                    // Icono de guardar
                    const bookmarkIcon = document.createElement("i");
                    bookmarkIcon.className = publicacion.usuarioHaGuardado
                        ? "fa-solid fa-bookmark"
                        : "fa-regular fa-bookmark";
                    bookmarkIcon.onclick = function () {
                        guardarPost(publicacion.idPublicacion, data.usuarioActual.id, event);
                    };

                    // Contador de guardados
                    const bookmarkCount = document.createElement("span");
                    bookmarkCount.style.display = "inline";
                    bookmarkCount.textContent = publicacion.guardados;

                    // Agregar elementos al div del post
                    postDiv.appendChild(postTitle);
                    postDiv.appendChild(postTime);
                    postDiv.appendChild(postContent);
                    postDiv.appendChild(document.createElement("br"));
                    postDiv.appendChild(postActions);
                    postActions.appendChild(likeIcon);
                    postActions.appendChild(document.createTextNode("\u00A0"));
                    postActions.appendChild(likeCount);
                    postActions.appendChild(document.createTextNode("\u00A0\u00A0\u00A0\u00A0\u00A0"));
                    postActions.appendChild(bookmarkIcon);
                    postActions.appendChild(document.createTextNode("\u00A0"));
                    postActions.appendChild(bookmarkCount);

                    // Agregar el div del post al contenedor
                    resultadosContainer.appendChild(postDiv);
                });

                // Mostrar información del usuario actual
                const usuarioActual = data.usuarioActual;
                console.log(`Usuario Actual - ID: ${usuarioActual.id}, Rol: ${usuarioActual.rol}`);
            })
            .catch(error => console.error('Error al realizar la solicitud fetch:', error));
    });
});