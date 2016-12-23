<?php
$conn=mysql_connect("localhost","root","")
or die("Can not connect database");
mysql_select_db("tamphat",$conn);

$result_property=mysql_query("SELECT id, name  FROM adoosite_property_standard WHERE property_id = ".$_POST["districtid"]." AND status = 1", $conn);

if(mysql_num_rows($result_property) > 0){
	// $properties = mysql_fetch_array($result_property);
	?>
	<option value="">-- Thuộc tính --</option>
	<?php
	while($row_huyen=mysql_fetch_array($result_property))
	{
		echo "<option value=$row_huyen[id]>$row_huyen[name]</option>" ;
	}
	mysql_free_result($result_property);
}
else
{
	echo "<option value=\"0\">Chọn loại động cơ trước</option>" ;
}