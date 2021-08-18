const validateForm = async () => {
    let loteInput = document.forms["myForm"]["fLote"].value;
    let nombreInput = document.forms["myForm"]["fNombre"].value;
    let apellidoInput = document.forms["myForm"]["fApellido"].value;
    let inicioInput = document.forms["myForm"]["fInicio"].value;
    let terminoInput = document.forms["myForm"]["fTermino"].value;
    let result = document.getElementById("tipo-id").value;
    // let tipoInput = document.forms["myForm"]["fTipo"].value;
    // let select = document.getElementById('tipo-id');
    // let currentOpt = select.options[select.selectedIndex];
    let numPiezasInput = document.forms["myForm"]["fNum"].value;
    let defPiezasInput = document.forms["myForm"]["fDef"].value;

    let loteError = false
    let nombreError = false
    let apellidoError = false
    let inicioError = false
    let teerminoError = false
    let tipoError = false
    let numError = false
    let defError = false

    // convertir fecha de inicio a string
    let DateString;
    if (inicioInput){
       DateString= inicioInput.toString()
    }
    // convertir fecha de terminacion a string
    let terminoFecha;
    if (terminoInput){
       terminoFecha= terminoInput.toString()
    }

    //funcion para mostrar error
    const inputError = (text, elToAdd) => {
        const para = document.createElement("span");
        const node = document.createTextNode(`Debe incluir un/a ${text} valido`);
        para.appendChild(node);
        para.classList.add('alert-danger');

        const element = document.getElementById(elToAdd);
        element.appendChild(para);
    }


    if (loteInput==="" ) {
        inputError("Numero de Lote", "lote-div");
        loteError = true
    }
    if (nombreInput === "") {
        inputError("Nombre", "nombre-div");
        nombreError = true
    }
    if (apellidoInput === "") {
        inputError("Apellido", "apellido-div");
        apellidoError = true
    }
    if (inicioInput === "") {
        inputError("Fecha de inicio", "inicio-div");
        inicioError = true
    }
    if (terminoInput === "") {
        inputError("Fecha de terminacion", "termino-div");
        teerminoError = true
    }
/*
    if (tipoInput === "") {
        inputError("Tipo de Pieza", "tipo-div");
        tipoError = true
    }
*/
    if (numPiezasInput === "" ||numPiezasInput>1000) {
        inputError("Numero de Pieza", "num-div");
        numError = true
    }
    if (defPiezasInput === ""||defPiezasInput>1000) {
        inputError("Numero de Piezas Defectuosas", "def-div");
        defError = true
    }

    const vendedor = {
        lote: loteInput,
        nombre: nombreInput,
        apellido: apellidoInput,
        inicio: DateString,
        terminacion: terminoFecha,
        tipo: result,
        numPieza: numPiezasInput,
        defPieza: defPiezasInput,
    }

    if (loteError === false && nombreError === false && apellidoError === false && inicioError === false && teerminoError === false && tipoError === false && numError === false && defError === false) {
        const jsonVendedor = JSON.stringify(vendedor);

        const response = await fetch('http://localhost:8080/api/create.php', {
            method: 'POST',
            body: jsonVendedor, // string or object
            /* headers: {
                 'Content-Type': 'application/json'
             }*/
        });
        const myJson = await response.json(); //extract JSON from the http response
        console.log(myJson)
        // do something with myJson
    }
}

