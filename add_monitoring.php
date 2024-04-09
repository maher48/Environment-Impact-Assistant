<?php
session_start();

if (!isset($_SESSION["user_name"])) {
    header("location: login.php");
    exit;
}

require_once "config.php";

if (isset($_GET['location_id'], $_GET['project_type_id'], $_GET['impact_id'])) {
    $location_id = $_GET['location_id'];
    $project_type_id = $_GET['project_type_id'];
    $impact_id = $_GET['impact_id'];
    $project_id = $_GET['project_id']; 
} else {
    header("location: add_impact.php");
    exit;
}

function generateNextMonitoringID($conn) {
    $sql_last_monitoring_id = "SELECT monitoring_id FROM monitoring ORDER BY monitoring_id DESC LIMIT 1";
    $result = $conn->query($sql_last_monitoring_id);
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $last_monitoring_id = $row['monitoring_id'];
        $numeric_part = (int)substr($last_monitoring_id, 3);
        $next_numeric_part = $numeric_part + 1;
        $next_monitoring_id = 'MON' . str_pad($next_numeric_part, 4, '0', STR_PAD_LEFT);
        return $next_monitoring_id;
    } else {
        return 'MON0001';
    }
}

$monitoring_id = generateNextMonitoringID($conn);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $date = $_POST['date'];
    $parameters_measured = $_POST['parameters_measured'];
    $audit = $_POST['audit'];

    $sql_insert_monitoring = "INSERT INTO monitoring (monitoring_id, project_id, date, parameters_measured, audit) VALUES (?, ?, ?, ?, ?)";
    if ($stmt = $conn->prepare($sql_insert_monitoring)) {
        $stmt->bind_param("sssss", $monitoring_id, $project_id, $date, $parameters_measured, $audit); 
        if ($stmt->execute()) {
            header("location: final_project_add.php?location_id=$location_id&project_type_id=$project_type_id&impact_id=$impact_id&project_id=$project_id&monitoring_id=$monitoring_id");

            exit;
        } else {
            echo "Something went wrong. Please try again later.";
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Monitoring</title>
    <link rel="stylesheet" href="https:
    <style>
        .container {
            margin-top: 50px;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Add Monitoring</h2>
    <form action="" method="post">
        <div class="form-group">
            <label for="monitoring_id">Monitoring ID:</label>
            <input type="text" class="form-control" id="monitoring_id" name="monitoring_id" value="<?php echo $monitoring_id; ?>" readonly>
        </div>
        <div class="form-group">
            <label for="project_id">Project ID:</label>
            <input type="text" class="form-control" id="project_id" name="project_id" value="<?php echo $project_id; ?>" readonly>
        </div>
        <div class="form-group">
            <label for="date">Date:</label>
            <input type="date" class="form-control" id="date" name="date" required>
        </div>
        <div class="form-group">
            <label for="parameters_measured">Parameters Measured:</label>
            <input type="text" class="form-control" id="parameters_measured" name="parameters_measured" required>
        </div>
        <div class="form-group">
            <label for="audit">Audit:</label>
            <input type="text" class="form-control" id="audit" name="audit" required>
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
        <a href="final_project_add.php?location_id=<?php echo $location_id; ?>&project_type_id=<?php echo $project_type_id; ?>&impact_id=<?php echo $impact_id; ?>" class="btn btn-secondary">Back to Final Project Add</a>
    </form>
</div>

</body>
</html>
