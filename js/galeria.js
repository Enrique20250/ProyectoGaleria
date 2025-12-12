let imagenes = []
let favoritos = []
let viendoFavoritos = false

document.addEventListener("DOMContentLoaded", () => {
  cargarFavoritos()
  cargarImagenes("todas")
})

function cargarFavoritos() {
  fetch("Backend/api/favoritos.php?accion=ids")
    .then((response) => response.json())
    .then((data) => {
      if (data.success) {
        favoritos = data.data
      }
    })
    .catch((error) => {
      console.error("Error al cargar favoritos:", error)
    })
}


function cargarImagenes(tipo, valor = null) {
  console.log("[v0] Cargando imágenes - tipo:", tipo, "valor:", valor)

  let url = "Backend/api/imagenes.php?accion="

  if (tipo === "todas") {
    url += "todas"
  } else if (tipo === "categoria") {
    url += "categoria&categoria_id=" + valor
  } else if (tipo === "buscar") {
    url += "buscar&texto=" + encodeURIComponent(valor)
  }

  console.log("[v0] URL de petición:", url)


  fetch(url)
    .then((response) => {
      console.log("[v0] Respuesta recibida:", response)
      return response.json()
    })
    .then((data) => {
      console.log("[v0] Datos recibidos:", data)
      if (data.success) {
        imagenes = data.data
        mostrarImagenes(imagenes)
      } else {
        console.error("[v0] Error al cargar imágenes:", data)
        document.getElementById("galeria").innerHTML =
          '<p class="cargando">Error: ' + (data.message || "Error desconocido") + "</p>"
      }
    })
    .catch((error) => {
      console.error("[v0] Error en la petición:", error)
      document.getElementById("galeria").innerHTML =
        '<p class="cargando">Error al cargar las imágenes. Verifica la consola.</p>'
    })
}

function mostrarImagenes(imagenes) {
  const galeria = document.getElementById("galeria")

  if (imagenes.length === 0) {
    galeria.innerHTML = '<p class="cargando">No se encontraron imágenes</p>'
    return
  }

  let html = ""
  imagenes.forEach((imagen) => {
    const esFavorito = favoritos.includes(imagen.id)
    const iconoFavorito = esFavorito ? "⭐" : "☆"
    const claseFavorito = esFavorito ? "favorito-activo" : ""

    html += `
            <div class="imagen-card">
                <button class="btn-favorito ${claseFavorito}" onclick="toggleFavorito(event, ${imagen.id})">
                    ${iconoFavorito}
                </button>
                <div onclick="abrirModal(${imagen.id})">
                    <img src="${imagen.url}" alt="${imagen.titulo}">
                    <div class="imagen-info">
                        <h3>${imagen.titulo}</h3>
                        <p>${imagen.descripcion.substring(0, 60)}...</p>
                        <span class="imagen-categoria">${imagen.categoria_nombre}</span>
                    </div>
                </div>
            </div>
        `
  })

  galeria.innerHTML = html
}


function filtrarPorCategoria(categoria) {
  viendoFavoritos = false


  const botones = document.querySelectorAll(".btn-filtro")
  botones.forEach((btn) => btn.classList.remove("active"))
  event.target.classList.add("active")


  if (categoria === "todas") {
    cargarImagenes("todas")
  } else {
    cargarImagenes("categoria", categoria)
  }


  document.getElementById("busqueda").value = ""
}

function buscarImagenes() {
  viendoFavoritos = false

  const texto = document.getElementById("busqueda").value.trim()

  if (texto === "") {
    cargarImagenes("todas")
    return
  }

  cargarImagenes("buscar", texto)


  const botones = document.querySelectorAll(".btn-filtro")
  botones.forEach((btn) => btn.classList.remove("active"))
}


document.addEventListener("DOMContentLoaded", () => {
  document.getElementById("busqueda").addEventListener("keypress", (e) => {
    if (e.key === "Enter") {
      buscarImagenes()
    }
  })
})

