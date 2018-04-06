
<?php
require 'vendor/autoload.php';

$client = new GuzzleHttp\Client(['base_uri' => 'https://akabab.github.io/superhero-api/api/']);

for ($i=1; $i <14 ; $i++) {
  if (9==$i) continue;
  $players=json_decode ($client->request('GET', 'id/' . $i . '.json')->getBody()->getContents());
  echo 'name ' . $players->name . '<br>';
  echo '<br>';
  foreach ($players->powerstats as $key=> $val) {
  echo $key . ' : '. $val . '<br>';
  };
echo '<br>';
  foreach ($players->biography as $key=> $val) {
  echo $key . ' : '. $val . '<br>';
  };
}
