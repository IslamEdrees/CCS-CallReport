<?php

include("config.php");

header("Content-Type: text/csv");
header("Content-Disposition: attachment; filename=call_report.csv");

echo "Time,Agent,Number,Status,Cause,GOIP\n";

$q=mysql_query("
SELECT
call_time,
agent,
real_number,
dialstatus,
hangupcause,
goip
FROM vw_call_attempts
ORDER BY call_time DESC
");

while($r=mysql_fetch_assoc($q))
{
echo
$r['call_time'].",".
$r['agent'].",".
$r['real_number'].",".
$r['dialstatus'].",".
$r['hangupcause'].",".
$r['goip']."\n";
}

?>
