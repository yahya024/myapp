<?php
session_start();

if (isset($_SESSION['admin_id'])) {
    include 'include/init.php';


    $ac = isset($_GET['ac']) ? $_GET['ac'] : 'to';
    $start = 0;
    $size = 4;
    $it_count = count_db('item_id', 'items')[0];
    $count_page = ceil($it_count / $size);
    if (isset($_GET['page']) && filter_var($_GET['page'], FILTER_VALIDATE_INT) && $_GET['page'] <= $count_page) {
        $start = (abs($_GET['page']) * $size) - $size;
    }

    // get all items
    if ($ac == 'to') {
        $sql = 'SELECT items.id AS item_id, items.date, items.item_name, items.description AS item_des, items.image AS item_img, users.id AS user_id, users.name AS user_name, categories.name AS cat_name FROM items INNER JOIN categories ON items.categories_id = categories.id INNER JOIN users ON items.user_id = users.id ORDER BY item_id ' . "LIMIT $start, $size";
        $stmt = $con->prepare($sql);
        $stmt->execute();
        $items = $stmt->fetchAll();
        ?>
        <section class="it-hom">
            <div class="container-fluid">
                <div class="row">
                    <h1>منشورات الاعضاء</h1>
                    <?php foreach ($items as $item) {
                        ?>
                        <div class="col-md-3 col-sm-6">
                            <div class="it">
                                <div class="panel-group">
                                    <div class="panel panel-success">
                                        <div class="panel-heading">
                                            <div class="it-info">
                                                <p class="it-nm"><a href="items.php?ac=sh&item_id=<?= $item['item_id'] ?>"><?= $item['item_name'] ?></a></p>
                                                <p><a href="#">قسم <?= $item['cat_name'] ?></a></p>
                                            </div>

                                            <div class="it-ac">
                                                <a href="items.php?ac=edit&item_id=<?= $item['item_id'] ?>"><span class="glyphicon glyphicon-pencil"></span></a>
                                                <a href="items.php?ac=delet&item_id=<?= $item['item_id'] ?>" class="conferm"><span class="glyphicon glyphicon-trash"></span></a>
                                            </div>

                                        </div>
                                        <div class="panel-body">
                                            <div class="it-img">
                                                <?php if ($item['item_img']) { ?>
                                                    <img src="../uploads/users/<?php
                                                    echo $item['user_id'] . '/imgitem/' . $item['item_img'];
                                                    ?>" class="img-responsive">

                                                <?php } else { ?>

                                                    <img src="layout/images/default.jpg" class="img-responsive">

                                                <?php } ?>
                                            </div>

                                            <div class="it-des"><?= $item['item_des'] ?></div>
                                        </div>
                                        <div class="panel-footer">
                                            <div>من طرف <?= $item['user_name'] ?></div>
                                            <div class="it-date"><?= strstr($item['date'], ' ', true) ?><span class="glyphicon glyphicon-time"></span></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>
                <div class="page text-center">
                    <ul class="pagination pagination-lg">
                        <?php for ($i = 0; $i < $count_page; $i++) { ?>
                            <li><a href="items.php?page=<?= $i + 1 ?>"><?= $i + 1 ?></a></li>
                        <?php } ?>
                    </ul>
                </div>
                <a href="items.php?ac=add"  class="btn btn-success">اضافة منشور جديد</a>
            </div>
        </section>
        <?php
    } elseif ($ac == 'add') {
        if ($_SERVER["REQUEST_METHOD"] === 'POST') {
            if (isset($_POST['categorie'], $_POST['user'], $_POST['name'], $_POST['it-des'], $_POST['status'], $_POST['add'], $_POST['itsrc'])) {
                $formerror = array();

                // check img
                $image = null;
                $ptrn = '/\w+\.\w+\b/';

                if (preg_match($ptrn, $_POST['itsrc'], $id) === 1) {
                    $tmp = '../uploads/tmp/' . $_POST['itsrc'];
                    if (file_exists($tmp)) {
                        $image = $_POST['itsrc'];
                    }
                }

                // check categorie
                $filtercat = filterint($_POST['categorie']);
                if (cat_exists($filtercat)) {
                    $categorie = $filtercat;
                } else {
                    $formerror[] = 'القسم الذي طلبته غير موجود';
                }

                // check user
                if (filter_var($_POST['user'], FILTER_VALIDATE_INT)) {
                    $filteruser = filterint($_POST['user']);
                    if (user_exists($filteruser)) {
                        $user = $filteruser;
                    } else {
                        $formerror[] = 'هذا العضو غير موجود' . $filteruser;
                    }
                } else {
                    $formerror[] = 'هذا العضو غير موجود';
                }

                // check name
                $name = filterstring($_POST['name']);
                if (strlen($name) < 4) {
                    $formerror[] = 'اختر عنوان من اربعة احرف او اكثر';
                }

                // check description
                $filterdes = filterstring($_POST['it-des']);
                if (strlen($filterdes) < 4) {
                    $formerror[] = 'اختر وصف طويل للاعلان';
                } else {
                    $description = $filterdes;
                }

                // check status
                if (in_array($_POST['status'], array('0', '1'))) {
                    $status = $_POST['status'];
                } else {
                    $formerror[] = 'الحالة غير معروفة';
                }
            } else {
                $formerror[] = 'هناك بيانات ناقصة';
            }

            if (empty($formerror)) {
                unset($_SESSION['img_tmp']);
                if ($image !== null) {
                    $dir_id = '../uploads/users/' . $user;
                    is_dir($dir_id) ? true : mkdir($dir_id);
                    $dir_itm = $dir_id . '/imgitem';
                    is_dir($dir_itm) ? true : mkdir($dir_itm);
                    copy('../uploads/tmp/' . $image, $dir_itm . '/' . $user . '_' . $image);
                    unlink('../uploads/tmp/' . $image);
                    $image = $user . '_' . $image;
                }
                $sql = 'INSERT INTO items (item_name, description, image, status, categories_id, user_id) VALUES (:name, :description, :image, :status, :categorie, :user)';
                $stmt = $con->prepare($sql);

                $stmt->execute(array(
                    'name' => $name,
                    'description' => $description,
                    'image' => $image,
                    'status' => $status,
                    'categorie' => $categorie,
                    'user' => $user
                ));
                $msg = $stmt !== 0 ? 'تم اضافة المنشور بنجاح' : '';
            }
        }
        ?>
        <div class="it-add">
            <div class="container">

                <div class="row">

                    <div class="col-sm-6">


                        <h2>اعضافة اعلان جديد</h2>


                        <?php
                        if (!empty($formerror)) {
                            foreach ($formerror as $error) {
                                ?>
                                <div class="alert alert-danger">
                                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                    <?= $error ?>
                                </div>

                                <?php
                            }
                        }
                        if (isset($msg)) {
                            ?>
                            <div class="alert alert-success"><?= $msg ?></div>
                            <?php
                        }
                        ?>
                        <div class="it-img" method="POST" enctype="multipart/form-data">

                            <form id="itImg">
                                <label for="itFile">رفع صورة</label>
                                <input type="file" id="itFile" name="image">
                                <div class="it-def">
                                    <span></span>

                                    <img id="itDef" src="layout/images/default.jpg" class="img-responsive img-thumbnail" />
                                    <div class="progress">
                                        <div class="progress-bar" role="progressbar" aria-valuenow="70"
                                             aria-valuemin="0" aria-valuemax="100" style="width:70%">
                                            70%
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <form action="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>?ac=add" method="POST">
                            <div class="form-group">
                                <label for="itCat">القسم</label>
                                <select id="itCat" name="categorie" >
                                    <?php
                                    $categories = get_cat();
                                    foreach ($categories as $cat) {
                                        ?>
                                        <option value="<?= $cat['id'] ?>"><?= $cat['name'] ?></option>
                                    <?php } ?>
                                </select>

                            </div>

                            <div class="form-group">
                                <label for="itUs">العضو</label>
                                <select id="itUs" name="user" >
                                    <?php
                                    $users = get_all_users();
                                    foreach ($users as $user) {
                                        ?>
                                        <option value="<?= $user['id'] ?>"><?= $user['name'] ?></option>
                                    <?php } ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="itName">العنوان</label>
                                <input id="itName" name="name" class="form-control" type="text" placeholder="ادخل عنوان الاعلان" />
                            </div>
                            <div class="form-group">
                                <label for="itDes">الوصف</label>
                                <textarea id="itDes" name="it-des" class="form-control"></textarea>
                            </div>

                            <div class="form-group">
                                <label>الحالة</label><br>
                                <label for="y">مفعل</label>
                                <input type="radio" id="y" name="status" value="1" checked /><br>
                                <label for="n">غير مفعل</label>
                                <input type="radio" id="n" name="status" value="0" /><br>
                            </div>
                            <input type="hidden" value="" id="itSrc" name="itsrc"/>
                            <div class="form-group">
                                <button type="submit" name="add" class="btn btn-success">نشر</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <?php
    } elseif ($ac == 'edit') {

        if (isset($_GET['item_id']) && filter_var($_GET['item_id'], FILTER_VALIDATE_INT)) {
            $filter_id = filterint($_GET['item_id']);
            if (item_exists($filter_id)) {
                $item_id = $filter_id;
                $item = getitem($item_id);
                ?>
                <div class="it-add">
                    <div class="container">

                        <div class="row">

                            <div class="col-sm-6">


                                <h2>اعضافة اعلان جديد</h2>


                                <?php
                                if (!empty($formerror)) {
                                    foreach ($formerror as $error) {
                                        ?>
                                        <div class="alert alert-danger">
                                            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                            <?= $error ?>
                                        </div>

                                        <?php
                                    }
                                }
                                if (isset($msg)) {
                                    ?>
                                    <div class="alert alert-success"><?= $msg ?></div>
                                    <?php
                                }
                                ?>
                                <div class="it-img" method="POST" enctype="multipart/form-data">

                                    <form id="itImg">
                                        <label for="itFile">رفع صورة</label>
                                        <input type="file" id="itFile" name="image">
                                        <div class="it-def">
                                            <span></span>
                                            <?php if ($item['image'] !== null) {
                                                ?>
                                                <img id="itDef" src="../uploads/users/<?= $item['user_id'] . '/imgitem/' . $item['image'] ?>" class="img-responsive img-thumbnail" />
                                                <?php
                                            } else {
                                                ?>
                                                <img id="itDef" src="layout/images/default.jpg" class="img-responsive img-thumbnail" />
                                                <?php
                                            }
                                            ?>

                                            <div class="progress">
                                                <div class="progress-bar" role="progressbar" aria-valuenow="70"
                                                     aria-valuemin="0" aria-valuemax="100" style="width:70%">
                                                    70%
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>

                                <form action="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>?ac=update" method="POST">
                                    <div class="form-group">
                                        <label for="itCat">القسم</label>
                                        <select id="itCat" name="categorie" >
                                            <?php
                                            $categories = get_cat();
                                            foreach ($categories as $cat) {
                                                ?>
                                                <option value="<?= $cat['id'] ?>
                                                        " 
                                                        <?= $item['categories_id'] === $cat['id'] ? 'selected' : '' ?>>
                                                            <?= $cat['name'] ?>
                                                </option>
                                            <?php } ?>
                                        </select>

                                    </div>

                                    <div class="form-group">
                                        <label for="itUs">العضو</label>
                                        <select id="itUs" name="user" >
                                            <?php
                                            $users = get_all_users();
                                            foreach ($users as $user) {
                                                ?>
                                                <option value="<?= $user['id'] ?>
                                                        " <?= $item['user_id'] == $user['id'] ? 'selected' : '' ?>><?= $user['name'] ?>
                                                </option>
                                            <?php } ?>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label for="itName">العنوان</label>
                                        <input id="itName" name="name" class="form-control" type="text" value="<?= $item['item_name'] ?>" placeholder="ادخل عنوان الاعلان" />
                                    </div>
                                    <div class="form-group">
                                        <label for="itDes">الوصف</label>
                                        <textarea id="itDes" name="it-des" class="form-control"><?= $item['description'] ?></textarea>
                                    </div>

                                    <div class="form-group">
                                        <label>الحالة</label><br>
                                        <label for="y">مفعل</label>
                                        <input type="radio" id="y" name="status" value="1" <?= $item['status'] === '1' ? 'checked' : '' ?>  /><br>
                                        <label for="n">غير مفعل</label>
                                        <input type="radio" id="n" name="status" value="0" <?= $item['status'] === '0' ? 'checked' : '' ?> /><br>
                                    </div>
                                    <input type="hidden" value="<?= $item['image'] ?>" id="itSrc" name="itsrc"/>
                                    <input type="hidden" value="<?= $item['user_id'] ?>" name="subid"/>
                                    <input type="hidden" value="<?= $item['id'] ?>" name="item_id"/>

                                    <div class="form-group">
                                        <button type="submit" name="edit" class="btn btn-success">حفض</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <?php
            } else {
                ?>
                <div class="alert alert-danger">لا يوجد هذا المنشور او تم حذفه</div>
                <?php
            }
        } else {
            ?>
            <div class="alert alert-danger">هذه الصفحة غير موجودة</div>
            <?php
        }
    } elseif ($ac == 'update') {
        $formerror = array();
        if ($_SERVER["REQUEST_METHOD"] === 'POST') {
            if (isset($_POST['categorie'], $_POST['user'], $_POST['name'], $_POST['it-des'], $_POST['status'], $_POST['edit'], $_POST['itsrc'], $_POST['item_id'], $_POST['subid'])) {

                // check item_id
                if (filter_var($_POST['item_id'], FILTER_VALIDATE_INT)) {
                    $filter_id = filterint($_POST['item_id']);
                    if (item_exists($filter_id)) {
                        $item_id = $filter_id;
                    } else {
                        $formerror[] = 'هذا المنشور غير موجود او تم حذفه';
                    }
                } else {
                    $formerror[] = 'هذا المنشور غير موجود';
                }

                // check subid
                if (filter_var($_POST['subid'], FILTER_VALIDATE_INT)) {
                    $filtersubuser = filterint($_POST['subid']);
                    if (user_exists($filtersubuser)) {
                        $sub_user = $filtersubuser;
                    } else {
                        $formerror[] = 'هذا العضو غير موجود';
                    }
                } else {
                    $formerror[] = 'هذا العضو غير موجود';
                }

                // check img
                $image = null;
                $ptrn = '/\w+\.\w+\b/';

                if (preg_match($ptrn, $_POST['itsrc'], $id) === 1) {
                    $tmp = '../uploads/tmp/' . $_POST['itsrc'];

                    $image = $_POST['itsrc'];
                }

                // check categorie
                $filtercat = filterint($_POST['categorie']);
                if (cat_exists($filtercat)) {
                    $categorie = $filtercat;
                } else {
                    $formerror[] = 'القسم الذي طلبته غير موجود';
                }

                // check user
                if (filter_var($_POST['user'], FILTER_VALIDATE_INT)) {
                    $filteruser = filterint($_POST['user']);
                    if (user_exists($filteruser)) {
                        $user = $filteruser;
                    } else {
                        $formerror[] = 'هذا العضو غير موجود';
                    }
                } else {
                    $formerror[] = 'هذا العضو غير موجود';
                }

                // check name
                $name = filterstring($_POST['name']);
                if (strlen($name) < 4) {
                    $formerror[] = 'اختر عنوان من اربعة احرف او اكثر';
                }

                // check description
                $filterdes = filterstring($_POST['it-des']);
                if (strlen($filterdes) < 4) {
                    $formerror[] = 'اختر وصف طويل للاعلان';
                } else {
                    $description = $filterdes;
                }

                // check status
                if (in_array($_POST['status'], array('0', '1'))) {
                    $status = $_POST['status'];
                } else {
                    $formerror[] = 'الحالة غير معروفة';
                }

                if (empty($formerror)) {
                    
                    
                    if ($user === $sub_user) {
                        if ($image !== null) {
                            $stmtimg = $con->prepare('SELECT image FROM items WHERE id = :id');
                            $stmtimg->execute(array('id' => $item_id));
                            $img = $stmtimg->fetch();
                            if ($img['image'] !== $image) {
                                unset($_SESSION['img_tmp']);
                                $dir_id = '../uploads/users/' . $user;
                                is_dir($dir_id) ? true : mkdir($dir_id);
                                $dir_itm = $dir_id . '/imgitem';
                                is_dir($dir_itm) ? true : mkdir($dir_itm);
                                if (file_exists('../uploads/tmp/' . $image)) {
                                    copy('../uploads/tmp/' . $image, $dir_itm . '/' . $user . '_' . $image);
                                    unlink('../uploads/tmp/' . $image);
                                    $image = $user . '_' . $image;
                                }
                            }
                        }
                    } else {
                        if ($image !== null) {
                            $stmtimg = $con->prepare('SELECT * FROM items WHERE id = :id');
                            $stmtimg->execute(array('id' => $item_id));
                            $img = $stmtimg->fetch();
                            if ($img['image'] === $image) {
                                is_dir('../uploads/users/' . $sub_user) ? true : mkdir('../uploads/users/' . $sub_user);
                                is_dir('../uploads/users/' . $sub_user . '/imgitem') ? true : mkdir('../uploads/users/' . $sub_user . '/imgitem');
                                
                                is_dir('../uploads/users/' . $user) ? true : mkdir('../uploads/users/' . $user);
                                is_dir('../uploads/users/' . $user . '/imgitem') ? true : mkdir('../uploads/users/' . $user . '/imgitem');
                                
                                if(copy('../uploads/users/' . $sub_user . '/imgitem/' . $image, '../uploads/users/' . $user . '/imgitem/' . $image)){
                                    unlink('../uploads/users/' . $sub_user . '/imgitem/' . $image);
                                }
//                                copy('../uploads/users/' . $sub_user . '/imgitem/' . $image, '../uploads/users/' . $user . '/imgitem/' . $image);
                                
                            } else {
                                unset($_SESSION['img_tmp']);
                                $dir_id = '../uploads/users/' . $user;
                                is_dir($dir_id) ? true : mkdir($dir_id);
                                $dir_itm = $dir_id . '/imgitem';
                                is_dir($dir_itm) ? true : mkdir($dir_itm);
                                if (file_exists('../uploads/tmp/' . $image)) {
                                    copy('../uploads/tmp/' . $image, $dir_itm . '/' . $user . '_' . $image);
                                    unlink('../uploads/tmp/' . $image);
                                    $image = $user . '_' . $image;
                                }
                            }
                        }
                    }


                    $sql = 'UPDATE items SET item_name = :item_name, description = :description, image = :image, status = :status, categories_id = :categories_id, user_id = :user_id WHERE id = :item_id';
                    $stmt = $con->prepare($sql);
                    $stmt->execute(array('item_name' => $name, 'description' => $description, 'image' => $image, 'status' => $status, 'categories_id' => $categorie, 'user_id' => $user, 'item_id' => $item_id));
                    echo $stmt->rowCount();
                }
            } else {
                $formerror[] = 'هناك بيانات ناقصة';
            }
        } else {
            $formerror[] = 'غير مسموح لك بدخول هذه الصفحة';
        }

        if (!empty($formerror)) {
            ?>
            <div class="alert alert-danger"><?php
                foreach ($formerror as $error) {
                    echo $error;
                }
                ?></div>
            <?php
        }
    } elseif ($ac == 'delet') {
        if (isset($_GET['item_id'])) {
            // check item_id
            if (filter_var($_GET['item_id'], FILTER_VALIDATE_INT)) {
                $filter_id = filterint($_GET['item_id']);

                $stm = $con->prepare('SELECT image, user_id FROM items WHERE id = :item_id');
                $stm->execute(array('item_id' => $filter_id));
                if ($stm->rowCount() == 1) {

                    // delete img
                    $itm = $stm->fetch();
                    $item_id = $filter_id;
                    if ($itm['image'] !== null) {
                        $img = '../uploads/users/' . $itm['user_id'] . '/imgitem/' . $itm['image'];
                        if (is_file($img)) {
                            unlink($img);
                        }
                    }

                    // delete item
                    $stmt = $con->prepare('DELETE FROM items WHERE id = :item_id');
                    $stmt->execute(array('item_id' => $item_id));
                    if ($stmt->rowCount() == 1) {
                        ?>
                        <div class="alert alert-success">تم حذف المنشور بنجاح</div>
                        <?php
                    }
                } else {
                    ?>
                    <div class="alert alert-danger">هذا المنشور غير موجود او تم حذفه</div>
                    <?php
                }
            } else {
                ?>
                <div class="alert alert-danger">هذا المنشور غير موجود</div>
                <?php
            }
        } else {
            ?>
            <div class="alert alert-danger">لا يمكنك دخول هذه الصفحة</div>
            <?php
        }
    } elseif ($ac == 'sh') {
        if (isset($_GET['item_id'])) {
            if (filter_var($_GET['item_id'], FILTER_VALIDATE_INT)) {

                if (item_exists($_GET['item_id'])) {

                    $sql = 'SELECT items.id AS item_id, items.item_name, items.description, items.image, items.status, items.date, users.id AS user_id, users.name FROM items INNER JOIN users ON items.user_id = users.id WHERE items.id = ' . $_GET['item_id'];
                    $stmt = $con->prepare($sql);
                    $stmt->execute();
                    $item = $stmt->fetch();
                    ?>
                    <div class="item-info">
                        <div class="container">
                            
                            <div class="media">
                                <div class="media-right">
                                    <img src="../uploads/users/<?= $item['user_id'] ?>/imgitem/<?= $item['image'] ?>" class="media-object" style="width:100px">
                                </div>
                                <div class="media-body">
                                    <h4 class="media-heading"><?= $item['item_name'] ?></h4>
                                    <p><?= $item['description'] ?></p>
                                </div>
                            </div>
                        </div>

                    </div>
                    <?php
                } else {
                    ?>
                    <div class="container">
                        <div class="alert alert-danger">هذا المنشور غير موجود او تم حذفه</div>
                    </div>
                    <?php
                }
            } else {
                ?>
                <div class="container">
                    <div class="alert alert-danger">هذا المنشور غير موجود او تم حذفه</div>
                </div>
                <?php
            }
        }
    }

    include 'include/templates/footer.php';
} else {
    header('Location: index.php');
}
