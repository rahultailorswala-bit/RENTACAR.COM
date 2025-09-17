<?php
include 'db.php';
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
 
$car_id = isset($_GET['car_id']) ? (int)$_GET['car_id'] : 0;
$start_date = isset($_GET['start_date']) ? trim($_GET['start_date']) : '';
$end_date = isset($_GET['end_date']) ? trim($_GET['end_date']) : '';
$confirmation = '';
 
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_name = filter_var($_POST['user_name'], FILTER_SANITIZE_STRING);
    $user_email = filter_var($_POST['user_email'], FILTER_SANITIZE_EMAIL);
    if ($user_name && $user_email && $car_id && $start_date && $end_date) {
        $stmt = $conn->prepare("INSERT INTO bookings (car_id, user_name, user_email, start_date, end_date) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("issss", $car_id, $user_name, $user_email, $start_date, $end_date);
        $confirmation = $stmt->execute() ? "Booking confirmed! You'll receive a confirmation email soon." : "Error: Unable to process booking.";
        $stmt->close();
    } else {
        $confirmation = "Error: Invalid booking details.";
    }
}
 
if ($car_id) {
    $stmt = $conn->prepare("SELECT * FROM cars WHERE id = ?");
    $stmt->bind_param("i", $car_id);
    $stmt->execute();
    $car = $stmt->get_result()->fetch_assoc();
} else {
    $car = null;
}
$stmt->close();
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book a Car</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(135deg, #1e3c72, #2a5298);
            color: #fff;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        .booking-form {
            background: #fff;
            color: #333;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }
        .booking-form h2 {
            margin-top: 0;
            font-size: 2em;
        }
        .booking-form img {
            max-width: 100%;
            border-radius: 5px;
        }
        .booking-form input {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 1em;
        }
        .booking-form button {
            padding: 10px 20px;
            background: #ff6f61;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background 0.3s;
        }
        .booking-form button:hover {
            background: #e55a50;
        }
        .confirmation {
            margin: 20px 0;
            padding: 10px;
            background: #28a745;
            border-radius: 5px;
            text-align: center;
        }
        .error {
            background: #dc3545;
        }
        @media (max-width: 768px) {
            .booking-form {
                padding: 15px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <?php if ($car): ?>
            <div class="booking-form">
                <h2>Book <?php echo htmlspecialchars($car['brand'] . ' ' . $car['model']); ?></h2>
                <img src="<?php echo htmlspecialchars($car['image_url'] ?: 'https://via.placeholder.com/200'); ?>" alt="<?php echo htmlspecialchars($car['model']); ?>">
                <p>Price: $<?php echo htmlspecialchars(number_format($car['price_per_day'], 2)); ?>/day</p>
                <p>Rental Period: <?php echo htmlspecialchars($start_date); ?> to <?php echo htmlspecialchars($end_date); ?></p>
                <form method="POST">
                    <input type="text" name="user_name" placeholder="Your Name" required>
                    <input type="email" name="user_email" placeholder="Your Email" required>
                    <button type="submit">Confirm Booking</button>
                </form>
                <?php if ($confirmation): ?>
                    <div class="confirmation <?php echo strpos($confirmation, 'Error') !== false ? 'error' : ''; ?>">
                        <?php echo htmlspecialchars($confirmation); ?>
                    </div>
                <?php endif; ?>
            </div>
        <?php else: ?>
            <p class="error">Invalid car selection.</p>
        <?php endif; ?>
    </div>
</body>
</html>
