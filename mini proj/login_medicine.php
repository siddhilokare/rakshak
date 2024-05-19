<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Medical Visit Log</title>
    <!-- Include Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
         body {
            margin: 0;
            padding: 0;
            background-image: url('medicine.png'); /* Update the path to your image */
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
            background: rgba(255, 255, 255, 0.8); /* Light background */
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
            display: none; /* Initially hide the table */
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
    <h2>Medical Visit Log</h2>
    <!-- Form to insert data into medicalvisitlog table -->
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <input type="text" name="student_id" class="input-field" placeholder="Student ID" required>
        <input type="datetime-local" name="date" class="input-field" required>
        <input type="text" name="reason" class="input-field" placeholder="Reason" required>
        <input type="text" name="notes" class="input-field" placeholder="Notes">
        <button type="submit" class="btn" name="submit">Submit</button>
    </form>

    <!-- Button to display medical visit log data -->
    <button id="displayBtn" class="btn">Display Data</button>

    <!-- Display all data from medicalvisitlog table -->
    <table border='1' id="visitLogTable">
        <tr>
            <th>Visit ID</th>
            <th>Student ID</th>
            <th>Date</th>
            <th>Reason</th>
            <th>Notes</th>
        </tr>
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
                $student_id = $_POST['student_id'];
                $date = $_POST['date'];
                $reason = $_POST['reason'];
                $notes = $_POST['notes'];

                // Insert data into medicalvisitlog table
                $stmt = $pdo->prepare('INSERT INTO medicalvisitlog (student_id, date, reason, notes) VALUES (:student_id, :date, :reason, :notes)');
                $stmt->bindParam(':student_id', $student_id);
                $stmt->bindParam(':date', $date);
                $stmt->bindParam(':reason', $reason);
                $stmt->bindParam(':notes', $notes);
                $stmt->execute();
            }

            // Retrieve all data from medicalvisitlog table
            $stmt = $pdo->query('SELECT * FROM medicalvisitlog');
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo "<tr>";
                echo "<td>".$row['visit_id']."</td>";
                echo "<td>".$row['student_id']."</td>";
                echo "<td>".$row['date']."</td>";
                echo "<td>".$row['reason']."</td>";
                echo "<td>".$row['notes']."</td>";
                echo "</tr>";
            }
        } catch (PDOException $e) {
            // Handle PDO exception
            echo "Error: " . $e->getMessage();
        }
        ?>
    </table>
</div>

<!-- Script to show table when the button is clicked -->
<script>
    document.getElementById('displayBtn').addEventListener('click', function() {
        document.getElementById('visitLogTable').style.display = 'table';
    });
</script>

</body>
</html>
