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
            background-image: url('teacher.png'); /* Update the path to your image */
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
            <input type="text" name="name" class="input-field" placeholder="Name" required>
            <input type="text" name="teacher_id" class="input-field" placeholder="Teacher ID" required>
            <button type="submit" class="btn">Login</button>
        </form>

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
                // Retrieve name and teacher_id from POST if set
                $name = isset($_POST['name']) ? $_POST['name'] : null;
                $teacher_id = isset($_POST['teacher_id']) ? $_POST['teacher_id'] : null;

                if ($name !== null && $teacher_id !== null) {
                    // Query database to fetch teacher's grade level
                    $stmt = $pdo->prepare('SELECT grade_level FROM teacher WHERE teacher_id = :teacher_id AND name = :name');
                    $stmt->bindParam(':teacher_id', $teacher_id);
                    $stmt->bindParam(':name', $name);
                    $stmt->execute();
                    $teacherData = $stmt->fetch(PDO::FETCH_ASSOC);

                    if ($teacherData) {
                        // Fetch students based on teacher's grade level
                        $grade_level = $teacherData['grade_level'];
                        $stmt = $pdo->prepare('SELECT student.*, parent.contact_no 
                                              FROM student 
                                              JOIN parent ON student.gr_number = parent.parent_id 
                                              WHERE student.grade_level = :grade_level');
                        $stmt->bindParam(':grade_level', $grade_level);
                        $stmt->execute();
                        $students = $stmt->fetchAll(PDO::FETCH_ASSOC);

                        // Display fetched student data...
                        if ($students) {
                            echo "<h2>Students in Grade Level: $grade_level</h2>";
                            echo "<table border='1'>";
                            echo "<tr><th>RFID</th><th>Name</th><th>Grade Level</th><th>Dietary Restrictions</th><th>GR Number</th><th>Band RFID</th><th>Last Seen</th><th>Parent</th><th>Contact Number</th></tr>";
                            foreach ($students as $student) {
                                echo "<tr>";
                                echo "<td>".$student['rfid']."</td>";
                                echo "<td>".$student['name']."</td>";
                                echo "<td>".$student['grade_level']."</td>";
                                echo "<td>".$student['dietary_restrictions']."</td>";
                                echo "<td>".$student['gr_number']."</td>";
                                echo "<td>".$student['band_rfid']."</td>";
                                echo "<td>".$student['lastseen']."</td>";
                                echo "<td>".$student['parent']."</td>";
                                echo "<td>".$student['contact_no']."</td>";
                                echo "</tr>";
                            }
                            echo "</table>";
                        } else {
                            echo "No students found in Grade Level: $grade_level";
                        }
                    } else {
                        echo "No teacher data found for the provided Teacher ID and Name.";
                    }
                }
            }
        } catch (PDOException $e) {
            // Handle PDO exception
            echo "Error: " . $e->getMessage();
        }
        ?>
    </div>
</body>
</html>
