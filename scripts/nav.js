document.addEventListener('DOMContentLoaded', function() {
    //Query check_session.php to check authentication
    fetch('check_session.php')
        .then(response => response.json())
        .then(data => {
            if (data.logged_in) {
                // Find any navigation links pointing to login.html
                const loginLinks = document.querySelectorAll('nav a[href="login.html"]');
                loginLinks.forEach((link) => {
                    link.href = 'logout.php';
                    link.textContent = `Logout`;
                });
            }
        })
        .catch((error) => {
            console.error('Error checking failed:', error);
        });
});
