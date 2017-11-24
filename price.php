<?php
$localhost = new mysqli('localhost', 'root', 'c3253220.', 'demo'); 
if($localhost ==true){echo("SQL OK");}else{echo("NO SQL");};
mysqli_query($localhost,"SET NAMES utf8");
$sql = "SELECT * FROM hr";
$result = $localhost->query($sql);
$row_Recordset1 = mysqli_fetch_assoc($result);
$totalRows_Recordset1 = mysqli_num_rows($result);
//输出数组
//var_dump($result);
$localhost->close();
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>无标题文档</title>
</head>

<body>
<table width="700" border="1" cellpadding="1" cellspacing="0">
  <tr>
    <td>id</td>
    <td>姓名</td>
    <td>年龄</td>
  <td>性别</td>
  <td>电话</td>
  <td>生日</td>
  <td>技能评分</td>
  <td>简历日期</td>
  <td>电话通知结果</td>
  </tr>
<?php do { ?>
    <tr>
      <td><?php echo $row_Recordset1["id"] ?></td>
      <td><?php echo $row_Recordset1['name']; ?></td>
      <td><?php echo $row_Recordset1['sex']; ?></td>
      <td><?php echo $row_Recordset1['age']; ?></td>
      <td><?php echo $row_Recordset1['tel']; ?></td>
      <td><?php echo $row_Recordset1['Birthday']; ?></td>
      <td><?php echo $row_Recordset1['Skill scoring']; ?></td>
      <td><?php echo $row_Recordset1['datetime']; ?></td>
      <td><?php echo $row_Recordset1['results']; ?></td>
    </tr>
    <?php } while ($row_Recordset1 = mysqli_fetch_assoc($result)); ?>
</table>
</body>
</html>
<?php
mysqli_free_result($result);
?>