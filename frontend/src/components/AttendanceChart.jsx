import { BarChart, Bar, XAxis, YAxis, Tooltip, ResponsiveContainer } from "recharts";

export default function AttendanceChart({ data }) {
  const chartData = data.map((d) => ({
    date: new Date(d.date).toLocaleDateString([], { month: "short", day: "numeric" }),
    check_ins: d.check_ins,
  }));

  return (
    <div className="card">
      <h2>Check-ins (last 14 days)</h2>
      <ResponsiveContainer width="100%" height={220}>
        <BarChart data={chartData}>
          <XAxis dataKey="date" fontSize={12} />
          <YAxis allowDecimals={false} fontSize={12} />
          <Tooltip />
          <Bar dataKey="check_ins" fill="#2563eb" radius={[4, 4, 0, 0]} />
        </BarChart>
      </ResponsiveContainer>
    </div>
  );
}
