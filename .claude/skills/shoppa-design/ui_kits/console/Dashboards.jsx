// Dashboard content per role. Buyer + Vendor recreate their dashboard.blade
// views; Admin shows the dashboard plus the users table.
const { Button, StatCard, Badge, Avatar, CertBadge, VerifiedPill, Icon } = window.ShoppaDesignSystem_cbc994;

function Greeting({ name, sub }) {
  const hour = new Date().getHours();
  const part = hour < 12 ? 'morning' : hour < 17 ? 'afternoon' : 'evening';
  return (
    <div style={{ marginBottom: 32 }}>
      <h2 style={{ margin: 0, fontSize: 20, fontWeight: 600, color: 'var(--stone-900)', letterSpacing: '-0.025em' }}>
        Good {part}, {name.split(' ')[0]}
      </h2>
      <p style={{ margin: '4px 0 0', font: 'var(--type-body)', color: 'var(--stone-500)' }}>{sub}</p>
    </div>
  );
}

function StatRow({ stats }) {
  return (
    <div style={{ display: 'grid', gridTemplateColumns: 'repeat(4, 1fr)', gap: 16, marginBottom: 32 }}>
      {stats.map((s, i) => <StatCard key={i} {...s} />)}
    </div>
  );
}

function Panel({ title, action, children }) {
  return (
    <div style={{ background: 'var(--white)', borderRadius: 'var(--radius-xl)', boxShadow: 'inset 0 0 0 1px var(--ring-card), var(--elevation-card)', overflow: 'hidden' }}>
      <div style={{ display: 'flex', alignItems: 'center', justifyContent: 'space-between', padding: '16px 20px', borderBottom: '1px solid var(--stone-100)' }}>
        <h3 style={{ margin: 0, font: 'var(--type-label)', fontWeight: 600, color: 'var(--stone-900)' }}>{title}</h3>
        {action && <a href="#" onClick={(e) => e.preventDefault()} style={{ font: 'var(--type-meta)', fontWeight: 600, color: 'var(--emerald-600)', textDecoration: 'none' }}>{action}</a>}
      </div>
      {children}
    </div>
  );
}

function TrustCallout() {
  return (
    <div style={{ display: 'flex', alignItems: 'flex-start', gap: 16, marginBottom: 32, padding: 20, borderRadius: 'var(--radius-xl)', background: 'var(--emerald-50)', border: '1px solid var(--emerald-200)' }}>
      <VerifiedPill size="lg" />
      <div>
        <p style={{ margin: 0, font: 'var(--type-label)', fontWeight: 600, color: 'var(--emerald-900)' }}>Every device on Shoppa is physically inspected</p>
        <p style={{ margin: '2px 0 0', font: 'var(--type-body)', color: 'var(--emerald-700)' }}>
          Before a listing goes live, our verification team checks IMEI legitimacy, hardware authenticity, and condition grading.{' '}
          <a href="#" onClick={(e) => e.preventDefault()} style={{ fontWeight: 600, color: 'var(--emerald-800)', textDecoration: 'underline', textUnderlineOffset: 2 }}>Browse verified devices →</a>
        </p>
      </div>
    </div>
  );
}

function Row({ children }) {
  return (
    <li style={{ display: 'flex', alignItems: 'center', justifyContent: 'space-between', gap: 16, padding: '14px 20px', borderTop: '1px solid var(--stone-100)' }}>{children}</li>
  );
}

// ── Buyer ──────────────────────────────────────────────────────────────────
const ORDERS = [
  { id: 4821, ago: '2 hours ago', status: 'Escrow held', color: 'amber' },
  { id: 4799, ago: 'Yesterday', status: 'Delivered', color: 'emerald' },
  { id: 4756, ago: '4 days ago', status: 'Confirmed', color: 'stone' },
];

