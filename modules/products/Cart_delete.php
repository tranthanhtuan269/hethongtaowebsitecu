<?php

if(isset($_GET['id']) &&  $_GET['id'] != ""){
	$idx = $_GET['id'];
	unset($_SESSION['giohang'][$idx]);
	unset($_SESSION['size'][$idx]);
} 
unset($_SESSION['giohang']);unset($_SESSION['size']);
?>
	<script type="text/javascript">
		window.history.back()
	</script>
<?php

?>