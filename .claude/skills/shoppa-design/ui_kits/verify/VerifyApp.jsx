// Shoppa public Trust Certificate lookup — the /verify/{identifier} surface.
// Anyone (no login) can look up a device by IMEI or serial and see its
// certificate. Light, public, trust-first. Outcomes: verified · expired ·
// rejected/flagged · not found.
const { Icon, Button, VerifiedPill, Badge, Alert } = window.ShoppaDesignSystem_cbc994;

// Seeded registry — try these.
const REGISTRY = {
  '356103275591824': {
    state: 'verified', model: 'iPhone 14 Pro', storage: '256GB', grade: 'A — Excellent',
    serial: 'F2LXX1A2HG7K', cert: '9f2a17c4-3b8e-4d21-a09f-77c3e1b4d8a2',
    issued: '02 May 2026', valid: '31 Jul 2026', vendor: 'Otieno Electronics', verifier: 'Amina Yusuf',
  },
  '353915867142093': {
    state: 'expired', model: 'Samsung Galaxy S24 Ultra', storage: '512GB', grade: 'A — Excellent',
    serial: 'RZ8W90KQ2LM', cert: '4b1c8d92-77a0-4e6f-9c11-2a5e7f0b9d34',
    issued: '08 Jan 2026', valid: '08 Apr 2026', vendor: 'Nairobi Mobiles', verifier: 'David Mwangi',
  },
  '356103275500000': {
    state: 'rejected', model: 'iPhone 11 (listed as iPhone 17 Pro Max)',
    reason: 'Hardware did not match the claimed model and the IMEI matched an existing active listing.',
  },
};

const SAMPLES = [
  { imei: '356103275591824', label: 'Verified' },
  { imei: '353915867142093', label: 'Expired' },
  { imei: '356103275500000', label: 'Flagged' },
  { imei: '000000000000000', label: 'Not found' },
];

function BrandBar() {
  return (
    <header style={{ height: 64, borderBottom: '1px solid var(--stone-200)', background: 'var(--white)', display: 'flex', alignItems: 'center', padding: '0 24px' }}>
      <div style={{ display: 'inline-flex', alignItems: 'center', gap: 8 }}>
        <span style={{ fontSize: 20, fontWeight: 600, letterSpacing: '-0.025em', color: 'var(--stone-900)' }}>Shoppa</span>
        <VerifiedPill size="sm">verified</VerifiedPill>
      </div>
      <span style={{ marginLeft: 'auto', font: 'var(--type-meta)', color: 'var(--stone-400)' }}>Public certificate lookup</span>
    </header>
  );
}

function Fact({ k, v, mono }) {
  return (
    <div style={{ display: 'flex', justifyContent: 'space-between', gap: 16, padding: '10px 0', borderTop: '1px solid var(--stone-100)' }}>
      <span style={{ font: 'var(--type-body)', color: 'var(--stone-500)' }}>{k}</span>
      <span style={{ font: mono ? 'var(--type-mono)' : 'var(--type-label)', color: 'var(--stone-900)', textAlign: 'right' }}>{v}</span>
    </div>
  );
}

function QrBlock() {
  return (
    <div style={{ display: 'flex', flexDirection: 'column', alignItems: 'center', gap: 8 }}>
      <div style={{ width: 96, height: 96, borderRadius: 'var(--radius-lg)', background: 'var(--white)', boxShadow: 'inset 0 0 0 1px var(--ring-card)', display: 'grid', placeContent: 'center', color: 'var(--stone-800)' }}>
        <Icon name="qr" size={56} strokeWidth={1.2} />
      </div>
      <span style={{ font: 'var(--type-meta)', color: 'var(--stone-400)' }}>Scan to re-verify</span>
    </div>
  );
}

