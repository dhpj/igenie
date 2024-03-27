<?
echo '<pre> :: ';print_r("처리 중지됨");
exit;
$conn = mssql_connect("dba.prsms.co.kr:1767", "db_gcm01_user", "it!gcm01#user95$");
if (!$conn) {  die('Not connected : ' . mssql_get_last_message());} 
$db_selected = mssql_select_db("BACK_SSANSMS", $conn);
if (!$db_selected) {
  die ('Can\'t use db : ' . mssql_get_last_message());
} 

mssql_query("SET ANSI_NULLS ON");
mssql_query("SET ANSI_PADDING ON");
mssql_query("SET ANSI_WARNINGS ON");
mssql_query("SET ARITHABORT ON");
mssql_query("SET CONCAT_NULL_YIELDS_NULL ON");
mssql_query("SET NUMERIC_ROUNDABORT OFF");
mssql_query("SET QUOTED_IDENTIFIER ON");




echo "<pre>:: ";print_r($conn);
echo "<pre>:: ";print_r($db_selected);

$return_value = "";

$ip = "210.114.225.53";
$id="dhntest";
$pw="dhn7985#!";
$reserv=0;
$reserv_dt=date('Y-m-d H:i');
$telno="01065748654";
$names=" ";
$callback="0552389456";
$type="GLMS";
$content="(광고)메시지 발송 테스트11, 수신거부:080-870-6789";
$content = iconv("UTF-8", "EUC-KR", $content);


$stmt = mssql_init('uspInsertSms');

// Bind values
mssql_bind($stmt, '@Ip',				$ip,				SQLVARCHAR);
mssql_bind($stmt, '@LogInId',			$id,				SQLVARCHAR);
mssql_bind($stmt, '@LogInPassword',	$pw,				SQLVARCHAR);
mssql_bind($stmt, '@IsReserved',    $reserv,			SQLBIT);
mssql_bind($stmt, '@ReserveDateTime',$reserv_dt,	SQLVARCHAR);
mssql_bind($stmt, '@PhoneListComma',$telno,        SQLVARCHAR);
mssql_bind($stmt, '@NameListComma',	$names,        SQLVARCHAR);
mssql_bind($stmt, '@CallBack',		$callback,		SQLVARCHAR);
mssql_bind($stmt, '@Stype',			$type,			SQLVARCHAR);
mssql_bind($stmt, '@Contents',		$content,      SQLVARCHAR);

mssql_bind($stmt, "@XmlInfoIs",		$return_value, SQLVARCHAR, true, true);

// Execute the statement
$result = mssql_execute($stmt);

$query = "Declare @XmlInfoIs Varchar 
EXEC uspInsertSms '".$ip."', '".$id."','".$pw."', ".$reserv.",'".$reserv_dt."','".$telno."','".$names."','".$callback."','".$type."','".$content."',@XmlInfoIs OUTPUT";
//$result = mssql_query($query);

if (!$result) {
  die ('Can\'t : ' . mssql_get_last_message());
} 
mssql_free_statement($stmt);
$arr = mssql_fetch_row($result);

echo "<pre> arr :: ";print_r($arr);
echo "<pre> return_value :: ";print_r($return_value);
// And we can free it like so:
mssql_free_statement($stmt);


?>

<?php
/*
$serverName = "dba.prsms.co.kr, 1767";
$connectionOptions = array(
    "Database" => "BACK_SSANSMS",
    "UID" => "db_gcm01_user",
    "PWD" => "it!gcm01#user95$"
);
//Establishes the connection
$conn = sqlsrv_connect($serverName, $connectionOptions);
if($conn) {
    echo "Connected!";
}
else {
	echo '<pre> :: ';print_r("ERROR");
}
*/
?>