 
document.addEventListener("DOMContentLoaded", function () {

    const loginBtn = document.querySelector('a[href="./Auth/Login.html"]')?.parentElement;
    const registerBtn = document.querySelector('a[href="./Auth/Register.html"]')?.parentElement;
    const logoutBtn = document.getElementById("logoutBtn");

    function updateNavbar() {
        const token = localStorage.getItem("token");

        if (token) {
            // logged in
            if (loginBtn) loginBtn.style.display = "none";
            if (registerBtn) registerBtn.style.display = "none";
            if (logoutBtn) logoutBtn.style.display = "inline-block";
        } else {
            // logged out
            if (loginBtn) loginBtn.style.display = "inline-block";
            if (registerBtn) registerBtn.style.display = "inline-block";
            if (logoutBtn) logoutBtn.style.display = "none";
        }
    }

    updateNavbar();

    if (logoutBtn) {
        logoutBtn.addEventListener("click", function () {

            const token = localStorage.getItem("token");

            // remove token
            localStorage.removeItem("token");

            // optional API call
            fetch("http://127.0.0.1:8000/api/user/logout", {
                method: "POST",
                headers: {
                    "Authorization": "Bearer " + token,
                    "Accept": "application/json"
                }
            });

            // 🔥 IMPORTANT: NO REDIRECT
            updateNavbar();
        });
    }

});
 