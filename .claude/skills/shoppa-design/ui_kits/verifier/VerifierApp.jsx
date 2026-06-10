// Inspection detail + certify/reject flow + the issued Trust Certificate.
const { Icon, Badge, Button, CertBadge, VerifiedPill, Alert } = window.ShoppaDesignSystem_cbc994;
const { Sidebar, Topbar, Queue, QUEUE_SEED, CHECKS, VERIFIER } = window.VerifierShell;

const PHOTOS = ['Front', 'Back', 'IMEI screen', 'Box & seal'];

function PhotoTile({ label }) {
  return (
    <div style={{ aspectRatio: '1 / 1', borderRadius: 'var(--radius-lg)', background: 'var(--stone-100)', boxShadow: 'inset 0 0 0 1px var(--ring-card)', display: 'flex', flexDirection: 'column', alignItems: 'center', justifyContent: 'center', gap: 6, color: 'var(--stone-400)' }}>
      <Icon name="package" size={22} />
      <span style={{ font: 'var(--type-meta)' }}>{label}</span>
    </div>
  );
}

function Fact({ k, v, mono }) {
  return (
    <div style={{ display: 'flex', justifyContent: 'space-between', gap: 16, padding: '9px 0', borderTop: '1px solid var(--stone-100)' }}>
      <span style={{ font: 'var(--type-body)', color: 'var(--stone-500)' }}>{k}</span>
      <span style={{ font: mono ? 'var(--type-mono)' : 'var(--type-label)', color: 'var(--stone-900)', textAlign: 'right' }}>{v}</span>
    </div>
  );
}

function Panel({ title, children, right }) {
  return (
    <div style={{ background: 'var(--white)', borderRadius: 'var(--radius-xl)', boxShadow: 'inset 0 0 0 1px var(--ring-card), var(--elevation-card)', overflow: 'hidden' }}>
      <div style={{ display: 'flex', alignItems: 'center', justifyContent: 'space-between', padding: '14px 20px', borderBottom: '1px solid var(--stone-100)' }}>
        <h3 style={{ margin: 0, font: 'var(--type-label)', fontWeight: 600, color: 'var(--stone-900)' }}>{title}</h3>
        {right}
      </div>
      <div style={{ padding: 20 }}>{children}</div>
    </div>
  );
}

