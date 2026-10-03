<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh sách khoa</title>
</head>
<body>
    <?php
        require 'connection.php';
        $sql_select = "SELECT * FROM t_khoa";
        $result = $conn->query($sql_select);
    ?>
    <center>
        <h2>Danh sách khoa đào tạo chuyên môn</h2><br>
        <table border="1">
            <th>STT</th>
            <th>Mã khoa</th>
            <th>Tên khoa</th>
            <th>Số điện thoại khoa</th>
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
            $conn->close();
        ?>
        </table>
    </center>
</body>
</html>