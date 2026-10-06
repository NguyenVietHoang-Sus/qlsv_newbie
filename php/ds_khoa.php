<?php
    $page_title = 'Danh sach khoa';
    $active_menu = 'khoa';
    require 'includes/header.php';
    $sql_select = "SELECT * FROM t_khoa";
    $result = $conn->query($sql_select);
?>
    <h1>Danh sách khoa đào tạo chuyên môn</h1><br>
    <table border="1">
        <tr>
        <th>STT</th>
        <th>Mã khoa</th>
        <th>Tên khoa</th>
        <th>Số điện thoại khoa</th>
        </tr>
    <?php
        if($result->num_rows > 0){
            $stt = 1;
            while($row = $result->fetch_assoc()){
                echo "<tr>";
                echo "<td>". $stt++ . "</td>";
                echo "<td>" . $row["ma_khoa"] . "</td>";
                echo "<td>" . $row["ten_khoa"] . "</td>";
                echo "<td>" . $row["dien_thoai_khoa"] . "</td>";
                echo "</tr>";
            }
        }
        else{
            echo "0 results";
        }
    ?>
    </table>

<?php  require 'includes/footer.php'; ?>
