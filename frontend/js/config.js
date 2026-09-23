const path = window.location.pathname
  .replace(/\/index\.php$/, "")
  .replace(/\/$/, "");

export const API_BASE = `${window.location.origin}${path}/backend/api`;

export const STORAGE_KEYS = {
  token: "mt_token",
  user: "mt_user",
  theme: "mt_theme",
  accent: "mt_accent",
  remember: "mt_remember",
};
