import { useState } from 'react'

function VehicleAvailabilityPage({ vehicle, onBack, onContinue }) {
  const [date, setDate] = useState('')
  const [startTime, setStartTime] = useState('')
  const [endTime, setEndTime] = useState('')

  const calculateHours = () => {
    if (!startTime || !endTime) {
      return 0
    }

    const [startHour, startMinute] = startTime
      .split(':')
      .map(Number)

    const [endHour, endMinute] = endTime
      .split(':')
      .map(Number)

    const start = startHour * 60 + startMinute
    const end = endHour * 60 + endMinute

    if (end <= start) {
      return 0
    }

    return (end - start) / 60
  }

  const hours = calculateHours()
  const total = hours * vehicle.pricePerHour

  const handleContinue = () => {
    if (!date || !startTime || !endTime) {
      alert('Please select the date, start time and end time.')
      return
    }

    if (hours <= 0) {
      alert('End time must be after start time.')
      return
    }

    onContinue({
      date,
      startTime,
      endTime,
      hours,
      total,
    })
  }

  return (
    <div className="app-shell">
      <nav className="navbar">
        <div className="brand">
          <span className="brand-icon">🚗</span>
          <span>CommunityRide</span>
        </div>

        <div className="nav-links">
          <a href="#home">Home</a>
          <a href="#vehicles">Vehicles</a>
          <a href="#bookings">My Bookings</a>
        </div>

        <button className="login-button">
          Login
        </button>
      </nav>

      <main className="details-page">
        <button
          className="back-button"
          onClick={onBack}
        >
          ← Back to Vehicle
        </button>

        <div className="details-container">
          <div className={`details-image ${vehicle.type}`}>
            <span className="details-emoji">
              {vehicle.type === 'car' ? '🚗' : '🛴'}
            </span>

            <span className="available-badge">
              ● Available
            </span>
          </div>

          <div className="details-content">
            <span className="vehicle-category">
              {vehicle.type}
            </span>

            <h1>{vehicle.name}</h1>

            <div className="details-rating">
              ★ 4.8
              <span> Excellent vehicle</span>
            </div>

            <p className="details-description">
              Select your preferred date and rental time.
              The total price will be calculated automatically.
            </p>

            <div className="details-info">
              <div className="info-item">
                <span>📍</span>

                <div>
                  <small>Pickup Location</small>

                  <strong>
                    {vehicle.pickupLocation}
                  </strong>
                </div>
              </div>

              <div className="info-item">
                <span>💰</span>

                <div>
                  <small>Price</small>

                  <strong>
                    {vehicle.pricePerHour} EGP / hour
                  </strong>
                </div>
              </div>
            </div>

            <div className="availability-form">
              <div className="form-group">
                <label htmlFor="booking-date">
                  Rental Date
                </label>

                <input
                  id="booking-date"
                  type="date"
                  value={date}
                  onChange={(event) =>
                    setDate(event.target.value)
                  }
                />
              </div>

              <div className="time-grid">
                <div className="form-group">
                  <label htmlFor="start-time">
                    Start Time
                  </label>

                  <input
                    id="start-time"
                    type="time"
                    value={startTime}
                    onChange={(event) =>
                      setStartTime(event.target.value)
                    }
                  />
                </div>

                <div className="form-group">
                  <label htmlFor="end-time">
                    End Time
                  </label>

                  <input
                    id="end-time"
                    type="time"
                    value={endTime}
                    onChange={(event) =>
                      setEndTime(event.target.value)
                    }
                  />
                </div>
              </div>

              <div className="booking-box">
                <div>
                  <small>Estimated Total</small>

                  <div className="details-price">
                    <strong>
                      {total.toFixed(0)}
                    </strong>

                    <span>EGP</span>
                  </div>

                  {hours > 0 && (
                    <small>
                      {hours} hour
                      {hours !== 1 ? 's' : ''}
                    </small>
                  )}
                </div>

                <button
                  className="book-button"
                  onClick={handleContinue}
                >
                  Continue to Summary →
                </button>
              </div>
            </div>
          </div>
        </div>
      </main>

      <footer>
        <div>
          <strong>CommunityRide</strong>
          <span>
            {' '}— Community Vehicle Rental Platform
          </span>
        </div>

        <span>© 2026 CommunityRide</span>
      </footer>
    </div>
  )
}

export default VehicleAvailabilityPage