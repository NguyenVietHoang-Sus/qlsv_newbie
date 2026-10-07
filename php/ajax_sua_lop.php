<?php
	require 'connection.php';
	header('Content-Type: application/json');

	$ma_khoa = $_POST['ma_khoa'] ?? '';
	$ma_lop = $_POST['ma_lop'] ?? '';
	$ten_lop = $_POST['ten_lop'] ?? '';
	$ma_gvcn = $_POST['ma_gvcn'] ?? '';

	$sql = "UPDATE t_lop SET ma_khoa = '$ma_khoa', ten_lop = '$ten_lop', ma_gvcn = '$ma_gvcn' WHERE ma_lop = '$ma_lop'";

	if($conn->query($sql) === TRUE){
		echo json_encode(['status' => 'success', 'message' => 'Cập nhật lớp thành công!']);
	}
	else{
		echo json_encode(['status' => 'error', 'message' => $conn->error]);
	}
	$conn->close();
?>