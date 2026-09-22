import { clearSession } from "./storage.js";

export function initSessionGuard() {
  window.addEventListener("sessionExpired", () => {
    clearSession();
    window.location.hash = "#/login";
  });
}
