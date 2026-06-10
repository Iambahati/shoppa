// Shoppa Console — the authenticated app shell. Dark stone sidebar, white
// sticky topbar, warm stone-100 canvas. Mirrors layouts/dashboard.blade.php
// and components/nav/*. A role switcher swaps the nav set + dashboard, the
// way Sidebar::buildNav() does server-side.
const { Icon, Avatar, Badge, VerifiedPill } = window.ShoppaDesignSystem_cbc994;

const NAV = {
  buyer: {
    label: 'Buyer', roleLabel: 'Buyer', staff: false,
    items: [
      { label: 'Dashboard', icon: 'home', key: 'dashboard' },
      { label: 'Browse', icon: 'search', key: 'browse' },
      { label: 'My orders', icon: 'box', key: 'orders' },
    ],
  },
  vendor: {
    label: 'Vendor', roleLabel: 'Vendor', staff: false,
    items: [
      { label: 'Dashboard', icon: 'home', key: 'dashboard' },
      { label: 'Listings', icon: 'layers', key: 'listings' },
    ],
  },
  admin: {
    label: 'Admin', roleLabel: 'Administrator', staff: true,
    items: [
      { label: 'Dashboard', icon: 'home', key: 'dashboard' },
      { label: 'Users', icon: 'users', key: 'users' },
      { label: 'Vendors', icon: 'store', key: 'vendors' },
      { label: 'Products', icon: 'package', key: 'products' },
      { label: 'Disputes', icon: 'flag', key: 'disputes' },
    ],
  },
};

const USER = { buyer: 'Jane Wanjiru', vendor: 'Otieno Electronics', admin: 'Amina Yusuf' };

function Sidebar({ role, active, setActive }) {
  const nav = NAV[role];
  return (
    <aside style={{
      position: 'absolute', insetBlock: 0, left: 0, width: 256,
      background: 'var(--stone-900)', display: 'flex', flexDirection: 'column',
      padding: '0 16px 16px',
    }}>
      <div style={{ height: 64, display: 'flex', alignItems: 'center', gap: 8 }}>
        <span style={{ fontSize: 20, fontWeight: 600, letterSpacing: '-0.025em', color: 'var(--white)' }}>Shoppa</span>
        <span style={{
          padding: '2px 8px', borderRadius: 'var(--radius-full)', fontSize: 11, fontWeight: 500,
          background: 'rgba(16,185,129,0.18)', color: '#34d399', boxShadow: 'inset 0 0 0 1px rgba(16,185,129,0.3)',
        }}>verified</span>
      </div>
      <nav style={{ flex: 1, marginTop: 8 }}>
        <ul style={{ listStyle: 'none', margin: 0, padding: 0, display: 'flex', flexDirection: 'column', gap: 2 }}>
          {nav.items.map(item => {
            const on = active === item.key;
            return (
              <li key={item.key}>
                <a href="#" onClick={(e) => { e.preventDefault(); setActive(item.key); }}
                  style={{
                    display: 'flex', alignItems: 'center', gap: 12, padding: '8px 12px',
                    borderRadius: 'var(--radius-lg)', textDecoration: 'none',
                    font: 'var(--type-label)',
                    color: on ? 'var(--white)' : 'var(--stone-400)',
                    background: on ? 'var(--stone-800)' : 'transparent',
                    transition: 'background var(--dur-fast), color var(--dur-fast)',
                  }}
                  onMouseEnter={(e) => { if (!on) { e.currentTarget.style.background = 'var(--stone-800)'; e.currentTarget.style.color = 'var(--white)'; } }}
                  onMouseLeave={(e) => { if (!on) { e.currentTarget.style.background = 'transparent'; e.currentTarget.style.color = 'var(--stone-400)'; } }}>
                  <Icon name={item.icon} size={16} />
                  {item.label}
                </a>
              </li>
            );
          })}
        </ul>
      </nav>
      <div style={{ borderTop: '1px solid var(--stone-800)', paddingTop: 16 }}>
        <div style={{ display: 'flex', alignItems: 'center', gap: 12 }}>
          <Avatar name={USER[role]} size="sm" />
          <div style={{ minWidth: 0 }}>
            <p style={{ margin: 0, font: 'var(--type-label)', color: 'var(--white)', whiteSpace: 'nowrap', overflow: 'hidden', textOverflow: 'ellipsis' }}>{USER[role]}</p>
            <p style={{ margin: 0, font: 'var(--type-meta)', color: 'var(--stone-400)' }}>{nav.roleLabel}</p>
          </div>
        </div>
      </div>
    </aside>
  );
}

function RoleSwitch({ role, setRole }) {
  return (
    <div style={{ display: 'inline-flex', padding: 3, gap: 2, background: 'var(--stone-100)', borderRadius: 'var(--radius-lg)' }}>
      {Object.keys(NAV).map(r => {
        const on = r === role;
        return (
          <button key={r} onClick={() => setRole(r)}
            style={{
              border: 'none', cursor: 'pointer', padding: '5px 12px', borderRadius: 'var(--radius-md)',
              font: 'var(--type-label)',
              background: on ? 'var(--white)' : 'transparent',
              color: on ? 'var(--stone-900)' : 'var(--stone-500)',
              boxShadow: on ? 'var(--shadow-xs)' : 'none',
            }}>
            {NAV[r].label}
          </button>
        );
      })}
    </div>
  );
}

function Topbar({ role, setRole, title }) {
  const nav = NAV[role];
  return (
    <header style={{
      position: 'sticky', top: 0, zIndex: 10, height: 64, flexShrink: 0,
      display: 'flex', alignItems: 'center', gap: 16, padding: '0 24px',
      background: 'var(--white)', borderBottom: '1px solid var(--stone-200)', boxShadow: 'var(--shadow-xs)',
    }}>
      <h1 style={{ margin: 0, font: 'var(--type-label)', color: 'var(--stone-900)' }}>{title}</h1>
      <div style={{ marginLeft: 'auto', display: 'flex', alignItems: 'center', gap: 16 }}>
        <RoleSwitch role={role} setRole={setRole} />
        <span style={{ position: 'relative', color: 'var(--stone-400)', display: 'inline-flex' }}>
          <Icon name="bell" size={20} />
          <span style={{ position: 'absolute', top: 1, right: 1, width: 7, height: 7, borderRadius: '50%', background: 'var(--emerald-500)' }}></span>
        </span>
        <Badge color={nav.staff ? 'purple' : 'emerald'}>{nav.roleLabel}</Badge>
        <div style={{ display: 'flex', alignItems: 'center', gap: 8 }}>
          <Avatar name={USER[role]} size="sm" />
          <span style={{ font: 'var(--type-label)', color: 'var(--stone-700)' }}>{USER[role].split(' ')[0]}</span>
          <Icon name="chevron-d" size={16} style={{ color: 'var(--stone-400)' }} />
        </div>
      </div>
    </header>
  );
}

function Console({ children, role, setRole, active, setActive, title }) {
  return (
    <div style={{ position: 'relative', minHeight: '100%', background: 'var(--stone-100)', fontFamily: 'var(--font-sans)' }}>
      <Sidebar role={role} active={active} setActive={setActive} />
      <div style={{ marginLeft: 256, minHeight: '100%', display: 'flex', flexDirection: 'column' }}>
        <Topbar role={role} setRole={setRole} title={title} />
        <main style={{ flex: 1, padding: 32 }}>
          <div style={{ maxWidth: 1100, margin: '0 auto' }}>{children}</div>
        </main>
      </div>
    </div>
  );
}

Object.assign(window, { Console, NAV, USER });
