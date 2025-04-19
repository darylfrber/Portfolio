document.getElementById('loginForm').addEventListener('submit', function (event) {
    event.preventDefault(); // Prevent the form from submitting normally

    // Get the form data
    const username = document.getElementById('username').value;
    const password = document.getElementById('password').value;
    const confirm_password = document.getElementById('confirm_password').value;

    fetch('/public/index.php?controller=User&method=registerPost', {
        method: 'POST',
        body: JSON.stringify({ username: username, password: password, confirm_password: confirm_password }),
        headers: {
            'Content-Type': 'application/json',
        },
    })
        .then(response => response.json())
        .then(data => {
            // Geef notificatie van verkeerde data
            if (data.error) {
                alert(data.error);
            }

            // Gebruiker is succesvol geregistreerd
            if (data.succes) {
                window.location.replace("/school");
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
});