function BuyerDashboard() {
  return (
    <>
      <Greeting name={USER.buyer} sub="Here's what's happening with your account." />
      <StatRow stats={[
        { label: 'Active orders', value: '3', icon: 'box', iconColor: 'blue' },
        { label: 'Total orders', value: '17', icon: 'layers', iconColor: 'emerald' },
        { label: 'Wishlist', value: '5', icon: 'search', iconColor: 'purple' },
        { label: 'Verified devices', value: '12', icon: 'shield', iconColor: 'emerald' },
      ]} />
      <TrustCallout />
      <Panel title="Recent orders" action="View all">
        <ul style={{ listStyle: 'none', margin: 0, padding: 0 }}>
          {ORDERS.map(o => (
            <Row key={o.id}>
              <div>
                <p style={{ margin: 0, font: 'var(--type-label)', color: 'var(--stone-900)' }}>Order #{o.id}</p>
                <p style={{ margin: '2px 0 0', font: 'var(--type-meta)', color: 'var(--stone-400)' }}>{o.ago}</p>
              </div>
              <div style={{ display: 'flex', alignItems: 'center', gap: 12 }}>
                <Badge color={o.color}>{o.status}</Badge>
                <a href="#" onClick={(e) => e.preventDefault()} style={{ font: 'var(--type-meta)', fontWeight: 600, color: 'var(--emerald-600)', textDecoration: 'none' }}>View →</a>
              </div>
            </Row>
          ))}
        </ul>
      </Panel>
    </>
  );
}

// ── Vendor ─────────────────────────────────────────────────────────────────
const LISTINGS = [
  { name: 'iPhone 14 Pro · 256GB', price: '128,000.00', status: 'verified', certId: '9f2a17c4-3b8e', issuedAt: '2026-05-02' },
  { name: 'Samsung Galaxy S24 Ultra', price: '142,500.00', status: 'in_review' },
  { name: 'Google Pixel 8 · 128GB', price: '74,900.00', status: 'pending' },
  { name: 'iPhone 11 · 64GB', price: '32,000.00', status: 'rejected' },
];

function VendorDashboard() {
  return (
    <>
      <div style={{ display: 'flex', alignItems: 'flex-start', justifyContent: 'space-between', gap: 16, marginBottom: 32 }}>
        <div>
          <h2 style={{ margin: 0, fontSize: 20, fontWeight: 600, color: 'var(--stone-900)', letterSpacing: '-0.025em' }}>{USER.vendor}</h2>
          <p style={{ margin: '4px 0 0', font: 'var(--type-body)', color: 'var(--stone-500)' }}>Seller dashboard</p>
        </div>
        <Button size="sm" iconLeft={<Icon name="plus" size={16} />}>Add listing</Button>
      </div>
      <StatRow stats={[
        { label: 'Active listings', value: '9', icon: 'layers', iconColor: 'emerald' },
        { label: 'Awaiting verification', value: '2', icon: 'shield', iconColor: 'amber' },
        { label: 'Total sales', value: '41', icon: 'box', iconColor: 'blue' },
        { label: 'Balance (KSh)', value: '84,200.00', icon: 'store', iconColor: 'purple' },
      ]} />
      <Panel title="Recent listings" action="View all">
        <ul style={{ listStyle: 'none', margin: 0, padding: 0 }}>
          {LISTINGS.map((l, i) => (
            <Row key={i}>
              <div>
                <p style={{ margin: 0, font: 'var(--type-label)', color: 'var(--stone-900)' }}>{l.name}</p>
                <p style={{ margin: '2px 0 0', font: 'var(--type-meta)', color: 'var(--stone-400)' }}>KSh {l.price}</p>
              </div>
              <div style={{ display: 'flex', alignItems: 'center', gap: 16 }}>
                <CertBadge status={l.status} certId={l.certId} issuedAt={l.issuedAt} />
                <a href="#" onClick={(e) => e.preventDefault()} style={{ font: 'var(--type-meta)', fontWeight: 600, color: 'var(--emerald-600)', textDecoration: 'none' }}>Edit →</a>
              </div>
            </Row>
          ))}
        </ul>
      </Panel>
    </>
  );
}

// ── Admin ──────────────────────────────────────────────────────────────────
const USERS = [
  { name: 'Jane Wanjiru', email: 'jane@example.com', role: 'User', staff: false, joined: '02 May 2026' },
  { name: 'Otieno Electronics', email: 'sales@otieno.co.ke', role: 'Vendor', staff: false, joined: '21 Apr 2026' },
  { name: 'Amina Yusuf', email: 'amina@shoppa.co.ke', role: 'Verifier', staff: true, joined: '14 Mar 2026' },
  { name: 'David Mwangi', email: 'david@shoppa.co.ke', role: 'Customer Service', staff: true, joined: '03 Mar 2026' },
  { name: 'Grace Njeri', email: 'grace@example.com', role: 'User', staff: false, joined: '28 Feb 2026' },
];

