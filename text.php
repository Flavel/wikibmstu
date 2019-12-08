<?php


 $arr = array(1,4,2,3);
 $max1 = 0;
 $max2 = 0;
	foreach ($arr as $value) {
    	if ($value > $max1) {
    		$max2 = $max1;
    		$max1 = $value;
    	} elseif($value > $max2) {
    		$max2 = $value;
    	}
	}
	echo("$max1, $max2");
	?>