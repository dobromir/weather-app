import React, { useState } from 'react';
import axios from 'axios';

function WeatherApp() {

  const [city, setCity] = useState('');
  const [weather, setWeather] = useState(null);
  const [error, setError] = useState(null);
  const [loading, setLoading] = useState(false);

  const fetchWeather = async () => {
    if (!city.trim()) {
      setError('The city field is required.');
      return;
    }

    setLoading(true);
    setError(null);
    setWeather(null);

    try {
      const response = await axios.get(`http://localhost:8080/api/weather?city=${city}`);
      setWeather(response.data);
    } catch (err) {
      setError(err.response?.data?.error || 'Something went wrong.');
    } finally {
      setLoading(false);
    }
  };

  return (
      <div>
        <h1>Weather</h1>
        <div className="input-group mb-3">
          <input
              className={"form-control"}
              type="text"
              placeholder="City"
              value={city}
              onChange={(e) => setCity(e.target.value)}
          />
          <button className={"btn btn btn-primary"} onClick={fetchWeather}>
            Search
          </button>
        </div>

        {error && <p style={{ color: 'red' }}>{error}</p>}
        {loading && <p>Loading...</p>}

        {weather && (
            <div style={{ marginTop: '1rem', border: '1px solid #ddd', padding: '1rem', borderRadius: '5px' }}>
              <h2>Result for: {weather.city}</h2>
              <p>
                Temperature: <strong>{weather.temperature}</strong>
              </p>
            </div>
        )}
      </div>
  );
}

export default WeatherApp;
