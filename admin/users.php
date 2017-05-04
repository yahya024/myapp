<?php

/*
 * yahya bensedira
 * Copyright © 2017
 */


session_start();

if (isset($_SESSION['admin_id'])) {

    include_once 'include/init.php';

    $ac = isset($_GET['ac']) ? $_GET['ac'] : 'to';

    if ($ac == 'to') {

        $start = 0;
        $size = 5;
        $get_page = 1;
        $count_user = count_db('id', 'users')['count'];
        $line = ceil($count_user / $size);

        if (isset($_GET['page'])) {

            $get_page = abs(filter_var($_GET['page'], FILTER_SANITIZE_NUMBER_INT));

            if ($get_page > 1 && $get_page <= $line) {

                $start = ($get_page * $size) - $size;
            } else {

                $get_page = 1;
            }
        }

        if (isset($_GET['order'])) {
            
        }


        $stmt = $con->prepare("SELECT * FROM users ORDER BY id ASC LIMIT $start, $size");
        $stmt->execute();
        $users = $stmt->fetchAll();
        ?>
        <div class="container">
            <h1>قائمة الاعضاء</h1>
            <div class="tbus">
                <h2>قائمة الاعضاء المسجلين</h2>
                <div class="table-responsive">
                <table>
                    <?php
                    $ii = $start + 1;

                    foreach ($users as $user) {
                        ?>
                        <tr>
                            <td><?= $ii++ ?></td>
                            <td><?= $user['name'] ?></td>
                        
                            <td><?= $user['email'] ?></td>
                            <td><a href="users.php?ac=edit&id=<?= $user['id'] ?>" class="btn btn-info btn-sm">تعديل</a></td>
                            <td><a href="users.php?ac=delet&id=<?= $user['id'] ?>" class="btn btn-danger btn-sm conferm">حذف</a></td>
                        </tr>
                        
                    <?php } ?>
                    <tr>
                        <td><a class="btn btn-default" href="users.php?page=<?= $get_page - 1 ?>">السابق</a></td>
                        <td colspan="3">
                            <ul class="pagination">
                                <?php for ($i = 0; $i < $line; $i++) { ?>
                                    <li class="<?= ($get_page == $i + 1) ? 'active' : '' ?>"><a href="users.php?page=<?= $i + 1 ?>"><?= $i + 1 ?></a></li>

                                <?php } ?>
                            </ul>
                        </td>
                        <td><a class="btn btn-default" href="users.php<?php
                            if ($get_page + 1 <= $line) {
                                echo '?page=' . ($get_page + 1);
                            }
                            ?>">التالي</a></td>
                    </tr>
                </table>
                </div>
            </div>
        </div>
        <?php
        // edit    
    } elseif ($ac == 'edit') {

        if (isset($_GET['id']) && filter_var($_GET['id'], FILTER_VALIDATE_INT)) {

            $userid = abs($_GET['id']);
            $user = getuser($userid);
            if ($user) {
                ?>

                <div class="container">
                    <div class="main-sing">
                        <h2 class="text-center">تعديل المستخدم <?= $user['name'] ?></h2>
                        <form action="<?= htmlspecialchars($_SERVER['PHP_SELF']) . '?ac=update' ?>" method="POST">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="name" class="control-label">الاسم</label>
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="glyphicon glyphicon-bookmark" aria-hidden="true"></i></span>
                                            <input type="text" class="form-control" name="name" id="name" value="<?= $user['name'] ?>" placeholder="ادخل الاسم"/>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="email" class="control-label">البريد الالكتروني</label>
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="glyphicon glyphicon-envelope" aria-hidden="true"></i></span>
                                            <input type="text" class="form-control" name="email" id="email" value="<?= $user['email'] ?>"  placeholder="ادخل البريد الالكتروني"/>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="username" class="control-label">اسم المستخدم</label>
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="glyphicon glyphicon-user" aria-hidden="true"></i></span>
                                            <input type="text" class="form-control" name="username" id="username" value="<?= $user['username'] ?>"  placeholder="ادخل اسم المستخدم"/>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="password" class="control-label">كلمة السر</label>
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="glyphicon glyphicon-lock" aria-hidden="true"></i></span>
                                            <input type="password" class="form-control" name="password" id="password"  placeholder="ادخل كلمة السر"/>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="confirm" class="control-label">اعد كتابة كلمة السر</label>
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="glyphicon glyphicon-lock" aria-hidden="true"></i></span>
                                            <input type="password" class="form-control" name="confirm" id="confirm"  placeholder="اعد ادخال كلمة السر"/>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="user_group">نوع المستخدم</label>
                                        <select name="user_group" id="user_group">
                                            <option value="0" <?php
                                            if ($user['user_group'] == 0) {
                                                echo 'selected';
                                            }
                                            ?> >عضوعادي</option>
                                            <option value="1" <?php
                                            if ($user['user_group'] == 1) {
                                                echo 'selected';
                                            }
                                            ?>>مدير عام</option>
                                            <option value="2" <?php
                                            if ($user['user_group'] == 2) {
                                                echo 'selected';
                                            }
                                            ?>>مساعد المدير</option>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label for="status">الحالة</label>
                                        <select name="status" id="status">
                                            <option value="0" <?php
                                            if ($user['status'] == 0) {
                                                echo 'selected';
                                            }
                                            ?> >غير مفعل</option>
                                            <option value="1" <?php
                                            if ($user['status'] == 1) {
                                                echo 'selected';
                                            }
                                            ?>>مفعل</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" name="userid" value="<?= $user['id'] ?>">
                            <div class="input-lg">
                                <button id="singup" type="submit" class="btn btn-success btn-block" name="updat" value="updat">تعديل</button>
                            </div>

                        </form>
                    </div>
                </div>
                <?php
            } else {
                $msg = 'لايوجد هذا العضو او تم حذفه';
            }
        } else {

            $msg = 'لايوجد هذا العضو او تم حذفه';
        }
    } elseif ($ac == 'update') {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['name'], $_POST['email'], $_POST['username'], $_POST['password'], $_POST['confirm'], $_POST['user_group'], $_POST['status'], $_POST['userid'])) {
                $formerror = array();

                // check name
                $name = filterstring($_POST['name']);
                if (strlen($_POST['name']) < 4) {
                    $formerror[] = 'يجب ان يحتوي الاسم على الاقل اربعة حروف';
                }

                // check email
                if (filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL)) {
                    $email = $_POST['email'];
                } else {
                    $formerror[] = 'البريد الالكتروني غير صالح';
                }

                // Check username
                $username = filterstring($_POST['username']);
                if (strlen($username) < 4) {
                    $formerror[] = 'يجب ان يحتوي الاسم على الاقل اربعة حروف';
                }

                // Check password
                if (!empty($_POST['password'])) {

                    if (strlen($_POST['password']) >= 4) {

                        $password1 = sha1($_POST['password']);
                        $password2 = sha1($_POST['confirm']);

                        if ($password1 == $password2) {

                            $password = $password1;
                        } else {
                            $formerror[] = 'اسف كلمة السر غير متطابقة';
                        }
                    } else {
                        $formerror[] = 'يجب ان تحتوي كلمة السر على الاقل اربعة حروف';
                    }
                } else {
                    $password = false;
                }

                // check user_group
                if (in_array($_POST['user_group'], array('0', '1', '2'))) {
                    $user_group = $_POST['user_group'];
                } else {
                    $formerror[] = 'نوع المستخدم غير موجود';
                }

                // check status
                if (in_array($_POST['status'], array('0', '1'))) {
                    $status = $_POST['status'];
                } else {
                    $formerror[] = 'حالت المستخدم غير معروفة';
                }

                // check id
                if (filter_var($_POST['userid'], FILTER_VALIDATE_INT)) {
                    $userid = abs($_POST['userid']);
                } else {
                    $formerror[] = 'هذا العضو غير موجود او تم حذفه';
                }

                if (empty($formerror)) {

                    $stmtchek = $con->prepare('SELECT email, username, id FROM users WHERE id != :userid AND username = :username AND email = :email');
                    $stmtchek->execute(array('userid' => $userid, 'username' => $username, 'email' => $email));
                    if ($stmtchek->rowCount() == 0) {
                        if ($password) {
                            $sql = 'UPDATE users SET name = :name, username = :username, email = :email, password = :password, user_group = :user_group, status = :status WHERE id = :userid';
                            $stmt = $con->prepare($sql);

                            $stmt->execute(array(
                                'name' => $name,
                                'username' => $username,
                                'email' => $email,
                                'password' => $password,
                                'user_group' => $user_group,
                                'status' => $status,
                                'userid' => $userid
                            ));
                        } else {
                            $sql = 'UPDATE users SET name = :name, username = :username, email = :email, user_group = :user_group, status = :status WHERE id = :userid';
                            $stmt = $con->prepare($sql);

                            $stmt->execute(array(
                                'name' => $name,
                                'username' => $username,
                                'email' => $email,
                                'user_group' => $user_group,
                                'status' => $status,
                                'userid' => $userid
                            ));
                        }
                        $msg = 'تم التحديث بنجاح';
                    } else {
                        $msg = 'اسف اسم العضو او الايمايل موجود من قبل';
                    }
                }
            } else {
                $msg = 'هناك بيانات ناقصة';
            }
        } else {
            $msg = 'لايمكنك الدخول الى هذه الصفحة مباشرة';
        }
        
    } elseif ($ac == 'delet') {
        if (isset($_GET['id'])) {
            if (filter_var($_GET['id'], FILTER_VALIDATE_INT)) {
                $userid = abs($_GET['id']);
                if (user_exists($userid)) {
                    if (delet_user($userid)) {
                        remove_dir_user('../uploads/users/' . $userid);
                        $msg = 'تم حذف العضو بنجاح';
                    }
                }
            } else {
                $msg = 'هذا المستخدم غير موجود او تم حذفه';
            }
        }
        
    } elseif ($ac == 'add') {
        ?>
        <div class="container">

        </div>
        <?php
    }

    echo isset($msg) ? $msg : ''; // test

    include_once 'include/templates/footer.php';
} else {
    header('Location: login.php');
}
