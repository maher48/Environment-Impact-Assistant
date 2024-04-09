<?php
session_start();

if (!isset($_SESSION["user_name"])) {
    header("location: login.php");
    exit;
}

require_once "config.php";


$sql_last_project_id = "SELECT project_id FROM project ORDER BY project_id DESC LIMIT 1";
$result = $conn->query($sql_last_project_id);
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $last_project_id = $row['project_id'];

    
    $last_project_digits = substr($last_project_id, -4);
    
    
    $next_id = "OWN" . str_pad($last_project_digits, 4, "0", STR_PAD_LEFT);
} else {
    $next_id = "OWN0001"; 
}

$name = $type = "";
$name_err = $type_err = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty(trim($_POST["name"]))) {
        $name_err = "Please enter a name.";
    } else {
        $name = trim($_POST["name"]);
    }
    
    if (empty(trim($_POST["type"]))) {
        $type_err = "Please select a type.";
    } else {
        $type = trim($_POST["type"]);
    }

    if (empty($name_err) && empty($type_err)) {
        $sql = "INSERT INTO project_owner (Project_owner_id, project_id, Name, type) VALUES (?, ?, ?, ?)";
        
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("ssss", $param_owner_id, $param_project_id, $param_name, $param_type);
            
            $param_owner_id = $next_id;
            $param_project_id = $last_project_id; 
            $param_name = $name;
            $param_type = $type;
            
            if ($stmt->execute()) {
                header("location: project_owner.php");
                exit();
            } else {
                echo "Something went wrong. Please try again later.";
            }

            $stmt->close();
        }
    }
    
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Project Owner</title>
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

        .navbtns {
            float: right;
        }

        .btn {
            margin-right: 10px;
        }

        .form-group {
            margin-top: 20px;
        }

        .btn-submit {
            margin-top: 20px;
        }
    </style>
</head>
<body>

<div class="navbar">
    <a href="project_owner.php" class="btn btn-primary">Go Home</a>
    <div class="navbtns">
        <a href="javascript:history.go(-1);" class="btn btn-primary">Go Back</a>
    </div>
</div>

<div class="container mt-4">
    <h2>Add Project Owner</h2>
    <p>Please fill in the details to add a project owner.</p>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <div class="form-group">
            <label>Name</label>
            <input type="text" name="name" class="form-control" value="<?php echo $name; ?>">
            <span class="text-danger"><?php echo $name_err; ?></span>
        </div>
        <div class="form-group">
            <label>Type</label>
            <select name="type" class="form-control">
                <option value="">Select a type</option>
                <option value="Individual">Individual</option>
                <option value="Company">Company</option>
                <option value="Organization">Organization</option>
                <option value="Government">Government</option>
                <option value="NGO">NGO</option>
            </select>
            <span class="text-danger"><?php echo $type_err; ?></span>
        </div>
        <div class="form-group">
            <input type="submit" class="btn btn-primary btn-submit" value="Submit">
        </div>
    </form>
</div>

</body>
</html>
