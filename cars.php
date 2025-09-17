<?php
include 'db.php';
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
 
$location = isset($_GET['location']) ? trim($_GET['location']) : '';
$start_date = isset($_GET['start_date']) ? trim($_GET['start_date']) : '';
$end_date = isset($_GET['end_date']) ? trim($_GET['end_date']) : '';
$car_type = isset($_GET['car_type']) ? trim($_GET['car_type']) : '';
$fuel_type = isset($_GET['fuel_type']) ? trim($_GET['fuel_type']) : '';
$brand = isset($_GET['brand']) ? trim($_GET['brand']) : '';
$sort = isset($_GET['sort']) ? trim($_GET['sort']) : 'price_asc';
 
$sql = "SELECT * FROM cars WHERE 1=1";
$params = [];
if ($location) {
    $sql .= " AND location LIKE ?";
    $params[] = "%$location%";
}
if ($car_type) {
    $sql .= " AND car_type = ?";
    $params[] = $car_type;
}
if ($fuel_type) {
    $sql .= " AND fuel_type = ?";
    $params[] = $fuel_type;
}
if ($brand) {
    $sql .= " AND brand = ?";
    $params[] = $brand;
}
if ($sort == 'price_asc') {
    $sql .= " ORDER BY price_per_day ASC";
} elseif ($sort == 'price_desc') {
    $sql .= " ORDER BY price_per_day DESC";
} elseif ($sort == 'rating') {
    $sql .= " ORDER BY rating DESC";
} else {
    $sort = 'price_asc';
    $sql .= " ORDER BY price_per_day ASC";
}
 
try {
    $stmt = $conn->prepare($sql);
    if ($params) {
        $types = str_repeat('s', count($params));
        $stmt->bind_param($types, ...$params);
    }
    $stmt->execute();
    $result = $stmt->get_result();
} catch (Exception $e) {
    die("Query failed: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Car Listings</title>
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
        .sort-bar {
            margin: 20px 0;
            text-align: right;
        }
        .sort-bar select {
            padding: 10px;
            font-size: 1em;
            border-radius: 5px;
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
        .car-card button {
            padding: 10px 20px;
            background: #ff6f61;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background 0.3s;
        }
        .car-card button:hover {
            background: #e55a50;
        }
        @media (max-width: 768px) {
            .sort-bar {
                text-align: center;
            }
            .sort-bar select {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Available Cars</h2>
        <div class="sort-bar">
            <select id="sort" onchange="sortCars()">
                <option value="price_asc" <?php echo $sort == 'price_asc' ? 'selected' : ''; ?>>Price: Low to High</option>
                <option value="price_desc" <?php echo $sort == 'price_desc' ? 'selected' : ''; ?>>Price: High to Low</option>
                <option value="rating" <?php echo $sort == 'rating' ? 'selected' : ''; ?>>Best Rated</option>
            </select>
        </div>
        <div class="car-grid">
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()): ?>
                    <div class="car-card">
                        <img src="<?php echo htmlspecialchars($row['image_url'] ?: 'https://via.placeholder.com/200'); ?>" alt="<?php echo htmlspecialchars($row['model']); ?>">
                        <h3><?php echo htmlspecialchars($row['brand'] . ' ' . $row['model']); ?></h3>
                        <p>$<?php echo htmlspecialchars(number_format($row['price_per_day'], 2)); ?>/day</p>
                        <p><?php echo htmlspecialchars($row['car_type']); ?>, <?php echo htmlspecialchars($row['fuel_type']); ?></p>
                        <p>Rating: <?php echo htmlspecialchars($row['rating']); ?>/5</p>
                        <button onclick="bookCar(<?php echo $row['id']; ?>, '<?php echo $start_date; ?>', '<?php echo $end_date; ?>')">Book Now</button>
                    </div>
                <?php endwhile;
            } else {
                echo "<p>No cars found matching your criteria.</p>";
            }
            ?>
        </div>
    </div>
    <script>
        function sortCars() {
            const sort = document.getElementById('sort').value;
            const url = new URL(window.location.href);
            url.searchParams.set('sort', sort);
            window.location.href = url.toString();
        }
        function bookCar(carId, startDate, endDate) {
            window.location.href = `booking.php?car_id=${carId}&start_date=${startDate}&end_date=${endDate}`;
        }
    </script>
</body>
</html>
<?php $stmt->close(); $conn->close(); ?>
