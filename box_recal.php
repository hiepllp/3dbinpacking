<?php


include "./lib.opti.php";

$file = $_REQUEST["file"];

$lines = array();
	$ff = fopen($file, "r");
	$c=0;
	while (!feof($ff)) {
		$line = fgets($ff);
		$lines[] = $line;		
		$c+=1;
	}
	fclose($ff);
	
	$BD = $_REQUEST["BD"];
	$B = $_REQUEST["B"];
	$D = $_REQUEST["D"];
	
	$WW = $_REQUEST["WW"];
	$HH = $_REQUEST["HH"];
	$DD = $_REQUEST["DD"];
	
	$i=0;
	foreach($D as $d){							
		$D[$i] = trim(str_replace("x"," ",$d ));
		$i+=1;
	}

	$i=0;
	foreach($BD as $bd){							
		$BD[$i] = trim(str_replace("x"," ",$bd ));
		$i+=1;
	}	
	
	//echo "<pre>",  print_r($BD) ;//."<h1>fin</fin>". print_r($BD);
//	exit;
 	unlink ( $file );
	$ff = fopen($file, "w");		$cc=0;
	foreach($lines as $line)
	{
			//$line = str_replace(" ","",trim($line));
			$line = trim($line);
			
			//$c = in_array(trim($line),$BD);
       $c=0;				
			foreach($BD as $bb){														
				if($line == $bb ){  break;}
				$c+=1;				
			}			
			if($c < count($BD))
			{			
				fwrite($ff, $D[$c]."\n");						
				//echo "<br>--->".$D[$c]."\n";										
			}			
			if($cc == 0)
			{
				$l = explode(" ",$line);
				fwrite($ff, $l[0]." $WW $HH $DD\n");							
			}
			 
			$cc+=1;
	}
	fclose($ff);
	
	$cmd = dirname($_SERVER['SCRIPT_FILENAME']) ."/3dbpp/Debug/3dbpp.exe ".  dirname($_SERVER['SCRIPT_FILENAME']) ."/3d.txt  3 3 3 0 " . dirname($_SERVER['SCRIPT_FILENAME']) ."/3d.csv";		
	print shell_exec( $cmd );   		  	  		
	 verOpti($file);
	echo "<p style='font-size:14px;'><br>Execute: $cmd</p>";	
	

?>

