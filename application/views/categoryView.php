<div class="category">
    <p class="myproject px-3">CATEGORIES</p>
    <ul>
        <?php
        if (isset($category)) {
            foreach ($category as $key) {
        ?>
                <li><a href="javascript:sortCategory(<?php echo $key['categoryId']; ?>);"><?php echo ($key['categoryName']); ?></a></li>
        <?php
            }
        }
        ?>
    </ul>



</div>
<script>
    var base_url = "<?php echo base_url();?>";
    function sortCategory(catID) {
        window.location.href=base_url+"index.php/category/"+catID;
    }
</script>