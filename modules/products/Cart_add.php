<?php
global $Default_Temp, $time, $url_sid;
//echo "giohang";
// unset($_SESSION['giohangs']);
// die();
if(isset($_POST['soluongnhap']))
{
	$slnhap = $_POST['soluongnhap'];
	$link = 1;
}else{
	$slnhap = 1;
	$link = 0;
}

$id = intval($_GET['id']);
@$size =  intval($_POST['size']);

if(@isset($_SESSION['giohang'][$id])){
	$soluong = $_SESSION['giohang'][$id]  + $slnhap ; 
}else{
	$soluong = $slnhap;
}
 
$_SESSION['giohang'][$id]=$soluong;
$_SESSION['size'][$id]=$size;
// $arr = $_SESSION['giohang'];
// foreach ($arr as $key => $value) {
// 	echo $key.'-'.$value.'<br>';
// }

if ($link == 1) {
	?>
		<script type="text/javascript">
			window.alert("Thêm giỏ hàng thành công!.");
			window.history.back();
		</script>
	<?php
}
else
{
	header("Location: ".url_sid('index.php?f=products&do=giohang')."");
}
// $url_cat = url_sid('index.php?f=products&do=giohang');


exit();
?>