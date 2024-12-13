<?php
// Start session to manage data
session_start();

// Database connection parameters
$servername = "localhost";
$username = "root";
$password = ""; // Replace with your database password
$dbname = "travel_registration"; // Replace with your database name

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get and sanitize form data
    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);
    $phone = htmlspecialchars($_POST['phone']);
    $address = htmlspecialchars($_POST['address']);
    $destination = htmlspecialchars($_POST['destination']);
    $travel_date = htmlspecialchars($_POST['travel_date']);
    $return_date = htmlspecialchars($_POST['return_date']);
    $message = htmlspecialchars($_POST['message']);

    // Save to database
    $stmt = $conn->prepare("INSERT INTO registrations (name, email, phone, address, destination, travel_date, return_date, message) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssss", $name, $email, $phone, $address, $destination, $travel_date, $return_date, $message);

    if ($stmt->execute()) {
        // Store submitted data in session for display
        $_SESSION['submission_data'] = [
            'name' => $name,
            'email' => $email,
            'phone' => $phone,
            'address' => $address,
            'destination' => $destination,
            'travel_date' => $travel_date,
            'return_date' => $return_date,
            'message' => $message
        ];

        // Redirect to self to display success message
        header("Location: " . $_SERVER['PHP_SELF'] . "?success=1");
        exit;
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();

// Check if the success parameter is set
if (isset($_GET['success'])) {
    $data = $_SESSION['submission_data'] ?? null;
    if (!$data) {
        echo "No data available.";
        exit;
    }
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Travel Registration Successful</title>
        <link rel="stylesheet" href="style.css">
    </head>
    <body>
        <header>
            <h1>Registration Successful</h1>
        </header>
        <section>
            <h3>Your Submitted Details:</h3>
            <ul>
                <li><strong>Name:</strong> <?= htmlspecialchars($data['name']) ?></li>
                <li><strong>Email:</strong> <?= htmlspecialchars($data['email']) ?></li>
                <li><strong>Phone:</strong> <?= htmlspecialchars($data['phone']) ?></li>
                <li><strong>Address:</strong> <?= htmlspecialchars($data['address']) ?></li>
                <li><strong>Destination:</strong> <?= htmlspecialchars($data['destination']) ?></li>
                <li><strong>Travel Date:</strong> <?= htmlspecialchars($data['travel_date']) ?></li>
                <li><strong>Return Date:</strong> <?= htmlspecialchars($data['return_date']) ?></li>
                <li><strong>Message:</strong> <?= htmlspecialchars($data['message']) ?></li>
            </ul>
        </section>
        <footer>
            <p>&copy; 2024 Travel Registration. All rights reserved.</p>
        </footer>
    </body>
    </html>
    <?php
} else {
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Travel Registration</title>
        <link rel="stylesheet" href="style.css">
    </head>
    <body>
        <header>
            <h1>Travel Registration Form</h1>
            <p>Register your trip easily with our form below!</p>
        </header>

        <section class="form-container">
            <form id="registrationForm" method="POST" action="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>">
                <fieldset>
                    <legend>Personal Information</legend>
                    <label for="name">Full Name:</label>
                    <input type="text" id="name" name="name" placeholder="Enter your full name" required>
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" placeholder="Enter a valid email" required>
                    <label for="address">Address:</label>
                    <input type="text" id="address" name="address" placeholder="Enter a valid address" required>
                    <label for="phone">Phone Number:</label>
                    <input type="tel" id="phone" name="phone" placeholder="e.g., +91" required>
                </fieldset>
                <fieldset>
                    <legend>Travel Information</legend>
                    <label for="destination">Travel Destination:</label>
                    <select id="destination" name="destination" required>
                        <option value="" disabled selected>Select a destination</option>
                        <option value="Goa">Goa</option>
                        <option value="Kerala">Kerala</option>
                        <option value="Manali">Manali</option>
                        <option value="Leh Ladakh">Leh Ladakh</option>
                        <option value="Other">Other</option>
                    </select>
                    <label for="travel_date">Travel Date:</label>
                    <input type="date" id="travel_date" name="travel_date" required>
                    <label for="return_date">Return Date:</label>
                    <input type="date" id="return_date" name="return_date" required>
                </fieldset>
                <fieldset>
                    <legend>Additional Information</legend>
                    <label for="message">Message/Comments:</label>
                    <textarea id="message" name="message" rows="5" placeholder="Enter additional requests or comments"></textarea>
                </fieldset>
                <button type="submit">Submit</button>
            </form>
        </section>

        <footer>
            <p>&copy; 2024 Travel Registration. All rights reserved.</p>
            <p>Contact us: <a href="mailto:support@travelregistration.com">support@travelregistration.com</a></p>
        </footer>
    </body>
    </html>
    <?php
}
?>
