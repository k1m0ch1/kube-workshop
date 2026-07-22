from datetime import date

from fastapi import APIRouter, Depends
from sqlalchemy.orm import Session

from app.auth import require_auth
from app.database import get_db
from app.models import Employee, Attendance

router = APIRouter(dependencies=[Depends(require_auth)])


@router.get("/employees")
def list_employees(db: Session = Depends(get_db)):
    employees = db.query(Employee).all()
    result = []
    for emp in employees:
        last = (
            db.query(Attendance)
            .filter(Attendance.employee_id == emp.id)
            .order_by(Attendance.date.desc())
            .first()
        )
        result.append(
            {
                "id": emp.id,
                "name": emp.name,
                "position": emp.position,
                "avatar_url": emp.avatar_url,
                "last_check_in": last.check_in.isoformat() if last and last.check_in else None,
                "last_check_out": last.check_out.isoformat() if last and last.check_out else None,
            }
        )
    return result


@router.get("/employees/online-today")
def online_today(db: Session = Depends(get_db)):
    today = date.today()
    rows = (
        db.query(Attendance, Employee)
        .join(Employee, Employee.id == Attendance.employee_id)
        .filter(Attendance.date == today, Attendance.check_in.isnot(None), Attendance.check_out.is_(None))
        .all()
    )
    return [
        {
            "id": emp.id,
            "name": emp.name,
            "avatar_url": emp.avatar_url,
            "check_in": att.check_in.isoformat(),
        }
        for att, emp in rows
    ]
