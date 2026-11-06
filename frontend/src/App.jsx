
import { useEffect, useState } from 'react'

import HomePage from './pages/HomePage'
import VehicleListPage from './pages/VehicleListPage'
import LoginPage from './pages/LoginPage'
import BookingSummaryPage from './pages/BookingSummaryPage'
import VehicleDetailsPage from './pages/VehicleDetailsPage'
import VehicleAvailabilityPage from './pages/VehicleAvailabilityPage'
import AddVehiclePage from './pages/AddVehiclePage'
import AdminDashboardPage from './pages/AdminDashboardPage'

const readHash = () => window.location.hash.replace('#', '') || 'home'

function App() {
  const [page, setPage] = useState(readHash)
  const [authUser, setAuthUser] = useState(null)
  const [selectedVehicle, setSelectedVehicle] = useState(null)
  const [bookingData, setBookingData] = useState(null)

  useEffect(() => {
    const onHash = () => setPage(readHash())

    window.addEventListener('hashchange', onHash)

    return () => window.removeEventListener('hashchange', onHash)
  }, [])

  const go = (p) => {
    window.location.hash = p === 'home' ? '' : p
  }

  const logout = () => {
    setAuthUser(null)
    go('home')
  }

  const handleSelectVehicle = (vehicle) => {
    setSelectedVehicle(vehicle)
    go('details')
  }

  const handleCheckAvailability = () => {
    go('availability')
  }

  const handleAvailabilityContinue = (data) => {
    setBookingData(data)
    go('summary')
  }

  const handleConfirmBooking = () => {
    alert('Booking confirmed successfully!')
    setBookingData(null)
    setSelectedVehicle(null)
    go('vehicles')
  }

  const handleBackToVehicles = () => {
    setSelectedVehicle(null)
    setBookingData(null)
    go('vehicles')
  }

  switch (page) {
    case 'vehicles':
      return (
        <VehicleListPage
          authUser={authUser}
          onLogin={() => go('login')}
          onSelectVehicle={handleSelectVehicle}
          onAddVehicle={() => go('addVehicle')}
          onAdminDashboard={() => go('adminDashboard')}
        />
      )

    case 'login':
      return (
        <LoginPage
          onBack={() => go('vehicles')}
          onLoginSuccess={(user) => {
            setAuthUser(user)
            go('vehicles')
          }}
        />
      )

    case 'details':
      return (
        <VehicleDetailsPage
          vehicle={selectedVehicle}
          onBack={handleBackToVehicles}
          onCheckAvailability={handleCheckAvailability}
        />
      )

    case 'availability':
      return (
        <VehicleAvailabilityPage
          vehicle={selectedVehicle}
          onBack={() => go('details')}
          onContinue={handleAvailabilityContinue}
        />
      )

    case 'summary':
      return (
        <BookingSummaryPage
          vehicle={selectedVehicle}
          booking={bookingData}
          onBack={() => go('availability')}
          onConfirm={handleConfirmBooking}
        />
      )

    case 'bookings':
      return (
        <BookingSummaryPage
          authUser={authUser}
        />
      )

    case 'addVehicle':
      return (
        <AddVehiclePage
          onBack={handleBackToVehicles}
          onSuccess={() => go('vehicles')}
        />
      )

    case 'adminDashboard':
      return (
        <AdminDashboardPage
          onBack={handleBackToVehicles}
        />
      )

    default:
      return (
        <HomePage
          onNavigate={go}
          authUser={authUser}
          onLogout={logout}
        />
      )
  }
}

export default App
