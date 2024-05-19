<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Canteen Transaction Log</title>
    <!-- Include Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
         body {
            margin: 0;
            padding: 0;
            background-image: url('canteen.png'); /* Update the path to your image */
            background-size: cover;
            font-family: Arial, sans-serif;
            color: #000; /* Set font color to black */
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            position: relative;
            width: 80%;
            max-width: 800px; /* Adjust the maximum width as needed */
            margin-top: 70px;
            padding: 30px;
            background: rgba(180, 180, 180, 0.8); /* Light background */
            border-radius: 15px;
            text-align: center;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); /* Add a subtle shadow */
        }
        .icon {
            position: absolute;
            top: -30px;
            right: -30px;
            font-size: 48px;
            color: #007bff; /* Blue color */
        }
        .input-field {
            width: 100%;
            margin-bottom: 20px;
            padding: 10px;
            border: none;
            border-radius: 5px;
            background-color: rgba(255, 255, 255, 0.8);
            color: #000;
        }
        .btn {
            display: block;
            width: 100%;
            padding: 15px 0;
            border: none;
            border-radius: 10px;
            font-size: 16px;
            cursor: pointer;
            background-color: #007bff;
            color: #fff;
            transition: background-color 0.3s;
            margin-bottom: 20px;
        }
        .btn:hover {
            background-color: #0056b3;
        }
        .back-icon {
            position: absolute;
            top: 10px;
            left: 10px;
            font-size: 24px;
            color: #fff;
            cursor: pointer;
            text-decoration: none;
        }
        .back-icon:hover {
            color: #ccc;
        }
        table {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
        }
        th, td {
            padding: 8px;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #007bff;
            color: #fff;
        }
    </style>
</head>
<body>
<a href="login.php" class="back-icon"><i class="fas fa-arrow-left"></i></a>
<div class="container">
    <h2>Canteen Transaction Log</h2>
    <!-- Form to insert data into canteentransaction table -->
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <input type="text" name="student_rfid" class="input-field" placeholder="Student RFID" required>
        <input type="datetime-local" name="date" class="input-field" required>
        <input type="text" name="amount" class="input-field" placeholder="Amount" required>
        <input type="text" name="items" class="input-field" placeholder="Items">
        <button type="submit" class="btn" name="submit">Submit</button>
    </form>

    <!-- Button to display canteen transaction log data -->
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="get">
        <button type="submit" class="btn" name="displayTransaction">Display Canteen Transaction Data</button>
    </form>

    <!-- Button to display student detail -->
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="get">
        <input type="text" name="student_search" class="input-field" placeholder="Enter Student RFID or GR Number" required>
        <button type="submit" class="btn" name="displayStudentDetail">Display Student Detail</button>
    </form>

    <!-- Display all data from canteentransaction table -->
    <?php
    // Database connection parameters
    $host = 'aws-0-ap-southeast-1.pooler.supabase.com';
    $dbname = 'postgres';
    $user = 'postgres.owddymptbahtsomgfxvb';
    $password = 'Tyitminproject@24';

    try {
        // Establish PDO database connection
        $pdo = new PDO("pgsql:host=$host;dbname=$dbname", $user, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Check if form is submitted
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Retrieve form data
            $student_rfid = $_POST['student_rfid'];
            $date = $_POST['date'];
            $amount = $_POST['amount'];
            $items = $_POST['items'];

            // Insert data into canteentransaction table
            $stmt = $pdo->prepare('INSERT INTO canteentransaction (student_rfid, date, amount, items) VALUES (:student_rfid, :date, :amount, :items)');
            $stmt->bindParam(':student_rfid', $student_rfid);
            $stmt->bindParam(':date', $date);
            $stmt->bindParam(':amount', $amount);
            $stmt->bindParam(':items', $items);
            $stmt->execute();
        }

        // Check if "Display Canteen Transaction Data" button is clicked
        if (isset($_GET['displayTransaction'])) {
            echo '<table border="1">';
            echo '<tr><th>Transaction ID</th><th>Student RFID</th><th>Date</th><th>Amount</th><th>Items</th></tr>';

            // Retrieve all data from canteentransaction table
            $stmt = $pdo->query('SELECT * FROM canteentransaction');
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo "<tr>";
                echo "<td>".$row['transaction_id']."</td>";
                echo "<td>".$row['student_rfid']."</td>";
                echo "<td>".$row['date']."</td>";
                echo "<td>".$row['amount']."</td>";
                echo "<td>".$row['items']."</td>";
                echo "</tr>";
            }
            echo '</table>';
        }

        // Check if "Display Student Detail" button is clicked
        if (isset($_GET['displayStudentDetail'])) {
            $student_search = $_GET['student_search'];
            
            echo '<table border="1">';
            echo '<tr><th>RFID</th><th>GR Number</th><th>Name</th><th>Grade Level</th><th>Dietary Restrictions</th></tr>';

            // Retrieve student detail based on RFID or GR Number
            $stmt = $pdo->prepare('SELECT rfid, gr_number, name, grade_level, dietary_restrictions FROM student WHERE rfid = :student_search OR gr_number = :student_search');
            $stmt->bindParam(':student_search', $student_search);
            $stmt->execute();
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo "<tr>";
                echo "<td>".$row['rfid']."</td>";
                echo "<td>".$row['gr_number']."</td>";
                echo "<td>".$row['name']."</td>";
                echo "<td>".$row['grade_level']."</td>";
                echo "<td>".$row['dietary_restrictions']."</td>";
                echo "</tr>";
            }
            echo '</table>';
        }

    } catch (PDOException $e) {
        // Handle PDO exception
        echo "Error: " . $e->getMessage();
    }
    ?>
</div>

</body>
</html>
