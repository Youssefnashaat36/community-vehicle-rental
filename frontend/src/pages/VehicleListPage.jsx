import { useEffect, useState } from 'react'

function VehicleListPage({
  onSelectVehicle,
  onLogin,
  onAddVehicle,
  onAdminDashboard,
  authUser,
}) {
  const [vehicles, setVehicles] = useState([])
  const [myVehicles, setMyVehicles] = useState([])
  const [loading, setLoading] = useState(true)
  const [myVehiclesLoading, setMyVehiclesLoading] = useState(false)
  const [error, setError] = useState('')
  const [myVehiclesError, setMyVehiclesError] = useState('')
  const [requestingId, setRequestingId] = useState(null)
  const [requestMessage, setRequestMessage] = useState('')
  const [search, setSearch] = useState('')
  const [selectedType, setSelectedType] = useState('all')

  const getHeaders = () => {
    const token = localStorage.getItem('auth_token')

    return {
      Accept: 'application/json',
      Authorization: `Bearer ${token}`,
    }
  }

  // Load public vehicles
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
        setError('Could not connect to the server. Please try again.')
      } finally {
        setLoading(false)
      }
    }

    fetchVehicles()
  }, [])

  // Load all vehicles owned by the logged-in provider
  useEffect(() => {
    if (authUser?.role !== 'provider') {
      setMyVehicles([])
      return
    }

    const fetchMyVehicles = async () => {
      try {
        setMyVehiclesLoading(true)
        setMyVehiclesError('')

        const response = await fetch(
          'http://127.0.0.1:8000/api/provider/vehicles',
          {
            headers: getHeaders(),
          }
        )

        const data = await response.json()

        if (!response.ok) {
          throw new Error(data.message || 'Failed to load your vehicles.')
        }

        setMyVehicles(data)
      } catch (err) {
        setMyVehiclesError(err.message || 'Could not load your vehicles.')
      } finally {
        setMyVehiclesLoading(false)
      }
    }

    fetchMyVehicles()
  }, [authUser])

  const handleRequestDeletion = async (vehicle) => {
    const reason = window.prompt(
      `Why do you want to delete "${vehicle.name}"?`
    )

    if (reason === null) return

    if (!reason.trim()) {
      alert('Please enter a reason for deletion.')
      return
    }

    try {
      setRequestingId(vehicle.id)
      setRequestMessage('')

      const response = await fetch(
        `http://127.0.0.1:8000/api/provider/vehicles/${vehicle.id}/deletion-request`,
        {
          method: 'POST',
          headers: {
            ...getHeaders(),
            'Content-Type': 'application/json',
          },
          body: JSON.stringify({
            provider_reason: reason.trim(),
          }),
        }
      )

      const data = await response.json()

      if (!response.ok) {
        throw new Error(data.message || 'Could not submit deletion request.')
      }

      setMyVehicles((currentVehicles) =>
        currentVehicles.map((item) =>
          item.id === vehicle.id
            ? { ...item, deletion_status: 'pending' }
            : item
        )
      )

      setRequestMessage(
        `Deletion request for "${vehicle.name}" was submitted successfully.`
      )
    } catch (err) {
      alert(err.message || 'Something went wrong.')
    } finally {
      setRequestingId(null)
    }
  }

  const filteredVehicles = vehicles.filter((vehicle) => {
    const matchesSearch =
      (vehicle.name || '').toLowerCase().includes(search.toLowerCase()) ||
      (vehicle.pickup_location || '').toLowerCase().includes(search.toLowerCase())

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

  const getDeletionStatus = (vehicle) => {
    if (vehicle.deletion_status === 'pending') {
      return 'Deletion request pending'
    }

    if (vehicle.deletion_status === 'approved') {
      return 'Deletion approved'
    }

    if (vehicle.deletion_status === 'rejected') {
      return 'Deletion request rejected'
    }

    return 'No deletion request'
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
          {authUser?.role === 'provider' && (
            <a href="#my-vehicles">My Vehicles</a>
          )}
          <a href="#bookings">My Bookings</a>
        </div>

        {authUser?.role === 'provider' && (
          <button className="details-button" onClick={onAddVehicle}>
            + Add Vehicle
          </button>
        )}

        {authUser?.role === 'admin' && (
          <button className="details-button" onClick={onAdminDashboard}>
            Admin Dashboard
          </button>
        )}

        <button className="login-button" onClick={onLogin}>
          {authUser ? authUser.name : 'Login'}
        </button>
      </nav>

      <main>
        {authUser?.role === 'provider' && (
          <section className="vehicles-section" id="my-vehicles">
            <div className="section-heading">
              <div>
                <span className="section-label">PROVIDER DASHBOARD</span>
                <h2>My Vehicles</h2>
                <p>Manage your vehicles and deletion requests.</p>
              </div>
            </div>

            {myVehiclesLoading && (
              <p className="loading-message">Loading your vehicles...</p>
            )}

            {myVehiclesError && (
              <p className="error-message">{myVehiclesError}</p>
            )}

            {requestMessage && (
              <p className="success-message">{requestMessage}</p>
            )}

            {!myVehiclesLoading &&
              !myVehiclesError &&
              myVehicles.length === 0 && (
                <p className="empty-message">
                  You haven't added any vehicles yet.
                </p>
              )}

            {!myVehiclesLoading && myVehicles.length > 0 && (
              <div className="vehicle-grid">
                {myVehicles.map((vehicle) => (
                  <article className="vehicle-card" key={vehicle.id}>
                    <div className={`card-image ${vehicle.type}`}>
                      {vehicle.image ? (
                        <img src={vehicle.image} alt={vehicle.name} />
                      ) : (
                        <span className="vehicle-emoji">
                          {getVehicleEmoji(vehicle.type)}
                        </span>
                      )}
                    </div>

                    <div className="card-content">
                      <span className="vehicle-type">{vehicle.type}</span>

                      <h3>{vehicle.name}</h3>

                      <p className="vehicle-description">
                        {vehicle.description || 'No description available.'}
                      </p>

                      <p>
                        Vehicle status: <strong>{vehicle.status}</strong>
                      </p>

                      <p>
                        {getDeletionStatus(vehicle)}
                      </p>

                      {vehicle.deletion_status !== 'pending' &&
                        vehicle.deletion_status !== 'approved' && (
                          <button
                            className="details-button"
                            disabled={requestingId === vehicle.id}
                            onClick={() => handleRequestDeletion(vehicle)}
                          >
                            {requestingId === vehicle.id
                              ? 'Sending Request...'
                              : 'Request Deletion'}
                          </button>
                        )}
                    </div>
                  </article>
                ))}
              </div>
            )}
          </section>
        )}

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

            <button
              onClick={() => {
                document.getElementById('vehicles')?.scrollIntoView({
                  behavior: 'smooth',
                })
              }}
            >
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
              <span className="section-label">EXPLORE OUR VEHICLES</span>
              <h2>Choose your ride</h2>
              <p>Find a vehicle that fits your needs and budget.</p>
            </div>
          </div>

          <div className="filter-tabs">
            {[
              ['all', 'All'],
              ['car', '🚗 Cars'],
              ['scooter', '🛴 Scooters'],
              ['motorbike', '🏍️ Motorbikes'],
              ['bicycle', '🚲 Bicycles'],
            ].map(([type, label]) => (
              <button
                key={type}
                className={selectedType === type ? 'active' : ''}
                onClick={() => setSelectedType(type)}
              >
                {label}
              </button>
            ))}
          </div>

          {loading && (
            <p className="loading-message">Loading vehicles...</p>
          )}

          {error && <p className="error-message">{error}</p>}

          {!loading && !error && filteredVehicles.length === 0 && (
            <p className="empty-message">No vehicles found.</p>
          )}

          {!loading && !error && (
            <div className="vehicle-grid">
              {filteredVehicles.map((vehicle) => (
                <article className="vehicle-card" key={vehicle.id}>
                  <div className={`card-image ${vehicle.type}`}>
                    {vehicle.image ? (
                      <img src={vehicle.image} alt={vehicle.name} />
                    ) : (
                      <span className="vehicle-emoji">
                        {getVehicleEmoji(vehicle.type)}
                      </span>
                    )}
                  </div>

                  <div className="card-content">
                    <div className="card-top">
                      <span className="vehicle-type">{vehicle.type}</span>
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
                        onClick={() =>
                          onSelectVehicle({
                            ...vehicle,
                            pricePerHour: Number(vehicle.price_per_hour),
                            pickupLocation: vehicle.pickup_location,
                          })
                        }
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
