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

	$sql = "UPDATE t_sinhvien SET ma_khoa = '$ma_khoa', ma_lop = '$ma_lop', ma_sv = '$ma_sv', ho_ten = '$ho_ten', gioi_tinh = '$gioi_tinh', ngay_sinh = '$ngay_sinh', dia_chi = '$dia_chi', email = '$email', sdt = '$sdt' WHERE ma_sv = '$ma_sv'";

	if($conn->query($sql) === TRUE){
		echo json_encode(['status' => 'success', 'message' => 'Cập nhật sinh viên thành công!']);
	}
	else{
		echo json_encode(['status' => 'error', 'message' => $conn->error]);
	}
	$conn->close();
?>