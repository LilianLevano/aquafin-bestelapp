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
function showToast(message, type) {
    // Simple toast system (you can later upgrade to Toastr/SweetAlert)
    let toast = document.createElement("div");

    toast.innerText = message;
    toast.style.position = "fixed";
    toast.style.bottom = "20px";
    toast.style.right = "20px";
    toast.style.padding = "12px 16px";
    toast.style.color = "white";
    toast.style.borderRadius = "6px";
    toast.style.zIndex = 9999;

    if (type === "success") toast.style.background = "green";
    if (type === "warning") toast.style.background = "orange";
    if (type === "error") toast.style.background = "red";

    document.body.appendChild(toast);

    setTimeout(() => toast.remove(), 3000);
}

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

        if (result.status === "success") {
            window.location.href = result.redirect;
            return;
        }

        if (result.status === "fail") {
            showToast("Foutieve login gegevens.", "warning");
        }

        if (result.status === "error") {
            showToast("Er ging iets mis met het verzoeken voor autorisatie...", "error");
        }

    } catch (err) {
        showToast("Er ging iets mis met het verzoeken voor autorisatie...", "error");
    }

    btn.disabled = false;
});
</script>

</body>
</html>