<?php

$json_table = "pi_table.json";
if (!file_exists($json_table)) {
	http_response_code(404);
	die();
}

$json_ip_data = json_decode(file_get_contents($json_table), True);

usort($json_ip_data["data"], function($a, $b) {
    return $a["pi_number"] - $b["pi_number"];
});

$current_time = time();
$json_ip_data_size = sizeof($json_ip_data["data"]);

?>

<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title>IPScript Raspberry Pi Local IP Table</title>

	<link href="iplist.css" rel="stylesheet" type="text/css">
</head>

<body>

<div id="content">
	<div id="title">
		<h1>IPScript Raspberry Pi Local IP Table</h1>
		<h2>FlowMeter Research Group 2018</h2>
	</div>
	<div class="table">
		<h2>Pi Number</h2>
		<ul>
		<?php 
			for ($i=0; $i < $json_ip_data_size; $i++) {
				echo "<li>". $json_ip_data["data"][$i]["pi_number"]. "</li>";
			}
		?>
		</ul>
	</div>
	<div class="table">
		<h2>Local IP</h2>
		<ul>
		<?php
			for ($i=0; $i < $json_ip_data_size; $i++) {
				echo "<li>". $json_ip_data["data"][$i]["local_ip"]. "</li>";
			}
		?>
		</ul>
	</div>
	<div class="table">
		<h2>Last Contact</h2>
		<ul>
		<?php
			for ($i=0; $i < $json_ip_data_size; $i++) {
				echo "<li>". ($current_time - (int)$json_ip_data["data"][$i]["updated_time"]). " seconds</li>";
			}
		?>
		</ul>
	</div>
	<div class="clear"></div>

</div>

</body>
</html>