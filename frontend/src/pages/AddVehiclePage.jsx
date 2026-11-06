import { useState } from 'react'

function AddVehiclePage({ onBack, onSuccess }) {
  const [formData, setFormData] = useState({
    type: 'car',
    name: '',
    description: '',
    image: '',
    price_per_hour: '',
    pickup_location: '',
  })

  const [loading, setLoading] = useState(false)
  const [error, setError] = useState('')
  const [success, setSuccess] = useState('')

  const handleChange = (e) => {
    setFormData({
      ...formData,
      [e.target.name]: e.target.value,
    })
  }

  const handleSubmit = async (e) => {
    e.preventDefault()
    setLoading(true)
    setError('')
    setSuccess('')

    try {
      const token = localStorage.getItem('auth_token')

      const response = await fetch('http://127.0.0.1:8000/api/vehicles', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          Accept: 'application/json',
          Authorization: `Bearer ${token}`,
        },
        body: JSON.stringify({
          ...formData,
          price_per_hour: Number(formData.price_per_hour),
        }),
      })

      const data = await response.json()

      if (!response.ok) {
        throw new Error(data.message || 'Failed to add vehicle.')
      }

      setSuccess(data.message)
      setFormData({
        type: 'car',
        name: '',
        description: '',
        image: '',
        price_per_hour: '',
        pickup_location: '',
      })

      if (onSuccess) {
        onSuccess()
      }
    } catch (err) {
      setError(err.message || 'Could not connect to the server.')
    } finally {
      setLoading(false)
    }
  }

  return (
    <div className="app-shell">
      <nav className="navbar">
        <div className="brand">
          <span className="brand-icon">🚗</span>
          <span>CommunityRide</span>
        </div>

        <button className="login-button" onClick={onBack}>
          Back to Vehicles
        </button>
      </nav>

      <main>
        <section className="vehicles-section">
          <div className="section-heading">
            <div>
              <span className="section-label">PROVIDER DASHBOARD</span>
              <h2>Add a New Vehicle</h2>
              <p>Enter your vehicle details below.</p>
            </div>
          </div>

          <form onSubmit={handleSubmit}>
            <div>
              <label htmlFor="type">Vehicle Type</label>
              <select
                id="type"
                name="type"
                value={formData.type}
                onChange={handleChange}
                required
              >
                <option value="bicycle">Bicycle</option>
                <option value="scooter">Scooter</option>
                <option value="motorbike">Motorbike</option>
                <option value="car">Car</option>
              </select>
            </div>

            <div>
              <label htmlFor="name">Vehicle Name</label>
              <input
                id="name"
                name="name"
                value={formData.name}
                onChange={handleChange}
                placeholder="e.g. Toyota Corolla"
                required
              />
            </div>

            <div>
              <label htmlFor="description">Description</label>
              <textarea
                id="description"
                name="description"
                value={formData.description}
                onChange={handleChange}
                placeholder="Describe your vehicle"
              />
            </div>

            <div>
              <label htmlFor="image">Image URL</label>
              <input
                id="image"
                name="image"
                type="url"
                value={formData.image}
                onChange={handleChange}
                placeholder="https://example.com/car.jpg"
              />
            </div>

            <div>
              <label htmlFor="price_per_hour">Price Per Hour</label>
              <input
                id="price_per_hour"
                name="price_per_hour"
                type="number"
                min="0"
                step="0.01"
                value={formData.price_per_hour}
                onChange={handleChange}
                placeholder="100"
                required
              />
            </div>

            <div>
              <label htmlFor="pickup_location">Pickup Location</label>
              <input
                id="pickup_location"
                name="pickup_location"
                value={formData.pickup_location}
                onChange={handleChange}
                placeholder="e.g. Cairo University"
                required
              />
            </div>

            {error && <p className="error-message">{error}</p>}
            {success && <p>{success}</p>}

            <button
              type="submit"
              className="details-button"
              disabled={loading}
            >
              {loading ? 'Submitting...' : 'Submit Vehicle'}
            </button>
          </form>
        </section>
      </main>
    </div>
  )
}

export default AddVehiclePage