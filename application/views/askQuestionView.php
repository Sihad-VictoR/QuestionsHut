<div class="askQuestion">
    <div class="p-5">
        <h2>Ask Your Question:</h2>
        <div class="mb-3">
            <label for="qTitle" class="form-label">Question Title</label>
            <input type="text" class="form-control" id="qTitle" placeholder="How to get id in PHP form?">
        </div>
        <div class="mb-3">
            <label for="qContent" class="form-label">Question</label>
            <textarea class="form-control" id="qContent" rows="3"></textarea>
        </div>
        <div class="dropdown">
            <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Choose Category
            </button>
            <div id="dropdown-menu" class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                <?php
                if (isset($category)) {
                    foreach ($category as $key) {
                ?>
                        <a id="<?php echo $key['categoryId']; ?>"class="dropdown-item" href="#"><?php echo ($key['categoryName']); ?></a>
                <?php
                    }
                }
                ?>
            </div>
        </div>

        <button class="btn btn-success btn-lg float-right" type="submit" onclick="addQuestion()">Submit</button>
    </div>
</div>

<script>
    var chosenCategory = null;
    $('#dropdown-menu a').on('click', function(){
        chosenCategory = $(this).attr('id');
        $('#dropdownMenuButton').html($(this).html());
    });
    function addQuestion() {
        if($('#qTitle').val()== '' || $('#qContent').val()==''){
            alert("Please fill the fields");
            return;
        }
        $.ajax({
            type: "POST",
            url: base_url+"index.php/HomeController/addQuestion",
            dataType: 'json',
            data: {
                questionTitle: $('#qTitle').val(),
                questionContent: $('#qContent').val(),
                categoryId: chosenCategory
            },
            success:function(data){
                if(data == null){
                    $("#ModalExample").modal("toggle");
                    return;
                }
                if(!data.status){
                    alert("Failed to add Question");
                }else{
                    alert("Successfully added Question");
                    window.location.href=base_url+"index.php/question/"+data.id;
                }
                
            },   
        })
    }
    
</script>