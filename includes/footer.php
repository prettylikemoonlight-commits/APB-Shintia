    </main>
    <footer style="margin-left: 260px; padding: 2rem; text-align: center; color: var(--text-muted); font-size: 0.875rem;">
        &copy; 2026 DigiLib - UKK RPL Application by Antigravity
    </footer>

    <script>
        const themeToggle = document.getElementById('themeToggle');
        const sunIcon = '<i class="fa-solid fa-sun"></i>';
        const moonIcon = '<i class="fa-solid fa-moon"></i>';
        
        // Check for saved theme
        if (localStorage.getItem('theme') === 'dark') {
            document.body.setAttribute('data-theme', 'dark');
            themeToggle.innerHTML = sunIcon;
        }

        themeToggle.addEventListener('click', () => {
            if (document.body.getAttribute('data-theme') === 'dark') {
                document.body.removeAttribute('data-theme');
                themeToggle.innerHTML = moonIcon;
                localStorage.setItem('theme', 'light');
            } else {
                document.body.setAttribute('data-theme', 'dark');
                themeToggle.innerHTML = sunIcon;
                localStorage.setItem('theme', 'dark');
            }
        });
    </script>
</body>
</html>
