<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>ForgotPassword</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc9npb8tCaSX9FK7E8HnRr0Jz8D6OP9dO5Vg3Q9ct" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/1000hz-bootstrap-validator/0.11.9/validator.min.js"></script>
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/styles.css?<?php echo date('l jS \of F Y h:i:s A'); ?>">
    <!-- Font Icon -->
    <!-- <link rel="stylesheet" href="fonts/material-icon/css/material-design-iconic-font.min.css"> -->

    <!-- Main css -->
</head>

<body>

<div class="forgotPassword">
    <div class="wrapperfp bg-white">
        <div class="h2 text-center">Questions Hut</div>
        <div class="h4 text-muted text-center pt-2">Reset Your Password</div>
        <p>If we find an account with the provided email address, you will receive an email with reset instructions.</p>
        <form id="mail-form">
        <input class="form-control input-sm" data-match-error="Please fill this field." id="inputsm" type="text" placeholder="Email Address" required>
        <!-- Error -->
        <div class="help-block with-errors"></div>
        </form>
        <div class="p-1 text-right">
            <button type="button" id="forgotBtn" class="btn btn-dark">Send</button>
        </div>
    </div>
</div>
</body>
<script>
        $(document).ready(function() {

            $("#forgotBtn").click(function(event) {
                event.preventDefault();
                //checking for form errors before ajax call
                if(!$('#mail-form').validator('validate').has('.has-error').length){
                    var userEmail = $("input#inputsm").val();
                    $.ajax({
                        method: "POST",
                        url: '<?php echo base_url(); ?>index.php/AuthController/checkMail',
                        dataType: 'JSON',
                        data: {
                            userEmail: userEmail
                        },
                        success: function(data) {
                            console.log(data);
                            if(data.status){
                                alert("Account Found : Email sent!");
                                window.location.href = "<?php echo base_url(); ?>index.php";
                            }else{
                                alert("No Account Found!");
                            }
                        }
                    });
                }
            });
        });
    </script>
</html>