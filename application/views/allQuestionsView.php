<div class="allquestions">
    <div class="my-component overflow-auto d-flex mx-auto align-self-center flex-column p-3 rounded-left">
        <div class="container-fluid mt-100">
            <div class="row">
                <div id="appendQuestions" class="col-md-12">
                <?php
                    if (isset($questions) && !isset($questions['status'])) {
                        foreach ($questions as $key) {
                    ?>
                            <div class="card mb-4">
                                <div class="card-header">
                                    <div class="media flex-wrap w-100 align-items-center"> <img width="30" src="https://bootdey.com/img/Content/avatar/avatar7.png" class="d-block ui-w-40 rounded-circle" alt="">
                                        <div class="media-body ml-3"> <a href="javascript:void(0)" data-abc="true"><?php echo ($key['userName']); ?></a>
                                            <div class="text-muted small"><?php echo ($key['questionTitle']); ?></div>
                                        </div>
                                        <div class="text-muted small ml-3">
                                            <div style='float:right'>Category</div><br>
                                            <div><strong><?php echo ($key['categoryName']); ?></strong> </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <p> <?php echo ($key['questionContent']); ?></p>
                                </div>
                                <div class="card-footer d-flex flex-wrap justify-content-between align-items-center px-0 pt-0 pb-3">
                                    <div class="px-4 pt-3"> <p class="text-muted d-inline-flex align-items-center align-middle" data-abc="true">
                                            <i class="fa fa-heart text-danger"></i>&nbsp; <span class="align-middle"><?php echo ($key['questionLikes']); ?></span> </p>
                                    </div>
                                    <div id="btnInsert" class="px-4 pt-3"><button id="<?php echo ($key['questionId']); ?>" class="btn btn-primary" onclick="viewQues(<?php echo ($key['questionId']); ?>)"> Reply</button> </div>
                                </div>
                            </div>
                    <?php
                        }
                    }
                    ?>
                </div>
             </div>
        </div>
    </div>                       
</div>

<script>
    // $(document).ready(function() {
    //     function fetchData() {
    //         $.ajax({
    //             type: "POST",
    //             url: "index.php/HomeController/getAllQuestions",
    //             dataType: 'json',
    //             success:function(data){
    //                 var questionsDiv = createQuestions(data);
    //                 $('.viewTwo').append(questionsDiv);
    //             },
    //         })
    //     }
    //     fetchData();      
    // });
    function viewQues(qId) {
        
        // $.ajax({
        //     type: "POST",
        //     url: "index.php/HomeController/viewQuestion",
        //     dataType: 'json',
        //     data: {
        //         questionId: qId
        //     },
        //     success:function(){
                console.log("Wosk");
                window.location.href=base_url+"index.php/question/"+qId;
        //     },   
        // })
    }
    
</script>