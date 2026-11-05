
import { useState } from 'react'
import './App.css'

import VehicleListPage from './pages/VehicleListPage'
import VehicleDetailsPage from './pages/VehicleDetailsPage'
import VehicleAvailabilityPage from './pages/VehicleAvailabilityPage'
import BookingSummaryPage from './pages/BookingSummaryPage'
import LoginPage from './pages/LoginPage'

function App() {
  const [currentPage, setCurrentPage] = useState('vehicles')
  const [selectedVehicle, setSelectedVehicle] = useState(null)
  const [bookingData, setBookingData] = useState(null)
  const [authUser, setAuthUser] = useState(null)

  const handleSelectVehicle = (vehicle) => {
    setSelectedVehicle(vehicle)
    setCurrentPage('details')
  }

  const handleCheckAvailability = () => {
    setCurrentPage('availability')
  }

  const handleAvailabilityContinue = (data) => {
    setBookingData(data)
    setCurrentPage('summary')
  }

  const handleBackToVehicles = () => {
    setSelectedVehicle(null)
    setBookingData(null)
    setCurrentPage('vehicles')
  }

  const handleBackToDetails = () => {
    setCurrentPage('details')
  }

  const handleBackToAvailability = () => {
    setCurrentPage('availability')
  }

  const handleConfirmBooking = () => {
    alert('Booking confirmed successfully!')

    setBookingData(null)
    setSelectedVehicle(null)
    setCurrentPage('vehicles')
  }

  const handleLoginSuccess = (user) => {
    setAuthUser(user)
    alert(`Welcome, ${user.name}!`)
    setCurrentPage('vehicles')
  }

  if (currentPage === 'login') {
    return (
      <LoginPage
        onBack={() => setCurrentPage('vehicles')}
        onLoginSuccess={handleLoginSuccess}
      />
    )
  }

  if (currentPage === 'details' && selectedVehicle) {
    return (
      <VehicleDetailsPage
        vehicle={selectedVehicle}
        onBack={handleBackToVehicles}
        onCheckAvailability={handleCheckAvailability}
      />
    )
  }

  if (currentPage === 'availability' && selectedVehicle) {
    return (
      <VehicleAvailabilityPage
        vehicle={selectedVehicle}
        onBack={handleBackToDetails}
        onContinue={handleAvailabilityContinue}
      />
    )
  }

  if (currentPage === 'summary' && selectedVehicle && bookingData) {
    return (
      <BookingSummaryPage
        vehicle={selectedVehicle}
        booking={bookingData}
        onBack={handleBackToAvailability}
        onConfirm={handleConfirmBooking}
      />
    )
  }

  return (
    <VehicleListPage
      onSelectVehicle={handleSelectVehicle}
      onLogin={() => setCurrentPage('login')}
      authUser={authUser}
    />
  )
}

export default App
