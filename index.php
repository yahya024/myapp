<?php
session_start();
require_once 'include/init.php';

$stmt = $con->prepare('SELECT items.*, categories.name AS cat_name, categories.active, users.name, users.id FROM items INNER JOIN categories ON items.categories_id = categories.id INNER JOIN users ON items.user_id = users.id WHERE categories.active = 1 AND users.status = 1 AND items.status = 1');
$stmt->execute();
$items = $stmt->fetchAll();
?>

<section class="hom">
    <div class="container-fluid">
        <div class="row">
            <h2 class="text-center">اخر منشورات الاعضاء</h2>
            <?php foreach ($items as $item) { ?>
                <div class="col-md-3 col-sm-6">
                    <div class="it">
                        <div class="panel-group">
                            <div class="panel panel-primary">

                                <div class="panel-heading">
                                    <h2><?= $item['item_name'] ?></h2>
                                    <p>قسم <?= $item['cat_name'] ?></p>
                                </div>

                                <div class="panel-body">
                                    <div class="it-img">
                                        <?php
                                        if ($item['image']) {
                                            ?>
                                            <img src="uploads/users/<?php echo $item['user_id'] . '/imgitem/' . $item['image']; ?>" class="img-responsive">
                                            <?php
                                        } else {
                                            ?>
                                            <img src="layout/images/default.jpg" class="img-responsive">
                                            <?php
                                        }
                                        ?>
                                            <!--<img src="uploads/unnamed.png" class="img-responsive">-->
                                    </div>

                                    <div class="it-des"><?= $item['description'] ?></div>
                                </div>

                                <div class="panel-footer">
                                    <div>من طرف <?= $item['name'] ?></div>
                                    <div class="it-date"><?= strstr($item['date'], ' ', true) ?><span class="glyphicon glyphicon-time"></span></div>
                                    <div class="it-com">
                                        <div><span class="badge">2</span><span class="glyphicon glyphicon-comment"></span>اضافة تعليق</div>

                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                </div>
            <?php } ?>


        </div>

    </div>
</div>
</section>


<?php include 'include/templates/footer.php'; ?>