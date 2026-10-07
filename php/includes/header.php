<?php
	require_once __DIR__ . '/../connection.php';

	$page_title = $page_title ?? 'Quản lý sinh viên';
	$active_menu = $active_menu ?? 'home';
?>
<!DOCTYPE html>
<html lang="vi">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>QLSV</title>
	<link rel="stylesheet" href="../css/style.css">
	<link rel="stylesheet" href="../css/modal.css">
	<link rel="stylesheet" href="../css/table.css">
</head>
<body>
	<?php require __DIR__ . "/sidebar.php"; ?>

<div class="content">