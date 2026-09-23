import { STORAGE_KEYS } from "./config.js";

// ------------------------------------------------------------
// Helpers internos
// ------------------------------------------------------------

function setItem(storage, key, value) {
  try {
    storage.setItem(key, JSON.stringify(value));
  } catch (e) {
    console.error(`Error guardando ${key}:`, e);
  }
}

function getItem(storage, key) {
  try {
    const raw = storage.getItem(key);
    return raw === null ? null : JSON.parse(raw);
  } catch (e) {
    console.error(`Error leyendo ${key}:`, e);
    return null;
  }
}

function removeItem(storage, key) {
  storage.removeItem(key);
}

// ------------------------------------------------------------
// Token y sesión
// ------------------------------------------------------------

export function saveToken(token, remember = false) {
  const storage = remember ? localStorage : sessionStorage;
  setItem(storage, STORAGE_KEYS.token, token);
  setItem(localStorage, STORAGE_KEYS.remember, remember);
}

export function getToken() {
  return (
    getItem(localStorage, STORAGE_KEYS.token) ??
    getItem(sessionStorage, STORAGE_KEYS.token)
  );
}

export function removeToken() {
  removeItem(localStorage, STORAGE_KEYS.token);
  removeItem(sessionStorage, STORAGE_KEYS.token);
}

export function getRemember() {
  return getItem(localStorage, STORAGE_KEYS.remember) === true;
}

// ------------------------------------------------------------
// Usuario
// ------------------------------------------------------------

export function saveUser(user) {
  const storage = getRemember() ? localStorage : sessionStorage;
  setItem(storage, STORAGE_KEYS.user, user);
}

export function getUser() {
  return (
    getItem(localStorage, STORAGE_KEYS.user) ??
    getItem(sessionStorage, STORAGE_KEYS.user)
  );
}

export function removeUser() {
  removeItem(localStorage, STORAGE_KEYS.user);
  removeItem(sessionStorage, STORAGE_KEYS.user);
}

// ------------------------------------------------------------
// Tema y acento
// ------------------------------------------------------------

export function saveTheme(theme) {
  setItem(localStorage, STORAGE_KEYS.theme, theme);
}

export function getTheme() {
  return getItem(localStorage, STORAGE_KEYS.theme);
}

export function saveAccent(accent) {
  setItem(localStorage, STORAGE_KEYS.accent, accent);
}

export function getAccent() {
  return getItem(localStorage, STORAGE_KEYS.accent);
}

// ------------------------------------------------------------
// Limpieza de sesión (logout)
// ------------------------------------------------------------

export function clearSession() {
  removeToken();
  removeUser();
  removeItem(localStorage, STORAGE_KEYS.remember);
}
