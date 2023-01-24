<div class="usersList">
    <section id="content" class="container">
    <div class="page-heading">
            <div class="media clearfix">
                <div class="media-left pr30">
                    <h2>Users List </h2>
                </div>
                <div class="media-body va-m">
                </div>
            </div>
        </div>
        <table class="table">
                <thead class="thead-dark">
                    <tr>
                        <th scope="col">User Name</th>
                        <th scope="col">Questions Created</th>
                        <th scope="col">Replies Posted</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if (isset($user)) {
                        foreach ($user as $key) {
                    ?>
                        <tr>
                            <th scope="row"><?php echo $key['userName']; ?></th>
                            <td><?php echo $key['questions']; ?></td>
                            <td><?php echo $key['answers']; ?></td>
                        </tr>
                           
                    <?php
                        }
                    }
                    ?>
                </tbody>
            </table>
    </section>
</div>