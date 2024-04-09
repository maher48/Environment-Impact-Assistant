<?php
session_start();

if (!isset($_SESSION["user_name"])) {
    header("location: login.php");
    exit;
}

require_once "config.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $sql_last_id = "SELECT MAX(mitigation_id) AS max_id FROM mitigation_measure";
    $result_last_id = $conn->query($sql_last_id);
    $row_last_id = $result_last_id->fetch_assoc();
    $last_id = $row_last_id["max_id"];

    $new_id = "MEA" . str_pad($last_id + 1, 4, '0', STR_PAD_LEFT);

    $impact_id = $new_id;

    $measure_type = $_POST["measure_type"];
    $cost = $_POST["cost"];

    $sql_insert = "INSERT INTO mitigation_measure (mitigation_id, impact_id, measure_type, cost) VALUES ('$new_id', '$impact_id', '$measure_type', '$cost')";
    if ($conn->query($sql_insert) === TRUE) {
        
        header("location: success.php");
        exit;
    } else {
        
        echo "Error: " . $sql_insert . "<br>" . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Mitigation Measure</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https:
    <style>
        .navbar {
            background-color: #468847;
            color: white;
        }

        .navbar a {
            color: white;
            text-decoration: none;
        }

        .navbar a:hover {
            background-color: #6c6c6c;
        }

        .container {
            margin-top: 50px;
        }
    </style>
</head>
<body>

<div class="navbar">
   
<a href="#" onclick="history.back();" class="btn btn-primary">Go Back</a>

    <a href="project_owner.php" class="btn btn-danger">Go Home</a>
</div>

<div class="container">
    <h1>Add Mitigation Measure</h1>
    
    <form method="POST">
        <div class="form-group">
            <label for="measure_type">Measure Type:</label>
            <input type="text" class="form-control" id="measure_type" name="measure_type" required>
        </div>
        <div class="form-group">
            <label for="cost">Cost:</label>
            <input type="number" class="form-control" id="cost" name="cost" required>
        </div>
        <button type="submit" class="btn btn-primary">Add Measure</button>
    </form>
</div>

</body>
</html>
