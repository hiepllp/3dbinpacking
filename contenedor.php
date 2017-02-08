<script src="./jquery-1.12.3.min.js"></script>
<?php

$translateX_= isset($_REQUEST["translateX"]) ? $_REQUEST["translateX"] : "";
$translateY_= isset($_REQUEST["translateY"]) ? $_REQUEST["translateY"] : "";
$translateZ_= isset($_REQUEST["translateZ"]) ? $_REQUEST["translateZ"] : "";

$rotateX_= isset($_REQUEST["rotateX"])  ? $_REQUEST["rotateX"] : "";
$rotateY_= isset($_REQUEST["rotateY"])  ? $_REQUEST["rotateY"] : "";
$rotateZ_= isset($_REQUEST["rotateZ"])  ? $_REQUEST["rotateZ"] : "";




$xAngle= isset($_REQUEST["xAngle"])  ? $_REQUEST["xAngle"] : -15;
$yAngle= isset($_REQUEST["yAngle"])  ? $_REQUEST["yAngle"] : -70;
$zAngle= isset($_REQUEST["zAngle"])  ? $_REQUEST["zAngle"] : 0;


$binid = isset($_REQUEST["binid"])  ? $_REQUEST["binid"] : -1;

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

?>
<STYLE>

.container{
	width: 1px;
	height: 1px;
	position: relative;	
	-webkit-perspective:1000px;
	margin-left:280px;
	margin-top:60px;	

	

}

#first_line_of_cubes{	
	position: absolute;
	left: 0px;
	-webkit-transform-style:preserve-3d;	
	transform: rotateZ(-180deg) ;
	margin-top:220px;
	
}
.cube{	
	width: 100%;
	height: 100%;
	display: block;
	position: absolute;
	-webkit-transform-style:preserve-3d;
	-webkit-transform:rotateY(0deg);
	-webkit-transform:rotateX(0deg);
}

.boxcolor{
	/*width:10px;
	height:10px;*/
	width:100%;
	height:100%;
	background-color:red;
}

