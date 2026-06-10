// Shoppa Verifier — the inspection console. This is the trust engine: the
// Verifier is the ONLY role that can certify or reject a device. Queue →
// inspect → run checks → issue a UUID-signed Trust Certificate (or reject
// with a reason). Mirrors verifierNav (Inspect queue / Inspections) and the
// pending → in_review → verified ↘ rejected state machine.
const { Icon, Avatar, Badge, Button, CertBadge, VerifiedPill, Alert, Input } = window.ShoppaDesignSystem_cbc994;

const VERIFIER = 'Amina Yusuf';

const QUEUE_SEED = [
  { id: 'D-2041', model: 'iPhone 14 Pro', storage: '256GB', vendor: 'Otieno Electronics', imei: '356103275591824', serial: 'F2LXX1A2HG7K', price: '128,000', submitted: '2h ago', claimed: 'iPhone 14 Pro', imeiUnique: true, status: 'pending' },
  { id: 'D-2039', model: 'Samsung Galaxy S24 Ultra', storage: '512GB', vendor: 'Nairobi Mobiles', imei: '353915867142093', serial: 'RZ8W90KQ2LM', price: '142,500', submitted: '5h ago', claimed: 'Galaxy S24 Ultra', imeiUnique: true, status: 'in_review' },
  { id: 'D-2036', model: 'iPhone 11', storage: '64GB', vendor: 'CBD Gadgets', imei: '356103275591824', serial: 'C7GZ4K9PJCL', price: '78,000', submitted: '1d ago', claimed: 'iPhone 17 Pro Max', imeiUnique: false, status: 'pending' },
  { id: 'D-2034', model: 'Google Pixel 8', storage: '128GB', vendor: 'Otieno Electronics', imei: '357218091334562', serial: 'GP8A1182KK', price: '74,900', submitted: '1d ago', claimed: 'Pixel 8', imeiUnique: true, status: 'pending' },
];

const CHECKS = [
  { key: 'imei', label: 'IMEI is unique across active listings' },
  { key: 'hardware', label: 'Hardware matches the claimed model' },
  { key: 'serial', label: 'Serial number verified against manufacturer' },
  { key: 'theft', label: 'Not present in the theft registry' },
  { key: 'condition', label: 'Condition grade confirmed' },
];

// ── Shell ───────────────────────────────────────────────────────────────────
function Sidebar({ active, setActive }) {
  const items = [
    { label: 'Inspect queue', icon: 'shield', key: 'queue' },
    { label: 'Inspections', icon: 'cpu', key: 'inspections' },
  ];
  return (
    <aside style={{ position: 'absolute', insetBlock: 0, left: 0, width: 256, background: 'var(--stone-900)', display: 'flex', flexDirection: 'column', padding: '0 16px 16px' }}>
      <div style={{ height: 64, display: 'flex', alignItems: 'center', gap: 8 }}>
        <span style={{ fontSize: 20, fontWeight: 600, letterSpacing: '-0.025em', color: 'var(--white)' }}>Shoppa</span>
        <span style={{ padding: '2px 8px', borderRadius: 'var(--radius-full)', fontSize: 11, fontWeight: 500, background: 'rgba(16,185,129,0.18)', color: '#34d399', boxShadow: 'inset 0 0 0 1px rgba(16,185,129,0.3)' }}>verifier</span>
      </div>
      <nav style={{ flex: 1, marginTop: 8 }}>
        <ul style={{ listStyle: 'none', margin: 0, padding: 0, display: 'flex', flexDirection: 'column', gap: 2 }}>
          {items.map(it => {
            const on = active.startsWith(it.key) || (it.key === 'queue' && active === 'detail');
            return (
              <li key={it.key}>
                <a href="#" onClick={(e) => { e.preventDefault(); setActive(it.key); }}
                  style={{ display: 'flex', alignItems: 'center', gap: 12, padding: '8px 12px', borderRadius: 'var(--radius-lg)', textDecoration: 'none', font: 'var(--type-label)', color: on ? 'var(--white)' : 'var(--stone-400)', background: on ? 'var(--stone-800)' : 'transparent' }}>
                  <Icon name={it.icon} size={16} />{it.label}
                </a>
              </li>
            );
          })}
        </ul>
      </nav>
      <div style={{ borderTop: '1px solid var(--stone-800)', paddingTop: 16, display: 'flex', alignItems: 'center', gap: 12 }}>
        <Avatar name={VERIFIER} size="sm" />
        <div>
          <p style={{ margin: 0, font: 'var(--type-label)', color: 'var(--white)' }}>{VERIFIER}</p>
          <p style={{ margin: 0, font: 'var(--type-meta)', color: 'var(--stone-400)' }}>Verifier</p>
        </div>
      </div>
    </aside>
  );
}

