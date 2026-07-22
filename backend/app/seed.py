import random
from datetime import date, datetime, timedelta

from sqlalchemy.orm import Session
from app.models import Employee, Attendance, Leave

NAMES = [
    ("Aditya Pratama", "Backend Engineer"),
    ("Sarah Wijaya", "Frontend Engineer"),
    ("Budi Santoso", "DevOps Engineer"),
    ("Citra Lestari", "Product Manager"),
    ("Dimas Prasetyo", "QA Engineer"),
    ("Eka Putri", "UI/UX Designer"),
    ("Fajar Nugraha", "Backend Engineer"),
    ("Gita Ramadhani", "HR Officer"),
]


def seed(db: Session):
    if db.query(Employee).first():
        return  # already seeded

    employees = [
        Employee(name=name, position=position, avatar_url=f"https://i.pravatar.cc/150?img={i + 1}")
        for i, (name, position) in enumerate(NAMES)
    ]
    db.add_all(employees)
    db.flush()  # assigns ids

    today = date.today()
    for day_offset in range(13, -1, -1):  # last 14 days, oldest first
        day = today - timedelta(days=day_offset)
        if day.weekday() >= 5:  # skip weekends
            continue
        for emp in employees:
            if random.random() < 0.1:  # ~10% absent
                continue
            check_in_hour = random.randint(7, 9)
            check_in_minute = random.randint(0, 59)
            check_in = datetime.combine(day, datetime.min.time()) + timedelta(
                hours=check_in_hour, minutes=check_in_minute
            )
            check_out = None
            is_today = day_offset == 0
            # a few people still "online" today (no check-out yet)
            if not (is_today and random.random() < 0.4):
                check_out_hour = random.randint(16, 19)
                check_out = datetime.combine(day, datetime.min.time()) + timedelta(
                    hours=check_out_hour, minutes=random.randint(0, 59)
                )
            db.add(Attendance(employee_id=emp.id, date=day, check_in=check_in, check_out=check_out))

    leave_types = ["pto", "sick"]
    for emp in random.sample(employees, 4):
        leave_type = random.choice(leave_types)
        start = today - timedelta(days=random.randint(1, 10))
        end = start + timedelta(days=random.randint(0, 3))
        db.add(
            Leave(
                employee_id=emp.id,
                type=leave_type,
                date_start=start,
                date_end=end,
                status=random.choice(["approved", "pending"]),
            )
        )

    db.commit()
