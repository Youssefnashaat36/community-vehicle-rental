const vehicles = [
  {
    id: 1,
    name: 'Toyota Corolla',
    type: 'car',
    pricePerHour: 500,
    pickupLocation: 'University Gate One',
    description:
      'Comfortable and reliable car for everyday trips.',
  },
  {
    id: 2,
    name: 'Electric Scooter',
    type: 'scooter',
    pricePerHour: 300,
    pickupLocation: 'Engineering Faculty Parking',
    description:
      'Easy and affordable transportation around the campus.',
  },
  {
    id: 3,
    name: 'BMW',
    type: 'car',
    pricePerHour: 500,
    pickupLocation: 'Dorms',
    description:
      'Premium driving experience for your journey.',
  },
]

function VehicleListPage({ onSelectVehicle }) {
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

      <main>
        <section className="hero-section">
          <div className="hero-badge">
            🚘 Community Vehicle Rental
          </div>

          <h1>
            Find your perfect
            <span> ride.</span>
          </h1>

          <p>
            Rent cars, scooters, motorcycles and bicycles
            from trusted vehicle providers in your community.
          </p>

          <div className="search-box">
            <span>🔎</span>

            <input
              type="text"
              placeholder="Search vehicles or pickup locations..."
            />

            <button>
              Search
            </button>
          </div>

          <div className="hero-stats">
            <div>
              <strong>50+</strong>
              <span>Vehicles available</span>
            </div>

            <div>
              <strong>20+</strong>
              <span>Trusted providers</span>
            </div>

            <div>
              <strong>4.9 ★</strong>
              <span>Average user rating</span>
            </div>
          </div>
        </section>

        <section className="vehicles-section" id="vehicles">
          <div className="section-heading">
            <div>
              <span className="section-label">
                EXPLORE OUR VEHICLES
              </span>

              <h2>Choose your ride</h2>

              <p>
                Find a vehicle that fits your needs and budget.
              </p>
            </div>
          </div>

          <div className="filter-tabs">
            <button className="active">
              All
            </button>

            <button>
              🚗 Cars
            </button>

            <button>
              🛴 Scooters
            </button>
          </div>

          <div className="vehicle-grid">
            {vehicles.map((vehicle) => (
              <article
                className="vehicle-card"
                key={vehicle.id}
              >
                <div
                  className={`card-image ${vehicle.type}`}
                >
                  <span className="vehicle-emoji">
                    {vehicle.type === 'car'
                      ? '🚗'
                      : '🛴'}
                  </span>
                </div>

                <div className="card-content">
                  <div className="card-top">
                    <span className="vehicle-type">
                      {vehicle.type}
                    </span>

                    <span className="rating">
                      ★ 4.8
                    </span>
                  </div>

                  <h3>{vehicle.name}</h3>

                  <p className="vehicle-description">
                    {vehicle.description}
                  </p>

                  <div className="card-location">
                    <span>📍</span>

                    <span>
                      {vehicle.pickupLocation}
                    </span>
                  </div>

                  <div className="card-bottom">
                    <div className="price">
                      <strong>
                        {vehicle.pricePerHour}
                      </strong>

                      <span>
                        EGP / hour
                      </span>
                    </div>

                    <button
                      className="details-button"
                      onClick={() =>
                        onSelectVehicle(vehicle)
                      }
                    >
                      View Details →
                    </button>
                  </div>
                </div>
              </article>
            ))}
          </div>
        </section>
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

export default VehicleListPage