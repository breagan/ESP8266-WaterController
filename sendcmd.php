<html>
<head>
<title>ESP Water Controller</title>
 <META http-equiv="refresh" content="0;URL=.">    
 </head>
<body style="background-color:#FFFCEA">
<pre>
<?php
//print_r(var_dump($_POST));
// build request

@$zone0 = $_POST['zone0'];     // parses time in variables for each zone.
@$zone1 = $_POST['zone1'];
@$zone2 = $_POST['zone2'];
@$stop = $_POST['stop'];
$totaltime = $zone0+$zone1+$zone2;

$IP = "192.168.11.44";             //  IP to the ESP8266

if($zone0)
{
$request = "zone0=$zone0&zone1=$zone1&zone2=$zone2";    // creats the attributes for the get request sent to ESP
}
else
{
$request = "stop";                                      //  if there isnt a $zone0 value that the script assumes that Stop was pressed. 
}

$fp = fsockopen($IP, 80, $errno, $errstr, 5);           //   opens connection
if (!$fp) {
	echo "$t not found or offline! - $errstr ($errno) \n"; }
	 else {
	$out = "GET /?$request HTTP/1.1\r\n";
	fwrite($fp, $out);                                   // sends request to ESP8266
	fclose($fp);
	flush($fp);
	}

$fh = fopen("lastcmd.txt", "w");                         // records the last command in a text file.  
$datestamp = date("m d Y H:i"); 
fwrite($fh, $request.":".$datestamp." - ".$totaltime);
fclose($fh);
	

?>
