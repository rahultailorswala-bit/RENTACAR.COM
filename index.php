<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Car Rental - Home</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(135deg, #1e3c72, #2a5298);
            color: #fff;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            text-align: center;
            padding: 50px 0;
            background: rgba(0, 0, 0, 0.5);
            border-radius: 10px;
        }
        .header h1 {
            font-size: 3em;
            margin: 0;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
        }
        .search-bar {
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            justify-content: center;
        }
        .search-bar input, .search-bar select {
            padding: 12px;
            font-size: 1em;
            border: 1px solid #ccc;
            border-radius: 5px;
            width: 200px;
        }
        .search-bar button {
            padding: 12px 20px;
            background: #ff6f61;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background 0.3s;
        }
        .search-bar button:hover {
            background: #e55a50;
        }
        .featured {
            margin: 40px 0;
            text-align: center;
        }
        .featured h2 {
            font-size: 2em;
            margin-bottom: 20px;
        }
        .car-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
        }
        .car-card {
            background: #fff;
            color: #333;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            text-align: center;
        }
        .car-card img {
            max-width: 100%;
            border-radius: 5px;
        }
        .car-card h3 {
            margin: 10px 0;
            font-size: 1.5em;
        }
        .car-card p {
            margin: 5px 0;
            color: #555;
        }
        @media (max-width: 768px) {
            .search-bar {
                flex-direction: column;
                align-items: center;
            }
            .search-bar input, .search-bar select {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Welcome to Car Rental</h1>
            <p>Find the perfect car for your journey!</p>
        </div>
        <form id="searchForm" class="search-bar">
            <input type="text" id="location" placeholder="Pickup Location" required>
            <input type="date" id="start_date" required>
            <input type="date" id="end_date" required>
            <select id="car_type">
                <option value="">Select Car Type</option>
                <option value="sedan">Sedan</option>
                <option value="suv">SUV</option>
                <option value="luxury">Luxury</option>
            </select>
            <select id="fuel_type">
                <option value="">Select Fuel Type</option>
                <option value="petrol">Petrol</option>
                <option value="diesel">Diesel</option>
                <option value="electric">Electric</option>
            </select>
            <select id="brand">
                <option value="">Select Brand</option>
                <option value="toyota">Toyota</option>
                <option value="honda">Honda</option>
                <option value="bmw">BMW</option>
            </select>
            <button type="submit">Search Cars</button>
        </form>
        <div class="featured">
            <h2>Featured Cars</h2>
            <div class="car-grid">
                <div class="car-card">
                    <img src="https://via.placeholder.com/200" alt="Car">
                    <h3>Toyota Corolla</h3>
                    <p>$30/day</p>
                    <p>Sedan, Petrol</p>
                </div>
                <div class="car-card">
                    <img src="https://via.placeholder.com/200" alt="Car">
                    <h3>Honda CR-V</h3>
                    <p>$50/day</p>
                    <p>SUV, Diesel</p>
                </div>
                <div class="car-card">
                    <img src="https://via.placeholder.com/200" alt="Car">
                    <h3>BMW X5</h3>
                    <p>$80/day</p>
                    <p>Luxury, Electric</p>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.getElementById('searchForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const location = encodeURIComponent(document.getElementById('location').value.trim() || '');
            const start_date = encodeURIComponent(document.getElementById('start_date').value || '');
            const end_date = encodeURIComponent(document.getElementById('end_date').value || '');
            const car_type = encodeURIComponent(document.getElementById('car_type').value || '');
            const fuel_type = encodeURIComponent(document.getElementById('fuel_type').value || '');
            const brand = encodeURIComponent(document.getElementById('brand').value || '');
            if (start_date && end_date && start_date > end_date) {
                alert('Start date must be before end date.');
                return;
            }
            const query = `?location=${location}&start_date=${start_date}&end_date=${end_date}&car_type=${car_type}&fuel_type=${fuel_type}&brand=${brand}`;
            window.location.href = `cars.php${query}`;
        });
    </script>
</body>
</html>
