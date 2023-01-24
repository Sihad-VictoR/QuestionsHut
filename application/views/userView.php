<div class="userView">
    <section id="content" class="container">
        <!-- Begin .page-heading -->
        <div class="page-heading">
            <div class="media clearfix">
                <div class="media-left pr30">
                    <a href="#">
                        <img class="media-object mw150" width="200" src="https://bootdey.com/img/Content/avatar/avatar7.png" alt="...">
                    </a>
                </div>
                <div class="media-body va-m">
                    <h2 class="media-heading"><?php echo $user[0]['userName'] ?>
                        <small> - Profile</small>
                    </h2>
                    <p class="lead"><?php echo $user[0]['userAbout'] ?></p>
                    <button type="button" class="btn btn-primary" onclick="createModal(js_array)">Edit Profile</button>
                </div>
            </div>
        </div>
    </section>
    <br>
    <div class="container">
        <ul class="nav nav-tabs">
            <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#home">Questions</a></li>
            <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#menu1">Answers</a></li>
        </ul>
        <div class="tab-content">
            <div id="home" class="tab-pane fade in active show">
                <?php
                if (isset($questions)) {
                    foreach ($questions as $key) {
                ?>
                        
                        <div class="userQA">
                        <h3><?php echo $key['questionTitle']; ?></h3>
                        <p>  <?php echo $key['questionContent']; ?></p>
                        </div>
                <?php
                    }
                }
                ?>
                
            </div>
            <div id="menu1" class="tab-pane fade">
                <?php
                if (isset($answers)) {
                    foreach ($answers as $key) {
                ?>
                        
                        <div class="userQA">
                        <h3><?php echo $key['questionTitle']; ?></h3>
                        <p>  <?php echo $key['answerContent']; ?></p>
                        </div>
                <?php
                    }
                }
                ?>
            </div>
        </div>
    </div>
    
</div>
<script type="text/template" id="modal-template">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Edit Profile</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <label for="uName">Name</label>
                <input type="text" class="form-control" id="uName" placeholder="" value="<%- userName %>">
                <label for="uEmail">Email address</label>
                <input type="email" class="form-control" id="uEmail" placeholder="<%- userEmail %>" disabled>
                <label for="uAbout">About</label>
                <input type="textArea" class="form-control" id="uAbout" rows="3" value="<%- userAbout %>"></input>
                <div id="verification">
                    <%- userEmailVerified %><br>
                </div>
                <a type="button" id="reset" > Reset my password</a><br>
                <a type="button" id="delete"> Delete my account</a>
            </div>
            <div class="modal-footer">
                <button type="button" id="save" class="btn btn-primary">Save changes</button>
            </div>
        </div>
</div>
</script>

<script src="<?php echo base_url(); ?>assets/js/userQABackbone.js?<?php echo date('l jS \of F Y h:i:s A'); ?>" type="text/javascript"></script>
<script>
    <?php
        $user_array = json_encode($user[0]);
        echo "var js_array = ". $user_array . ";\n";
        ?> 
</script>


