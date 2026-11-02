function VehicleDetailsPage({
  vehicle,
  onBack,
  onCheckAvailability,
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

      <main className="details-page">
        <button
          className="back-button"
          onClick={onBack}
        >
          ← Back to Vehicles
        </button>

        <div className="details-container">
          <div
            className={`details-image ${vehicle.type}`}
          >
            <span className="details-emoji">
              {vehicle.type === 'car'
                ? '🚗'
                : '🛴'}
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
              <span>
                {' '}Excellent vehicle
              </span>
            </div>

            <p className="details-description">
              {vehicle.description}
            </p>

            <div className="details-info">
              <div className="info-item">
                <span>📍</span>

                <div>
                  <small>
                    Pickup Location
                  </small>

                  <strong>
                    {vehicle.pickupLocation}
                  </strong>
                </div>
              </div>

              <div className="info-item">
                <span>🚘</span>

                <div>
                  <small>
                    Vehicle Type
                  </small>

                  <strong>
                    {vehicle.type}
                  </strong>
                </div>
              </div>

              <div className="info-item">
                <span>🟢</span>

                <div>
                  <small>
                    Availability
                  </small>

                  <strong>
                    Available now
                  </strong>
                </div>
              </div>
            </div>

            <div className="booking-box">
              <div>
                <small>
                  Starting from
                </small>

                <div className="details-price">
                  <strong>
                    {vehicle.pricePerHour}
                  </strong>

                  <span>
                    EGP / hour
                  </span>
                </div>
              </div>

              <button
                className="book-button"
                onClick={onCheckAvailability}
              >
                Check Availability →
              </button>
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

export default VehicleDetailsPage