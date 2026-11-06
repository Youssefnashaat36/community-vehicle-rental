
import { useEffect, useState } from 'react'


function VehicleListPage({ onSelectVehicle, onLogin, authUser }) {
  const [vehicles, setVehicles] = useState([])
  const [loading, setLoading] = useState(true)
  const [error, setError] = useState('')
  const [search, setSearch] = useState('')
  const [selectedType, setSelectedType] = useState('all')

  useEffect(() => {
    const fetchVehicles = async () => {
      try {
        setLoading(true)
        setError('')

        const API = import.meta.env.VITE_API_URL
        const response = await fetch(`${API}/vehicles`)

        if (!response.ok) {
          throw new Error('Failed to load vehicles.')
        }

        const data = await response.json()
        setVehicles(data)
      } catch (err) {
        setError(
          'Could not connect to the server. Please try again.'
        )
      } finally {
        setLoading(false)
      }
    }

    fetchVehicles()
  }, [])

  const filteredVehicles = vehicles.filter((vehicle) => {
    const matchesSearch =
      vehicle.name.toLowerCase().includes(search.toLowerCase()) ||
      vehicle.pickup_location.toLowerCase().includes(search.toLowerCase())

    const matchesType =
      selectedType === 'all' || vehicle.type === selectedType

    return matchesSearch && matchesType
  })

  const getVehicleEmoji = (type) => {
    switch (type) {
      case 'car':
        return '🚗'
      case 'scooter':
        return '🛴'
      case 'motorbike':
        return '🏍️'
      case 'bicycle':
        return '🚲'
      default:
        return '🚘'
    }
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

        <button className="login-button" onClick={onLogin}>
  {authUser ? authUser.name : 'Login'}
</button>
      </nav>

      <main>
        <section className="hero-section" id="home">
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
              value={search}
              onChange={(e) => setSearch(e.target.value)}
            />

            <button onClick={() => {
              document.getElementById('vehicles')?.scrollIntoView({
                behavior: 'smooth'
              })
            }}>
              Search
            </button>
          </div>

          <div className="hero-stats">
            <div>
              <strong>{vehicles.length}</strong>
              <span>Vehicles available</span>
            </div>

            <div>
              <strong>Community</strong>
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
            <button
              className={selectedType === 'all' ? 'active' : ''}
              onClick={() => setSelectedType('all')}
            >
              All
            </button>

            <button
              className={selectedType === 'car' ? 'active' : ''}
              onClick={() => setSelectedType('car')}
            >
              🚗 Cars
            </button>

            <button
              className={selectedType === 'scooter' ? 'active' : ''}
              onClick={() => setSelectedType('scooter')}
            >
              🛴 Scooters
            </button>

            <button
              className={selectedType === 'motorbike' ? 'active' : ''}
              onClick={() => setSelectedType('motorbike')}
            >
              🏍️ Motorbikes
            </button>

            <button
              className={selectedType === 'bicycle' ? 'active' : ''}
              onClick={() => setSelectedType('bicycle')}
            >
              🚲 Bicycles
            </button>
          </div>

          {loading && (
            <p className="loading-message">
              Loading vehicles...
            </p>
          )}

          {error && (
            <p className="error-message">
              {error}
            </p>
          )}

          {!loading && !error && filteredVehicles.length === 0 && (
            <p className="empty-message">
              No vehicles found.
            </p>
          )}

          {!loading && !error && (
            <div className="vehicle-grid">
              {filteredVehicles.map((vehicle) => (
                <article
                  className="vehicle-card"
                  key={vehicle.id}
                >
                  <div className={`card-image ${vehicle.type}`}>
                    {vehicle.image ? (
                      <img
                        src={vehicle.image}
                        alt={vehicle.name}
                      />
                    ) : (
                      <span className="vehicle-emoji">
                        {getVehicleEmoji(vehicle.type)}
                      </span>
                    )}
                  </div>

                  <div className="card-content">
                    <div className="card-top">
                      <span className="vehicle-type">
                        {vehicle.type}
                      </span>
                    </div>

                    <h3>{vehicle.name}</h3>

                    <p className="vehicle-description">
                      {vehicle.description || 'No description available.'}
                    </p>

                    <div className="card-location">
                      <span>📍</span>
                      <span>{vehicle.pickup_location}</span>
                    </div>

                    <div className="card-bottom">
                      <div className="price">
                        <strong>
                          {Number(vehicle.price_per_hour).toLocaleString()}
                        </strong>

                        <span>EGP / hour</span>
                      </div>

                      <button
                        className="details-button"
                        onClick={() => onSelectVehicle({
                          ...vehicle,
                          pricePerHour: Number(vehicle.price_per_hour),
                          pickupLocation: vehicle.pickup_location
                        })}
                      >
                        View Details →
                      </button>
                    </div>
                  </div>
                </article>
              ))}
            </div>
          )}
        </section>
      </main>

      <footer>
        <div>
          <strong>CommunityRide</strong>
          <span> — Community Vehicle Rental Platform</span>
        </div>

        <span>© 2026 CommunityRide</span>
      </footer>
    </div>
  )
}

export default VehicleListPage
