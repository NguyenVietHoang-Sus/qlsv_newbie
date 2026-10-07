<?php
	require 'connection.php';
	header('Content-Type: application/json');

	$ma_khoa = $_POST['ma_khoa'] ?? '';
	$ma_lop = $_POST['ma_lop'] ?? '';
	$ten_lop = $_POST['ten_lop'] ?? '';
	$ma_gvcn = $_POST['ma_gvcn'] ?? '';

	$sql = "INSERT INTO t_lop (ma_khoa, ma_lop, ten_lop, ma_gvcn) VALUES ('$ma_khoa', '$ma_lop', '$ten_lop', '$ma_gvcn')";

	if($conn->query($sql) === TRUE){
		echo json_encode(['status' => 'success', 'message' => 'Thêm lớp thành công!']);
	}
	else{
		echo json_encode(['status' => 'error', 'message' => $conn->error]);
	}
	$conn->close();
?>