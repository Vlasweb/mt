import { API_BASE } from "./config.js";
import { getToken } from "./storage.js";

class ApiError extends Error {
  constructor(error, message, status) {
    super(message);
    this.name = "ApiError";
    this.error = error;
    this.status = status;
  }
}

async function request(method, endpoint, data = null) {
  const url = `${API_BASE}${endpoint}`;
  const headers = {
    "Content-Type": "application/json",
  };

  const token = getToken();
  if (token) {
    headers["Authorization"] = `Bearer ${token}`;
  }

  const options = {
    method,
    headers,
  };

  if (data !== null) {
    options.body = JSON.stringify(data);
  }

  let response;
  try {
    response = await fetch(url, options);
  } catch (e) {
    window.dispatchEvent(new CustomEvent("networkError"));
    throw new ApiError("network_error", "No se pudo conectar al servidor", 0);
  }

  let body;
  try {
    body = await response.json();
  } catch (e) {
    throw new ApiError(
      "invalid_response",
      "Respuesta del servidor inválida",
      response.status,
    );
  }

  if (response.status === 401) {
    window.dispatchEvent(new CustomEvent("sessionExpired"));
    throw new ApiError("unauthorized", body.message ?? "Sesión expirada", 401);
  }

  if (!response.ok || body.ok === false) {
    throw new ApiError(
      body.error ?? "unknown_error",
      body.message ?? "Error desconocido",
      response.status,
    );
  }

  return body;
}

export function apiGet(endpoint) {
  return request("GET", endpoint);
}

export function apiPost(endpoint, data = null) {
  return request("POST", endpoint, data);
}

export function apiPut(endpoint, data = null) {
  return request("PUT", endpoint, data);
}

export function apiDelete(endpoint) {
  return request("DELETE", endpoint);
}
