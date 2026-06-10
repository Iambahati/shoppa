// Shoppa — Auth UI kit. Recreates the guest layout: warm stone canvas,
// brand lock-up + trust mark, a single white card hosting login / register /
// forgot-password. Composes DS primitives only.
const { Button, Input, Checkbox, VerifiedPill, Alert } = window.ShoppaDesignSystem_cbc994;

function BrandLock() {
  return (
    <a href="#" onClick={(e) => e.preventDefault()} style={{
      display: 'inline-flex', alignItems: 'center', gap: 8, textDecoration: 'none',
    }}>
      <span style={{ fontSize: 26, fontWeight: 600, letterSpacing: '-0.025em', color: 'var(--stone-900)' }}>Shoppa</span>
      <VerifiedPill size="sm">verified</VerifiedPill>
    </a>
  );
}

function Shell({ heading, subheading, children, footer }) {
  return (
    <div style={{
      minHeight: '100%', background: 'var(--stone-50)', fontFamily: 'var(--font-sans)',
      display: 'flex', flexDirection: 'column', justifyContent: 'center',
      padding: '48px 24px',
    }}>
      <div style={{ width: '100%', maxWidth: 420, margin: '0 auto', textAlign: 'center' }}>
        <BrandLock />
        <h1 style={{ margin: '24px 0 0', fontSize: 24, fontWeight: 600, color: 'var(--stone-900)' }}>{heading}</h1>
        {subheading && <p style={{ margin: '4px 0 0', fontSize: 14, color: 'var(--stone-500)' }}>{subheading}</p>}
      </div>
      <div style={{ width: '100%', maxWidth: 420, margin: '32px auto 0' }}>
        <div style={{
          background: 'var(--white)', borderRadius: 'var(--radius-xl)',
          boxShadow: 'inset 0 0 0 1px var(--ring-card), var(--shadow-sm)',
          padding: '32px 24px', textAlign: 'left',
        }}>
          {children}
        </div>
        {footer}
      </div>
    </div>
  );
}

function PasswordField({ label, name, error, autoComplete }) {
  const [show, setShow] = React.useState(false);
  return (
    <Input
      label={label} name={name} type={show ? 'text' : 'password'} required error={error}
      autoComplete={autoComplete}
      rightSlot={
        <button type="button" onClick={() => setShow(s => !s)}
          style={{ border: 'none', background: 'none', cursor: 'pointer', color: 'var(--stone-400)', font: 'var(--type-meta)', padding: 0 }}>
          {show ? 'Hide' : 'Show'}
        </button>
      }
    />
  );
}

function LoginForm({ go, onAuthed }) {
  const [err, setErr] = React.useState('');
  return (
    <form onSubmit={(e) => { e.preventDefault(); const f = new FormData(e.target);
      if (!f.get('email') || !f.get('password')) { setErr("Those credentials don't match our records."); return; } onAuthed(); }}
      style={{ display: 'flex', flexDirection: 'column', gap: 20 }}>
      <Input label="Email address" name="email" type="email" placeholder="you@example.com" required autoComplete="email" />
      <div style={{ display: 'flex', flexDirection: 'column', gap: 4 }}>
        <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center' }}>
          <span style={{ font: 'var(--type-label)', color: 'var(--stone-700)' }}>Password <span style={{ color: 'var(--red-500)' }}>*</span></span>
          <a href="#" onClick={(e) => { e.preventDefault(); go('forgot'); }}
            style={{ font: 'var(--type-meta)', fontWeight: 600, color: 'var(--emerald-600)', textDecoration: 'none' }}>Forgot password?</a>
        </div>
        <PasswordField name="password" error={err} autoComplete="current-password" />
      </div>
      <Checkbox name="remember" label="Keep me signed in" defaultChecked />
      <Button type="submit" fullWidth>Sign in</Button>
    </form>
  );
}