function verFavoritos() {
  viendoFavoritos = true

  const botones = document.querySelectorAll(".btn-filtro")
  botones.forEach((btn) => btn.classList.remove("active"))
  event.target.classList.add("active")


  fetch("Backend/api/favoritos.php?accion=obtener")
    .then((response) => response.json())
    .then((data) => {
      if (data.success) {
        imagenes = data.data
        mostrarImagenes(imagenes)
      } else {
        console.error("Error al cargar favoritos")
      }
    })
    .catch((error) => {
      console.error("Error en la petición:", error)
      document.getElementById("galeria").innerHTML = '<p class="cargando">Error al cargar favoritos</p>'
    })

  document.getElementById("busqueda").value = ""
}

function toggleFavorito(event, idImagen) {
  event.stopPropagation()

  const esFavorito = favoritos.includes(idImagen)
  const accion = esFavorito ? "desmarcar" : "marcar"

  fetch(`Backend/api/favoritos.php?accion=${accion}&id_imagen=${idImagen}`)
    .then((response) => response.json())
    .then((data) => {
      if (data.success) {
 
        if (accion === "marcar") {
          favoritos.push(idImagen)
        } else {
          favoritos = favoritos.filter((id) => id !== idImagen)
        }

    
        if (viendoFavoritos) {
          verFavoritos()
        } else {
 
          mostrarImagenes(imagenes)
        }
      } else {
        alert(data.message)
      }
    })
    .catch((error) => {
      console.error("Error al gestionar favorito:", error)
      alert("Error al actualizar favorito")
    })
}

function toggleFavoritoModal() {
  const imagenId = Number.parseInt(document.getElementById("modal-imagen").dataset.imagenId)
  const esFavorito = favoritos.includes(imagenId)
  const accion = esFavorito ? "desmarcar" : "marcar"

  fetch(`Backend/api/favoritos.php?accion=${accion}&id_imagen=${imagenId}`)
    .then((response) => response.json())
    .then((data) => {
      if (data.success) {

        if (accion === "marcar") {
          favoritos.push(imagenId)
        } else {
          favoritos = favoritos.filter((id) => id !== imagenId)
        }
        actualizarBotonFavoritoModal(imagenId)

        if (viendoFavoritos) {
          verFavoritos()
        } else {
          mostrarImagenes(imagenes)
        }
      }
    })
    .catch((error) => {
      console.error("Error al gestionar favorito:", error)
    })
}

function actualizarBotonFavoritoModal(imagenId) {
  const esFavorito = favoritos.includes(imagenId)
  const icono = document.getElementById("modal-favorito-icono")
  const texto = document.getElementById("modal-favorito-texto")

  if (esFavorito) {
    icono.textContent = "⭐"
    texto.textContent = "Quitar de favoritos"
  } else {
    icono.textContent = "☆"
    texto.textContent = "Agregar a favoritos"
  }
}


function abrirModal(id) {
  const imagen = imagenes.find((img) => img.id == id)

  if (!imagen) return

  document.getElementById("modal-imagen").src = imagen.url
  document.getElementById("modal-imagen").dataset.imagenId = imagen.id
  document.getElementById("modal-titulo").textContent = imagen.titulo
  document.getElementById("modal-descripcion").textContent = imagen.descripcion
  document.getElementById("modal-categoria").textContent = "Categoría: " + imagen.categoria_nombre

  actualizarBotonFavoritoModal(imagen.id)

  document.getElementById("modal").style.display = "block"
}


function cerrarModal() {
  document.getElementById("modal").style.display = "none"
}


window.onclick = (event) => {
  const modal = document.getElementById("modal")
  if (event.target == modal) {
    cerrarModal()
  }
}

document.addEventListener("keydown", (e) => {
  if (e.key === "Escape") {
    cerrarModal()
  }
})
