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
    return localStorage.getItem(storageKey) || "light";
  }

  applyTheme(currentPreference());

  document.addEventListener("click", function (event) {
    var toggleBtn = event.target.closest("[data-theme-toggle]");
    if (toggleBtn) {
      var current = localStorage.getItem(storageKey) || "light";
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

  // Footnote Smooth Scrolling and Highlight Handler
  document.addEventListener("DOMContentLoaded", function () {
    document.body.addEventListener("click", function (e) {
      var link = e.target.closest('a[href*="#"]');
      if (!link) return;

      var href = link.getAttribute("href");
      if (!href) return;

      var hashIndex = href.indexOf("#");
      if (hashIndex === -1) return;
      var hash = href.substring(hashIndex);
      if (hash.length <= 1) return;

      var targetId = decodeURIComponent(hash.substring(1));
      var targetElement = document.getElementById(targetId) || document.querySelector('[id="' + CSS.escape(targetId) + '"]');

      if (targetElement) {
        var isFootnoteRef = link.closest('.fn, .wp-block-footnote-link, [data-fn]') || targetElement.closest('.wp-block-footnotes, .footnotes, ol.wp-block-footnotes') || targetElement.tagName === 'LI';
        var isFootnoteBackLink = link.closest('.wp-block-footnotes, .footnotes, ol.wp-block-footnotes') || targetElement.closest('.fn, .wp-block-footnote-link, [data-fn]');

        if (isFootnoteRef || isFootnoteBackLink) {
          e.preventDefault();

          var headerOffset = 100;
          var elementPosition = targetElement.getBoundingClientRect().top + window.pageYOffset;
          var offsetPosition = elementPosition - headerOffset;

          window.scrollTo({
            top: offsetPosition,
            behavior: "smooth"
          });

          targetElement.classList.add("ss-footnote-highlight");
          setTimeout(function () {
            targetElement.classList.remove("ss-footnote-highlight");
          }, 2500);
        }
      }
    });
  });
})();