function VerifiedCert({ d, imei }) {
  return (
    <div style={{ background: 'var(--white)', borderRadius: 'var(--radius-2xl)', boxShadow: 'inset 0 0 0 1px var(--ring-card), var(--shadow-md)', overflow: 'hidden' }}>
      <div style={{ background: 'var(--emerald-600)', color: 'var(--white)', padding: '24px 28px', display: 'flex', alignItems: 'center', justifyContent: 'space-between' }}>
        <div>
          <p style={{ margin: 0, font: 'var(--type-meta)', color: 'rgba(255,255,255,0.85)', textTransform: 'uppercase', letterSpacing: 'var(--tracking-wider)' }}>Trust Certificate</p>
          <p style={{ margin: '4px 0 0', fontSize: 20, fontWeight: 600 }}>This device is genuine</p>
        </div>
        <Icon name="shield-check" solid size={44} />
      </div>
      <div style={{ display: 'grid', gridTemplateColumns: '1fr auto', gap: 28, padding: 28, alignItems: 'start' }}>
        <div>
          <div style={{ display: 'flex', alignItems: 'center', gap: 12, marginBottom: 6 }}>
            <h2 style={{ margin: 0, fontSize: 18, fontWeight: 600, color: 'var(--stone-900)' }}>{d.model}</h2>
            <Badge color="emerald">{d.storage}</Badge>
          </div>
          <Fact k="IMEI" v={imei} mono />
          <Fact k="Serial" v={d.serial} mono />
          <Fact k="Condition grade" v={d.grade} />
          <Fact k="Certificate id" v={d.cert.slice(0, 18) + '…'} mono />
          <Fact k="Issued" v={d.issued} />
          <Fact k="Valid until" v={d.valid} />
          <Fact k="Sold by" v={d.vendor} />
          <Fact k="Verified by" v={`${d.verifier} · Licensed Verifier`} />
        </div>
        <QrBlock />
      </div>
    </div>
  );
}

function Result({ imei }) {
  const d = REGISTRY[imei];
  if (!d) {
    return (
      <div style={{ background: 'var(--white)', borderRadius: 'var(--radius-xl)', boxShadow: 'inset 0 0 0 1px var(--ring-card), var(--elevation-card)', padding: 28, textAlign: 'center' }}>
        <div style={{ width: 52, height: 52, margin: '0 auto 14px', borderRadius: 'var(--radius-full)', background: 'var(--stone-100)', color: 'var(--stone-400)', display: 'grid', placeContent: 'center' }}>
          <Icon name="search" size={26} />
        </div>
        <h3 style={{ margin: 0, fontSize: 17, fontWeight: 600, color: 'var(--stone-900)' }}>No certificate found</h3>
        <p style={{ margin: '6px 0 0', font: 'var(--type-body)', color: 'var(--stone-500)' }}>
          We have no Trust Certificate for <span style={{ font: 'var(--type-mono)' }}>{imei}</span>. If a seller claims it's Shoppa Verified, treat that with caution.
        </p>
      </div>
    );
  }
  if (d.state === 'verified') return <VerifiedCert d={d} imei={imei} />;
  if (d.state === 'expired') {
    return (
      <div style={{ display: 'flex', flexDirection: 'column', gap: 16 }}>
        <Alert type="warning" title="Certificate expired">
          This device was verified on {d.issued} but its certificate lapsed on {d.valid}. Ask the seller to re-submit it for inspection before purchase.
        </Alert>
        <div style={{ opacity: 0.6 }}><VerifiedCert d={d} imei={imei} /></div>
      </div>
    );
  }
  // rejected / flagged
  return (
    <div style={{ background: 'var(--white)', borderRadius: 'var(--radius-xl)', boxShadow: 'inset 0 0 0 1px var(--ring-card), var(--elevation-card)', overflow: 'hidden' }}>
      <div style={{ background: 'var(--red-600)', color: 'var(--white)', padding: '20px 28px', display: 'flex', alignItems: 'center', gap: 14 }}>
        <Icon name="x" size={26} strokeWidth={2.4} />
        <div>
          <p style={{ margin: 0, font: 'var(--type-meta)', color: 'rgba(255,255,255,0.85)', textTransform: 'uppercase', letterSpacing: 'var(--tracking-wider)' }}>Do not buy</p>
          <p style={{ margin: '3px 0 0', fontSize: 18, fontWeight: 600 }}>This device failed verification</p>
        </div>
      </div>
      <div style={{ padding: 28 }}>
        <Fact k="IMEI" v={imei} mono />
        <Fact k="Submitted as" v={d.model} />
        <p style={{ margin: '16px 0 0', font: 'var(--type-body)', color: 'var(--stone-600)', lineHeight: 1.6 }}>{d.reason}</p>
      </div>
    </div>
  );
}

