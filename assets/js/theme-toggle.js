(function () {
  var storageKey = "sukusastra-theme";
  var root = document.documentElement;

  function prefersDark() {
    return window.matchMedia && window.matchMedia("(prefers-color-scheme: dark)").matches;
  }

  function applyTheme(value) {
    var resolved = value === "system" ? (prefersDark() ? "dark" : "light") : value;
    root.classList.toggle("dark", resolved === "dark");
    root.setAttribute("data-theme", value);
  }

  function currentPreference() {
    return localStorage.getItem(storageKey) || "system";
  }

  applyTheme(currentPreference());

  document.addEventListener("click", function (event) {
    var toggleBtn = event.target.closest("[data-theme-toggle]");
    if (toggleBtn) {
      var current = localStorage.getItem(storageKey) || "system";
      var resolved = current === "system" ? (prefersDark() ? "dark" : "light") : current;
      var nextTheme = resolved === "dark" ? "light" : "dark";
      localStorage.setItem(storageKey, nextTheme);
      applyTheme(nextTheme);
      return;
    }

    var button = event.target.closest("[data-theme-choice]");
    if (!button) {
      return;
    }

    var nextTheme = button.getAttribute("data-theme-choice");
    localStorage.setItem(storageKey, nextTheme);
    applyTheme(nextTheme);
  });
})();
