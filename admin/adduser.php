<?php
session_start();
if (isset($_SESSION['admin_id'])) {


    require_once 'include/init.php';

    if (isset($_POST['singup']) && $_POST['singup'] == 'singup') {
        if (isset($_POST['name'], $_POST['email'], $_POST['username'], $_POST['password'], $_POST['confirm'], $_POST['user_group'], $_POST['status'])) {

            $formerror = array();

            // Check name
            if (!empty($_POST['name'])) {
                if (strlen($_POST['name']) < 4) {
                    $formerror[] = 'اسم المستخدم قصير';
                } else {
                    $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
                    $name = stripslashes($name);
                }
            } else {
                $formerror[] = 'لا تترك الاسم فارغا';
            }


            // Check email
            if (filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL)) {
                $email = $_POST['email'];
            } else {
                $formerror[] = 'البريد الالكتروني غير صالح';
            }

            // Check username
            $filterusername = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
            if (strlen($filterusername) >= 4) {
                $username = stripslashes($filterusername);
            } else {
                $formerror[] = "يجب ان يحتوي اسم المستخدم على الاقل اربعة حروف";
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
                $formerror[] = 'لا تترك حقل كلمة السر فارغا';
            }
            
            // check user_group
                if(in_array($_POST['user_group'], array('0', '1', '2'))){
                    $user_group = $_POST['user_group'];
                } else {
                    $formerror[] = 'نوع المستخدم غير موجود';
                }
                
                // check status
                if(in_array($_POST['status'], array('0', '1'))){
                    $status = $_POST['status'];
                } else {
                    $formerror[] = 'حالت المستخدم غير معروفة';
                }
            

            if (empty($formerror)) {

                $stmt = $con->prepare("SELECT username, email FROM users WHERE username = :username OR email = :email");
                $stmt->execute(array(
                    'username' => $username,
                    'email' => $email
                ));
                $count = $stmt->rowCount();
                if ($count == 0) {
                    $stmt = $con->prepare("INSERT INTO users (name, username, email, password, user_group, status, date) VALUES (:name, :username, :email, :password, :user_group, :status, NOW())");
                    $stmt->execute(array(
                        'name' => $name,
                        'username' => $username,
                        'email' => $email,
                        'password' => $password,
                        'user_group' => $user_group,
                        'status' => $status
                    ));
                    $msg = $stmt->rowCount() ? 'تم اضافة العضو الجديد بنجاح' : 'هناك مشكلة في التسجيل حاول في وقت لاحق';
                } else {
                    $formerror[] = 'اسف اسم المستخدم موجود من قبل';
                }
            }
        }
    } else {
        
    }
    ?>
    <div class="main">
        <div class="main-con">

            <div class="container">
                <div class="main-sing">
                    <h1 class="text-center">التسجيل بالموقع</h1>
                    <?php
                    if (isset($filterusername)) {
                        foreach ($formerror as $error) {
                            ?>
                            <div class="alert alert-danger alert-dismissable fade in">
                                <?= $error ?>
                                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                            </div>
                            <?php
                        }
                    }

                    if (isset($msg)) {
                        ?>
                        <div class="alert alert-success">
                            <?= $msg ?>
                            <p>اسم المستخدم هو: <b><?= $username ?></b></p>
                            <p>الايمايل هو: <b><?= $email ?></b></p>
                        </div>
                    <?php } ?>
                    <form action="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="POST">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="name" class="control-label">Your Name</label>
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="glyphicon glyphicon-bookmark" aria-hidden="true"></i></span>
                                        <input type="text" class="form-control" name="name" id="name"  placeholder="ادخل الاسم"/>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="email" class="control-label">Your Email</label>
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="glyphicon glyphicon-envelope" aria-hidden="true"></i></span>
                                        <input type="text" class="form-control" name="email" id="email"  placeholder="ادخل البريد الالكتروني"/>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="username" class="control-label">اسم المستخدم</label>
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="glyphicon glyphicon-user" aria-hidden="true"></i></span>
                                        <input type="text" class="form-control" name="username" id="username"  placeholder="ادخل اسم المستخدم"/>
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
                            <div class="col-sm-6">
                                
                                <div class="form-group">
                                    <label for="user_group" class="control-label">نوع المستخدم</label>
                                    <select name="user_group" id="user_group">
                                        <option value="0">عضو عادي</option>
                                        <option value="1">مدير</option>
                                        <option value="2">مساعد المدير</option>
                                    </select>
                                </div>
                                
                                <div class="form-group">
                                    <label for="status" class="control-label">الحالة</label>
                                    <select name="status" id="status">
                                        <option value="0">غير مفعل</option>
                                        <option value="1" selected >مفعل</option>
                                    </select>
                                </div>
                            </div>
                            
                        </div>
                        <div class="input-lg">
                            <button id="singup" type="submit" class="btn btn-success btn-block" name="singup" value="singup">اضافة العضو</button>
                        </div>

                    </form>
                </div>

            </div>
        </div>
    </div>
    <?php
    include './include/templates/footer.php';
} else {
    header('Location: index.php');
}
?>