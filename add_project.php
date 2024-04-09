<?php
session_start();

if (!isset($_SESSION["user_name"])) {
    header("location: login.php");
    exit;
}


require_once "config.php";




$sql_project_types = "SELECT project_type_id, project_type_name FROM project_type";
$result_project_types = $conn->query($sql_project_types);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Select Project Type</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https:
    <style>
        .container {
            margin-top: 50px;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Select Project Type</h2>
    <form action="add_location.php" method="post">
        <div class="form-group">
            <label for="project_type_id">Select Project Type:</label>
            <select class="form-control" id="project_type_id" name="project_type_id" required>
                <?php
                if ($result_project_types->num_rows > 0) {
                    while ($row = $result_project_types->fetch_assoc()) {
                        echo "<option value='" . $row["project_type_id"] . "'>" . $row["project_type_name"] . "</option>";
                    }
                }
                ?>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Next</button>
        <a href="project_owner.php" class="btn btn-secondary">Back to Project Owner Page</a>
    </form>
</div>

</body>
</html>
