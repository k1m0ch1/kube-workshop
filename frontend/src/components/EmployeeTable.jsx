function fmt(iso) {
  if (!iso) return "-";
  return new Date(iso).toLocaleString([], { month: "short", day: "numeric", hour: "2-digit", minute: "2-digit" });
}

export default function EmployeeTable({ employees }) {
  return (
    <div className="card">
      <h2>Employees</h2>
      <table>
        <thead>
          <tr>
            <th>Employee</th>
            <th>Position</th>
            <th>Last Check-in</th>
            <th>Last Check-out</th>
          </tr>
        </thead>
        <tbody>
          {employees.map((e) => (
            <tr key={e.id}>
              <td className="emp-cell">
                <img src={e.avatar_url} alt={e.name} />
                {e.name}
              </td>
              <td>{e.position}</td>
              <td>{fmt(e.last_check_in)}</td>
              <td>{fmt(e.last_check_out)}</td>
            </tr>
          ))}
        </tbody>
      </table>
    </div>
  );
}
