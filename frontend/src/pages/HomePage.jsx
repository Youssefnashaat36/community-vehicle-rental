import { useEffect, useState } from 'react'
import Navbar from '../components/Navbar'
import HeroArt from '../components/HeroArt'

const API = import.meta.env.VITE_API_URL

const Stat = ({ value, label }) => (
  <div>
    <div className="text-4xl font-extrabold tracking-tight text-white">{value}</div>
    <div className="mt-1 text-[#8e96e6]">{label}</div>
  </div>
)

const Step = ({ n, title, text }) => (
  <div className="rounded-3xl border border-white/10 bg-white/[0.03] p-7">
    <div className="mb-4 grid h-11 w-11 place-items-center rounded-full bg-[#ffa62b] font-extrabold text-slate-900">{n}</div>
    <h3 className="text-xl font-bold text-white">{title}</h3>
    <p className="mt-2 text-[#8e96e6]">{text}</p>
  </div>
)

function HomePage({ onNavigate, authUser, onLogout }) {
  const [count, setCount] = useState(null)

  useEffect(() => {
    fetch(`${API}/vehicles`)
      .then((r) => r.json())
      .then((d) => setCount(Array.isArray(d) ? d.length : d.data?.length ?? 0))
      .catch(() => setCount(null))
  }, [])

  return (
    <div className="home-root min-h-screen bg-[#0a0d2b] font-sans text-white">
      <Navbar onNavigate={onNavigate} authUser={authUser} onLogout={onLogout} />

      <main className="mx-auto grid max-w-7xl items-center gap-12 px-6 pb-20 pt-6 lg:grid-cols-[1.1fr_1fr]">
        <section>
          <span className="inline-flex items-center gap-3 rounded-full border border-white/10 px-5 py-2.5 text-[#ffb94d]">
            <span className="h-2.5 w-2.5 rounded-full bg-[#ffa62b]" />
            Rent from people on your street
          </span>

          <h1 className="mt-10 text-6xl font-extrabold leading-[0.95] tracking-[-0.04em] text-[#eef0ff] md:text-[5.5rem]">
            Your neighbors<br />have wheels.<br />
            <span className="text-[#ffa62b]">Borrow them.</span>
          </h1>

          <p className="mt-8 max-w-xl text-xl leading-relaxed text-[#8e96e6]">
            CommunityRide connects you with cars, scooters, motorbikes and bicycles that locals
            share. Fair prices, verified hosts, and no counters or queues.
          </p>

          <div className="mt-10 flex flex-wrap gap-4">
            <button onClick={() => onNavigate('vehicles')}
              className="rounded-2xl bg-gradient-to-r from-[#ffa62b] to-[#ffbe55] px-9 py-5 text-lg font-bold text-slate-900 shadow-xl shadow-orange-500/30 hover:brightness-110">
              Browse vehicles →
            </button>
            <button onClick={() => onNavigate('login')}
              className="rounded-2xl border border-white/10 px-9 py-5 text-lg font-bold text-white hover:bg-white/10">
              Create free account
            </button>
          </div>

          <div className="mt-12 flex gap-14 border-t border-white/10 pt-10">
            <Stat value={count === null ? '—' : `${count}`} label="Shared vehicles" />
            <Stat value="48" label="Neighborhoods" />
            <Stat value="4.9" label="Average rating" />
          </div>
        </section>

        <HeroArt />
      </main>

      <section id="how-it-works" className="mx-auto max-w-7xl px-6 pb-24">
        <h2 className="mb-10 text-4xl font-extrabold tracking-tight">How it works</h2>
        <div className="grid gap-6 md:grid-cols-3">
          <Step n="1" title="Browse" text="Find a vehicle available in your community." />
          <Step n="2" title="Book" text="Pick your dates and confirm in seconds." />
          <Step n="3" title="Ride" text="Pick up from your neighbor and enjoy the ride." />
        </div>
      </section>
    </div>
  )
}

export default HomePage