function RegisterForm({ onAuthed }) {
  return (
    <form onSubmit={(e) => { e.preventDefault(); onAuthed(); }} style={{ display: 'flex', flexDirection: 'column', gap: 20 }}>
      <Input label="Full name" name="name" placeholder="Jane Wanjiru" required autoComplete="name" />
      <Input label="Email address" name="email" type="email" placeholder="jane@example.com" required autoComplete="email" />
      <Input label="Phone number" name="phone" type="tel" placeholder="+254 7XX XXX XXX" hint="Used for order updates and delivery coordination." autoComplete="tel" />
      <PasswordField label="Password" name="password" autoComplete="new-password" />
      <div style={{
        display: 'flex', alignItems: 'flex-start', gap: 12,
        background: 'var(--stone-50)', boxShadow: 'inset 0 0 0 1px var(--stone-200)',
        borderRadius: 'var(--radius-lg)', padding: '12px 16px',
      }}>
        <VerifiedPill size="sm" />
        <p style={{ margin: 0, font: 'var(--type-meta)', color: 'var(--stone-500)', lineHeight: 1.55 }}>
          Every device on Shoppa is physically inspected before listing. Your purchase is protected by escrow until you confirm delivery.
        </p>
      </div>
      <Button type="submit" fullWidth>Create account</Button>
    </form>
  );
}

function ForgotForm({ go }) {
  const [sent, setSent] = React.useState(false);
  return (
    <form onSubmit={(e) => { e.preventDefault(); setSent(true); }} style={{ display: 'flex', flexDirection: 'column', gap: 20 }}>
      {sent && <Alert type="success">If that email is registered, a reset link is on its way.</Alert>}
      <Input label="Email address" name="email" type="email" placeholder="you@example.com" required autoComplete="email" />
      <Button type="submit" fullWidth>Email me a reset link</Button>
      <button type="button" onClick={() => go('login')}
        style={{ border: 'none', background: 'none', cursor: 'pointer', color: 'var(--stone-500)', font: 'var(--type-body)', textAlign: 'center' }}>
        ← Back to sign in
      </button>
    </form>
  );
}

function Authed({ reset }) {
  return (
    <Shell heading="You're in" subheading="Signed in to your Shoppa account">
      <div style={{ textAlign: 'center', padding: '8px 0' }}>
        <div style={{
          width: 56, height: 56, margin: '0 auto 16px', borderRadius: 'var(--radius-full)',
          background: 'var(--emerald-50)', color: 'var(--emerald-600)',
          display: 'grid', placeContent: 'center',
        }}>
          <svg width="30" height="30" viewBox="0 0 20 20" fill="currentColor"><path fillRule="evenodd" d="M16.403 12.652a3 3 0 000-5.304 3 3 0 00-3.75-3.751 3 3 0 00-5.305 0 3 3 0 00-3.751 3.75 3 3 0 000 5.305 3 3 0 003.75 3.751 3 3 0 005.305 0 3 3 0 003.751-3.75zm-2.546-4.46a.75.75 0 00-1.214-.883l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clipRule="evenodd"/></svg>
        </div>
        <p style={{ margin: '0 0 20px', font: 'var(--type-body)', color: 'var(--stone-600)' }}>
          Welcome to a marketplace where every device is verified.
        </p>
        <Button variant="secondary" onClick={reset}>Sign out</Button>
      </div>
    </Shell>
  );
}

function AuthApp() {
  const [view, setView] = React.useState('login');
  if (view === 'authed') return <Authed reset={() => setView('login')} />;

  const config = {
    login: { heading: 'Welcome back', subheading: 'Sign in to your Shoppa account' },
    register: { heading: 'Create your account', subheading: 'Buy and sell verified electronics with confidence' },
    forgot: { heading: 'Reset your password', subheading: "We'll email you a secure reset link" },
  }[view];

  const footer = view !== 'forgot' && (
    <p style={{ margin: '24px 0 0', textAlign: 'center', font: 'var(--type-body)', color: 'var(--stone-500)' }}>
      {view === 'login' ? "Don't have an account? " : 'Already have an account? '}
      <a href="#" onClick={(e) => { e.preventDefault(); setView(view === 'login' ? 'register' : 'login'); }}
        style={{ fontWeight: 600, color: 'var(--emerald-600)', textDecoration: 'none' }}>
        {view === 'login' ? 'Create one free' : 'Sign in'}
      </a>
    </p>
  );

  return (
    <Shell heading={config.heading} subheading={config.subheading} footer={footer}>
      {view === 'login' && <LoginForm go={setView} onAuthed={() => setView('authed')} />}
      {view === 'register' && <RegisterForm onAuthed={() => setView('authed')} />}
      {view === 'forgot' && <ForgotForm go={setView} />}
    </Shell>
  );
}

window.AuthApp = AuthApp;
