<?php
// Include config file
require_once "config.php";
 
// Define variables and initialize with empty values
$accountNumber = "";
$money = 0.0;
$accountNumberErr = $moneyErr = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
    // Validate name
    $inputAccountNumber = trim($_POST["accountNumber"]);
    if(empty($inputAccountNumber)){
        $accountNumberErr = "Please enter a account number.";
    } else{
        $accountNumber = $inputAccountNumber;
    }
    
    // Validate address
    $inputMoney = trim($_POST["money"]);
    if(empty($inputMoney)){
        $moneyErr = "Please enter money.";     
    } elseif($inputMoney<0){ 
        $moneyErr = "Please enter a positive number";
    }
    else  $money = $inputMoney;
    
    
    // Check input errors before inserting in database
    //&& empty($salary_err)
    if(empty($accountNumberErr) && empty($moneyErr) ){
        // Prepare an insert statement
        $sql = "INSERT INTO cuentas (numero_cuenta, monto) VALUES (?, ?)";
         
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "sd", $paramAccountNumber, $paramMoney);
            
            // Set parameters
            $paramAccountNumber = $accountNumber;
            $paramMoney = $money;
            
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
                        <h2>Create Record</h2>
                    </div>
                    <p>Please fill this form and submit to add account number to client</p>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="form-group <?php echo (!empty($accountNumberErr)) ? 'has-error' : ''; ?>">
                            <label>Account number</label>
                            <input type="text" name="accountNumber" class="form-control"
                                value="<?php echo $accountNumber; ?>">
                            <span class="help-block"><?php echo $accountNumberErr;?></span>
                        </div>
                        <div class="form-group <?php echo (!empty($moneyErr)) ? 'has-error' : ''; ?>">
                            <label>Money</label>
                            <input type="text" name="money" class="form-control" value="<?php echo $money; ?>">

                            <span class="help-block"><?php echo $moneyErr;?></span>
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