<?php

$json_table = "pi_table.json";
if (!file_exists($json_table)) {
	http_response_code(404);
	die();
}

if (isset($_GET["pi_number"]) && isset($_GET["local_ip"]) && is_numeric(($_GET["pi_number"]))) {
	$pi_number = $_GET["pi_number"];
	$local_ip = $_GET["local_ip"];
} else {
	http_response_code(400);
	die();
}

$json_ip_data = json_decode(file_get_contents($json_table), True);

usort($json_ip_data["data"], function($a, $b) {
    return $a["pi_number"] - $b["pi_number"];
});

$new_entry = True;
for ($i = 0; $i < sizeof($json_ip_data["data"]); $i++) {
	if ($json_ip_data["data"][$i]["pi_number"] == $pi_number) {
		$new_entry = False;
		$json_ip_data["data"][$i]["local_ip"] = $local_ip;
		$json_ip_data["data"][$i]["updated_time"] = time();
	}
}

if ($new_entry) {
	$new_entry_json = new \stdClass();
	$new_entry_json->pi_number = (int)$pi_number;
	$new_entry_json->local_ip = $local_ip;
	$new_entry_json->updated_time = time();
	array_push($json_ip_data["data"], $new_entry_json);
}

file_put_contents($json_table, json_encode($json_ip_data));

echo "Thanks for stopping by!";

?>