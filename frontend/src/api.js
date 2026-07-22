// Empty string = relative "/api/..." requests, proxied by nginx to the backend
// service (see frontend/nginx.conf). Set VITE_API_URL at build time to point
// at an absolute backend URL instead (e.g. for local `npm run dev`).
const API_URL = import.meta.env.VITE_API_URL || "";

async function request(path, options = {}) {
  const token = localStorage.getItem("token");
  const res = await fetch(`${API_URL}${path}`, {
    ...options,
    headers: {
      "Content-Type": "application/json",
      ...(token ? { Authorization: `Bearer ${token}` } : {}),
      ...options.headers,
    },
  });
  if (res.status === 401) {
    localStorage.removeItem("token");
    window.location.href = "/login";
    throw new Error("Unauthorized");
  }
  if (!res.ok) throw new Error(`Request failed: ${res.status}`);
  return res.json();
}

export function login(username, password) {
  return request("/api/login", { method: "POST", body: JSON.stringify({ username, password }) });
}

export const getEmployees = () => request("/api/employees");
export const getOnlineToday = () => request("/api/employees/online-today");
export const getAttendanceSummary = () => request("/api/attendance/summary");
export const getLeave = () => request("/api/leave");
