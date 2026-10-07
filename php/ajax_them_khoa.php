<?php
	require 'connection.php';
	header('Content-Type: application/json');

	$ma_khoa = $_POST['ma_khoa'] ?? '';
	$ten_khoa = $_POST['ten_khoa'] ?? '';
	$dien_thoai_khoa = $_POST['dien_thoai_khoa'] ?? '';
	
	$sql = "INSERT INTO t_khoa (ma_khoa, ten_khoa, dien_thoai_khoa) VALUES ('$ma_khoa', '$ten_khoa', '$dien_thoai_khoa')";

	if($conn->query($sql) === TRUE){
		echo json_encode(['status' => 'success', 'message' => 'Thêm khoa thành công!']);
	}
	else{
		echo json_encode(['status' => 'error', 'message' => $conn->error]);
	}
	$conn->close();
?>