function Detail({ device, back, onResolve }) {
  const [checked, setChecked] = React.useState({});
  const [rejecting, setRejecting] = React.useState(false);
  const mismatch = device.claimed !== device.model;
  const blocker = !device.imeiUnique || mismatch;
  const allChecked = CHECKS.every(c => checked[c.key]);

  const toggle = (k) => setChecked(s => ({ ...s, [k]: !s[k] }));

  return (
    <>
      <button onClick={back} style={{ border: 'none', background: 'none', cursor: 'pointer', color: 'var(--stone-500)', font: 'var(--type-body)', padding: 0, marginBottom: 16, display: 'inline-flex', alignItems: 'center', gap: 6 }}>
        <Icon name="chevron-r" size={14} style={{ transform: 'rotate(180deg)' }} /> Back to queue
      </button>

      <div style={{ display: 'flex', alignItems: 'flex-start', justifyContent: 'space-between', gap: 16, marginBottom: 24 }}>
        <div>
          <h2 style={{ margin: 0, fontSize: 20, fontWeight: 600, color: 'var(--stone-900)', letterSpacing: '-0.025em' }}>{device.model} · {device.storage}</h2>
          <p style={{ margin: '4px 0 0', font: 'var(--type-body)', color: 'var(--stone-500)' }}>{device.id} · submitted by {device.vendor} · {device.submitted}</p>
        </div>
        <CertBadge status={device.status} />
      </div>

      {!device.imeiUnique && (
        <div style={{ marginBottom: 20 }}>
          <Alert type="error" title="IMEI collision — block this listing">
            This IMEI is already attached to an active listing. Duplicate IMEIs are rejected at the database level and may indicate a cloned or stolen device.
          </Alert>
        </div>
      )}
      {device.imeiUnique && mismatch && (
        <div style={{ marginBottom: 20 }}>
          <Alert type="warning" title="Model mismatch">
            Vendor listed this as “{device.claimed}”, but the hardware reads as {device.model}. Do not certify until resolved.
          </Alert>
        </div>
      )}

      <div style={{ display: 'grid', gridTemplateColumns: '1fr 1fr', gap: 20, marginBottom: 20 }}>
        <Panel title="Device details">
          <Fact k="Claimed model" v={device.claimed} />
          <Fact k="Detected model" v={device.model} />
          <Fact k="IMEI" v={device.imei} mono />
          <Fact k="Serial" v={device.serial} mono />
          <Fact k="Asking price" v={`KSh ${device.price}`} />
          <Fact k="IMEI uniqueness" v={device.imeiUnique ? 'Unique ✓' : 'Duplicate ✗'} />
        </Panel>
        <Panel title="Submitted photos">
          <div style={{ display: 'grid', gridTemplateColumns: '1fr 1fr', gap: 12 }}>
            {PHOTOS.map(p => <PhotoTile key={p} label={p} />)}
          </div>
        </Panel>
      </div>

      <Panel title="Verification checklist" right={<span style={{ font: 'var(--type-meta)', color: 'var(--stone-400)' }}>{Object.values(checked).filter(Boolean).length}/{CHECKS.length} complete</span>}>
        <ul style={{ listStyle: 'none', margin: 0, padding: 0, display: 'flex', flexDirection: 'column', gap: 4 }}>
          {CHECKS.map(c => {
            const isImeiFail = c.key === 'imei' && !device.imeiUnique;
            const isHwFail = c.key === 'hardware' && mismatch;
            const disabled = isImeiFail || isHwFail;
            return (
              <li key={c.key}>
                <label style={{ display: 'flex', alignItems: 'center', gap: 12, padding: '9px 10px', borderRadius: 'var(--radius-lg)', cursor: disabled ? 'not-allowed' : 'pointer', background: checked[c.key] ? 'var(--emerald-50)' : 'transparent' }}>
                  <span style={{ width: 20, height: 20, borderRadius: 'var(--radius-md)', display: 'grid', placeContent: 'center', flexShrink: 0, background: disabled ? 'var(--red-50)' : checked[c.key] ? 'var(--emerald-600)' : 'var(--white)', boxShadow: `inset 0 0 0 1.5px ${disabled ? 'var(--red-400)' : checked[c.key] ? 'var(--emerald-600)' : 'var(--stone-300)'}`, color: disabled ? 'var(--red-500)' : 'var(--white)' }}>
                    {disabled ? <Icon name="x" size={13} /> : checked[c.key] ? <Icon name="check" size={13} strokeWidth={2.5} /> : null}
                  </span>
                  <input type="checkbox" checked={!!checked[c.key]} disabled={disabled} onChange={() => toggle(c.key)} style={{ position: 'absolute', opacity: 0, pointerEvents: 'none' }} />
                  <span style={{ font: 'var(--type-body)', color: disabled ? 'var(--red-700)' : 'var(--stone-700)' }}>{c.label}{disabled ? ' — failed' : ''}</span>
                </label>
              </li>
            );
          })}
        </ul>

        <div style={{ display: 'flex', alignItems: 'center', gap: 12, marginTop: 16, paddingTop: 16, borderTop: '1px solid var(--stone-100)' }}>
          {!rejecting ? (
            <>
              <Button disabled={blocker || !allChecked} iconLeft={<Icon name="check-badge" solid size={16} />} onClick={() => onResolve(device.id, 'verified')}>
                Issue Trust Certificate
              </Button>
              <Button variant="danger" onClick={() => onResolve(device.id, 'rejected')}>Reject device</Button>
              <span style={{ marginLeft: 'auto', font: 'var(--type-meta)', color: 'var(--stone-400)' }}>
                {blocker ? 'Blocked by a failed check' : allChecked ? 'All checks passed' : 'Complete all checks to certify'}
              </span>
            </>
          ) : null}
        </div>
      </Panel>
    </>
  );
}

