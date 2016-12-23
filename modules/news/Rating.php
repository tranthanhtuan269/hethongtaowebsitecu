<?php
	$result_total = $db->sql_query_simple("SELECT id FROM {$prefix}_rating WHERE cid='$id' AND type='news' ");
	$num_total = $db->sql_numrows($result_total);
	$wmax=1;
	$wmin=3;
	
	$result_0 = $db->sql_query_simple("SELECT id FROM {$prefix}_rating WHERE cid='$id' AND type='news' AND rating='0'");
	$num_0 = $db->sql_numrows($result_0);
	$p0 = @round($num_0*100/$num_total);
	$w0 = $p0*$wmax + $wmin;
	
	$result_1 = $db->sql_query_simple("SELECT id FROM {$prefix}_rating WHERE cid='$id' AND type='news' AND rating='1'");
	$num_1 = $db->sql_numrows($result_1);
	$p1 = @round($num_1*100/$num_total);
	$w1 = $p1*$wmax + $wmin;
	
	$result_2 = $db->sql_query_simple("SELECT id FROM {$prefix}_rating WHERE cid='$id' AND type='news' AND rating='2'");
	$num_2 = $db->sql_numrows($result_2);
	$p2 = @round($num_2*100/$num_total);
	$w2 = $p2*$wmax + $wmin;
	
	$result_3 = $db->sql_query_simple("SELECT id FROM {$prefix}_rating WHERE cid='$id' AND type='news' AND rating='3'");
	$num_3 = $db->sql_numrows($result_3);
	$p3 = @round($num_3*100/$num_total);
	$w3 = $p3*$wmax + $wmin;
	
	$result_4 = $db->sql_query_simple("SELECT id FROM {$prefix}_rating WHERE cid='$id' AND type='news' AND rating='4'");
	$num_4 = $db->sql_numrows($result_4);
	$p4 = @round($num_4*100/$num_total);
	$w4 = $p4*$wmax + $wmin;
	
	$result_5 = $db->sql_query_simple("SELECT id FROM {$prefix}_rating WHERE cid='$id' AND type='news' AND rating='5'");
	$num_5 = $db->sql_numrows($result_5);
	$p5 = @round($num_5*100/$num_total);
	$w5 = $p5*$wmax + $wmin;
			
			echo "<table width=\"101%\" cellspacing=\"7\" style=\"margin:0px;\">";
			
			echo "<tr>";
			  echo "<td valign=\"middle\" width=\"85\">";
			  echo "<div class=\"Clear\">	";	
				 echo "<input class=\"hover-star\" type=\"radio\" name=\"rating0\" value=\"1\" disabled=\"disabled\" />";
				 echo "<input class=\"hover-star\" type=\"radio\" name=\"rating0\" value=\"2\" disabled=\"disabled\" />";
				 echo "<input class=\"hover-star\" type=\"radio\" name=\"rating0\" value=\"3\" disabled=\"disabled\" />";
				 echo "<input class=\"hover-star\" type=\"radio\" name=\"rating0\" value=\"4\" disabled=\"disabled\" />";
				 echo "<input class=\"hover-star\" type=\"radio\" name=\"rating0\" value=\"5\" disabled=\"disabled\" />";			
			   echo "</div>";
			   echo "</td>";
			   echo "<td valign=\"middle\" width=\"50\"><b>$num_0 "._PHIEU."</b></td>	";
			   echo "<td valign=\"middle\"><div style=\"width:".$w0."px;height:17px;margin-right:3px;float:left;background:".backgroundvote($p0)."\"></div><b>$p0%</b></td>  ";
			 echo "</tr>";
			 
			 echo "<tr>";
			  echo "<td valign=\"middle\">";
			  echo "<div class=\"Clear\">	";	
				 echo "<input class=\"hover-star\" type=\"radio\" name=\"rating1\" value=\"1\" disabled=\"disabled\" checked=\"checked\" />";
				 echo "<input class=\"hover-star\" type=\"radio\" name=\"rating1\" value=\"2\" disabled=\"disabled\"/>";
				 echo "<input class=\"hover-star\" type=\"radio\" name=\"rating1\" value=\"3\" disabled=\"disabled\" />";
				 echo "<input class=\"hover-star\" type=\"radio\" name=\"rating1\" value=\"4\" disabled=\"disabled\" />";
				 echo "<input class=\"hover-star\" type=\"radio\" name=\"rating1\" value=\"5\" disabled=\"disabled\" />";			
			   echo "</div>";
			   echo "</td>";
			   echo "<td valign=\"middle\" width=\"47\"><b>$num_1 "._PHIEU."</b></td>	";
			   echo "<td valign=\"middle\"><div style=\"width:".$w1."px;height:17px;margin-right:3px;float:left;background:".backgroundvote($p1)."\"></div><b>$p1%</b></td>  ";
			 echo "</tr>";
			 
			 echo "<tr>";
			  echo "<td valign=\"middle\" >";
			  echo "<div class=\"Clear\">	";	
				 echo "<input class=\"hover-star\" type=\"radio\" name=\"rating2\" value=\"1\" disabled=\"disabled\" />";
				 echo "<input class=\"hover-star\" type=\"radio\" name=\"rating2\" value=\"2\" disabled=\"disabled\" checked=\"checked\"/>";
				 echo "<input class=\"hover-star\" type=\"radio\" name=\"rating2\" value=\"3\" disabled=\"disabled\" />";
				 echo "<input class=\"hover-star\" type=\"radio\" name=\"rating2\" value=\"4\" disabled=\"disabled\" />";
				 echo "<input class=\"hover-star\" type=\"radio\" name=\"rating2\" value=\"5\" disabled=\"disabled\" />";			
			   echo "</div>";
			   echo "</td>";
			   echo "<td valign=\"middle\" width=\"47\"><b>$num_2 "._PHIEU."</b></td>	";
			   echo "<td valign=\"middle\"><div style=\"width:".$w2."px;height:17px;margin-right:3px;float:left;background:".backgroundvote($p2)."\"></div><b>$p2%</b></td>  ";
			 echo "</tr>";
			 
			 echo "<tr>";
			  echo "<td valign=\"middle\" >";
			  echo "<div class=\"Clear\">	";	
				 echo "<input class=\"hover-star\" type=\"radio\" name=\"rating3\" value=\"1\" disabled=\"disabled\" />";
				 echo "<input class=\"hover-star\" type=\"radio\" name=\"rating3\" value=\"2\" disabled=\"disabled\"  />";
				 echo "<input class=\"hover-star\" type=\"radio\" name=\"rating3\" value=\"3\" disabled=\"disabled\" checked=\"checked\" />";
				 echo "<input class=\"hover-star\" type=\"radio\" name=\"rating3\" value=\"4\" disabled=\"disabled\" />";
				 echo "<input class=\"hover-star\" type=\"radio\" name=\"rating3\" value=\"5\" disabled=\"disabled\"  />";			
			   echo "</div>";
			   echo "</td>";
			   echo "<td valign=\"middle\" width=\"47\"><b>$num_3 "._PHIEU."</b></td>	";
			   echo "<td valign=\"middle\"><div style=\"width:".$w3."px;height:17px;margin-right:3px;float:left;background:".backgroundvote($p3)."\"></div><b>$p3%</b></td>  ";
			 echo "</tr>";
			 
			 echo "<tr>";
			  echo "<td valign=\"middle\" >";
			  echo "<div class=\"Clear\">	";	
				 echo "<input class=\"hover-star\" type=\"radio\" name=\"rating4\" value=\"1\" disabled=\"disabled\" />";
				 echo "<input class=\"hover-star\" type=\"radio\" name=\"rating4\" value=\"2\" disabled=\"disabled\" />";
				 echo "<input class=\"hover-star\" type=\"radio\" name=\"rating4\" value=\"3\" disabled=\"disabled\"  />";
				 echo "<input class=\"hover-star\" type=\"radio\" name=\"rating4\" value=\"4\" disabled=\"disabled\" checked=\"checked\" />";
				 echo "<input class=\"hover-star\" type=\"radio\" name=\"rating4\" value=\"5\" disabled=\"disabled\" />";			
			   echo "</div>";
			   echo "</td>";
			   echo "<td valign=\"middle\" width=\"47\"><b>$num_4 "._PHIEU."</b></td>	";
			   echo "<td valign=\"middle\"><div style=\"width:".$w4."px;height:17px;margin-right:3px;float:left;background:".backgroundvote($p4)."\"></div><b>$p4%</b></td>  ";;
			 echo "</tr>";
			 
			 echo "<tr>";
			  echo "<td valign=\"middle\" >";
			  echo "<div class=\"Clear\">	";	
				 echo "<input class=\"hover-star\" type=\"radio\" name=\"rating5\" value=\"1\" disabled=\"disabled\" />";
				 echo "<input class=\"hover-star\" type=\"radio\" name=\"rating5\" value=\"2\" disabled=\"disabled\"  />";
				 echo "<input class=\"hover-star\" type=\"radio\" name=\"rating5\" value=\"3\" disabled=\"disabled\"  />";
				 echo "<input class=\"hover-star\" type=\"radio\" name=\"rating5\" value=\"4\" disabled=\"disabled\"  />";
				 echo "<input class=\"hover-star\" type=\"radio\" name=\"rating5\" value=\"5\" disabled=\"disabled\" checked=\"checked\" />";			
			   echo "</div>";
			   echo "</td>";
			   echo "<td valign=\"middle\" width=\"47\" cellspacing=\"0\"><b>$num_5 "._PHIEU."</b></td>	";
			   echo "<td valign=\"middle\"><div style=\"width:".$w5."px;height:17px;margin-right:3px;float:left;background:".backgroundvote($p5)."\"></div><b>$p5%</b></td>  ";
			 echo "</tr>";	
			 
			 echo "<tr><td valign=\"middle\" align=\"right\" colspan=\"3\"><hr style=\"margin:0px;\"/></td>";
			 
			 echo "<tr>";
			  echo "<td valign=\"middle\" align=\"right\" colspan=\"3\"><b>"._TONG_CONG." : $num_total "._PHIEU."&nbsp;&nbsp;</td>";
			 echo "</tr>";				 
			 
			echo "</table>";	
			
			
	function backgroundvote($p) {
	if($p >= 90) { $bg = "#FF0000";}
	elseif ($p >= 50 && $p < 90) { $bg = "#FF3300"; }
	elseif ($p >= 30 && $p < 50) { $bg = "#0C186D"; }
	elseif ($p >= 20 && $p < 30) { $bg = "#2CC603"; }
	elseif ($p >= 10 && $p < 20) { $bg = "#9999FF"; }
	else{ $bg = "#FF0080"; }
	
	return $bg;
}				
	
?>


