# Kubernetes Workshop Manifests

Copy-paste teaching material for the employee management demo app. Apply in
order (numbered filenames):

```bash
kubectl apply -f k8s/00-namespace.yaml
kubectl apply -f k8s/01-secret.yaml
kubectl apply -f k8s/02-backend-deployment.yaml
kubectl apply -f k8s/03-backend-service.yaml
kubectl apply -f k8s/04-frontend-deployment.yaml
kubectl apply -f k8s/05-frontend-service.yaml

# or apply the whole folder at once:
kubectl apply -f k8s/
```

Then visit `http://<any-node-ip>:30080` and log in with `admin` / `admin`.

## Concepts covered

| File | Concept | What it teaches |
|---|---|---|
| `00-namespace.yaml` | **Namespace** | A virtual cluster-within-a-cluster to group and isolate resources. |
| `01-secret.yaml` | **Secret** | Storing sensitive config (base64-encoded) separately from images, injected into containers as env vars. |
| `02-backend-deployment.yaml` | **Pod, Deployment, Label, Annotation** | A Deployment manages replica Pods from a template. Labels tag Pods for selection; annotations are free-form metadata for humans/tools. |
| `03-backend-service.yaml` | **Service, Selector** | A Service gives Pods a stable network identity and load-balances traffic to any Pod whose labels match its selector. |
| `04-frontend-deployment.yaml` | Same as `02`, for the frontend image | |
| `05-frontend-service.yaml` | **NodePort Service** | Exposes a Service on a fixed port on every node, reachable from outside the cluster without an Ingress. |

## Try it live

- `kubectl get pods -n kube-workshop -l app=backend` — labels in action.
- `kubectl describe svc backend -n kube-workshop` — see the selector matching pod labels.
- `kubectl scale deployment backend -n kube-workshop --replicas=3` — watch the Service load-balance across replicas.
- `kubectl delete pod -n kube-workshop -l app=backend` — kill a pod, watch the Deployment recreate it.
