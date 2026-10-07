<?php
	require 'connection.php';
	header('Content-Type: application/json');

	$ma_khoa = $_GET['ma_khoa'] ?? '';
	$sql = "SELECT * FROM t_khoa WHERE ma_khoa = '$ma_khoa'";
	$result = $conn->query($sql);

	if($result->num_rows > 0){
		echo json_encode(['status' => 'success', 'data' => $result->fetch_assoc()]);
	}
	else{
		echo json_encode(['status' => 'error', 'message' => 'Không tìm thấy khoa']);
	}
	$conn->close();
?>