/*********/
.profile-picS {  
  -webkit-filter: url(#monochromeS);
  filter:  url(#monochromeS);
  
}

/*********/
.profile-pic { 
  -webkit-filter: url(#monochrome<?=$id?>);
  filter:  url(#monochrome<?=$id?>);
  
}



tr.selec:hover {
    background: #000;
}
tr.selec:hover td {
    background: #c7d4dd !important;
    cursor: pointer; cursor: hand
}


</STYLE>

<body>
	
	<svg style="height: 0;">
<filter id="monochrome" color-interpolation-filters="sRGB" x="0" y="0" height="100%" width="100%">
<feColorMatrix type="matrix"
   values = "0.2125  0.7154  0.0721  0  0
                  0.2125  0.7154  0.0721  0  0
                  0.2125  0.7154  0.0721  0  0
                  0       0       0       1  0"
/>
</filter>
</svg>

	
<svg style="height: 0;">
<filter id="monochromeS" color-interpolation-filters="sRGB" x="0" y="0" height="100%" width="100%">
<feColorMatrix type="matrix"
   values = "0.2125  0.7154  0.0721  0  10
                  0.2125  0.7154  0.0721  0  0
                  0.2125  0.7154  0.0721  0  0
                  0       0       0       1  0"
/>
</filter>
</svg>
	
	<section class="container">
		<div id="first_line_of_cubes">
<?php
$ret = "";
$cx = 0;


$bno = 1;

foreach($lines as $line)
{  	
	// 0  1  2  3  4  5 6 7 8 9  10 
	// TX;TY;TZ;RX;RY;RZ;W;H;D;N;BNO     				
	
	$row=explode(";",$line);		
	$translateZ= 0;
	$zindex -=1;  
  
  if(!isset($row[9]))break;
   
  $id=  isset($row[9]) ? $row[9] : 3333;
  
  
  
	$W = isset($row[6]) ? floatval ($row[6]):0;
	$H = isset($row[7]) ? floatval ($row[7]):0;		
	$D = isset($row[8]) ? floatval ($row[8]):0;
				
			
		if($id==500){		
		//$translateY_=-2;
		$H+=5;
		$translateZ_= ($D/2)-60 	;
		$D+=5;		
		//$translateX_= -2;							
	}else{
		//$translateX_=0+$cx;	
		$translateZ_=0;		
		//$translateY_=0;
	}	
			
	$translateX= isset($row[0]) ? floatval ($row[0]+$translateX_):0;
	$translateY= isset($row[1]) ? floatval ($row[1]+$translateY_):0;
	$translateZ= isset($row[2]) ? floatval ($row[2]+$translateZ_):0;
		
	//$scale3d=floatval ($row[SC]+$scale3d_);
	$scale3d=1;
	
	$rotateX= isset($row[3]) ?  -floatval ($row[3]+$rotateX_):0;
	$rotateY= isset($row[4]) ?   floatval ($row[4]+$rotateY_):0;
	$rotateZ= isset($row[5]) ?  -floatval ($row[5]+$rotateZ_):0;	
	
	
	/////////////////////////////////////////////////////////////////
	$right = ($W-296)+150;
	$bottom = ($H-196)+100;
	$topbuttom =  50-(($D-96)/2);	
	$left = (-1 *($D-96)/2)+100+$translateX;		
	$front = 50+(($D-96)/2)-$translateZ;
	$back = 50+(($D-96)/2)+$translateZ;
	$bkcolor = random_color();
	/////////////////////////////////////////////////////////////////
	
	$bno = $row[10];
		
	if ( $binid==-1 ){
		if ($bno>0) $bno-=1;
		$translateX_= -$bno * 300;	
	}	
	

	//}
	
	//$bno = $row[10];
	
if($binid==-1 || $bno == $binid || $id == 500){	
	$ret .= "<TR class='selec' onmouseover=\" boxS(".$id.");\" onmouseout=\"boxD(".$id.");\" onclick=\"view($id,$('#chk$id').is(':checked')); \"   >";
	$ret .= "<td><input id='chk$id' type='checkbox' onclick='view($id,this.checked); '></td><td>";
	if($id!=500) $ret .= "<div class='boxcolor' style='background-color:$bkcolor;opacity: 0.7;'>";
	$ret .= "<b>$id&nbsp</b>";
	if($id!=500) $ret .= "</div>";	
	//$ret .= "</td><td>$translateX</td><td>$translateY</td><td>$translateZ</td><td>$rotateX</td><td>$rotateY</td><td>$rotateZ</td><td>$W</td><td>$H</td><td>$D</td><td>".$row[10]."</td><tr>";  			
	$ret .= "</td><td>$W</td><td>$H</td><td>$D</td><td>".$row[10]."</td><tr>";  			
	
?>

<STYLE>
		.cube<?=$id?> figure{	
			width: <?=$W?>px;
			height: <?=$H?>px;	
			display: block;
			position: absolute;
			border: 2px solid black;
			opacity: 0.7;
			margin-top:<?=$translateY?>px;						
			margin-left:<?=$translateX?>px;								
		}
    .cube<?=$id?> .front,
    .cube<?=$id?> .back {
      width:<?=$W?>px;
      height:<?=$H?>px;	        
    }
    .cube<?=$id?> .right,
    .cube<?=$id?> .left {
      width:<?=$D?>px;
      height:<?=$H?>px;            
      margin-left:<?=$left?>px;            
    }
    .cube<?=$id?> .top,
    .cube<?=$id?> .bottom {
      width:<?=$W?>px;
      height:<?=$D?>px;      
	    top:<?=$topbuttom?>px;	  	    
      line-height: 96px;
    }        
    .cube<?=$id?> .front  {
    	<?= $id==500 ? "background: green;" : "background: $bkcolor;" ?>   	
      -webkit-transform: translateZ( <?=$front?>px );
    }
    .cube<?=$id?> .back   {    	
    	<?= $id==500 ? "background: green;" : "background-color: $bkcolor;" ?>   	
      -webkit-transform: rotateX( -180deg ) translateZ(  <?=$back?>px );
    }
    .cube<?=$id?> .right {	    	
    	<?= $id==500 ? "background: green;" : "background-color: $bkcolor;" ?>   	
      -webkit-transform: rotateY(   90deg ) translateZ( <?=$right?>px ) translateX(<?=$translateZ?>px);
    }
    .cube<?=$id?> .left {  
  		<?= $id==500 ? "background: green;" : "background-color: $bkcolor;" ?>   	
      -webkit-transform: rotateY(  -90deg ) translateZ( 150px )  translateX(-<?=$translateZ?>px);
    }	
    .cube<?=$id?> .top {    	
    	<?= $id==500 ? "background: green;" : "background-color: $bkcolor;" ?>   	
      -webkit-transform: rotateX(   90deg ) translateZ( 100px )  translateY(-<?=$translateZ?>px);
    }
    .cube<?=$id?> .bottom {    	
    	<?= $id==500 ? "background: green;" : "background-color: $bkcolor;" ?>   	
      -webkit-transform: rotateX(  -90deg ) translateZ( <?=$bottom?>px ) translateY(<?=$translateZ?>px);     
    }        
    </STYLE>

			<div id="cube<?=$id?>" class="cube<?=$id?>"   onmouseover="boxS(<?=$id?>);$('#boxSM').html('<?=$id?>');" onmouseout="boxD(<?=$id?>);$('#boxSM').html('&nbsp');">						
				<figure class="front"  id="if<?=$id?>"></figure>
				<figure class="back"   id="iba<?=$id?>"></figure>
				<figure class="right"  id="ir<?=$id?>"></figure>
				<figure class="left"   id="il<?=$id?>"></figure>
				<figure class="top"    id="t<?=$id?>"></figure>
				<figure class="bottom" id="ibo<?=$id?>"></figure>
			</div>
<?php

} // end if


//break;
} // END FOR LINES

function random_color_part() {
    return str_pad( dechex( mt_rand( 0, 255 ) ), 2, '0', STR_PAD_LEFT);
}

function random_color() {
    return random_color_part() . random_color_part() . random_color_part();
}

?>		
</div>	
</section>

<div id="boxSM"></div>


<style>
	.table-wrapper
{
    border: 1px solid grey;
    width: 440px;
    height: 330px;
    overflow: auto;
    margin-top: 300px;    
    margin-right: auto;
    margin-left: auto;    
}

table
{
    
    margin-right: 20px;
}

td
{
   
    height: 20px;
   
}
</style>


<div class="table-wrapper">
<?php
//echo "<table style='text-align:right' border=0><TR><td><input type='checkbox' onclick='viewall(); '></td><td width=30><b>$binid</b></td><td width=100>TX</td><td width=100>TY</td><td width=120>TZ</td><td width=100>RX</td><td width=100>RY</td><td width=100>RZ</td><td width=100>W</td><td width=100>H</td><td width=100>D</td><td width=100>B</td></TR>";

echo "<table style='text-align:right' border=0><TR><td><input type='checkbox' onclick='viewall(); '></td>

<td width=30><b>$binid</b></td><td width=100>W</td><td width=100>H</td><td width=100>D</td><td width=100>B</td></TR>";

echo $ret;
echo "</table>";
?>
</div>

</body>`


<SCRIPT>
	
	
	function boxS(id) // mouse over
	{
	//$('#if'+id).removeClass('profile-pic');$('#iba'+id).removeClass('profile-pic');$('#ir'+id).removeClass('profile-pic');$('#il'+id).removeClass('profile-pic'); $('#it'+id).removeClass('profile-pic'); $('#ibo'+id).removeClass('profile-pic');
	$('#if'+id).addClass('profile-picS');$('#iba'+id).addClass('profile-picS');$('#ir'+id).addClass('profile-picS');$('#il'+id).addClass('profile-picS'); $('#it'+id).addClass('profile-picS'); $('#ibo'+id).addClass('profile-picS');
	}
		function boxD(id) // mouse out
	{
	$('#if'+id).removeClass('profile-picS');$('#iba'+id).removeClass('profile-picS');$('#ir'+id).removeClass('profile-picS');$('#il'+id).removeClass('profile-picS'); $('#it'+id).removeClass('profile-picS'); $('#ibo'+id).removeClass('profile-picS');
	//$('#if'+id).addClass('profile-pic');$('#iba'+id).addClass('profile-pic');$('#ir'+id).addClass('profile-pic');$('#il'+id).addClass('profile-pic'); $('#it'+id).addClass('profile-pic'); $('#ibo'+id).addClass('profile-pic');
	}	
	
	
	
// gira el contedor
var deg = 90;
function animateMe() {
    $(this).attr("style", "-webkit-transform: rotateY(" + deg++ + "deg) rotateZ(-180deg)");
    $(this).animate([1], { duration: 20, complete: animateMe });
};
//animateMe.call($("#first_line_of_cubes"));


// oculta o muestra una caja
function view(id,val){
	
	//alert($('#chk$id').is(':checked'));
	
	var val_ = val.toString();
			
	if (val == true ){
		$("#cube"+id).css({"display":"block"});
		$("#chk"+id).prop('checked', false);		
	}else{		
		$("#cube"+id).css({"display":"none"});		
		$("#chk"+id).prop('checked', true);
	}
	
		//alert(id+" " +val);
		
	
}	


// oculta muestra todas las cajas
function viewall(){
	var cnt = <?=count($lines)?>;		
	var val = $('#chk500').is(':checked');		
	if(val==true){ $("#chk500").prop('checked',false);$("#cube500").css({"display":"block"});}else{$("#chk500").prop('checked',true);$("#cube500").css({"display":"none"});}	
	for (var i = 0 ; i < cnt-1 ; i+=1)	{	
		if (val == true ){
			$("#cube"+i).css({"display":"block"});
			$("#chk"+i).prop('checked', false);		
		}else{		
			$("#cube"+i).css({"display":"none"});		
			$("#chk"+i).prop('checked', true);
		}
	}
}

 xAngle = <?=$xAngle?>;
 yAngle = <?=$yAngle?>;
 zAngle = <?=$zAngle?>;







$("#first_line_of_cubes").attr("style", "-webkit-transform: rotateX(" + <?=$xAngle?> + "deg) rotateY(" + <?=$yAngle?> + "deg) rotateZ(-180deg)  translateZ("+<?=$zAngle?>+"px)");            


</SCRIPT>


<input type="hidden" id="xAngle" value="<?=$xAngle?>">
<input type="hidden" id="yAngle" value="<?=$yAngle?>">
<input type="hidden" id="zAngle" value="<?=$zAngle?>">


