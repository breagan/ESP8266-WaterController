
<html>
<head>
<title>ESP Water Controller</title>
<style type="text/css">
#maincontainer 
{   
top:0px;
padding-top:0;
margin:auto; position:relative;
width:320px;
height:50%;
}
  </style>

</head>

<body style="background-color:#FFFCEA">
<pre>
<?php
$t="";
$temp="";
@$fp = fsockopen("192.168.11.44", 80, $errno, $errstr, 2);   //  edit this to contain IP number and port assigned to ESP8266.
if (!$fp) {
	//  echo error message $errstr if wanted.  !$fp indicates that this script cannot reach the ESP.
	}
	 else {								// used for debugging
	$out = "GET / HTTP/1.1\r\n";              
	fwrite($fp, $out);
	$x=0;
	while (!feof($fp)) {
		$temp[0] = fgetss($fp,256);
		$x++;
	}
	fclose($fp);
	flush($fp);
$temp = explode(":",$temp[0]);
	}
@$mac = $temp[0];
?>
<div id="maincontainer">
<table cellpadding = "2"  border="0" width="300"><tr><td><center><h2><font color="#928646">Irrigation Controller</h2></td></tr></table>
<?PHP
if(!$temp){echo "<B><center>Controller is Offline..."; exit;}
?>
	<table cellpadding = "5"  bgcolor="#ADD6AD" border="1" width="300">
	<tr>
	<td><form action="sendcmd.php"method = "post" >
	<font size="5"><center>All zones-60 min each <br>3 hours total</font>
	<input type="hidden" name="zone0" value="60">
	<input type="hidden" name="zone1" value="60">
	<input type="hidden" name="zone2" value="60"><BR><BR>
	<input type="submit" value ="START All Zones"></form>
	</td>
	</tr>
	</table>
   <table cellpadding = "5"  bgcolor="#ADD6AD" border="1" width=300>
   <form action="sendcmd.php" method = "post">
    <tr>
	<td  colspan="2"><font size="5"><center>Individual Zones.</font></td>
	<TR>
	  <TD><font size="5"><center>Zone</TD> 
	  <TD><font size="5"><center>Duration</TD>
	</TR>
  <TR>
	  <TD bgcolor="C4E1C4" align="center"><font size="5">Back Yard</TD> 
	  <TD bgcolor="C4E1C4">  </select><center>
	  <select name="zone0">
	  <option value=00>Off</option>
	  <option value=10>10</option>
	  <option value=20>20</option>
	  <option value=30>30</option>
	  <option value=45>45</option>
	  <option value=60>60</option>
	  </select></TD>
	</TR>
    <TR>
	  <TD bgcolor="C4E1C4" align="center"><font size="5">Shrubs</TD>
	  <TD bgcolor="C4E1C4"></select><center>
	  <select name="zone1">
	  <option value=00>Off</option>
	  <option value=10>10</option>
	  <option value=20>20</option>
	  <option value=30>30</option>
	  <option value=45>45</option>
	  <option value=60>60</option>
	  </select>  </TD>
  </TR>
  <TR>
	  <TD bgcolor="C4E1C4" align="center"><font size="5">Front Yard</TD>
	  <TD bgcolor="C4E1C4"></select><center>
	  <select name="zone2">
	  <option value=00>Off</option>
	  <option value=10>10</option>
	  <option value=20>20</option>
	  <option value=30>30</option>
	  <option value=45>45</option>
	  <option value=60>60</option>
      </select>  </TD>
  </TR>
<tr>
   <td colspan="2" align="center"><input type="submit" value="START Selected Zones">
  </form>
  </td>
</tr>
  </table>
<?php
$fh = file("lastcmd.txt");               //  this file contains the last command sent to the ESP and time sent.  
//	echo $fh[0];
//	echo "<BR>";
$plusHour = 0;
$plustime = trim(substr($fh[0],46,3));
//echo $plustime;
	if($plustime > 59) 
		{
			$plusHour = intval($plustime/60);
			$plusMin = $plustime % 60;
		}
	else
		{
		$plusMin = $plustime;
		}
		
		echo "<BR>";
//echo $plusHour."  ".$plusMin;

