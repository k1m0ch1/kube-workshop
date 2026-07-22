import os
from fastapi import Header, HTTPException

ADMIN_USER = os.environ.get("ADMIN_USER", "admin")
ADMIN_PASSWORD = os.environ.get("ADMIN_PASSWORD", "admin")
# ponytail: single static demo token, no real session/JWT — this is a teaching app.
TOKEN = "devtoken"


def login(username: str, password: str) -> str:
    if username != ADMIN_USER or password != ADMIN_PASSWORD:
        raise HTTPException(status_code=401, detail="Invalid credentials")
    return TOKEN


def require_auth(authorization: str = Header(default="")):
    if authorization != f"Bearer {TOKEN}":
        raise HTTPException(status_code=401, detail="Unauthorized")
