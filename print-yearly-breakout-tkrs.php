function getTkrData($ch, $tkr) {
    $url = "https://www.asx.com.au/asx/1/company/$tkr?fields=primary_share,latest_annual_reports,last_dividend,primary_share.indices&callback=angular.callbacks._0";
    curl_setopt($ch, CURLOPT_URL, $url);
    $jsonStr = curl_exec($ch);
    return json_decode($jsonStr);
}

$ch = curl_init();

$tkrs = file($filename, FILE_IGNORE_NEW_LINES);
foreach ($tkrs as $tkr) {
    $data = getTkrData($ch, $tkr);
    if ($data->primary_share->day_high_price == $data->primary_share->year_high_price) {
        echo "$tkr,";
    }
}

echo 'done';
curl_close($ch);
