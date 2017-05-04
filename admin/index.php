<?php
session_start();
if (isset($_SESSION['admin_id'])) {
    include_once 'include/init.php';
    ?>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-3 col-sm-6">
                <div class="cat cat-1">
                    <span class="glyphicon glyphicon-user"></span>
                    <p class="lead">الاعضاء المسجلين</p>
                    <a href="users.php"><p class="lead"><?= count_db('id', 'users')['count'] ?></p></a>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="cat cat-2">
                    <span class="glyphicon glyphicon-user"></span>
                    <p class="lead">مشاركات هذا اليوم</p>
                    <p class="lead">30</p>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="cat cat-3">
                    <span class="glyphicon glyphicon-user"></span>
                    <p class="lead">الاعضاء النشطين حاليا</p>
                    <p class="lead">60</p>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="cat cat-4">
                    <span class="glyphicon glyphicon-user"></span>
                    <p class="lead">الاعضاء المسجلين</p>
                    <p class="lead">40</p>
                </div>
            </div>
        </div>
    </div>

    <!-- start recent -->
    <section class="recent">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-6">
                    <h2 class="text-center">اخر خمسة اعضاء مسجلين</h2>
                    <div class="table-responsive">
                        <table>
                            <tr>
                                <th>Firstname</th>
                                <th>username</th>
                                <th>email</th>
                                <th>user group</th>
                                <th>status</th>
                                <th>date</th>
                                <th colspan="2">action</th>
                            </tr>
                            <?php foreach (user_no_activ('DESC', 5) as $user) { ?>
                                <tr>
                                    <td><?= $user['name'] ?></td>
                                    <td><?= $user['username'] ?></td>
                                    <td><?= $user['email'] ?></td>
                                    <td>
                                        <?php
                                        switch ($user['user_group']) {
                                            case '0':
                                                echo 'عضو عادي';
                                                break;
                                            case '1':
                                                echo 'مدير عام';
                                                break;
                                            case '2':
                                                echo 'مساعد مدير';
                                                break;
                                            default:
                                                echo 'غير معروف';
                                        }
                                        ?>
                                    </td>
                                    <td>
                                        <?php
                                        switch ($user['status']) {
                                            case '0':
                                                echo 'غير مفعل';
                                                break;
                                            case '1':
                                                echo 'غضو مفعل';
                                                break;
                                            default:
                                                echo 'غير معروف';
                                        }
                                        ?> 
                                    </td>
                                    <td><?= $user['date'] ?></td>
                                    <td><a href="users.php?ac=edit&id=<?= $user['id'] ?>" class="btn btn-info btn-sm">تعديل</a></td>
                                    <td><a href="users.php?ac=delet&id=<?= $user['id'] ?>" class="btn btn-danger btn-sm conferm">حذف</a></td>
                                </tr>
                            <?php } ?>
                            <tr>
                                <td colspan="2"><a href="adduser.php" class="btn btn-success btn-sm">اضافة عضو جديد</a></td>
                                <td colspan="2"><a href="#" class="btn btn-danger btn-sm">مشاهدة كافة الاعضاء الغير مفعلين</a></td>
                                <td colspan="4"><a href="users.php" class="btn btn-info btn-sm">مشاهدة قائمة الاعضاء</a></td>
                            </tr>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </section>
    <!-- start recent -->

    <?php
    include_once 'include/templates/footer.php';
} else {
    header('Location: login.php');
}