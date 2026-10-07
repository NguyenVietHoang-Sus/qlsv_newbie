<?php
	require 'connection.php';
	header('Content-Type: application/json');

	$ma_lop = $_GET['ma_lop'] ?? '';
	$sql = "SELECT * FROM t_lop WHERE ma_lop = '$ma_lop'";
	$result = $conn->query($sql);

	if($result->num_rows > 0){
		echo json_encode(['status' => 'success', 'data' => $result->fetch_assoc()]);
	}
	else{
		echo json_encode(['status' => 'error', 'message' => 'Không tìm thấy lớp']);
	}
	$conn->close();
?>