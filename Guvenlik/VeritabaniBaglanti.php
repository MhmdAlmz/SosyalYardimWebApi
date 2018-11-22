<?php


$serverName = "localhost";
$database = "SosyalYardimProjeDB";
$uid = 'sa';
$pwd = '123';
try {
    $db = new PDO(
        "sqlsrv:server=$serverName;Database=$database",
        $uid,
        $pwd,
        array(
            //PDO::ATTR_PERSISTENT => true,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        )
    );
}
catch(PDOException $e) {
    die("Error connecting to SQL Server: " . $e->getMessage());
}