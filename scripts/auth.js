document.addEventListener('DOMContentLoaded', function() {
    //Locate registration form 
    const regForm = document.querySelector('form[action="register.php"]');
    const regMessage = document.getElementById("reg-message");

    //Only attach registration logic if the registration form exists
    if (regForm && regMessage) {
        regForm.addEventListener('submit', function(event) {
            // Stop browser rom submitting the form
            event.preventDefault();

            //Read input
            const username = document.getElementById('reg-username').value.trim();
            const password = document.getElementById('reg-password').value;
            const confirmPassword = document.getElementById('reg-confirm-password').value;

            //Validate input
            if (!username || !password || !confirmPassword) {
                regMessage.style.color = 'red';
                regMessage.textContent = 'Please fill in all fields.';
                return;
            }

            if (password.length < 6) {
                regMessage.style.color = 'red';
                regMessage.textContent = 'Password must be at least 6 characters long.';
                return;
            }

            if (password !== confirmPassword) {
                regMessage.style.color = 'red';
                regMessage.textContent = 'Passwords do not match.';
                return;
            }
            
            // Package data to send
            const formData = new FormData(regForm);

            // Send data to server
            fetch('register.php', {
                method: 'POST',
                body: formData
            })
            .then((response) => response.json())
            .then((data) => {
                if (data.success) {
                    regMessage.style.color = 'green';
                    regMessage.textContent = data.message;
                    regForm.reset();
                } else {
                    regMessage.style.color = 'red';
                    regMessage.textContent = data.message;
                }
            })
            .catch((error) => {
                console.error('Error:', error);
                regMessage.style.color = 'red';
                regMessage.textContent = 'An error occurred. Please try again later.';
            });
        });
    }

// Login form handling
const loginForm = document.querySelector('form[action="login.php"]');
const loginMessage = document.getElementById("login-message");

if (loginForm && loginMessage) {
    loginForm.addEventListener("submit", function(event) {
        event.preventDefault();

        const username = document.getElementById("username").value.trim();
        const password = document.getElementById("password").value;

        if (!username || !password) {
            loginMessage.style.color = "red";
            loginMessage.textContent = "Please enter both username and password";
            return;
        }

        const formData = new FormData(loginForm);

        fetch("login.php", {
            method: "POST",
            body: formData
        })
        .then((response) => response.json())
        .then((data) => {
            if (data.success) {
                loginMessage.style.color = "green";
                loginMessage.textContent = data.message;
            } else {
                loginMessage.style.color = "red";
                loginMessage.textContent = data.message;
            }
        })
        .catch((error) => {
            console.error("Error:", error);
            loginMessage.style.color = "red";
            loginMessage.textContent = "An error occurred. Please try again later.";
        });
    });
}
});