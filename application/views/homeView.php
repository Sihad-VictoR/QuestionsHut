<!DOCTYPE html>
<html>

<head>
    <title>QuestionsHut</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc9npb8tCaSX9FK7E8HnRr0Jz8D6OP9dO5Vg3Q9ct" crossorigin="anonymous"></script>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/underscore.js/1.8.2/underscore-min.js" type="text/javascript"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/backbone.js/1.2.2/backbone-min.js" type="text/javascript"></script>
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/styles.css?<?php echo date('l jS \of F Y h:i:s A'); ?>">
    
</head>
<body id="body">
    <div id="header">
        <?= $header ?>
    </div>
    <div class="content">
        <div class="container">
            <div class="row g-0">
                <div class="col-2">
                    <div class="viewOne">
                        <?= $category ?>
                    </div>
                </div>
                <div class="col-7">
                    <div class="viewTwo"><?= $main ?></div>
                </div>
                <div class="col-3">
                    <div class="viewThree">
                        <?= $trending ?>
                    </div>
                </div>
            </div>

        </div>
    </div>
</body>


</html>