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
    button.setAttribute("aria-expanded", expanded ? "false" : "true");
    target.hidden = expanded;
    if (expanded) {
      target.classList.add("hidden");
      target.classList.remove("flex");
    } else {
      target.classList.remove("hidden");
      target.classList.add("flex");
    }
  });
})();
