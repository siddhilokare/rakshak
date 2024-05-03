<?php
$host = 'aws-0-ap-southeast-1.pooler.supabase.com'; // Supabase database IP address
$dbname = 'postgres';
$user = 'postgres.owddymptbahtsomgfxvb';
$password = 'Tyitminproject@24';

try {
    // Establish PDO database connection
    $pdo = new PDO("pgsql:host=$host;dbname=$dbname", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Fetch data from the student table
    $stmtStudent = $pdo->prepare('SELECT rfid, name, grade_level, dietary_restrictions, gr_number, band_rfid , lastseen FROM student');
    $stmtStudent->execute();
    $students = $stmtStudent->fetchAll(PDO::FETCH_ASSOC);

    // Fetch data from the medicalvisitlog table
    $stmtMedical = $pdo->prepare('SELECT visit_id, student_id, date, reason, notes FROM medicalvisitlog');
    $stmtMedical->execute();
    $medicalLogs = $stmtMedical->fetchAll(PDO::FETCH_ASSOC);

    // Fetch data from the canteentransaction table
    $stmtCanteen = $pdo->prepare('SELECT transaction_id, student_id, date, amount, items FROM canteentransaction');
    $stmtCanteen->execute();
    $canteenTransactions = $stmtCanteen->fetchAll(PDO::FETCH_ASSOC);

    // Return JSON response with fetched data
    $response = [
        'status' => 'success',
        'students' => $students,
        'medicalLogs' => $medicalLogs,
        'canteenTransactions' => $canteenTransactions
    ];

    // Set HTTP response code and send JSON response
    http_response_code(200);
    echo json_encode($response);
} catch (PDOException $e) {
    // Handle PDO exception (e.g., database connection error)
    $errorMessage = 'Error fetching data: ' . $e->getMessage();
    error_log($errorMessage); // Log detailed error message
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => $errorMessage]);
}
?>