function Topbar({ title }) {
  return (
    <header style={{ position: 'sticky', top: 0, zIndex: 10, height: 64, flexShrink: 0, display: 'flex', alignItems: 'center', gap: 16, padding: '0 24px', background: 'var(--white)', borderBottom: '1px solid var(--stone-200)', boxShadow: 'var(--shadow-xs)' }}>
      <h1 style={{ margin: 0, font: 'var(--type-label)', color: 'var(--stone-900)' }}>{title}</h1>
      <div style={{ marginLeft: 'auto', display: 'flex', alignItems: 'center', gap: 16 }}>
        <span style={{ position: 'relative', color: 'var(--stone-400)', display: 'inline-flex' }}>
          <Icon name="bell" size={20} />
          <span style={{ position: 'absolute', top: 1, right: 1, width: 7, height: 7, borderRadius: '50%', background: 'var(--emerald-500)' }}></span>
        </span>
        <Badge color="purple">Verifier</Badge>
        <div style={{ display: 'flex', alignItems: 'center', gap: 8 }}>
          <Avatar name={VERIFIER} size="sm" />
          <span style={{ font: 'var(--type-label)', color: 'var(--stone-700)' }}>{VERIFIER.split(' ')[0]}</span>
          <Icon name="chevron-d" size={16} style={{ color: 'var(--stone-400)' }} />
        </div>
      </div>
    </header>
  );
}

// ── Queue ───────────────────────────────────────────────────────────────────
function Queue({ rows, open }) {
  const th = { padding: '12px 20px', textAlign: 'left', font: 'var(--type-meta)', fontWeight: 600, color: 'var(--stone-500)', textTransform: 'uppercase', letterSpacing: 'var(--tracking-wider)' };
  const td = { padding: '14px 20px', font: 'var(--type-body)', color: 'var(--stone-600)', borderTop: '1px solid var(--stone-100)', verticalAlign: 'middle' };
  const pending = rows.filter(r => r.status === 'pending' || r.status === 'in_review').length;
  return (
    <>
      <div style={{ marginBottom: 24 }}>
        <h2 style={{ margin: 0, fontSize: 20, fontWeight: 600, color: 'var(--stone-900)', letterSpacing: '-0.025em' }}>Inspection queue</h2>
        <p style={{ margin: '4px 0 0', font: 'var(--type-body)', color: 'var(--stone-500)' }}>{pending} devices awaiting your certification</p>
      </div>
      <div style={{ background: 'var(--white)', borderRadius: 'var(--radius-xl)', boxShadow: 'inset 0 0 0 1px var(--ring-card), var(--elevation-card)', overflow: 'hidden' }}>
        <table style={{ width: '100%', borderCollapse: 'collapse' }}>
          <thead><tr style={{ background: 'var(--stone-50)' }}>
            <th style={th}>Device</th><th style={th}>IMEI</th><th style={th}>Vendor</th><th style={th}>Submitted</th><th style={th}>Status</th><th style={th}></th>
          </tr></thead>
          <tbody>
            {rows.map(r => (
              <tr key={r.id}>
                <td style={td}>
                  <p style={{ margin: 0, font: 'var(--type-label)', color: 'var(--stone-900)' }}>{r.model}</p>
                  <p style={{ margin: '2px 0 0', font: 'var(--type-meta)', color: 'var(--stone-400)' }}>{r.storage} · {r.id}</p>
                </td>
                <td style={{ ...td, font: 'var(--type-mono)', color: 'var(--stone-500)' }}>{r.imei}</td>
                <td style={td}>{r.vendor}</td>
                <td style={{ ...td, color: 'var(--stone-400)' }}>{r.submitted}</td>
                <td style={td}><CertBadge status={r.status} /></td>
                <td style={{ ...td, textAlign: 'right' }}>
                  <Button size="sm" variant="secondary" onClick={() => open(r.id)} iconRight={<Icon name="arrow-right" size={14} />}>Inspect</Button>
                </td>
              </tr>
            ))}
          </tbody>
        </table>
      </div>
    </>
  );
}

window.VerifierShell = { Sidebar, Topbar, Queue, QUEUE_SEED, CHECKS, VERIFIER };
