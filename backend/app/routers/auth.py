from fastapi import APIRouter
from pydantic import BaseModel
from app.auth import login

router = APIRouter()


class LoginRequest(BaseModel):
    username: str
    password: str


@router.post("/login")
def do_login(body: LoginRequest):
    token = login(body.username, body.password)
    return {"token": token}
