<?php
session_start();
ob_start();
if (!isset($_SESSION['id'])) {
    require_once 'include/init.php';
    if (isset($_POST['login']) && $_POST['login'] == 'login') {

        if (isset($_POST['username'], $_POST['password'])) {

            if (!empty($_POST['username']) && !empty($_POST['password'])) {

                $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
                $username = stripslashes($username);
                $password = sha1($_POST['password']);
                $ue = filter_var($username, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
                $stmt = $con->prepare("SELECT * FROM users WHERE $ue = :ue AND password = :password");
                $stmt->execute(array(
                    'ue' => $username,
                    'password' => $password
                ));

                if ($stmt->rowCount() > 0) {
                    $ftch = $stmt->fetch();
                    echo '<pre>';
                    print_r($ftch);
                    $_SESSION['id'] = $ftch['id'];
                    $_SESSION['username'] = $ftch['username'];
                    $_SESSION['name'] = $ftch['name'];
                    header('Location: index.php');
                } else {
                    $msg = 'خطاء في كلمة السر او اسم المستخدم حاول مرة اخرى';
                }
            } else {
                $msg = 'خطاء في كلمة السر او اسم المستخدم حاول مرة اخرى';
            }
        }
    }
} else {
    header('Location: index.php');
}
?>
<div class="main">
    <div class="main-con">

        <div class="container">
            <div class="main-sing">
                <h1 class="text-center">تسجيل الدخول</h1>
                <?php
                if (isset($msg)) {
                    ?>
                    <div class="alert alert-danger alert-dismissable fade in">
                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                        <p><?= $msg ?></p>
                    </div>
                <?php } ?>
                <form action="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="POST">
                    <div class="form-group">
                        <label for="username" class="control-label">اسم المستخدم او البريد الالكتروني</label>
                        <div class="input-group">
                            <span class="input-group-addon"><i class="glyphicon glyphicon-user" aria-hidden="true"></i></span>
                            <input type="text" class="form-control" name="username" id="username"  placeholder="ادخل اسم المستخدم او البريد الالكتروني"/>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="password" class="control-label">كلمة السر</label>
                        <div class="input-group">
                            <span class="input-group-addon"><i class="glyphicon glyphicon-lock" aria-hidden="true"></i></span>
                            <input type="password" class="form-control" name="password" id="password"  placeholder="ادخل كلمة السر"/>
                        </div>
                    </div>

                    <div class="input-lg">
                        <button id="login" type="submit" class="btn btn-success btn-block" name="login" value="login">تسجيل الدخول</button>
                    </div>

                </form>
            </div>

        </div>
    </div>
</div>
<?php
include './include/templates/footer.php';
ob_end_flush();
?>