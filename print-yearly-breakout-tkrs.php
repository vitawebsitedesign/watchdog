<?php
function getTkrData($ch, $tkr) {
    $url = "https://www.asx.com.au/asx/1/company/$tkr?fields=primary_share,latest_annual_reports,last_dividend,primary_share.indices&callback=";
    curl_setopt($ch, CURLOPT_URL, $url);
    $response = curl_exec($ch);
	$jsonStr = substr($response, 1, strlen($response) - 3);
    return json_decode($jsonStr);
}

$ch = curl_init();
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

$tkrs = file('tkrs.txt', FILE_IGNORE_NEW_LINES);
foreach ($tkrs as $tkr) {
    $data = getTkrData($ch, $tkr);
	
	$dailyHigh = null;
	if (isset($data->primary_share->day_high_price)) {
		$dailyHigh = $data->primary_share->day_high_price;
	}
	
	$yearlyHigh = null;
	if (isset($data->primary_share->year_high_price)) {
		$yearlyHigh = floatval($data->primary_share->year_high_price);
	}
	
    if ($dailyHigh != null && $yearlyHigh != null && $dailyHigh > 0.04 && $dailyHigh == $yearlyHigh) {
        echo "$tkr,";
    } else {
		echo '.';
	}
}

echo 'done';
curl_close($ch);
