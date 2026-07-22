# kube-workshop

A tiny employee management app (check-in/check-out, PTO/sick leave, who's
online today) used to teach core Kubernetes concepts: namespace, pod,
deployment, service, secret, label, selector, annotation.

- `backend/` — FastAPI + SQLite API (login: `admin` / `admin`)
- `frontend/` — React (Vite) dashboard, served by nginx
- `k8s/` — copy-paste manifests, see `k8s/README.md`
- `.github/workflows/publish.yml` — builds and pushes both images to GHCR on push to `main`

## Run locally

```bash
docker compose up --build
```

Then open http://localhost:8080 and log in with `admin` / `admin`.

## Images

Published (public) on GHCR after every push to `main`:

- `ghcr.io/k1m0ch1/kube-workshop-backend:latest`
- `ghcr.io/k1m0ch1/kube-workshop-frontend:latest`
