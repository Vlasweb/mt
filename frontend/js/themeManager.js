import { getTheme, saveTheme } from "./storage.js";
import { withTransition } from "./pageTransitions.js";

function getAccent() {
  return localStorage.getItem("mt_accent") || "#6366F1";
}

function updateFavicon(theme) {
  const favicon = document.getElementById("favicon");
  if (!favicon) return;

  const accent = getAccent();
  const base = favicon.href.split("?")[0];
  favicon.href =
    base + "?theme=" + theme + "&accent=" + encodeURIComponent(accent);
}

export function initTheme() {
  const saved = getTheme();
  const system = window.matchMedia("(prefers-color-scheme: dark)").matches
    ? "dark"
    : "light";
  const theme = saved ?? system;
  document.documentElement.setAttribute("data-theme", theme);
  updateFavicon(theme);
}

export function getCurrentTheme() {
  return document.documentElement.getAttribute("data-theme") || "light";
}

export function toggleTheme() {
  withTransition(() => {
    const current = getCurrentTheme();
    const next = current === "light" ? "dark" : "light";
    document.documentElement.setAttribute("data-theme", next);
    saveTheme(next);
    updateFavicon(next);
  });
}
