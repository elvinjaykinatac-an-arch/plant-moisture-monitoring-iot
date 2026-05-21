<?php

$conn = new mysqli(
    "mysql.railway.internal",
    "root",
    "qkhOTtUHBwklFgEjDSIvoEHZCzVVqmxd",
    "railway",
    3306
);

if ($conn->connect_error) {
    die("Connection Failed");
}

$moisture = $_GET['moisture'];
$pump = $_GET['pump'];

$sql = "INSERT INTO sensor_data (moisture, pump_status)
VALUES ('$moisture', '$pump')";

if ($conn->query($sql) === TRUE) {
    echo "OK";
} else {
    echo "ERROR";
}

$conn->close();

?>