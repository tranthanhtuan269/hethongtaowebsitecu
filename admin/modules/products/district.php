<?php
$conn=mysql_connect("localhost","root","")
or die("Can not connect database");
mysql_select_db("tamphat",$conn);

// $query_huyen=mysql_query("SELECT * FROM adoosite_huyen WHERE tid=".$_POST["provinceid"]." ");
$result_property=mysql_query("SELECT p.* FROM adoosite_property AS p INNER JOIN adoosite_cat_property_mapping AS m ON p.id = m.property_id WHERE m.cat_id =".$_POST["provinceid"]."", $conn);


if(mysql_num_rows($result_property) > 0){
	// $properties = mysql_fetch_array($result_property);
	?>
	<option value="">-- Loại động cơ --</option>
	<?php
	while($row_huyen=mysql_fetch_array($result_property))
	{
		if ($row_huyen[id] == $_POST["property"]) {
			$seld =" selected";
		}
		else
		{
			$seld ="";
		}
		echo "<option value=\"$row_huyen[id]\" $seld>$row_huyen[name]</option>" ;
	}
	mysql_free_result($result_property);
}
else
{
	echo "<option value=\"0\">Chọn sản phẩm trước</option>" ;
}