function AdminDashboard({ active }) {
  if (active === 'users') return <AdminUsers />;
  return (
    <>
      <Greeting name={USER.admin} sub="Platform health at a glance." />
      <StatRow stats={[
        { label: 'Total users', value: '2,481', icon: 'users', iconColor: 'blue', trend: '+8%', trendDir: 'up' },
        { label: 'Active vendors', value: '156', icon: 'store', iconColor: 'emerald', trend: '+5%', trendDir: 'up' },
        { label: 'Devices verified', value: '1,038', icon: 'shield', iconColor: 'emerald', trend: '+12%', trendDir: 'up' },
        { label: 'Open disputes', value: '4', icon: 'flag', iconColor: 'red', trend: '−2', trendDir: 'down' },
      ]} />
      <AdminUsers compact />
    </>
  );
}

function AdminUsers({ compact }) {
  const th = { padding: '12px 20px', textAlign: 'left', font: 'var(--type-meta)', fontWeight: 600, color: 'var(--stone-500)', textTransform: 'uppercase', letterSpacing: 'var(--tracking-wider)' };
  const td = { padding: '12px 20px', font: 'var(--type-body)', color: 'var(--stone-600)', borderTop: '1px solid var(--stone-100)' };
  return (
    <div>
      {!compact && (
        <div style={{ display: 'flex', alignItems: 'center', justifyContent: 'space-between', gap: 16, marginBottom: 24 }}>
          <div>
            <h2 style={{ margin: 0, fontSize: 20, fontWeight: 600, color: 'var(--stone-900)', letterSpacing: '-0.025em' }}>Users</h2>
            <p style={{ margin: '4px 0 0', font: 'var(--type-body)', color: 'var(--stone-500)' }}>2,481 total accounts</p>
          </div>
          <Button size="sm" iconLeft={<Icon name="user" size={16} />}>Add staff account</Button>
        </div>
      )}
      <div style={{ background: 'var(--white)', borderRadius: 'var(--radius-xl)', boxShadow: 'inset 0 0 0 1px var(--ring-card), var(--elevation-card)', overflow: 'hidden' }}>
        {compact && (
          <div style={{ display: 'flex', alignItems: 'center', justifyContent: 'space-between', padding: '16px 20px', borderBottom: '1px solid var(--stone-100)' }}>
            <h3 style={{ margin: 0, font: 'var(--type-label)', fontWeight: 600, color: 'var(--stone-900)' }}>Recent accounts</h3>
            <a href="#" onClick={(e) => e.preventDefault()} style={{ font: 'var(--type-meta)', fontWeight: 600, color: 'var(--emerald-600)', textDecoration: 'none' }}>View all</a>
          </div>
        )}
        <table style={{ width: '100%', borderCollapse: 'collapse' }}>
          <thead>
            <tr style={{ background: 'var(--stone-50)' }}>
              <th style={th}>Name</th><th style={th}>Email</th><th style={th}>Role</th><th style={th}>Joined</th><th style={th}></th>
            </tr>
          </thead>
          <tbody>
            {USERS.map((u, i) => (
              <tr key={i}>
                <td style={td}>
                  <div style={{ display: 'flex', alignItems: 'center', gap: 12 }}>
                    <Avatar name={u.name} tone="soft" size="sm" />
                    <span style={{ font: 'var(--type-label)', color: 'var(--stone-900)' }}>{u.name}</span>
                  </div>
                </td>
                <td style={td}>{u.email}</td>
                <td style={td}><Badge color={u.staff ? 'purple' : 'stone'}>{u.role}</Badge></td>
                <td style={{ ...td, color: 'var(--stone-500)' }}>{u.joined}</td>
                <td style={{ ...td, textAlign: 'right' }}>
                  <a href="#" onClick={(e) => e.preventDefault()} style={{ font: 'var(--type-meta)', fontWeight: 600, color: 'var(--emerald-600)', textDecoration: 'none' }}>Edit</a>
                </td>
              </tr>
            ))}
          </tbody>
        </table>
      </div>
    </div>
  );
}

Object.assign(window, { BuyerDashboard, VendorDashboard, AdminDashboard });
