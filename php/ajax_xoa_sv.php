<?php
	require 'connection.php';
	header('Content-Type: application/json');

	$ma_sv = $_GET['ma_sv'];
	$sql = "DELETE FROM t_sinhvien WHERE ma_sv = '$ma_sv'";

	if($conn->query($sql) === TRUE){
		echo json_encode(['status' => 'success', 'message' => 'Xóa sinh viên thành công!']);
	}
	else{
		echo json_encode(['status' => 'error', 'message' => $conn->error]);
	}
	$conn->close();
?>