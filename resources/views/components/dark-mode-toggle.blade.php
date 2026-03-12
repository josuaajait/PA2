<div class="dark-mode-toggle" onclick="toggleDarkMode()" id="darkModeToggle">
    <i class="fas fa-moon"></i>
</div>

<script>
    function toggleDarkMode() {
        document.body.classList.toggle('dark-mode');
        const toggle = document.getElementById('darkModeToggle');
        const icon = toggle.querySelector('i');
        
        if (document.body.classList.contains('dark-mode')) {
            icon.className = 'fas fa-sun';
            toggle.style.background = '#ffc107';
            toggle.style.color = '#333';
            localStorage.setItem('darkMode', 'enabled');
        } else {
            icon.className = 'fas fa-moon';
            toggle.style.background = '#333';
            toggle.style.color = 'white';
            localStorage.setItem('darkMode', 'disabled');
        }
    }

    // Check for saved dark mode preference
    if (localStorage.getItem('darkMode') === 'enabled') {
        document.body.classList.add('dark-mode');
        const toggle = document.getElementById('darkModeToggle');
        const icon = toggle.querySelector('i');
        icon.className = 'fas fa-sun';
        toggle.style.background = '#ffc107';
        toggle.style.color = '#333';
    }
</script>