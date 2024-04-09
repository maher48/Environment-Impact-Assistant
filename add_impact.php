<?php
session_start();

if (!isset($_SESSION["user_name"])) {
    header("location: login.php");
    exit;
}

require_once "config.php";

if (isset($_GET['location_id'], $_GET['project_type_id'])) {
    $location_id = $_GET['location_id'];
    $project_type_id = $_GET['project_type_id'];
} else {
    header("location: add_project.php");
    exit;
}

function generateNextProjectID($conn) {
    $sql_last_project_id = "SELECT project_id FROM project ORDER BY project_id DESC LIMIT 1";
    $result = $conn->query($sql_last_project_id);
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $last_project_id = $row['project_id'];
        
        $numeric_part = (int)substr($last_project_id, 3);
        
        $next_numeric_part = $numeric_part + 1;
       
        $next_project_id = 'PRJ' . str_pad($next_numeric_part, 4, '0', STR_PAD_LEFT);
        return $next_project_id;
    } else {
        
        return 'PRJ0001';
    }
}

function generateNextImpactID($conn) {
    $sql_last_impact_id = "SELECT impact_id FROM impact ORDER BY impact_id DESC LIMIT 1";
    $result = $conn->query($sql_last_impact_id);
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $last_impact_id = $row['impact_id'];
        
        $numeric_part = (int)substr($last_impact_id, 3);
       
        $next_numeric_part = $numeric_part + 1;
      
        $next_impact_id = 'IMP' . str_pad($next_numeric_part, 4, '0', STR_PAD_LEFT);
        return $next_impact_id;
    } else {
        
        return 'IMP0001';
    }
}

$impact_id = generateNextImpactID($conn);
$project_id = generateNextProjectID($conn);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $impact_type = $_POST['impact_type'];
    $duration = $_POST['duration'];

    $sql_insert_impact = "INSERT INTO impact (impact_id, project_id, impact_type, duration) VALUES (?, ?, ?, ?)";
    if ($stmt = $conn->prepare($sql_insert_impact)) {
        $stmt->bind_param("ssss", $impact_id, $project_id, $impact_type, $duration);
        $stmt->execute();
        $stmt->close();
    }

    header("location: add_monitoring.php?location_id=$location_id&project_type_id=$project_type_id&impact_id=$impact_id&project_id=$project_id");

    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Impact</title>
    <link rel="stylesheet" href="https:
    <style>
        .container {
            margin-top: 50px;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Add Impact</h2>
    <form action="add_impact.php?location_id=<?php echo $location_id; ?>&project_type_id=<?php echo $project_type_id; ?>" method="post">
        <div class="form-group">
            <label for="impact_id">Impact ID:</label>
            <input type="text" class="form-control" id="impact_id" name="impact_id" value="<?php echo $impact_id; ?>" readonly>
        </div>
        <div class="form-group">
            <label for="project_id">Project ID:</label>
            <input type="text" class="form-control" id="project_id" name="project_id" value="<?php echo $project_id; ?>" readonly>
        </div>
        <div class="form-group">
            <label for="impact_type">Impact Type:</label>
            <input type="text" class="form-control" id="impact_type" name="impact_type" required>
        </div>
        <div class="form-group">
            <label for="duration">Duration:</label>
            <input type="text" class="form-control" id="duration" name="duration" required>
        </div>
        <button type="submit" class="btn btn-primary">Add</button>
        <a href="add_monitoring.php?location_id=<?php echo $location_id; ?>&project_type_id=<?php echo $project_type_id; ?>&project_id=<?php echo $project_id; ?>" class="btn btn-secondary">Next</a>
    </form>
</div>

</body>
</html>
