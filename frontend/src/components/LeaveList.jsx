export default function LeaveList({ leave }) {
  return (
    <div className="card">
      <h2>PTO / Sick Leave</h2>
      {leave.length === 0 ? (
        <div className="empty">No leave records.</div>
      ) : (
        <table>
          <thead>
            <tr>
              <th>Employee</th>
              <th>Type</th>
              <th>Dates</th>
              <th>Status</th>
            </tr>
          </thead>
          <tbody>
            {leave.map((l) => (
              <tr key={l.id}>
                <td className="emp-cell">
                  <img src={l.avatar_url} alt={l.employee_name} />
                  {l.employee_name}
                </td>
                <td>
                  <span className={`badge ${l.type}`}>{l.type}</span>
                </td>
                <td>
                  {l.date_start} → {l.date_end}
                </td>
                <td>
                  <span className={`badge ${l.status}`}>{l.status}</span>
                </td>
              </tr>
            ))}
          </tbody>
        </table>
      )}
    </div>
  );
}
