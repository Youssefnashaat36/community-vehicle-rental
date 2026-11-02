function BookingSummaryPage({
  vehicle,
  booking,
  onBack,
  onConfirm,
}) {
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

      <main className="summary-page">
        <button
          className="back-button"
          onClick={onBack}
        >
          ← Back to Availability
        </button>

        <div className="summary-container">
          <div className="summary-header">
            <span>📋</span>

            <div>
              <span className="section-label">
                BOOKING SUMMARY
              </span>

              <h1>
                Review your booking
              </h1>

              <p>
                Make sure everything looks correct
                before confirming.
              </p>
            </div>
          </div>

          <div className="summary-grid">
            <div className="summary-card">
              <div
                className={`summary-image ${vehicle.type}`}
              >
                <span>
                  {vehicle.type === 'car'
                    ? '🚗'
                    : '🛴'}
                </span>
              </div>

              <div className="summary-vehicle">
                <span className="vehicle-category">
                  {vehicle.type}
                </span>

                <h2>{vehicle.name}</h2>

                <div className="summary-location">
                  📍 {vehicle.pickupLocation}
                </div>
              </div>
            </div>

            <div className="summary-card booking-details">
              <h2>Rental Details</h2>

              <div className="summary-row">
                <span>📅 Date</span>

                <strong>
                  {booking.date}
                </strong>
              </div>

              <div className="summary-row">
                <span>🕐 Start Time</span>

                <strong>
                  {booking.startTime}
                </strong>
              </div>

              <div className="summary-row">
                <span>🕐 End Time</span>

                <strong>
                  {booking.endTime}
                </strong>
              </div>

              <div className="summary-row">
                <span>⏱ Duration</span>

                <strong>
                  {booking.hours} hour
                  {booking.hours !== 1
                    ? 's'
                    : ''}
                </strong>
              </div>

              <div className="summary-divider" />

              <div className="summary-total">
                <span>Total</span>

                <strong>
                  {booking.total.toFixed(0)}
                  {' '}EGP
                </strong>
              </div>

              <button
                className="confirm-button"
                onClick={onConfirm}
              >
                Confirm Booking ✓
              </button>
            </div>
          </div>

          <div className="summary-note">
            🔒 Your booking information will be
            securely processed by the platform.
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

export default BookingSummaryPage