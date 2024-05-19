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
            background-color: #f3f4f6; /* Light background color */
            color: #333; /* Dark text color */
            margin: 0;
            padding: 0;
        }

        .container {
            text-align: center;
            padding: 40px;
        }

        .home-button {
            position: absolute;
            top: 20px;
            right: 20px;
            cursor: pointer;
            color: #333;
            font-size: 24px; /* Adjust icon size */
        }

        .button-container {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-bottom: 40px;
        }

        .button {
            padding: 15px 30px;
            font-size: 18px;
            border: none;
            border-radius: 5px;
            margin: 0 10px;
            cursor: pointer;
            background-color: #4CAF50; /* Green button color */
            color: white;
            transition: background-color 0.3s ease;
        }
    .button:disabled {
            background-color: #cccccc; /* Darker disabled button color */
            cursor: default;
        }

        .button:hover:not(:disabled) {
            background-color: #45a049; /* Darker green hover color */
        }

        .data-container {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin-bottom: 40px;
        }

        .data-container h2 {
            color: #333;
        }

        .search-container {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-bottom: 20px;
        }

        .search-input {
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 5px;
            margin-right: 10px;
        }

        .search-button {
            padding: 10px 20px;
            font-size: 16px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            background-color: #2196F3; /* Blue search button color */
            color: white;
            transition: background-color 0.3s ease;
        }

        .search-button:hover {
            background-color: #0b7dda; /* Darker blue hover color */
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background-color: #fff; /* White table background */
            border: 1px solid #ddd; /* Light border for table */
        }

        th, td {
            border: 1px solid #ddd; /* Light border for table cells */
            padding: 12px;
            text-align: left;
        }

        /* Styling for table headers */
        th {
            background-color: #4CAF50; /* Green header background */
            color: white;
        }
    </style>
</head>
<body>
<div class="container">
    <!-- Home button (icon) -->
    <a href="http://localhost/phpproject/mini%20proj/" class="home-button"><i class="fas fa-home"></i></a>

    <h1 style="color: #4CAF50;">Welcome to the Dashboard</h1>
    <div class="button-container">
        <button class="button" onclick="connectToDatabase()">Connect to Database</button>
        <button class="button disabled" onclick="fetchData('student')" disabled>Students</button>
        <button class="button disabled" onclick="fetchData('medicalvisitlog')" disabled>Medical Logs</button>
        <button class="button disabled" onclick="fetchData('canteentransaction')" disabled>Canteen</button>
    </div>

    <div class="data-container" id="studentDataContainer" style="display: none;">
        <h2>Student Data</h2>
        <div class="search-container">
            <input type="text" id="searchInput" class="search-input" placeholder="Search by RFID or GR Number">
            <button class="search-button" onclick="searchStudent()">Search</button>
        </div>
        <div id="studentTableContainer"></div>
    </div>

    <div class="data-container" id="dataContainer"></div>
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
        const tableContainer = document.getElementById('studentTableContainer');
        tableContainer.innerHTML = ''; // Clear previous data

        if (students && students.length > 0) {
            let tableHtml = '<table>';
            tableHtml += '<tr><th>RFID</th><th>Name</th><th>Grade Level</th><th>Dietary Restrictions</th><th>GR Number</th><th>Band RFID</th><th>Last Seen</th></tr>';
            students.forEach(student => {
                tableHtml += `<tr><td>${student.rfid}</td><td>${student.name}</td><td>${student.grade_level}</td><td>${student.dietary_restrictions || ''}</td><td>${student.gr_number}</td><td>${student.band_rfid || ''}</td><td>${student.lastseen}</td></tr>`;
            });
            tableHtml += '</table>';
            tableContainer.innerHTML = tableHtml;
        } else {
            tableContainer.textContent = 'No student data available.';
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
        const students = document.querySelectorAll('#studentTableContainer table tr:not(:first-child)');

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
