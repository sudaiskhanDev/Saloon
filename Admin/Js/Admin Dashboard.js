document.getElementById("logoutBtn").addEventListener("click", function () {

    const token = localStorage.getItem("token");

    if (!token) {
        window.location.href = "../../Login.html";
        return;
    }

    fetch("http://127.0.0.1:8000/api/auth/logout", {
        method: "POST",
        headers: {
            "Authorization": "Bearer " + token,
            "Content-Type": "application/json"
        }
    })
    .then(() => {

        // no alert, no wait
        localStorage.removeItem("token");

        // direct redirect
        window.location.href = "../../Frontend/Html/Auth/Login.html";;
    })
    .catch(() => {

        // even error → force logout
        localStorage.removeItem("token");
        window.location.href = "../../Frontend/Html/Auth/Login.html";
    });

});