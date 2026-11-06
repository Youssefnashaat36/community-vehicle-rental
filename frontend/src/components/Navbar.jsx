function Navbar({ onNavigate, authUser, onLogout }) {
  const scrollToHow = () => {
    onNavigate('home')
    setTimeout(() => document.getElementById('how-it-works')?.scrollIntoView({ behavior: 'smooth' }), 50)
  }

  return (
    <header className="mx-auto flex max-w-7xl items-center justify-between px-6 py-8">
      <button onClick={() => onNavigate('home')} className="flex items-center gap-3">
        <span className="grid h-12 w-12 place-items-center rounded-2xl bg-[#ffa62b] text-slate-900">
          <svg viewBox="0 0 24 24" className="h-6 w-6" fill="none" stroke="currentColor" strokeWidth="2.4" strokeLinecap="round" strokeLinejoin="round">
            <path d="M5 16V11l2-5h10l2 5v5M3 16h18M7 19v-3M17 19v-3" />
          </svg>
        </span>
        <span className="text-2xl font-extrabold tracking-tight text-white">CommunityRide</span>
      </button>

      <nav className="hidden items-center gap-10 rounded-full border border-white/10 px-9 py-4 font-semibold text-[#8e96e6] md:flex">
        <button className="hover:text-white" onClick={() => onNavigate('vehicles')}>Vehicles</button>
        <button className="hover:text-white" onClick={scrollToHow}>How it works</button>
        <button className="hover:text-white" onClick={() => onNavigate(authUser ? 'vehicles' : 'login')}>Become a host</button>
      </nav>

      <div className="flex items-center gap-3">
        {authUser ? (
          <>
            <button onClick={() => onNavigate('bookings')} className="rounded-full border border-white/10 px-5 py-3 font-bold text-white hover:bg-white/10">My Bookings</button>
            <button onClick={onLogout} className="rounded-2xl bg-gradient-to-r from-[#ffa62b] to-[#ffbe55] px-6 py-3.5 font-bold text-slate-900">Log out</button>
          </>
        ) : (
          <>
            <button onClick={() => onNavigate('login')} className="rounded-full border border-white/10 px-5 py-3 font-bold text-white hover:bg-white/10">Sign in</button>
            <button onClick={() => onNavigate('login')} className="rounded-2xl bg-gradient-to-r from-[#ffa62b] to-[#ffbe55] px-6 py-3.5 font-bold text-slate-900 shadow-lg shadow-orange-500/30 hover:brightness-110">Get started</button>
          </>
        )}
      </div>
    </header>
  )
}

export default Navbar