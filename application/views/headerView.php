<div class="header">
    <nav class="navbar sticky-top navbar-expand-lg navbar-light bg-white">
        <a class="navbar-brand" href="<?php echo base_url() ?>index.php">
            <img src="<?php echo base_url() ?>assets/images/qhut-image.png" width="30" height="30" class="d-inline-block align-top" alt="">
            Questions Hut
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse justify-content-end" id="navbarSupportedContent">

            
            <ul class="navbar-nav my-auto">
                <li class="form-inline my-2 my-lg-0">
                    <input id="sQue" class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search">
                    <button class="btn btn-outline-success my-2 my-sm-0" onclick="searchQuestion()">Search</button>
                </li>
                <li class="nav-item px-1" id="submitQuestion">
                    <!-- <button class="btn btn-outline-success my-2 my-sm-0" onclick="submitQue" >Submit a Question</button> -->
                    <button class="btn btn-outline-success my-2 my-sm-0" id="submitQue">Submit a Question</button>
                </li>
                <li class="nav-item dropdown" id="user">
                    <div class="btn-group">
                        <button id="navbarDropdown" type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            User
                        </button>
                        <div class="dropdown-menu dropdown-menu-right">
                            <a class="dropdown-item" href="javascript:void(0);" onclick="viewProfile()">Profile</a>
                            <a class="dropdown-item" href="javascript:void(0);" onclick="viewUsers()">Users</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="<?php echo base_url(); ?>index.php/logout">LogOut</a>
                        </div>
                    </div>
                </li>
                <li class="nav-item" id="loginuser">
                    <a class="nav-link" href="<?php echo base_url(); ?>index.php/login">Log In</a>
                </li>
                <li class="nav-item" id="signupuser">
                    <a class="nav-link" href="<?php echo base_url(); ?>index.php/signup">Sign Up</a>
                </li>
            </ul>
        </div>
    </nav>
</div>

<!-- Modal HTML Markup -->
<div id="ModalExample" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">See more of this awesome website by logging in</h4>
            </div>
            <div class="modal-body">
                <p class="lead text-xs-center">It only takes a few seconds to level up!</p>
                <div class="lead text-xs-center"><a class="btn btn-info" href="<?php echo base_url(); ?>index.php/signup">Create Account</a> or
                    <a class="btn btn-success" href="<?php echo base_url(); ?>index.php/login">Sign In</a></div>
            </div>
            <div class="modal-footer">
                QuestionsHut
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<script>
    var base_url = "<?php echo base_url();?>";
    $(document).ready(function() {
        $.ajax({
            method: "GET",
            url: "<?php echo base_url(); ?>index.php/isUserLoggedIn",
            context: document.body,
            dataType: 'json',
            success: function(data) {
                if (data.status == true) {
                    $('#loginuser').hide();
                    $('#signupuser').hide();
                    $("#navbarDropdown").text(data.name);
                } else {
                    $('#user').hide();
                };
            }
        });
    });

    function viewUsers(){
        window.location.href= base_url + "index.php/users";
    }

    $('#submitQue').click(function() {
        window.location.href= base_url + "index.php/askQuestion";
    });

    function viewProfile() {
        window.location.href= base_url + "index.php/profile";
    }

    function searchQuestion(){
       let searchString = $('#sQue').val();
       console.log(searchString);
       window.location.href= base_url + "index.php/search/"+searchString;
    }
    
    
    
</script>