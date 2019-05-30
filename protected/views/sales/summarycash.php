
<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

//var_dump($summary);
// print_r($summary);

echo "<br>";
echo "<div style='width:250px;margin:5px 0;border-top:0px solid #888;border-bottom:1px solid #888;'>";
echo "<br>";
echo "<table class='items table' >";
echo "<tr>";
	echo "<td>Total Cost</td>";
	echo "<td style='text-align:left;'>:</td>";
	echo "<td style='text-align:right;font-weight:bold'>".number_format($summary['grandtotal'])."</td>";
echo "</tr>";
echo "<tr>";
	echo "<td>Cash</td>";
	echo "<td style='text-align:left;'>:</td>";
	echo "<td style='text-align:right;'>".number_format($summary['totalcash'])."</td>";
echo "</tr>";
// echo "<tr>";
// 	echo "<td>Compliment</td>";
// 	echo "<td style='text-align:left;'>:</td>";
// 	echo "<td style='text-align:right;'>".number_format($summary['totalcomp'])."</td>";
// echo "</tr>";
echo "<tr>";
	echo "<td>EDC Mandiri</td>";
	echo "<td style='text-align:left;'>:</td>";
	echo "<td style='text-align:right;'>".number_format($summary['totalniaga'])."</td>";
echo "</tr>";
echo "<tr>";
	echo "<td>EDC BCA</td>";
	echo "<td style='text-align:left;'>:</td>";
	echo "<td style='text-align:right;'>".number_format($summary['totalbca'])."</td>";
echo "</tr>";

echo "<tr>";
	echo "<td>Transfer Mandiri</td>";
	echo "<td style='text-align:left;'>:</td>";
	echo "<td style='text-align:right;'>".number_format($summary['credit_bca'])."</td>";
echo "</tr>";
// echo "<tr>";
// 	echo "<td>KREDIT MANDIRI</td>";
// 	echo "<td style='text-align:left;'>:</td>";
// 	echo "<td style='text-align:right;'>".number_format($summary['credit_mandiri'])."</td>";
// echo "</tr>";

// echo "<tr>";
// 	echo "<td>Dll</td>";
// 	echo "<td style='text-align:left;'>:</td>";
// 	echo "<td style='text-align:right;'>".number_format($summary['totaldll'])."</td>";
// echo "</tr>";

// echo "Net Sales	: ".number_format($summary['sst'])."<br />";
// echo "Discount  : ".number_format($summary['sd'])."<br />";
// echo "Tax	    : ".number_format($summary['t'])."<br />";
// echo "Sales		: ". number_format($summary['stc'])."<br />";

echo "</table>";
echo "</div>";
//foreach ($summary as $rows)
//{
//	
//}
?>
