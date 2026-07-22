import { useEffect, useState } from "react";
import { useNavigate } from "react-router-dom";
import { getEmployees, getOnlineToday, getAttendanceSummary, getLeave } from "../api.js";
import OnlineToday from "../components/OnlineToday.jsx";
import EmployeeTable from "../components/EmployeeTable.jsx";
import AttendanceChart from "../components/AttendanceChart.jsx";
import LeaveList from "../components/LeaveList.jsx";

export default function Dashboard() {
  const [employees, setEmployees] = useState([]);
  const [online, setOnline] = useState([]);
  const [summary, setSummary] = useState([]);
  const [leave, setLeave] = useState([]);
  const navigate = useNavigate();

  useEffect(() => {
    Promise.all([getEmployees(), getOnlineToday(), getAttendanceSummary(), getLeave()])
      .then(([emp, onl, sum, lv]) => {
        setEmployees(emp);
        setOnline(onl);
        setSummary(sum);
        setLeave(lv);
      })
      .catch(() => {});
  }, []);

  function logout() {
    localStorage.removeItem("token");
    navigate("/login");
  }

  return (
    <div className="dashboard">
      <header>
        <h1>Employee Management</h1>
        <button onClick={logout}>Log out</button>
      </header>
      <OnlineToday people={online} />
      <AttendanceChart data={summary} />
      <EmployeeTable employees={employees} />
      <LeaveList leave={leave} />
    </div>
  );
}
