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
            background-image: url('Designer.png'); /* Update the path to your image */
            background-size: cover;
            font-family: Arial, sans-serif;
            color: #fff; /* Set font color to white */
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            position: relative;
            width: 90%; /* Adjusted width for better responsiveness */
            max-width: 600px; /* Limit maximum width */
            padding: 40px; /* Increased padding */
            background: rgba(0, 0, 0, 0.7); /* Darken the background and set opacity */
            border-radius: 20px; /* Increased border radius */
        }
        .btn {
            display: block;
            width: 100%;
            padding: 15px 0; /* Increased padding to make buttons squarer */
            margin: 10px 0;
            border: none;
            border-radius: 10px; /* Rounded corners */
            font-size: 16px;
            cursor: pointer;
            background-color: #fff; /* Set button background color to white */
            color: #333; /* Set button text color to dark */
            transition: background-color 0.3s; /* Smooth transition */
        }
        .btn:hover {
            background-color: #f0f0f0; /* Lighten button background on hover */
        }
        .home-btn {
            position: absolute;
            top: 10px;
            right: 10px;
            font-size: 24px;
            color: #fff; /* Set home button color to white */
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="container">
        <i class="fas fa-home home-btn" onclick="goHome()"></i>
        <button class="btn" onclick="loginAsParent()">Login as Parent</button>
        <button class="btn" onclick="loginAsTeacher()">Login as Teacher</button>
        <button class="btn" onclick="loginAsCanteen()">Login as Canteen Superintendant</button>
        <button class="btn" onclick="loginAsMedicine()">Login as Infirmary</button>
    </div>

    <!-- JavaScript for redirecting home -->
    <script>
        function goHome() {
            // Redirect to home page or any desired location
            window.location.href = "index.php";
        }
        function loginAsParent() {
            // Redirect to login page for parent
            window.location.href = "login_parent.php";
        }
        function loginAsTeacher() {
            // Redirect to login page for teacher
            window.location.href = "login_teacher.php";
        }

        function loginAsCanteen() {
            // Redirect to login page for teacher
            window.location.href = "login_canteen.php";
        }

        function loginAsMedicine() {
            // Redirect to login page for teacher
            window.location.href = "login_medicine.php";
        }
    </script>
</body>
</html>
