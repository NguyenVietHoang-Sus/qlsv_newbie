<?php
	require 'connection.php';
	header('Content-Type: application/json');

	$ma_khoa = $_POST['ma_khoa'] ?? '';
	$ma_lop = $_POST['ma_lop'] ?? '';
	$ma_sv = $_POST['ma_sv'] ?? '';
	$ho_ten = $_POST['ho_ten'] ?? '';
	$gioi_tinh = $_POST['gioi_tinh'] ?? '';
	$ngay_sinh = $_POST['ngay_sinh'] ?? '';
	$dia_chi = $_POST['dia_chi'] ?? '';
	$email = $_POST['email'] ?? '';
	$sdt = $_POST['sdt'] ?? '';

	$sql = "INSERT INTO t_sinhvien (ma_khoa, ma_lop, ma_sv, ho_ten, gioi_tinh, ngay_sinh, dia_chi, email, sdt) VALUES ('$ma_khoa', '$ma_lop', '$ma_sv', '$ho_ten', '$gioi_tinh', '$ngay_sinh', '$dia_chi', '$email', '$sdt')";

	if($conn->query($sql) === TRUE){
		echo json_encode(['status' => 'success', 'message' => 'Thêm sinh viên thành công!']);
	}
	else{
		echo json_encode(['status' => 'error', 'message' => '$conn->error']);
	}
	$conn->close();
?>