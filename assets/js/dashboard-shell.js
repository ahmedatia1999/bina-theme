/**
 * Customer portal: mobile sidebar toggle + backdrop close.
 */
(function () {
  document.querySelectorAll("[data-bina-dashboard-shell]").forEach(function (shell) {
    var triggers = shell.querySelectorAll("[data-bina-dashboard-sidebar-trigger]");
    var panel = shell.querySelector("[data-bina-sidebar-container]");
    var backdrop = shell.querySelector("[data-bina-portal-backdrop], [data-bina-sp-backdrop]");
    if (!panel || !triggers.length) return;

    function closeSidebar() {
      shell.classList.remove("bina-dashboard-sidebar-open");
    }

    triggers.forEach(function (btn) {
      btn.addEventListener("click", function (e) {
        e.preventDefault();
        e.stopPropagation();
        shell.classList.toggle("bina-dashboard-sidebar-open");
      });
    });

    if (backdrop) {
      backdrop.addEventListener("click", function () {
        closeSidebar();
      });
    }

    shell.addEventListener("click", function (e) {
      if (!shell.classList.contains("bina-dashboard-sidebar-open")) return;
      if (e.target === panel || panel.contains(e.target)) return;
      if (e.target.closest("[data-bina-dashboard-sidebar-trigger]")) return;
      if (e.target === backdrop || (backdrop && backdrop.contains(e.target))) return;
      closeSidebar();
    });
  });
})();
