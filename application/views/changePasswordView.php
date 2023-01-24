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

<div class="resetPassword">
    <div class="wrapperfp bg-white">
        <div class="h2 text-center">Questions Hut</div>
        <div class="h4 text-muted text-center pt-2">Change Password</div>
        <form id= "cp-form">
            <div class="form-group">
                <label for="exampleInputEmail1">New Password</label>
                <input type="password" data-minlength="4" class="form-control" id="pass" aria-describedby="emailHelp" placeholder="Password" data-error="You must have atleast 4 characters." required>
                <!-- Error -->
                <div class="help-block with-errors"></div>
            </div>
            <div class="form-group">
                <label for="exampleInputPassword1">Confirm New Password</label>
                <input type="password" data-match="#pass" data-match-error="Password don't match" class="form-control" id="rePass" placeholder="Re-enter Password" required>
                <!-- Error -->
                <div class="help-block with-errors"></div>
            </div>
        </form>
        <div class="p-1 text-right">
        <?php
        if (isset($user)) {
        ?>
            <button id="<?php echo $user[0]['userId'] ?>" type="button" class="btn btn-dark">Change</button>
        <?php
        }
        ?>
        </div>
    </div>
</div>
</body>
<script>
        $(document).ready(function() {

            $(".btn").click(function(event) {
                event.preventDefault();
                //checking for form errors before ajax call
                if(!$('#cp-form').validator('validate').has('.has-error').length){
                    var userId = $(this).attr('id');
                    var password = $("input#pass").val();
                    $.ajax({
                        method: "POST",
                        url: '<?php echo base_url(); ?>index.php/AuthController/resetUserPassword',
                        dataType: 'JSON',
                        data: {
                            userId: userId,
                            password: password
                        },
                        success: function(data) {
                            console.log(data);
                            if(data.status){
                                alert("Password reset successfull");
                                window.location.href = "<?php echo base_url(); ?>index.php";
                            }else{
                                alert("Error Occured!");
                            }
                        }
                    });
                }
            });
        });
    </script>
</html>