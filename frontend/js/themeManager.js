import { getTheme, saveTheme } from "./storage.js";
import { withTransition } from "./pageTransitions.js";

export function initTheme() {
  const saved = getTheme();
  const system = window.matchMedia("(prefers-color-scheme: dark)").matches
    ? "dark"
    : "light";
  const theme = saved ?? system;
  document.documentElement.setAttribute("data-theme", theme);
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
  });
}