$start_time = trim(substr($fh[0],27,100));

$timeMonth = trim(substr($fh[0],27,2));
$timeDay = trim(substr($fh[0],30,2));
$timeYear = trim(substr($fh[0],33,4));
$hourAdd = 0;
$timeMin = trim(substr($fh[0],41,2)) + $plusMin;
  if($timeMin > 59) {$hourAdd = 1; $timeMin = $timeMin - 60;}
$timeHour = trim(substr($fh[0],38,2)) + $plusHour + $hourAdd;
  if($timeHour > 23) {$timeHour = $timeHour - 24;}
$endtime = $timeMonth."/".$timeDay."/".$timeYear." ".$timeHour.":".$timeMin;
//echo "<BR>Endtime:<BR>";
//echo $endtime;

$zone0= substr($fh[0],6,2);
$zone1= substr($fh[0],15,2);
$zone2= substr($fh[0],24,2);
if(substr($fh[0],0,4) == "stop"){
$zone0 = 0; $zone1=0; $zone2=0; $start_time = "Stop";}
if($zone0 == "00") {$zone0 = "off";}
if($zone1 == "00") {$zone1 = "off";}
if($zone2 == "00") {$zone2 = "off";}

if($plustime > 0){

echo "<table cellpadding = '5'  bgcolor='#FFB2B2' border='1' width=300>";
echo "  <tr><td>";
echo "<form action='sendcmd.php' method = 'post'>";
echo "<font size='5'><center>Stop all zones.<BR><BR></font>";
echo "<input type='hidden' name='stop' value='stop'>";
echo "<input type='submit' value ='STOP all Zones'>";
echo "</form>";
echo "</td></tr></table>";
echo "<p>";
echo "<table cellpadding = '5'  bgcolor='#FFFFF8' border='1' width='300'>";
echo "<tr>";
echo "<td colspan='3'><center><font color='#928646'>Last command sent</td>";
echo "</tr>";
echo "<td><font color='#928646'><center>Back Yard $zone0</td>";
echo "<td><font color='#928646'><center>Shrubs $zone1</td>";
echo "<td><font color='#928646'><center>Front Yard $zone2</td>";
echo "</tr>";
echo "<tr>";
echo "<td colspan='3'><center><font color='#928646'>$start_time</td>";
echo "</tr></table>";


//echo "<BR>";
//echo "<font color='#D3CFB5'><center>$mac<BR>";
echo "</font>";
	
			echo "  <table cellpadding = '5'  bgcolor='#ADD6AD' border='1' width=300>";
			echo "  <tr><td><center><span id='countdown'></span>";
			echo "  </td>";
			echo "  </tr></table>";
		}
?>
</div>
<script>
// set the date we're counting down to
var target_date = new Date("<?php echo $endtime; ?>").getTime();
 
// variables for time units
var days, hours, minutes, seconds;
 
// get tag element
var countdown = document.getElementById("countdown");
 
// update the tag with id "countdown" every 1 second
setInterval(function () {
 
    // find the amount of "seconds" between now and target
    var current_date = new Date().getTime();
    var seconds_left = (target_date - current_date) / 1000;
    var rawseconds = seconds_left
    
	// do some time calculations
    days = parseInt(seconds_left / 86400);
    seconds_left = seconds_left % 86400;
     
    hours = parseInt(seconds_left / 3600);
    seconds_left = seconds_left % 3600;
     
    minutes = parseInt(seconds_left / 60);
    seconds = parseInt(seconds_left % 60);
     
    // format countdown string + set tag value
    if(rawseconds < 0){rawseconds = 0} 
	
//	countdown.innerHTML = days + "d, " + hours + "h, "
//   + minutes + "m, " + seconds + "s---- " + rawseconds;  
    if(seconds <0) {hours = "0"; minutes = "0"; seconds = "0";}
	if(hours <10) {hours = "0"+hours}
	if(minutes <10) {minutes = "0"+minutes}
	if(seconds <10) {seconds = "0"+seconds}
	countdown.innerHTML = "Time remaining:  <B>" + hours + ":"
    + minutes + ":" + seconds; 
 
}, 1000);
</script>
</body>
</html>
