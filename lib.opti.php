<?php

function verOpti($inputFile){
	
	$lines = array();
	$file = fopen("./3D.csv", "r");
	$c=0;
	while (!feof($file)) {
		$line = fgets($file);
		if($c>0){						
			$line = str_replace(' ', '', $line);
			$lines[] = $line;
			$row=explode(";",$line);			
		}		
		$c+=1;
	}
	fclose($file);
	$zindex = count($lines);
	//echo "<pre>",print_r($lines);
	$ret = "";
	$cx = 1;
	//carga->contenedor->bultom
	
	$volbb = 0;
	
	$boxes = array();
	$cnt = array();
	
	
	$bobo = array();
	$bobov = array();

	foreach($lines as $line)
	{  	
		// 0  1  2  3  4  5  6 7 8 9 10 
		// TX;TY;TZ;RX;RY;RZ;W;H;D;N;BNO     					
		$row=explode(";",$line);				
		//echo 	 "<pre>", print_r($row);	
		$W = isset($row[6]) ? floatval ($row[6]):0;
		$H = isset($row[7]) ? floatval ($row[7]):0;		
		$D = isset($row[8]) ? floatval ($row[8]):0;	
		$id=  isset($row[9]) ? $row[9] : -1;      	  
	  $volbb  =  $W*$H*$D*0.000001;  		  		  
	  // cuenta las cajas por tamaño.
	  if($id==500){	  	
	  	$Wc = isset($row[6]) ? floatval ($row[6]):0;
			$Hc = isset($row[7]) ? floatval ($row[7]):0;		
			$Dc = isset($row[8]) ? floatval ($row[8]):0;	
			$Vc =	$Wc*$Hc*$Dc*0.000001;	  	
	  }
		$box = array ($W, $H, $D);
		$c = array_search($box, $boxes);			
		if(!$c){
		 	$a = array_push($boxes,$box);
			$a-=1;
			$cnt[$a]=1;
		}else{
			$cnt[$c] +=1;
		}							
		$bobo[$row[10]]+=1;
		$bobov[$row[10]]+=$volbb;						
	}	// end freach
	echo "<table style='margin-left: auto;margin-right: auto;'><tr><td width=100><b>Bin</b></td><td width=100><b>Boxes</b></td><td width=150><b>Volume used</b></td></tr>";
	$Vc = $bobov[0];
	for ($i=1;$i< count($bobo)-1;$i+=1)
	{		  
		$VU  = round(($bobov[$i] * 100) / $Vc,1);
		echo "<tr class='selec'><td  onclick=\"viewContainer($i);\" >$i</td><td onclick=\"viewContainer($i);\">".$bobo[$i]."</td><td onclick=\"viewContainer($i);\">".$VU." %</td></tr>";
	}	
	echo "</table>";
	echo "<br><table style='margin-left: auto;margin-right: auto;' id='table1'>";	
	echo "<tr><td width=150><b>Boxes</b></td><td width=250><b>Dimensions</b></td></tr>";	
	$c=0; $boxo="";	
	foreach($cnt as $cn){		
		if(($boxes[$c][0]!=0 || $boxes[$c][1]!=0||$boxes[$c][2]!=0 ) && $c>0 ) {
			$vol =  $boxes[$c][0]*$boxes[$c][1]*$boxes[$c][2]*0.000001; 
			$dim = $boxes[$c][0]."x". $boxes[$c][1]."x".$boxes[$c][2];		
			$boxo .= "&BD[]=$dim" ;
			echo "<tr class='selec'><td>$cn</td><td onclick=\"redim('_".$c."_$dim')\" id='_".$c."_$dim'>$dim</td></tr>";	
	  }
		$c+=1;
	}		
	echo "</table>";			
	echo "<br><input class='text' type='button' value='ReCalc' onclick='recal(\"".$inputFile."\");' >";	
	echo "<input type='hidden' value='$boxo' id='boxo'>"; // <input type='checkbox'>
} //end function
?>