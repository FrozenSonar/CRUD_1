<?php
// Include config file
require_once "config.php";
 
// Define variables and initialize with empty values
$stud_no = $first_name = $last_name = $age = "";
$stud_no_err = $first_name_err = $last_name_err = $age_err = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
    
    // Validate stud_no
    $input_stud_no = trim($_POST['stud_no']);
    if(empty($input_stud_no)){
        $stud_no_err = "Please enter the student no.";     
    } else{
        $stud_no = $input_stud_no;
    }
    
    // Validate first name
    $input_first_name = trim($_POST['first_name']);
    if(empty($input_first_name)){
        $first_name_err = "Please enter the first name.";
    } elseif(!filter_var($input_first_name, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z\s]+$/")))){
        $first_name_err = "Please enter a valid name.";
    } else{
        $first_name = $input_first_name;
    }
    
    // Validate last name
    $input_last_name = trim($_POST['last_name']);
    if(empty($input_last_name)){
        $last_name_err = "Please enter the last name.";     
    } elseif(!filter_var($input_last_name, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z\s]+$/")))){
        $last_name_err = "Please enter a valid name.";
    } else{
        $last_name = $input_last_name;
    }
    
    // Validate age
    $input_age = trim($_POST['age']);
    if(empty($input_age)){
        $age_err = "Please enter the age.";     
    } elseif(!ctype_digit($input_age)){
        $age_err = "Please enter a positive integer value.";
    } else{
        $age = $input_age;
    }
    
    // Check input errors before inserting in database
    if (empty($stud_no_err) && empty($first_name_err) && empty($last_name_err) && empty($age_err)){
        // Prepare an insert statement
        $sql = "INSERT INTO SampleTable (stud_no, first_name, last_name, age) VALUES (?, ?, ?, ?)";
         
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ssss", $param_stud_no, $param_first_name, $param_last_name, $param_age);
            
            // Set parameters
            $param_stud_no = $stud_no;
            $param_first_name = $first_name;
            $param_last_name = $last_name;
            $param_age = $age;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Records created successfully. Redirect to landing page
                header("location: index.php");
                exit();
            } else{
                echo "Something went wrong. Please try again later.";
            }
        }
         
        // Close statement
        mysqli_stmt_close($stmt);
    }
    
    // Close connection
    mysqli_close($link);
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Record</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        .wrapper{
            width: 500px;
            margin: 0 auto;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="page-header">
                        <h2>Create Record</h2>
                    </div>
                    <p>Please fill this form and submit to add employee record to the database.</p>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="form-group <?php echo (!empty($stud_no_err)) ? 'has-error' : ''; ?>">
                            <label>Student No #</label>
                            <input type="text" name="stud_no" class="form-control" value="<?php echo $stud_no; ?>">
                            <span class="help-block"><?php echo $stud_no_err;?></span>
                        </div>
                        <div class="form-group <?php echo (!empty($first_name_err)) ? 'has-error' : ''; ?>">
                            <label>First Name</label>
                            <input type="text" name="first_name" class="form-control" value="<?php echo $first_name; ?>">
                            <span class="help-block"><?php echo $first_name_err;?></span>
                        </div>
                        <div class="form-group <?php echo (!empty($last_name_err)) ? 'has-error' : ''; ?>">
                            <label>Last Name</label>
                            <input type = "text" name="last_name" class="form-control" value="<?php echo $last_name; ?>">
                            <span class="help-block"><?php echo $last_name_err;?></span>
                        </div>
                        <div class="form-group <?php echo (!empty($age_err)) ? 'has-error' : ''; ?>">
                            <label>Age</label>
                            <input type="text" name="age" class="form-control" value="<?php echo $age; ?>">
                            <span class="help-block"><?php echo $age_err;?></span>
                        </div>
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="index.php" class="btn btn-default">Cancel</a>
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>