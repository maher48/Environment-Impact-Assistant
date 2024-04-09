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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $air_quality = $_POST['air_quality'];
    $air_temperature = $_POST['air_temperature'];
    $humidity = $_POST['humidity'];
    $wind_speed = $_POST['wind_speed'];

    
    $soil_type = $_POST['soil_type'];
    $soil_moisture = $_POST['soil_moisture'];
    $soil_ph = $_POST['soil_ph'];
    $soil_nutrients = $_POST['soil_nutrients'];

    
    $water_quality = $_POST['water_quality'];
    $water_ph = $_POST['water_ph'];
    $water_temperature = $_POST['water_temperature'];
    $water_level = $_POST['water_level'];

    
    $sql_insert_air_data = "INSERT INTO air_data (location_id, air_quality, air_temperature, humidity, wind_speed) VALUES (?, ?, ?, ?, ?)";
    if ($stmt = $conn->prepare($sql_insert_air_data)) {
        $stmt->bind_param("sssss", $location_id, $air_quality, $air_temperature, $humidity, $wind_speed);
        $stmt->execute();
        $stmt->close();
    }

    
    $sql_insert_soil_data = "INSERT INTO soil_data (location_id, Soiltype, Soilmoisture, soilPH, SoilNutrients) VALUES (?, ?, ?, ?, ?)";
    if ($stmt = $conn->prepare($sql_insert_soil_data)) {
        $stmt->bind_param("sssss", $location_id, $soil_type, $soil_moisture, $soil_ph, $soil_nutrients);
        $stmt->execute();
        $stmt->close();
    }

    
    $sql_insert_water_data = "INSERT INTO water_data (location_id, water_quality, water_ph, water_temperature, water_level) VALUES (?, ?, ?, ?, ?)";
    if ($stmt = $conn->prepare($sql_insert_water_data)) {
        $stmt->bind_param("sssss", $location_id, $water_quality, $water_ph, $water_temperature, $water_level);
        $stmt->execute();
        $stmt->close();
    }

    
    header("location: add_impact.php?location_id=$location_id&project_type_id=$project_type_id");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Data</title>
    <link rel="stylesheet" href="https:
    <style>
        .container {
            margin-top: 50px;
        }
        .section {
            margin-bottom: 30px;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Add Data</h2>
    <form action="add_data.php?location_id=<?php echo $location_id; ?>&project_type_id=<?php echo $project_type_id; ?>" method="post">
        <div class="section">
            <h3>Air Data</h3>
            <div class="form-group">
                <label for="air_quality">Air Quality:</label>
                <input type="text" class="form-control" id="air_quality" name="air_quality" required>
            </div>
            <div class="form-group">
                <label for="air_temperature">Air Temperature:</label>
                <input type="text" class="form-control" id="air_temperature" name="air_temperature" required>
            </div>
            <div class="form-group">
                <label for="humidity">Humidity:</label>
                <input type="text" class="form-control" id="humidity" name="humidity" required>
            </div>
            <div class="form-group">
                <label for="wind_speed">Wind Speed:</label>
                <input type="text" class="form-control" id="wind_speed" name="wind_speed" required>
            </div>
        </div>

        <div class="section">
            <h3>Soil Data</h3>
            <div class="form-group">
                <label for="soil_type">Soil Type:</label>
                <input type="text" class="form-control" id="soil_type" name="soil_type" required>
            </div>
            <div class="form-group">
                <label for="soil_moisture">Soil Moisture:</label>
                <input type="text" class="form-control" id="soil_moisture" name="soil_moisture" required>
            </div>
            <div class="form-group">
                <label for="soil_ph">Soil pH:</label>
                <input type="text" class="form-control" id="soil_ph" name="soil_ph" required>
            </div>
            <div class="form-group">
                <label for="soil_nutrients">Soil Nutrients:</label>
                <input type="text" class="form-control" id="soil_nutrients" name="soil_nutrients" required>
            </div>
        </div>

        <div class="section">
            <h3>Water Data</h3>
            <div class="form-group">
                <label for="water_quality">Water Quality:</label>
                <input type="text" class="form-control" id="water_quality" name="water_quality" required>
            </div>
            <div class="form-group">
                <label for="water_ph">Water pH:</label>
                <input type="text" class="form-control" id="water_ph" name="water_ph" required>
            </div>
            <div class="form-group">
                <label for="water_temperature">Water Temperature:</label>
                <input type="text" class="form-control" id="water_temperature" name="water_temperature" required>
            </div>
            <div class="form-group">
                <label for="water_level">Water Level:</label>
                <input type="text" class="form-control" id="water_level" name="water_level" required>
            </div>
        </div>

        <button type="submit" class="btn btn-primary">Next</button>
        <a href="add_location.php" class="btn btn-secondary">Back to Add Location</a>
    </form>
</div>

</body>
</html>
