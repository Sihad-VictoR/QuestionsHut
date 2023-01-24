<!doctype html>
<html lang="en">

<head>
    <title>Sign Up</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc9npb8tCaSX9FK7E8HnRr0Jz8D6OP9dO5Vg3Q9ct" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/1000hz-bootstrap-validator/0.11.9/validator.min.js"></script>
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/styles.css?<?php echo date('l jS \of F Y h:i:s A'); ?>">
</head>

<body>

    <div class="main">
        <!-- Sign up form -->
        <section id="signup" class="signup bg-white">
            <div class="container">
                <div class="signup-content">
                    <div class="signup-form">
                        <h2 class="form-title">Sign up</h2>
                        <form method="POST" class="register-form" id="register-form" data-toggle="validator">
                            <div class="form-group">
                                <input class="form-control input-sm" minlength="3" type="text" name="signupName" id="signupName" placeholder="Your Name" data-error="You must have a name with atleast 3 characters." required/>
                                <!-- Error -->
                                <div class="help-block with-errors"></div>
                            </div>
                            <div class="form-group">
                                <input class="form-control input-sm" type="email" name="signupEmail" id="signupEmail" placeholder="Your Email" required/>
                                <!-- Error -->
                                <div class="help-block with-errors"></div>
                            </div>
                            <div class="form-group">
                                <input class="form-control input-sm" data-minlength="4" data-error="Have atleast 4 characters" type="password" name="signupPass" id="signupPass" placeholder="Password" required/>
                                <!-- Error -->
                                <div class="help-block with-errors"></div>
                            </div>
                            <div class="form-group">
                                <!-- <label for="re-pass"><i class="zmdi zmdi-lock-outline"></i></label> -->
                                <input class="form-control" data-match="#signupPass" data-match-error="Password don't match" input-sm" type="password" name="signupRe_pass" id="signupRe_pass" placeholder="Repeat your password" required/>
                                <!-- Error -->
                                <div class="help-block with-errors"></div>
                            </div>
                            <div class="form-group form-button">
                                <input type="submit" name="signupBtn" id="signupBtn" class="form-submit" value="Register" />
                            </div>
                        </form>
                    </div>
                    <div class="signup-image">
                        <figure><img width="400px" src="<?php echo base_url() ?>assets/images/signup-image.jpg" alt="sing up image"></figure>
                        <a href="login" class=" signup-image-link">Login Instead</a>
                    </div>
                </div>
            </div>
        </section>
    </div>
    <script>
        $(document).ready(function() {

            $("#signupBtn").click(function(event) {
                event.preventDefault();
                if(!$('#register-form').validator('validate').has('.has-error').length){
                    var name = $("input#signupName").val();
                    var email = $("input#signupEmail").val();
                    var password = $("input#signupPass").val();
                    var re_password = $("input#signupRe_pass").val();
                    $.ajax({
                        method: "POST",
                        url: '<?php echo base_url(); ?>index.php/AuthController/registerUser',
                        dataType: 'JSON',
                        data: {
                            name: name,
                            email: email,
                            password: password
                        },
                        success: function(data) {
                            console.log(data);
                            if(data.status){
                                alert("Registration Success");
                                window.location.href = "<?php echo base_url(); ?>index.php";
                            }else{
                                alert("Error Occured : User Already Registered");
                            }
                        }
                    });
                }
            });
        });
    </script>

</body>

</html>