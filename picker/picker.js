//Contar los caracteres restantes en comentarios principales

function actualizarConteo() {
    var maxCaracteres = 280;
    var texto = document.getElementById("texto").value;
    var caracteresRestantes = maxCaracteres - texto.length;
    document.getElementById("conteo").textContent = caracteresRestantes;
}



// Script para seleccionar y subir contenido multimedia 1º

function arc(event){
    var input = event.target;

    if (input.files && input.files[0]) {
        var archivo = input.files[0];
        var maxTamano = 40 * 1024 * 1024; // 40MB

        if (archivo.size > maxTamano) {
            alert('El archivo es demasiado grande, no se puede subir');
            input.value = '';
            return;
        }

    }
}

function subir_archivo() {
    document.getElementById('archivo_subido').click();
}



//Script para seleccionar y subir contenido multimedia 2º

function arcc(event, id) {
    var input = event.target;

    if (input.files && input.files[0]) {
        var archivo = input.files[0];
        var maxTamano = 40 * 1024 * 1024; // 40MB

        if (archivo.size > maxTamano) {
            alert('El archivo es demasiado grande, no se puede subir');
            input.value = '';
            return;
        }
    }
}

  

// Ocultar comentarios 2º

function comentarios(id) {
    var comentarios = document.getElementById('comentariosocultar-' + id);

    if (comentarios.style.display === 'none') {
        comentarios.style.display = '';
    } else {
        comentarios.style.display = 'none';
    }
}

// //Cerrar la pagina de error
// function cerrarPagina() {
// if (confirm("¿Estas seguro que quieres cerrar la pagina?")){
//   window.close(); }
// }