function VerifyApp() {
  const [q, setQ] = React.useState('');
  const [submitted, setSubmitted] = React.useState(null);
  const go = (val) => { const v = (val ?? q).replace(/\s+/g, ''); setQ(v); setSubmitted(v); };

  return (
    <div style={{ minHeight: '100%', background: 'var(--stone-50)', fontFamily: 'var(--font-sans)' }}>
      <BrandBar />
      <div style={{ maxWidth: 600, margin: '0 auto', padding: '48px 24px 64px' }}>
        <div style={{ textAlign: 'center', marginBottom: 28 }}>
          <h1 style={{ margin: 0, fontSize: 28, fontWeight: 600, color: 'var(--stone-900)', letterSpacing: '-0.025em' }}>Verify a device</h1>
          <p style={{ margin: '8px 0 0', font: 'var(--type-base, 1rem)', fontSize: 15, color: 'var(--stone-500)', lineHeight: 1.6 }}>
            Buying second-hand? Enter the IMEI or serial number to check it against Shoppa's Trust Certificate registry — before any money changes hands.
          </p>
        </div>

        <form onSubmit={(e) => { e.preventDefault(); go(); }} style={{ display: 'flex', gap: 10, marginBottom: 16 }}>
          <div style={{ position: 'relative', flex: 1 }}>
            <span style={{ position: 'absolute', left: 14, top: '50%', transform: 'translateY(-50%)', color: 'var(--stone-400)' }}><Icon name="search" size={18} /></span>
            <input value={q} onChange={(e) => setQ(e.target.value)} placeholder="Enter IMEI or serial number"
              style={{ width: '100%', boxSizing: 'border-box', padding: '12px 14px 12px 42px', font: 'var(--type-mono)', fontSize: 14, color: 'var(--stone-900)', background: 'var(--white)', border: 'none', borderRadius: 'var(--radius-lg)', boxShadow: 'inset 0 0 0 1px var(--stone-300)', outline: 'none' }}
              onFocus={(e) => e.target.style.boxShadow = 'inset 0 0 0 2px var(--emerald-600)'}
              onBlur={(e) => e.target.style.boxShadow = 'inset 0 0 0 1px var(--stone-300)'} />
          </div>
          <Button type="submit" size="lg">Verify</Button>
        </form>

        <div style={{ display: 'flex', flexWrap: 'wrap', gap: 8, marginBottom: 32, alignItems: 'center' }}>
          <span style={{ font: 'var(--type-meta)', color: 'var(--stone-400)' }}>Try:</span>
          {SAMPLES.map(s => (
            <button key={s.imei} onClick={() => go(s.imei)}
              style={{ border: 'none', cursor: 'pointer', padding: '4px 10px', borderRadius: 'var(--radius-full)', background: 'var(--white)', boxShadow: 'inset 0 0 0 1px var(--stone-200)', font: 'var(--type-meta)', fontWeight: 500, color: 'var(--stone-600)' }}>
              {s.label}
            </button>
          ))}
        </div>

        {submitted && <Result imei={submitted} />}

        {!submitted && (
          <div style={{ textAlign: 'center', padding: '24px 0', color: 'var(--stone-400)' }}>
            <Icon name="qr" size={40} strokeWidth={1.2} style={{ margin: '0 auto 10px' }} />
            <p style={{ margin: 0, font: 'var(--type-body)' }}>Every verified device carries a QR-backed certificate. Scan it, or enter the number above.</p>
          </div>
        )}
      </div>
    </div>
  );
}

window.VerifyApp = VerifyApp;
