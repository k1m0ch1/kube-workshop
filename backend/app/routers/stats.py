from datetime import date, timedelta

from fastapi import APIRouter, Depends
from sqlalchemy.orm import Session

from app.auth import require_auth
from app.database import get_db
from app.models import Attendance, Leave, Employee

router = APIRouter(dependencies=[Depends(require_auth)])


@router.get("/attendance/summary")
def attendance_summary(db: Session = Depends(get_db)):
    today = date.today()
    result = []
    for offset in range(13, -1, -1):
        day = today - timedelta(days=offset)
        count = (
            db.query(Attendance)
            .filter(Attendance.date == day, Attendance.check_in.isnot(None))
            .count()
        )
        result.append({"date": day.isoformat(), "check_ins": count})
    return result


@router.get("/leave")
def list_leave(db: Session = Depends(get_db)):
    rows = db.query(Leave, Employee).join(Employee, Employee.id == Leave.employee_id).all()
    return [
        {
            "id": leave.id,
            "employee_name": emp.name,
            "avatar_url": emp.avatar_url,
            "type": leave.type,
            "date_start": leave.date_start.isoformat(),
            "date_end": leave.date_end.isoformat(),
            "status": leave.status,
        }
        for leave, emp in rows
    ]
