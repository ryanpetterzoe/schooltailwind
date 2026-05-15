    </div><!-- /inner content -->
  </div><!-- /admin-content -->
</div><!-- /flex wrapper -->

<script src="<?= APP_URL ?>/assets/js/main.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Sidebar overlay close on mobile
    const sidebar = document.getElementById('adminSidebar');
    document.addEventListener('click', function(e) {
        if (window.innerWidth < 1024 && sidebar && sidebar.classList.contains('open')) {
            if (!sidebar.contains(e.target) && e.target.id !== 'sidebarToggle') {
                sidebar.classList.remove('open');
            }
        }
    });
});
</script>
</body>
</html>
