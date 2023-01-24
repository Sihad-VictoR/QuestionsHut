<!doctype html>
<html lang="en">

<head>
    <title>Log In</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc9npb8tCaSX9FK7E8HnRr0Jz8D6OP9dO5Vg3Q9ct" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/1000hz-bootstrap-validator/0.11.9/validator.min.js">
        
    </script><link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/styles.css?<?php echo date('l jS \of F Y h:i:s A'); ?>">
</head>

<body>
    <!-- Log in  Form -->
    <div class="wrapperfp bg-white">
        <section class="sign-in">
            <div class="container">
                <div class="signin-content">
                    <div class="signin-image">
                        <a href="<?php echo base_url() ?>index.php"><img width="400px" src="<?php echo base_url() ?>assets/images/signin-image.jpg" alt="log in image"></a>
                    </div>

                    <div class="signin-form">
                        <h2 class="form-title">Log in</h2>
                        <form method="POST" class="login-form" id="login-form">
                            <div class="form-group">
                                <input class="form-control input-sm" type="email" name="loginmail" id="loginmail" placeholder="Email" data-error="You must Fill this field." required />
                                <!-- Error -->
                                <div class="help-block with-errors"></div>
                            </div>
                            <div class="form-group">
                                <input class="form-control input-sm" data-minlength="4" type="password" name="loginpass" id="loginpass" placeholder="Password"  data-error="You must have atleast 4 characters." required/>
                                <!-- Error -->
                                <div class="help-block with-errors"></div>
                            </div>
                            <input type="checkbox" id="rememberme" value="option1">  Remember Me
                            <div class="form-group">
                                <a href="<?php echo base_url() ?>index.php/forgotPassword" class="signup-image-link">Forgot Password ?</a>
                            </div>
                            <div class="form-group form-button">
                                <input type="submit" name="login" id="login" class="form-submit" value="Log in" />
                                <a href="signup" class="signup-image-link">Create an account</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </div>

</body>
<script>
        $(document).ready(function() {
            $("#login").click(function(event) {
                event.preventDefault();
                //checking for form errors before ajax call
                if(!$('#login-form').validator('validate').has('.has-error').length){
                    var email = $("input#loginmail").val();
                    var password = $("input#loginpass").val();
                    var checkbox = document.getElementById('rememberme');
                    $.ajax({
                        method: "POST",
                        url: '<?php echo base_url(); ?>index.php/AuthController/authenticateUser',
                        dataType: 'JSON',
                        data: {
                            email: email,
                            password: password,
                            checkbox: checkbox.checked
                        },
                        success: function(data) {
                            console.log(data);
                            if(data.status){
                                alert("Login Success");
                                window.location.href = "<?php echo base_url(); ?>index.php";
                            }else{
                                alert("Error Occured : Login Failed!");
                            }
                        }
                    });
                }
            });
        });
    </script>


</html>