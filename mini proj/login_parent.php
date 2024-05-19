<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <!-- Include Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
         body {
            margin: 0;
            padding: 0;
            background-image: url('parent.png'); /* Update the path to your image */
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
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <input type="text" name="parent_id" class="input-field" placeholder="Parent ID" required>
            <input type="text" name="parent_name" class="input-field" placeholder="Parent Name" required>
            <button type="submit" class="btn">Login</button>
        </form>

        <?php
        // Database connection parameters
        $host = 'aws-0-ap-southeast-1.pooler.supabase.com';
        $dbname = 'postgres';
        $user = 'postgres.owddymptbahtsomgfxvb';
        $password = 'Tyitminproject@24';

        // Check if form is submitted
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Retrieve parent_id and parent_name from POST if set
            $parent_id = isset($_POST['parent_id']) ? $_POST['parent_id'] : null;
            $parent_name = isset($_POST['parent_name']) ? $_POST['parent_name'] : null;

            if ($parent_id !== null && $parent_name !== null) {
                try {
                    // Establish PDO database connection
                    $pdo = new PDO("pgsql:host=$host;dbname=$dbname", $user, $password);
                    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                    // Query database to fetch student data matching parent_id and parent_name
                    $stmt = $pdo->prepare('SELECT * FROM student WHERE gr_number = :parent_id AND parent = :parent_name');
                    $stmt->bindParam(':parent_id', $parent_id);
                    $stmt->bindParam(':parent_name', $parent_name);
                    $stmt->execute();
                    $studentData = $stmt->fetchAll(PDO::FETCH_ASSOC);

                    // Display fetched student data
                    if ($studentData) {
                        echo "<h2>Student Data</h2>";
                        echo "<table border='1'>";
                        echo "<tr><th>RFID</th><th>Name</th><th>Grade Level</th><th>Dietary Restrictions</th><th>GR Number</th><th>Band RFID</th><th>Last Seen</th><th>Parent</th></tr>";
                        foreach ($studentData as $student) {
                            echo "<tr>";
                            echo "<td>".$student['rfid']."</td>";
                            echo "<td>".$student['name']."</td>";
                            echo "<td>".$student['grade_level']."</td>";
                            echo "<td>".$student['dietary_restrictions']."</td>";
                            echo "<td>".$student['gr_number']."</td>";
                            echo "<td>".$student['band_rfid']."</td>";
                            echo "<td>".$student['lastseen']."</td>";
                            echo "<td>".$student['parent']."</td>";
                            echo "</tr>";
                        }
                        echo "</table>";
                    } else {
                        echo "No student data found for the provided Parent ID and Parent Name.";
                    }
                } catch (PDOException $e) {
                    // Handle PDO exception
                    echo "Error: " . $e->getMessage();
                }
            } else {
                echo "Please provide Parent ID and Parent Name.";
            }
        }
        ?>
    </div>
</body>
</html>
