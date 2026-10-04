<?php
	require 'connection.php';
	header('Content-Type: application/json');

	$ma_sv = $_GET['ma_sv'] ?? '';
	$sql = "SELECT * FROM t_sinhvien WHERE ma_sv = '$ma_sv'";
	$result = $conn->query($sql);

	if($result->num_rows > 0){
		echo json_encode(['status' => 'success', 'data' => $result->fetch_assoc()]);
	}
	else{
		echo json_encode(['status' => 'error', 'message' => 'Không tìm thấy sinh viên']);
	}
	$conn->close();
?>