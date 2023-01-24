<div id="question">
<?php
        if (isset($question)) {
        ?>
        <h1><?php echo $question['questionTitle']; ?></h1>
    <h4>Category - <?php echo $question['categoryName']; ?></h4>
    <small>Posted on : <?php echo $question['questionTimeStamp']; ?></small>

    <section class="p-3">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title"><?php echo $question['userName']; ?></h5>
                <p class="card-text"><?php echo $question['questionContent']; ?></p>

                <a class="toggle" href="#">
                <?php
                    if (isset($liked) ) {
                        $var_liked = $liked->likeValue === '1'? true: false;
                        if($var_liked ){
                    ?>
                    <i class="fa fa-thumbs-up active" style="font-size:40px;color:blue   "><?php echo $question['questionLikes']; ?></i>
                    <?php
                        }else{                   
                    ?> 
                    <i class="fa fa-thumbs-o-up" style="font-size:40px;color:blue   "><?php echo $question['questionLikes']; ?></i>
                    <?php
                        }
                    }else{
                        ?>
                        <i class="fa fa-thumbs-o-up" style="font-size:40px;color:blue   "><?php echo $question['questionLikes']; ?></i>
                        <?php
                        }
                        ?>
                </a>
            </div>
        </div>

    </section>
                
        <?php
            }
        
        ?> 

    <div class="ans">
        <section id="ans" class="p-3">
            <h2>Answers</h2>
            <div id="addAnswer">
            <div class="d-flex justify-content-center">
                <div class="spinner-border" role="status">
                    <span class="sr-only">Loading...</span>
                </div>
            </div>
        </div>
        </section>
    </div>
    <div class="askAns">
        <section class="p-3">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Add Your Answer:</h5>
                    <form id="answerForm">
                        <div class="form-group">
                            <textarea class="form-control" id="answerArea" rows="3"></textarea>
                        </div>
                        <button type="button" id="submitForm" class="btn btn-primary">Submit</button>
                    </form>
                </div>
            </div>

        </section>
    </div>
</div>

<script type="text/template" id="answer-template">
    <div class="card mt-4">
        <div class="card-body">
            <h5 class="card-title"><%- userName %></h5>
            <div class="row pl-3">
                <div class="card" style="width: 50rem;">
                    <p class="card-text"><%- answerContent %></p>
                </div>
                <div class="column">
      	            <span id="voteValue" style="font-size: 40px;"><%- answerVoteCount %></span>
      	            <button id="<%- answerId %>"  style="border: medium none; background: transparent;" class="upvote pl-2">
      		            <i class="fa fa-chevron-up" style="font-size:40px;color:blue   "></i>
      	            </button>
      	            <button id="<%- answerId %>" style="border: medium none; background: transparent;" class="downvote pl-2">
      		            <i class="fa fa-chevron-down" style="font-size:40px;color:blue   "></i>
      	            </button>
                </div>
            </div>
        </div>
        </div>
  </script>
  
  <script src="<?php echo base_url(); ?>assets/js/answerBackbone.js?<?php echo date('l jS \of F Y h:i:s A'); ?>" type="text/javascript"></script>
<script>
    $(".toggle").click(function() {
        if ($(".toggle i").hasClass("active")) {
            likeQuestion(0);
            
        } else {
            likeQuestion(1);
            
        }
    })
    function likeQuestion(liked){
        $.ajax({
            type: "POST",
            url: base_url+"index.php/HomeController/likeQuestion",
            dataType: 'json',
            data: {
                questionId: questionId,
                likeValue: liked
            },
            success: function(data) {
                console.log(data);
                if(data == null){
                    $("#ModalExample").modal("toggle");
                    return;
                }
                if(data.status){
                    if(!liked){
                        $(".toggle i").removeClass("fa fa-thumbs-up").addClass("fa fa-thumbs-o-up")
                        $(".toggle i").removeClass("active")
                        $(".toggle i").html(function(i, val) {
                            return +val - 1
                        });
                    }else{
                        $(".toggle i").removeClass("fa-thumbs-o-up").addClass("fa fa-thumbs-up active")
                        $(".toggle i").html(function(i, val) {
                            return +val + 1
                        });
                    }
                }else{
                    alert("You have already voted as Same");
                };
            }
            })
    }
    var questionId = <?php echo $question['questionId']; ?>;
    
    $(document).ready(function() {
        fetchAnswers(questionId); 
    });

    
</script>

