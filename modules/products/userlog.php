<?php 

if (isset($_GET['user']) && (int)$_GET['user']) {
}else{
	echo "Bạn phải đăng nhập để có thể sử dụng tính năng này!";
}