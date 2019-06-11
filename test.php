<?php 

$dt = new DateTime(); // current time
$dt->add(new DateInterval('PT1H'));
$date = $dt->format('h:i:s');
echo strtotime($date);

?>