<?php
// Include config file
require_once "config.php";
 
// Define variables and initialize with empty values
$accountNumber  ="";
$money = 0;
$accountNumberErr = $moneyErr = "";
 
// Processing form data when form is submitted
if(isset($_POST["id"]) && !empty($_POST["id"])){
    // Get hidden input value
    $id = $_POST["id"];
    
    // Validate name
    $inputAccountNumber = trim($_POST["accountNumber"]);
    if(empty($inputAccountNumber)){
        $accountNumberErr = "Please enter a account number.";
    } else{
        $accountNumber = $inputAccountNumber;
    }
    
    // Validate salary
    $inputMoney = trim($_POST["money"]);
    if(empty($inputMoney)){
        $moneyErr = "Please enter the amount.";     
    } elseif(!ctype_digit($inputMoney)){
        $moneyErr = "Please enter a positive value.";
    } else{
        $money = $inputMoney;
    }
    
    // Check input errors before inserting in database
    if(empty($moneyErr) && empty($address_err) && empty($salary_err)){
        // Prepare an update statement
        $sql = "insert into cuentas (numero_cuenta,monto) values (?,?)";
         
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "sd", $paramAccountNumber, $paramMoney);
            
            // Set parameters
            $paramAccountNumber = $accountNumber;
            $paramMoney = $money;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Records updated successfully. Redirect to landing page
                $recentlyInserted = mysqli_stmt_insert_id($stmt);
                $sql = "insert into cliente_cuenta (cliente_id,cuenta_id) values (?,?)";
                if($stmt = mysqli_prepare($link, $sql)){
                    mysqli_stmt_bind_param($stmt, "ii", $paramClientId, $paramAccountNumber);
                    $paramClientId = trim($_GET["id"]);
                    $paramAccountNumber = $recentlyInserted;
                    if(mysqli_stmt_execute($stmt)){
                        header("location: index.php");
                        exit();
                    }

                }


            } else{
                echo "Something went wrong. Please try again later.";
            }
        }
         
        // Close statement
        mysqli_stmt_close($stmt);
    }
    
    // Close connection
    mysqli_close($link);
} else{
    // Check existence of id parameter before processing further
    if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){
        // Get URL parameter
        $id =  trim($_GET["id"]);
    }  else{
        // URL doesn't contain id parameter. Redirect to error page
        header("location: error.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Update Record</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
    .wrapper {
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
                        <h2>Update Record</h2>
                    </div>
                    <p>Please edit the input values and submit to update the record.</p>
                    <form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="post">
                        <div class="form-group <?php echo (!empty($accountNumberErr)) ? 'has-error' : ''; ?>">
                            <label>Account Number</label>
                            <input type="text" name="accountNumber" class="form-control"
                                value="<?php echo $accountNumber; ?>">
                            <span class="help-block"><?php echo $accountNumberErr;?></span>
                        </div>
                        <div class="form-group <?php echo (!empty($moneyErr)) ? 'has-error' : ''; ?>">
                            <label>Money</label>
                            <input type="text" name="money" class="form-control" value=<?php echo trim($money); ?>>

                            </input>
                            <span class="help-block"><?php echo $moneyErr;?></span>
                        </div>
                        <input type="hidden" name="id" value="<?php echo $id; ?>" />
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="index.php" class="btn btn-default">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>

</html>