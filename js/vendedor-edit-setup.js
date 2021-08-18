const EditSetup = (idV) => {
    localStorage.setItem('tempId', idV);
    window.location.href = '/CoolAdmin/edit-vendedor.php';
}