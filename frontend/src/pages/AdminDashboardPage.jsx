import { useEffect, useState } from 'react'

function AdminDashboardPage({ onBack }) {
  const [vehicles, setVehicles] = useState([])
  const [deletionRequests, setDeletionRequests] = useState([])

  const [loading, setLoading] = useState(true)
  const [deletionLoading, setDeletionLoading] = useState(true)

  const [error, setError] = useState('')
  const [deletionError, setDeletionError] = useState('')

  const [message, setMessage] = useState('')
  const [deletionMessage, setDeletionMessage] = useState('')

  const [processingId, setProcessingId] = useState(null)
  const [deletionProcessingId, setDeletionProcessingId] = useState(null)

  const getHeaders = () => {
    const token = localStorage.getItem('auth_token')

    return {
      Accept: 'application/json',
      Authorization: `Bearer ${token}`,
    }
  }

  // Fetch pending vehicle approvals.
  const fetchPendingVehicles = async () => {
    try {
      setLoading(true)
      setError('')

      const response = await fetch(
        'http://127.0.0.1:8000/api/admin/vehicles/pending',
        {
          headers: getHeaders(),
        }
      )

      const data = await response.json()

      if (!response.ok) {
        throw new Error(data.message || 'Failed to load pending vehicles.')
      }

      setVehicles(data)
    } catch (err) {
      setError(err.message || 'Could not connect to the server.')
    } finally {
      setLoading(false)
    }
  }

  // Fetch pending vehicle deletion requests.
  const fetchDeletionRequests = async () => {
    try {
      setDeletionLoading(true)
      setDeletionError('')

      const response = await fetch(
        'http://127.0.0.1:8000/api/admin/vehicle-deletion-requests',
        {
          headers: getHeaders(),
        }
      )

      const data = await response.json()

      if (!response.ok) {
        throw new Error(data.message || 'Failed to load deletion requests.')
      }

      setDeletionRequests(data)
    } catch (err) {
      setDeletionError(
        err.message || 'Could not load vehicle deletion requests.'
      )
    } finally {
      setDeletionLoading(false)
    }
  }

  // Refresh both dashboard sections.
  const refreshDashboard = () => {
    fetchPendingVehicles()
    fetchDeletionRequests()
  }

  useEffect(() => {
    refreshDashboard()
  }, [])

  // Approve a new vehicle.
  const handleApprove = async (vehicleId) => {
    try {
      setProcessingId(vehicleId)
      setError('')
      setMessage('')

      const response = await fetch(
        `http://127.0.0.1:8000/api/admin/vehicles/${vehicleId}/approve`,
        {
          method: 'PATCH',
          headers: getHeaders(),
        }
      )

      const data = await response.json()

      if (!response.ok) {
        throw new Error(data.message || 'Failed to approve vehicle.')
      }

      setVehicles((current) =>
        current.filter((vehicle) => vehicle.id !== vehicleId)
      )

      setMessage(data.message || 'Vehicle approved successfully.')
    } catch (err) {
      setError(err.message || 'Could not approve vehicle.')
    } finally {
      setProcessingId(null)
    }
  }

  // Reject a new vehicle.
  const handleReject = async (vehicleId) => {
    const reason = window.prompt('Please enter the rejection reason:')

    if (!reason || !reason.trim()) {
      return
    }

    try {
      setProcessingId(vehicleId)
      setError('')
      setMessage('')

      const response = await fetch(
        `http://127.0.0.1:8000/api/admin/vehicles/${vehicleId}/reject`,
        {
          method: 'PATCH',
          headers: {
            ...getHeaders(),
            'Content-Type': 'application/json',
          },
          body: JSON.stringify({
            rejection_reason: reason.trim(),
          }),
        }
      )

      const data = await response.json()

      if (!response.ok) {
        throw new Error(data.message || 'Failed to reject vehicle.')
      }

      setVehicles((current) =>
        current.filter((vehicle) => vehicle.id !== vehicleId)
      )

      setMessage(data.message || 'Vehicle rejected successfully.')
    } catch (err) {
      setError(err.message || 'Could not reject vehicle.')
    } finally {
      setProcessingId(null)
    }
  }

  // Approve a vehicle deletion request.
  const handleApproveDeletion = async (requestId) => {
    const confirmed = window.confirm(
      'Are you sure you want to approve this vehicle deletion request?'
    )

    if (!confirmed) {
      return
    }

    try {
      setDeletionProcessingId(requestId)
      setDeletionError('')
      setDeletionMessage('')

      const response = await fetch(
        `http://127.0.0.1:8000/api/admin/vehicle-deletion-requests/${requestId}/approve`,
        {
          method: 'PATCH',
          headers: getHeaders(),
        }
      )

      const data = await response.json()

      if (!response.ok) {
        throw new Error(data.message || 'Failed to approve deletion request.')
      }

      setDeletionRequests((current) =>
        current.filter((item) => item.id !== requestId)
      )

      setDeletionMessage(data.message || 'Deletion request approved.')
    } catch (err) {
      setDeletionError(
        err.message || 'Could not approve deletion request.'
      )
    } finally {
      setDeletionProcessingId(null)
    }
  }

  // Reject a vehicle deletion request.
  const handleRejectDeletion = async (requestId) => {
    const reason = window.prompt(
      'Please enter the reason for rejecting this deletion request:'
    )

    if (!reason || !reason.trim()) {
      return
    }

    try {
      setDeletionProcessingId(requestId)
      setDeletionError('')
      setDeletionMessage('')

      const response = await fetch(
        `http://127.0.0.1:8000/api/admin/vehicle-deletion-requests/${requestId}/reject`,
        {
          method: 'PATCH',
          headers: {
            ...getHeaders(),
            'Content-Type': 'application/json',
          },
          body: JSON.stringify({
            admin_reason: reason.trim(),
          }),
        }
      )

      const data = await response.json()

      if (!response.ok) {
        throw new Error(data.message || 'Failed to reject deletion request.')
      }

      setDeletionRequests((current) =>
        current.filter((item) => item.id !== requestId)
      )

      setDeletionMessage(data.message || 'Deletion request rejected.')
    } catch (err) {
      setDeletionError(
        err.message || 'Could not reject deletion request.'
      )
    } finally {
      setDeletionProcessingId(null)
    }
  }

  return (
    <div className="app-shell">

      {/* Navbar */}
      <nav className="navbar">
        <div className="brand">
          <span className="brand-icon">🚗</span>
          <span>CommunityRide Admin</span>
        </div>

        <button
          className="login-button"
          onClick={onBack}
        >
          Back to Vehicles
        </button>
      </nav>

      <main>
        <section className="vehicles-section">

          {/* Dashboard Heading */}
          <div className="section-heading">
            <div>
              <span className="section-label">
                ADMIN DASHBOARD
              </span>

              <h2>Vehicle Management</h2>

              <p>
                Review new vehicle submissions and manage deletion requests.
              </p>
            </div>

            <button
              className="details-button"
              onClick={refreshDashboard}
              disabled={loading || deletionLoading}
            >
              Refresh
            </button>
          </div>

          {/* ================================= */}
          {/* Pending Vehicle Approvals */}
          {/* ================================= */}

          <h2>Pending Vehicle Approvals</h2>

          <p>
            Pending vehicles: <strong>{vehicles.length}</strong>
          </p>

          {loading && (
            <p>Loading pending vehicles...</p>
          )}

          {error && (
            <p className="error-message">{error}</p>
          )}

          {message && (
            <p className="success-message">{message}</p>
          )}

          {!loading && !error && vehicles.length === 0 && (
            <p className="empty-message">
              There are no vehicles waiting for approval.
            </p>
          )}

          {!loading && vehicles.length > 0 && (
            <div className="vehicle-grid">

              {vehicles.map((vehicle) => (
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
                        {vehicle.type === 'car'
                          ? '🚗'
                          : vehicle.type === 'bicycle'
                            ? '🚲'
                            : vehicle.type === 'scooter'
                              ? '🛴'
                              : '🏍️'}
                      </span>
                    )}
                  </div>

                  <div className="card-content">

                    <span className="vehicle-type">
                      {vehicle.type}
                    </span>

                    <h3>{vehicle.name}</h3>

                    <p>
                      {vehicle.description || 'No description available.'}
                    </p>

                    <p>📍 {vehicle.pickup_location}</p>

                    <p>
                      💰 {Number(vehicle.price_per_hour).toLocaleString()} EGP / hour
                    </p>

                    <p>Provider ID: {vehicle.provider_id}</p>

                    <p>
                      Status: <strong>{vehicle.status}</strong>
                    </p>

                    <div className="card-bottom">

                      <button
                        className="details-button"
                        onClick={() => handleApprove(vehicle.id)}
                        disabled={processingId === vehicle.id}
                      >
                        {processingId === vehicle.id
                          ? 'Processing...'
                          : 'Approve'}
                      </button>

                      <button
                        className="login-button"
                        onClick={() => handleReject(vehicle.id)}
                        disabled={processingId === vehicle.id}
                      >
                        Reject
                      </button>

                    </div>
                  </div>
                </article>
              ))}

            </div>
          )}

          {/* ================================= */}
          {/* Vehicle Deletion Requests */}
          {/* ================================= */}

          <div className="section-heading" style={{ marginTop: '50px' }}>
            <div>
              <span className="section-label">
                DELETION MANAGEMENT
              </span>

              <h2>Vehicle Deletion Requests</h2>

              <p>
                Review providers' requests to remove their vehicles.
              </p>
            </div>
          </div>

          <p>
            Pending deletion requests:{' '}
            <strong>{deletionRequests.length}</strong>
          </p>

          {deletionLoading && (
            <p>Loading deletion requests...</p>
          )}

          {deletionError && (
            <p className="error-message">{deletionError}</p>
          )}

          {deletionMessage && (
            <p className="success-message">{deletionMessage}</p>
          )}

          {!deletionLoading &&
            !deletionError &&
            deletionRequests.length === 0 && (
              <p className="empty-message">
                There are no pending vehicle deletion requests.
              </p>
            )}

          {!deletionLoading && deletionRequests.length > 0 && (
            <div className="vehicle-grid">

              {deletionRequests.map((item) => {
                const vehicle = item.vehicle

                return (
                  <article
                    className="vehicle-card"
                    key={item.id}
                  >

                    <div className="card-image">
                      {vehicle?.image ? (
                        <img
                          src={vehicle.image}
                          alt={vehicle.name}
                        />
                      ) : (
                        <span className="vehicle-emoji">🚗</span>
                      )}
                    </div>

                    <div className="card-content">

                      <span className="vehicle-type">
                        Deletion Request #{item.id}
                      </span>

                      <h3>
                        {vehicle?.name || 'Vehicle information unavailable'}
                      </h3>

                      {vehicle && (
                        <>
                          <p>
                            Type: {vehicle.type}
                          </p>

                          <p>
                            📍 {vehicle.pickup_location}
                          </p>

                          <p>
                            Provider ID: {item.provider_id}
                          </p>

                          <p>
                            Vehicle Status: {vehicle.status}
                          </p>
                        </>
                      )}

                      <p>
                        <strong>Provider reason:</strong>
                      </p>

                      <p>
                        {item.provider_reason || 'No reason provided.'}
                      </p>

                      <p>
                        <strong>Request status:</strong>{' '}
                        {item.status}
                      </p>

                      <div className="card-bottom">

                        <button
                          className="details-button"
                          onClick={() => handleApproveDeletion(item.id)}
                          disabled={
                            deletionProcessingId === item.id ||
                            !vehicle
                          }
                        >
                          {deletionProcessingId === item.id
                            ? 'Processing...'
                            : 'Approve Deletion'}
                        </button>

                        <button
                          className="login-button"
                          onClick={() => handleRejectDeletion(item.id)}
                          disabled={deletionProcessingId === item.id}
                        >
                          Reject
                        </button>

                      </div>
                    </div>
                  </article>
                )
              })}

            </div>
          )}

        </section>
      </main>
    </div>
  )
}

export default AdminDashboardPage
