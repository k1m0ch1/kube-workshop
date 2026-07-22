export default function OnlineToday({ people }) {
  return (
    <div className="card">
      <h2>Online Today ({people.length})</h2>
      {people.length === 0 ? (
        <div className="empty">No one is checked in right now.</div>
      ) : (
        <div className="avatar-row">
          {people.map((p) => (
            <div className="avatar-item" key={p.id}>
              <img src={p.avatar_url} alt={p.name} />
              <span>{p.name.split(" ")[0]}</span>
            </div>
          ))}
        </div>
      )}
    </div>
  );
}
