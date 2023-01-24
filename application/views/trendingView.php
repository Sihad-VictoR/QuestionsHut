<div class="trendingView">
    <ul>
    
        <li class="px-3"><a>Recently Asked Questions</a></li>
        <?php
                if (isset($recent)) {
                    foreach ($recent as $key) {
                ?>
                        
                        <div class='trend'>
                        <a href="#" onclick="viewQues(<?php echo $key['questionId']; ?>)"><?php echo $key['questionTitle']; ?></a><br>
                        <i class="fa fa-star text-danger"></i>&nbsp; <span class="align-middle"><?php echo ($key['questionLikes']); ?>
                        </div>
                <?php
                    }
                }
                ?>
        <hr>
        <li class="px-3"><a>Most Liked Questions</a></li>
        <?php
                if (isset($mostliked)) {
                    foreach ($mostliked as $key) {
                ?>
                        
                        <div class='trend'>
                        <a href="#" onclick="viewQues(<?php echo $key['questionId']; ?>)"><?php echo $key['questionTitle']; ?></a><br>
                        <i class="fa fa-star text-danger"></i>&nbsp; <span class="align-middle"><?php echo ($key['questionLikes']); ?>
                        </div>
                <?php
                    }
                }
                ?>
        <hr>
        <li class="px-3"><a>Unanswered Questions</a></li>
        <?php
                if (isset($unanswered)) {
                    foreach ($unanswered as $key) {
                ?>
                        
                        <div class='trend'>
                        <a href="#" onclick="viewQues(<?php echo $key['questionId']; ?>)"><?php echo $key['questionTitle']; ?></a><br>
                        <i class="fa fa-star text-danger"></i>&nbsp; <span class="align-middle"><?php echo ($key['questionLikes']); ?>
                        </div>
                <?php
                    }
                }
                ?>
        
    </ul>

</div>

<script>
var base_url = "<?php echo base_url();?>";
function viewQues(qId) {
     window.location.href=base_url+"index.php/question/"+qId;
}  
</script>