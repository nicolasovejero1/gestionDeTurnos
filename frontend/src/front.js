const optionButtons = document.querySelectorAll('.optionButton');

optionButtons.forEach(button => {

    button.addEventListener('click', function(){
        console.log(button.id);
        generarTurno(button.id);
    });
});
/*
function generarTurno(id){

    fetch('../../backend/app.php',{
        method:'POST',
        headers:{
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({tipo: id})
    })
    .then(response => response.json())
    .then(data =>{
        console.log(data.turno);
    })
    .catch(error => console.log('Error:', error));

};
*/
const generarTurno = (id) => {
    fetch('http://localhost/GestiónDeTurnos/backend/app.php', { 
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ tipo: id })
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`Error HTTP: ${response.status}`);
        }
        return response.text(); // Obtener la respuesta como texto primero
    })
    .then(text => {
        console.log("Raw Response:", text); // Verifica qué está devolviendo el servidor
        return JSON.parse(text); // Convierte a JSON
    })
    .then(data => {
        console.log("Turno generado:", data.turno);
        mostrarTurno(data.turno);
    })
    .catch(error => console.error('Error:', error));
};


function mostrarTurno(turno) {
    let turnoDiv = document.createElement("div");
    let texto = document.createElement("p");
    let textTurno = document.createElement("p");
    turnoDiv.id = "turno-popup";
    texto.id = "texto";
    textTurno.id ="textTurno";
    texto.innerText = `Turno`;
    textTurno.innerText = `${turno}`;
    turnoDiv.appendChild(texto);
    turnoDiv.appendChild(textTurno);
    document.body.appendChild(turnoDiv);

    // Ocultar después de 3 segundos
    setTimeout(() => {
        turnoDiv.style.opacity = "0"; // Desvanecimiento
        setTimeout(() => turnoDiv.remove(), 500); // Remover el elemento
    }, 3000);
};