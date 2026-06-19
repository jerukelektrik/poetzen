(function () {
  document.addEventListener("click", function (event) {
    var button = event.target.closest("[data-nav-toggle]");
    if (!button) {
      return;
    }

    var targetId = button.getAttribute("aria-controls");
    var target = document.getElementById(targetId);
    if (!target) {
      return;
    }

    var expanded = button.getAttribute("aria-expanded") === "true";
    
    // Toggle all buttons targeting this menu for aria-expanded consistency
    var allButtons = document.querySelectorAll('[aria-controls="' + targetId + '"]');
    allButtons.forEach(function(btn) {
      btn.setAttribute("aria-expanded", expanded ? "false" : "true");
    });

    var backdrop = document.getElementById("mobile-menu-backdrop");

    if (expanded) {
      // Close Drawer
      target.classList.add("translate-x-full");
      target.classList.remove("translate-x-0");
      if (backdrop) {
        backdrop.classList.add("hidden");
      }
      setTimeout(function() {
        if (target.classList.contains("translate-x-full")) {
          target.classList.add("hidden");
          target.classList.remove("flex");
        }
      }, 300);
    } else {
      // Open Drawer
      target.classList.remove("hidden");
      target.classList.add("flex");
      if (backdrop) {
        backdrop.classList.remove("hidden");
      }
      // Trigger reflow to ensure CSS transition works smoothly
      target.offsetHeight;
      target.classList.remove("translate-x-full");
      target.classList.add("translate-x-0");
    }
  });
})();
