const borrarVendedor = async (idV) => {
    const obj= {
        id: idV
    }
    const jsonVendedor = JSON.stringify(obj);

    const response = await fetch('http://localhost:8080/api/delete.php', {
        method: 'POST',
        body: jsonVendedor, // string or object
        /* headers: {
             'Content-Type': 'application/json'
         }*/
    });
    const myJson = await response.json(); //extract JSON from the http response
    console.log(myJson)
    location.reload();
}