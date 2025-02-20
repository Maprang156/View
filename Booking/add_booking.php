<?php
header("Content-Type: application/json");

// รับข้อมูลจาก Vue
$data = json_decode(file_get_contents("php://input"), true);

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "booking";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// บันทึกการจอง
$sql = "INSERT INTO bookings (room_id, title, booking_date, start_time, end_time, equipment) VALUES (?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);

$equipment = implode(',', $data['equipment']); // รวมรายการอุปกรณ์เป็นสตริง
$stmt->bind_param(
    "isssss",
    $data['room_id'],
    $data['title'],
    $data['booking_date'],
    $data['start_time'],
    $data['end_time'],
    $equipment
);

if ($stmt->execute()) {
    echo json_encode(["message" => "Booking added successfully"]);
} else {
    echo json_encode(["error" => $stmt->error]);
}

$stmt->close();
$conn->close();
?>
