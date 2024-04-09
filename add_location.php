<?php
session_start();

if (!isset($_SESSION["user_name"])) {
    header("location: login.php");
    exit;
}

require_once "config.php";

if (isset($_POST['project_type_id'])) {
    $project_type_id = $_POST['project_type_id'];
} else {
    header("location: add_project.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    function generateNextLocationID($conn) {
        $sql_last_location_id = "SELECT location_id FROM location ORDER BY location_id DESC LIMIT 1";
        $result = $conn->query($sql_last_location_id);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $last_location_id = $row['location_id'];
            $numeric_part = (int)substr($last_location_id, 3);
            $next_numeric_part = $numeric_part + 1;
            $next_location_id = 'LOC' . str_pad($next_numeric_part, 4, '0', STR_PAD_LEFT);
            return $next_location_id;
        } else {
            return 'LOC0001';
        }
    }

    $location_id = generateNextLocationID($conn);
    $location_name = isset($_POST["location_name"]) ? $_POST["location_name"] : '';
    $baseline_data = isset($_POST['location_name']) ? "Baseline data of " . $_POST['location_name'] : "";

    if (isset($_POST['next'])) {
        $sql_insert_location = "INSERT INTO location (location_id, location_name, baseline_data) VALUES (?, ?, ?)";
        if ($stmt = $conn->prepare($sql_insert_location)) {
            $stmt->bind_param("sss", $location_id, $location_name, $baseline_data);
            if ($stmt->execute()) {
                header("location: add_impact.php?location_id=$location_id&project_type_id=$project_type_id");
                exit;
            } else {
                echo "Something went wrong. Please try again later.";
            }
            $stmt->close();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Location</title>
    <link rel="stylesheet" href="https:
    <style>
        .container {
            margin-top: 50px;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Add Location</h2>
    <form action="" method="post">
        <div class="form-group">
            <label for="location_id">Location ID:</label>
            <input type="text" class="form-control" id="location_id" name="location_id"
                   value="<?php echo isset($location_id) ? $location_id : ''; ?>" readonly>
        </div>
        <div class="form-group">
            <label for="project_type_id">Project Type ID:</label>
            <input type="text" class="form-control" id="project_type_id" name="project_type_id"
                   value="<?php echo $project_type_id; ?>" readonly>
        </div>
        <div class="form-group">
            <label for="location_name">Location Name:</label>
            <input type="text" class="form-control" id="location_name" name="location_name" required>
        </div>
        <div class="form-group">
            <label for="baseline_data">Baseline Data:</label>
            <input type="text" class="form-control" id="baseline_data" name="baseline_data"
                   value="<?php echo $baseline_data; ?>" readonly>
        </div>
        <button type="submit" class="btn btn-primary" name="next">Next</button>
        <a href="add_project.php" class="btn btn-secondary">Back to Select Project Type Page</a>
    </form>
</div>

</body>
</html>