function Issued({ device, cert, back }) {
  const issued = new Date();
  const expiry = new Date(issued.getTime() + 90 * 864e5);
  const fmt = (d) => d.toLocaleDateString('en-GB', { day: '2-digit', month: 'short', year: 'numeric' });
  const verified = device.lastStatus === 'verified';
  return (
    <>
      <button onClick={back} style={{ border: 'none', background: 'none', cursor: 'pointer', color: 'var(--stone-500)', font: 'var(--type-body)', padding: 0, marginBottom: 16, display: 'inline-flex', alignItems: 'center', gap: 6 }}>
        <Icon name="chevron-r" size={14} style={{ transform: 'rotate(180deg)' }} /> Back to queue
      </button>
      <div style={{ maxWidth: 540, margin: '0 auto' }}>
        {verified ? (
          <div style={{ background: 'var(--white)', borderRadius: 'var(--radius-2xl)', boxShadow: 'inset 0 0 0 1px var(--ring-card), var(--shadow-md)', overflow: 'hidden' }}>
            <div style={{ background: 'var(--emerald-600)', padding: '24px 28px', color: 'var(--white)', display: 'flex', alignItems: 'center', justifyContent: 'space-between' }}>
              <div>
                <p style={{ margin: 0, font: 'var(--type-meta)', color: 'rgba(255,255,255,0.8)', textTransform: 'uppercase', letterSpacing: 'var(--tracking-wider)' }}>Trust Certificate</p>
                <p style={{ margin: '4px 0 0', fontSize: 18, fontWeight: 600 }}>Shoppa Verified</p>
              </div>
              <Icon name="shield-check" solid size={40} />
            </div>
            <div style={{ padding: 28 }}>
              <Fact k="Device" v={`${device.model} · ${device.storage}`} />
              <Fact k="IMEI" v={device.imei} mono />
              <Fact k="Certificate id" v={cert.slice(0, 18) + '…'} mono />
              <Fact k="Issued" v={fmt(issued)} />
              <Fact k="Valid until" v={fmt(expiry)} />
              <Fact k="Verified by" v={`${VERIFIER} · Licensed Verifier`} />
              <div style={{ marginTop: 16 }}>
                <Alert type="success">Certificate issued and logged to the audit trail. The listing is now live and publicly verifiable at /verify/{device.imei}.</Alert>
              </div>
            </div>
          </div>
        ) : (
          <div style={{ background: 'var(--white)', borderRadius: 'var(--radius-2xl)', boxShadow: 'inset 0 0 0 1px var(--ring-card), var(--shadow-md)', padding: 28, textAlign: 'center' }}>
            <div style={{ width: 56, height: 56, margin: '0 auto 16px', borderRadius: 'var(--radius-full)', background: 'var(--red-50)', color: 'var(--red-600)', display: 'grid', placeContent: 'center' }}>
              <Icon name="x" size={28} strokeWidth={2} />
            </div>
            <h3 style={{ margin: 0, fontSize: 18, fontWeight: 600, color: 'var(--stone-900)' }}>Device rejected</h3>
            <p style={{ margin: '6px 0 0', font: 'var(--type-body)', color: 'var(--stone-500)' }}>
              {device.model} ({device.id}) will not be listed. The vendor has been notified and the decision logged to the audit trail.
            </p>
          </div>
        )}
      </div>
    </>
  );
}

function VerifierApp() {
  const [view, setView] = React.useState('queue');     // queue | detail | issued | inspections
  const [rows, setRows] = React.useState(QUEUE_SEED);
  const [activeId, setActiveId] = React.useState(null);
  const [cert, setCert] = React.useState(null);

  const device = rows.find(r => r.id === activeId);

  const open = (id) => { setActiveId(id); setView('detail'); };
  const resolve = (id, status) => {
    const newCert = (crypto.randomUUID ? crypto.randomUUID() : 'cert-' + Math.random().toString(16).slice(2));
    setRows(rs => rs.map(r => r.id === id ? { ...r, status, lastStatus: status } : r));
    setCert(newCert);
    setView('issued');
  };
  const title = view === 'queue' ? 'Inspect queue' : view === 'inspections' ? 'Inspections' : 'Device inspection';

  return (
    <div style={{ position: 'relative', minHeight: '100%', background: 'var(--stone-100)', fontFamily: 'var(--font-sans)' }}>
      <Sidebar active={view} setActive={(k) => { setView(k); }} />
      <div style={{ marginLeft: 256, minHeight: '100%', display: 'flex', flexDirection: 'column' }}>
        <Topbar title={title} />
        <main style={{ flex: 1, padding: 32 }}>
          <div style={{ maxWidth: 1000, margin: '0 auto' }}>
            {view === 'queue' && <Queue rows={rows} open={open} />}
            {view === 'inspections' && <Queue rows={rows} open={open} />}
            {view === 'detail' && device && <Detail device={device} back={() => setView('queue')} onResolve={resolve} />}
            {view === 'issued' && device && <Issued device={device} cert={cert} back={() => setView('queue')} />}
          </div>
        </main>
      </div>
    </div>
  );
}

window.VerifierApp = VerifierApp;
