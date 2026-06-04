<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
</head>
<body>

<h2>Login</h2>

<form id="loginForm">
    @csrf

    <input type="email" name="email" id="email" placeholder="Email">
    <br><br>

    <input type="password" name="password" id="password" placeholder="Password">
    <br><br>

    <button type="submit" id="loginBtn">Login</button>
</form>

<p id="message"></p>

<script>
document.getElementById("loginForm").addEventListener("submit", async function(e) {
    e.preventDefault();

    let btn = document.getElementById("loginBtn");
    btn.disabled = true;

    let data = {
        email: document.getElementById("email").value,
        password: document.getElementById("password").value
    };

    try {
        let response = await fetch("/login", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": document.querySelector('input[name="_token"]').value
            },
            body: JSON.stringify(data)
        });

        let result = await response.json();

        if (response.status === 200) {
            window.location.href = result.redirect;
        }

        if (response.status === 401) {
            alert("Foutieve login gegevens.");
        }

        if (response.status === 500) {
            alert("Er ging iets mis met het verzoeken voor autorisatie...");
        }

    } catch (err) {
        alert("Er ging iets mis met het verzoeken voor autorisatie...");
    }

    btn.disabled = false;
});
</script>

</body>
</html>