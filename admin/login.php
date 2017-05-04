<?php
session_start();
if (!isset($_SESSION['admin_id'])) {


    require_once 'include/config.php';
    include 'include/templates/header.php';

    if (isset($_POST['login']) && $_POST['login'] == 'login') {

        if (isset($_POST['username'], $_POST['password'])) {

            if (!empty($_POST['username']) && !empty($_POST['password'])) {

                $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
                $username = stripslashes($username);
                $password = sha1($_POST['password']);
                $ue = filter_var($username, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
                $stmt = $con->prepare("SELECT * FROM users WHERE $ue = :ue AND password = :password AND user_group = :user_group");
                $stmt->execute(array(
                    'ue' => $username,
                    'password' => $password,
                    'user_group' => 1
                ));

                if ($stmt->rowCount() > 0) {
                    $ftch = $stmt->fetch();
                    $_SESSION['admin_id'] = $ftch['id'];
                    $_SESSION['admin_username'] = $ftch['username'];
                    $_SESSION['admin_name'] = $ftch['name'];
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

<div class="container">
    <div class="form-login">
        <form action="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="POST">
            <h1 class="text-center">تسجيل الدخول</h1>
            <?php
                if (isset($msg)) {
                    ?>
                    <div class="alert alert-danger alert-dismissable fade in">
                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                        <p><?= $msg ?></p>
                    </div>
                <?php } ?>
            <div class="input-group">
                <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                <input id="username" type="text" class="form-control" name="username" placeholder="ادخل اسم المستخدم او البريد الالكتروني">
            </div>   

            <div class="input-group">
                <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                <input id="password" type="password" class="form-control" name="password" placeholder="ادخل كلمة السر">
            </div>

            <div class="input-group" id="in">
                <button id="submit" type="submit" class="btn btn-info btn-block" name="login" value="login">دخول</button>
            </div>

        </form>
    </div>

</div>

<?php include 'include/templates/footer.php'; ?>

