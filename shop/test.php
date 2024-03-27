<?php
//phpinfo();
//exit;
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

$ip = "218.146.252.38";
$id="db_gcm01_user";
$pw="it!gcm01#user95$";
$reserv=0;
$reserv_dt=date('Y-m-d H:i');
$telno="01093111339";
$names="홍길동";
$callback="01093111339";
$type="GLMS";
$content="메시지 발송 테스트";

$procedure_params = array(
	array(&$ip, SQLSRV_PARAM_OUT),
	array(&$id, SQLSRV_PARAM_OUT),
	array(&$pw, SQLSRV_PARAM_OUT),
	array(&$reserv, SQLSRV_PARAM_OUT),
	array(&$reserv_dt, SQLSRV_PARAM_OUT),
	array(&$telno, SQLSRV_PARAM_OUT),
	array(&$names, SQLSRV_PARAM_OUT),
	array(&$callback, SQLSRV_PARAM_OUT),
	array(&$type, SQLSRV_PARAM_OUT),
	array(&$content, SQLSRV_PARAM_OUT),
	array(&$return_value, SQLSRV_PARAM_OUT)
);
// EXEC the procedure, {call stp_Create_Item (@Item_ID = ?, @Item_Name = ?)} seems to fail with various errors in my experiments
$sql = "EXEC uspInsertSms @Ip=?, @LogInId=?, @LogInPassword=?, @IsReserved=?, @ReserveDateTime=?, @PhoneListComma=?, @NameListComma=?, @Callback=?, @Stype=?, @Contents=?, @XmlInfoIs=?";
$stmt = sqlsrv_prepare($conn, $sql, $procedure_params);

echo "<pre> result :: ";print_r($return_value);

 


?>