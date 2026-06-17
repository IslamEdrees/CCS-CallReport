<?php
include("config.php");

$agent  = isset($_GET['agent']) ? mysql_real_escape_string($_GET['agent']) : '';
$status = isset($_GET['status']) ? mysql_real_escape_string($_GET['status']) : '';

$today = date("Y-m-d");

$q = mysql_query("
SELECT
MIN(call_time) min_time,
MAX(call_time) max_time
FROM call_attempts
WHERE DATE(call_time)=CURDATE()
");

$range = mysql_fetch_assoc($q);

$total_calls = 0;
$busy = 0;
$noanswer = 0;
$congestion = 0;
$unavailable = 0;

$s = mysql_query("SELECT * FROM vw_status_summary_today");

while($r=mysql_fetch_assoc($s))
{
    $total_calls += $r['total'];

    if($r['dialstatus']=='BUSY')
        $busy = $r['total'];

    if($r['dialstatus']=='NOANSWER')
        $noanswer = $r['total'];

    if($r['dialstatus']=='CONGESTION')
        $congestion = $r['total'];

    if($r['dialstatus']=='CHANUNAVAIL')
        $unavailable = $r['total'];
}

$sql="
SELECT
call_time,
agent,
real_number,
dialstatus,
hangupcause,
goip
FROM vw_call_attempts
WHERE DATE(call_time)=CURDATE()
";

if($agent!='')
    $sql.=" AND agent='$agent'";

if($status!='')
    $sql.=" AND dialstatus='$status'";

$sql.=" ORDER BY call_time DESC LIMIT 500";

$rs=mysql_query($sql);
?>

<html>
<head>

<title>CCS Manager Dashboard</title>

<style>

body{
background:#0f172a;
color:#e5e7eb;
font-family:Segoe UI,Tahoma;
margin:20px;
}

h1,h2,h3{
color:white;
}

.card{
background:#1e293b;
border:1px solid #334155;
border-radius:10px;
padding:20px;
width:180px;
display:inline-block;
margin:5px;
text-align:center;
}

.card-value{
font-size:28px;
font-weight:bold;
margin-top:10px;
}

.filter-box{
background:#1e293b;
padding:15px;
border-radius:10px;
margin-bottom:20px;
}

table{
width:100%;
border-collapse:collapse;
margin-top:10px;
}

th{
background:#1e293b;
padding:10px;
border:1px solid #334155;
}

td{
padding:8px;
border:1px solid #334155;
}

tr:nth-child(even){
background:#111827;
}

input,select{
background:#111827;
color:white;
border:1px solid #334155;
padding:6px;
}

.btn{
background:#2563eb;
color:white;
border:none;
padding:7px 15px;
cursor:pointer;
}

a{
color:#60a5fa;
text-decoration:none;
}

.range{
background:#1e293b;
padding:10px;
border-radius:10px;
margin-bottom:15px;
}

.section{
margin-top:25px;
}

</style>

</head>

<body>

<h1>CCS Manager Dashboard</h1>

<div class="range">
<b>Date :</b> <?php echo $today; ?>
<br>
<b>Data Range :</b>
<?php echo $range['min_time']; ?>
&nbsp; → &nbsp;
<?php echo $range['max_time']; ?>
</div>

<div class="card">
Total Calls
<div class="card-value"><?php echo $total_calls; ?></div>
</div>

<div class="card">
Busy
<div class="card-value"><?php echo $busy; ?></div>
</div>

<div class="card">
No Answer
<div class="card-value"><?php echo $noanswer; ?></div>
</div>

<div class="card">
Congestion
<div class="card-value"><?php echo $congestion; ?></div>
</div>

<div class="card">
Unavailable
<div class="card-value"><?php echo $unavailable; ?></div>
</div>

<div class="section">

<form method="get" class="filter-box">

Agent

<select name="agent">
<option value="">ALL</option>

<?php

$a=mysql_query("
SELECT DISTINCT agent
FROM vw_call_attempts
ORDER BY agent
");

while($x=mysql_fetch_assoc($a))
{
$sel=($agent==$x['agent'])?'selected':'';
echo "<option $sel>".$x['agent']."</option>";
}

?>

</select>

Status

<select name="status">

<option value="">ALL</option>

<option value="BUSY">BUSY</option>
<option value="NOANSWER">NOANSWER</option>
<option value="CONGESTION">CONGESTION</option>
<option value="CHANUNAVAIL">CHANUNAVAIL</option>

</select>

<input class="btn" type="submit" value="Search">

&nbsp;&nbsp;

<a href="export.php">Export CSV</a>

</form>

</div>

<div class="section">

<h2>Agent Statistics Today</h2>

<table>

<tr>
<th>Agent</th>
<th>Total</th>
<th>Busy</th>
<th>NoAnswer</th>
<th>Congestion</th>
<th>Unavailable</th>
</tr>

<?php

$q=mysql_query("
SELECT *
FROM vw_agent_summary_today
ORDER BY total_calls DESC
");

while($r=mysql_fetch_assoc($q))
{

echo "<tr>";

echo "<td>".$r['agent']."</td>";
echo "<td>".$r['total_calls']."</td>";
echo "<td>".$r['busy_calls']."</td>";
echo "<td>".$r['noanswer_calls']."</td>";
echo "<td>".$r['congestion_calls']."</td>";
echo "<td>".$r['unavailable_calls']."</td>";

echo "</tr>";
}

?>

</table>

</div>

<div class="section">

<h2>GOIP Statistics Today</h2>

<table>

<tr>
<th>GOIP</th>
<th>Total</th>
<th>Busy</th>
<th>NoAnswer</th>
<th>Congestion</th>
<th>Unavailable</th>
</tr>

<?php

$q=mysql_query("
SELECT *
FROM vw_goip_summary_today
ORDER BY total_calls DESC
");

while($r=mysql_fetch_assoc($q))
{

echo "<tr>";

echo "<td>".$r['goip']."</td>";
echo "<td>".$r['total_calls']."</td>";
echo "<td>".$r['busy_calls']."</td>";
echo "<td>".$r['noanswer_calls']."</td>";
echo "<td>".$r['congestion_calls']."</td>";
echo "<td>".$r['unavailable_calls']."</td>";

echo "</tr>";
}

?>

</table>

</div>

<div class="section">

<h2>Latest Call Attempts</h2>

<table>

<tr>
<th>Time</th>
<th>Agent</th>
<th>Number</th>
<th>Status</th>
<th>Cause</th>
<th>GOIP</th>
</tr>

<?php

while($row=mysql_fetch_assoc($rs))
{

echo "<tr>";

echo "<td>".$row['call_time']."</td>";
echo "<td>".$row['agent']."</td>";
echo "<td>".$row['real_number']."</td>";
echo "<td>".$row['dialstatus']."</td>";
echo "<td>".$row['hangupcause']."</td>";
echo "<td>".$row['goip']."</td>";

echo "</tr>";
}

?>

</table>

</div>

</body>
</html>
