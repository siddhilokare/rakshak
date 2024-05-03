<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Display Data</title>
    <!-- Include Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <style>
        /* CSS styles */
        body {
            font-family: 'Arial', sans-serif;
            background-color: #212121; /* Dark background color */
            color: #ffffff; /* Light text color */
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .container {
            text-align: center;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            background-color: #424242; /* Darker container background */
            position: relative; /* Enable positioning for child elements */
        }

        .home-button {
            position: absolute;
            top: 10px;
            right: 10px;
            cursor: pointer;
            color: #ffffff;
            font-size: 24px; /* Adjust icon size */
        }

        .button-container {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-bottom: 20px;
        }

        .button {
            padding: 15px 30px;
            font-size: 18px;
            border: none;
            border-radius: 5px;
            margin: 0 10px;
            cursor: pointer;
            background-color: #1976D2; /* Dark blue button color */
            color: #ffffff;
            transition: background-color 0.3s ease;
        }

        .button:disabled {
            background-color: #666666; /* Darker disabled button color */
            cursor: default;
        }

        .button:hover:not(:disabled) {
            background-color: #0D47A1; /* Darker blue hover color */
        }

        table {
            width: 80%;
            margin: 20px auto;
            border-collapse: collapse;
            background-color: #757575; /* Medium dark table background */
            border: 1px solid #ffffff; /* White border for table */
        }

        th, td {
            border: 1px solid #ffffff; /* White border for table cells */
            padding: 12px;
            text-align: left;
        }

        /* Styling for table headers */
        th {
            background-color: #546E7A; /* Dark cyan header background */
            color: #ffffff;
        }
    </style>
</head>
<body>
<div class="container">
    <!-- Home button (icon) -->
    <a href="http://localhost/phpproject/mini%20proj/" class="home-button"><i class="fas fa-home"></i></a>

    <h1>Welcome to the Dashboard</h1>
    <div class="button-container">
        <button class="button" onclick="connectToDatabase()">Connect to Database</button>
        <button class="button disabled" onclick="fetchData('student')" disabled>Students</button>
        <button class="button disabled" onclick="fetchData('medicalvisitlog')" disabled>Medical Logs</button>
        <button class="button disabled" onclick="fetchData('canteentransaction')" disabled>Canteen</button>
    </div>

    <div id="studentDataContainer" style="display: none;">
    <h2>Student Data</h2>
    <label for="searchInput" style="font-size: 18px; font-weight: bold; color: #ffffff;">Search by RFID or GR Number:</label>
    <input type="text" id="searchInput">
    <button class="button" onclick="searchStudent()">Search</button> <!-- Added search button -->
</div>

    <div id="dataContainer"></div>
    <p id="connectionStatus"></p>

    <script>
        // Function to connect to the database
        function connectToDatabase() {
            const xhr = new XMLHttpRequest();
            xhr.open('POST', 'test_connection.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4) {
                    if (xhr.status === 200) {
                        const response = JSON.parse(xhr.responseText);
                        if (response.status === 'success') {
                            document.getElementById('connectionStatus').textContent = 'Connection successful!';
                            enableDataButtons(); // Enable data buttons after successful connection
                        } else {
                            document.getElementById('connectionStatus').textContent = 'Error: ' + response.message;
                        }
                    } else {
                        document.getElementById('connectionStatus').textContent = 'Error connecting to the server (HTTP ' + xhr.status + ')';
                    }
                }
            };
            xhr.send('action=connect');
        }

        // Function to enable data buttons
        function enableDataButtons() {
            const dataButtons = document.querySelectorAll('.button.disabled');
            dataButtons.forEach(button => {
                button.disabled = false;
                button.classList.remove('disabled');
            });
        }

        // Function to fetch data for a specific table
        function fetchData(tableName) {
            const xhr = new XMLHttpRequest();
            xhr.open('POST', 'test_connection.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4) {
                    if (xhr.status === 200) {
                        const response = JSON.parse(xhr.responseText);
                        if (response.status === 'success') {
                            if (tableName === 'student') {
                                renderStudentData(response.students);
                                showStudentDataContainer(); // Show student data container
                            } else if (tableName === 'medicalvisitlog') {
                                renderMedicalVisitLog(response.medicalLogs);
                            } else if (tableName === 'canteentransaction') {
                                renderCanteenData(response.canteenTransactions);
                            }
                        } else {
                            console.error('Error fetching data:', response.message);
                            document.getElementById('dataContainer').textContent = 'Error fetching data.';
                        }
                    } else {
                        console.error('Error fetching data. Status:', xhr.status);
                        document.getElementById('dataContainer').textContent = 'Error fetching data.';
                    }
                }
            };
            xhr.send('action=fetch&table=' + tableName);
        }

        // Function to render fetched student data
        function renderStudentData(students) {
            const dataContainer = document.getElementById('dataContainer');
            dataContainer.innerHTML = ''; // Clear previous data

            if (students && students.length > 0) {
                let tableHtml = '<table>';
                tableHtml += '<tr><th>RFID</th><th>Name</th><th>Grade Level</th><th>Dietary Restrictions</th><th>GR Number</th><th>Band RFID</th><th>Last Seen</th></tr>';
                students.forEach(student => {
                    tableHtml += `<tr><td>${student.rfid}</td><td>${student.name}</td><td>${student.grade_level}</td><td>${student.dietary_restrictions || ''}</td><td>${student.gr_number}</td><td>${student.band_rfid || ''}</td><td>${student.lastseen}</td></tr>`;
                });
                tableHtml += '</table>';
                dataContainer.innerHTML = tableHtml;
            } else {
                dataContainer.textContent = 'No student data available.';
            }
        }

        // Function to render fetched medical visit log data
        function renderMedicalVisitLog(medicalLogs) {
            const dataContainer = document.getElementById('dataContainer');
            dataContainer.innerHTML = ''; // Clear previous data

            if (medicalLogs && medicalLogs.length > 0) {
                let tableHtml = '<table>';
                tableHtml += '<tr><th>Visit ID</th><th>Student ID</th><th>Date</th><th>Reason</th><th>Notes</th></tr>';
                medicalLogs.forEach(log => {
                    tableHtml += `<tr><td>${log.visit_id}</td><td>${log.student_id}</td><td>${log.date}</td><td>${log.reason || ''}</td><td>${log.notes || ''}</td></tr>`;
                });
                tableHtml += '</table>';
                dataContainer.innerHTML = tableHtml;
            } else {
                dataContainer.textContent = 'No medical visit log data available.';
            }
        }

        // Function to render fetched canteen transaction data
        function renderCanteenData(canteenTransactions) {
            const dataContainer = document.getElementById('dataContainer');
            dataContainer.innerHTML = ''; // Clear previous data

            if (canteenTransactions && canteenTransactions.length > 0) {
                let tableHtml = '<table>';
                tableHtml += '<tr><th>Transaction ID</th><th>Student ID</th><th>Date</th><th>Amount</th><th>Items</th></tr>';
                canteenTransactions.forEach(transaction => {
                    tableHtml += `<tr><td>${transaction.transaction_id}</td><td>${transaction.student_id}</td><td>${transaction.date}</td><td>${transaction.amount || ''}</td><td>${transaction.items || ''}</td></tr>`;
                });
                tableHtml += '</table>';
                dataContainer.innerHTML = tableHtml;
            } else {
                dataContainer.textContent = 'No canteen transaction data available.';
            }
        }

        // Function to show the student data container
        function showStudentDataContainer() {
            document.getElementById('studentDataContainer').style.display = 'block';
        }

        // Function to search student data by RFID or GR Number
        function searchStudent() {
            const searchValue = document.getElementById('searchInput').value.toLowerCase();
            const students = document.querySelectorAll('#dataContainer table tr:not(:first-child)');

            students.forEach(student => {
                const studentData = student.innerHTML.toLowerCase(); // Use innerHTML to include HTML tags
                if (studentData.includes(searchValue)) {
                    student.style.display = ''; // Show the student row if the search value is found
                } else {
                    student.style.display = 'none'; // Hide the student row if the search value is not found
                }
            });
        }
    </script>
</div>
</body>
</html>
