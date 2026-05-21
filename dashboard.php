<?php
session_start();

// LOGIN PROTECTION
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$conn = new mysqli("localhost", "root", "", "plant_monitoring");

if ($conn->connect_error) {
    die("Database Connection Failed");
}

// Latest data
$latest = $conn->query("SELECT * FROM sensor_data ORDER BY id DESC LIMIT 1");
$latestData = $latest->fetch_assoc();

// Graph data
$result = $conn->query("SELECT * FROM sensor_data ORDER BY id DESC LIMIT 20");

$labels = [];
$moisture = [];

while($row = $result->fetch_assoc()) {
    $labels[] = $row['created_at'];
    $moisture[] = $row['moisture'];
}
?>

<!DOCTYPE html>
<html>
<head>

    <title>Smart Plant Dashboard</title>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <meta http-equiv="refresh" content="5">

    <style>

        body {
            font-family: Arial;
            background: #f4f4f4;
            margin: 0;
            padding: 20px;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .logout-btn {
            background: red;
            color: white;
            padding: 10px 15px;
            text-decoration: none;
            border-radius: 5px;
        }

        h1 {
            color: green;
            margin: 0;
        }

        .cards {
            display: flex;
            gap: 20px;
            margin-bottom: 20px;
        }

        .card {
            background: white;
            padding: 20px;
            flex: 1;
            border-radius: 10px;
            box-shadow: 0px 0px 10px #ccc;
        }

        .table-container {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px #ccc;
            margin-top: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table th, table td {
            padding: 10px;
            border: 1px solid #ccc;
            text-align: center;
        }

    </style>

</head>

<body>

<!-- HEADER WITH LOGOUT -->
<div class="header">
    <h1>🌱 Smart Plant Monitoring Dashboard</h1>

    <a href="logout.php" class="logout-btn">Logout</a>
</div>

<div class="cards">

    <div class="card">
        <h3>💧 Moisture</h3>
        <h2><?php echo $latestData['moisture']; ?></h2>
    </div>

    <div class="card">
        <h3>🚰 Pump Status</h3>
        <h2><?php echo $latestData['pump_status']; ?></h2>
    </div>

    <div class="card">
        <h3>🕒 Last Update</h3>
        <h2><?php echo $latestData['created_at']; ?></h2>
    </div>

</div>

<div class="card">
    <h3>📈 Moisture Graph</h3>
    <canvas id="moistureChart"></canvas>
</div>

<div class="table-container">

    <h3>📋 Recent Logs</h3>

    <table>

        <tr>
            <th>ID</th>
            <th>Moisture</th>
            <th>Pump</th>
            <th>Timestamp</th>
        </tr>

        <?php
        $logs = $conn->query("SELECT * FROM sensor_data ORDER BY id DESC LIMIT 10");

        while($row = $logs->fetch_assoc()) {
            echo "<tr>
                    <td>{$row['id']}</td>
                    <td>{$row['moisture']}</td>
                    <td>{$row['pump_status']}</td>
                    <td>{$row['created_at']}</td>
                  </tr>";
        }
        ?>

    </table>

</div>

<script>

const ctx = document.getElementById('moistureChart').getContext('2d');

new Chart(ctx, {

    type: 'line',

    data: {

        labels: <?php echo json_encode(array_reverse($labels)); ?>,

        datasets: [{

            label: 'Moisture Level',

            data: <?php echo json_encode(array_reverse($moisture)); ?>,

            borderColor: 'blue',

            fill: false,

            tension: 0.3

        }]
    },

    options: {

        responsive: true,

        scales: {
            y: {
                beginAtZero: true
            }
        }
    }

});

</script>

</body>
</html>