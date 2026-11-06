import { useEffect, useState } from 'react'
import HomePage from './pages/HomePage'
import VehicleListPage from './pages/VehicleListPage'
import LoginPage from './pages/LoginPage'
import BookingSummaryPage from './pages/BookingSummaryPage'
import VehicleDetailsPage from './pages/VehicleDetailsPage'
import VehicleAvailabilityPage from './pages/VehicleAvailabilityPage'

const readHash = () => window.location.hash.replace('#', '') || 'home'

function App() {
  const [page, setPage] = useState(readHash)
  const [authUser, setAuthUser] = useState(null)
  const [selectedVehicle, setSelectedVehicle] = useState(null)

  useEffect(() => {
    const onHash = () => setPage(readHash())
    window.addEventListener('hashchange', onHash)
    return () => window.removeEventListener('hashchange', onHash)
  }, [])

  const go = (p) => { window.location.hash = p === 'home' ? '' : p }
  const logout = () => { setAuthUser(null); go('home') }

  switch (page) {
    case 'vehicles':
      return <VehicleListPage authUser={authUser} onLogin={() => go('login')}
        onSelectVehicle={(v) => { setSelectedVehicle(v); go('details') }} />
    case 'login':
      return <LoginPage onLoginSuccess={(u) => { setAuthUser(u); go('vehicles') }} />
    case 'bookings':
      return <BookingSummaryPage authUser={authUser} />
    case 'details':
      return <VehicleDetailsPage vehicle={selectedVehicle} />
    case 'availability':
      return <VehicleAvailabilityPage vehicle={selectedVehicle} />
    default:
      return <HomePage onNavigate={go} authUser={authUser} onLogout={logout} />
  }
}

export default App