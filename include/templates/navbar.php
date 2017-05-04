        <!-- start navbar -->
        <nav class="navbar navbar-inverse">
            <div class="container">
                
                <div class="navbar-header navbar-right">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="#">Yahya-Dz</a>
                </div>
                <div class="collapse navbar-collapse" id="myNavbar">

                    <ul class="nav navbar-nav navbar-right">
                        <li class="active"><a href="index.php">الصفحة الرئيسية</a></li>
                        <li><a href="#">Page 1</a></li>
                        <li><a href="#">Page 2</a></li>
                        <li><a href="#">Page 3</a></li>
                        <li class="dropdown">
                            <a class="dropdown-toggle" data-toggle="dropdown" href="#">اقسام الموقع<span class="caret"></span></a>
                            <ul class="dropdown-menu">
                                <li><a href="#">Page 1</a></li>
                                <li><a href="#">Page 2</a></li>
                                <li><a href="#">Page 3</a></li>
                            </ul>
                        </li>
                        
                    </ul>
                    <ul class="nav navbar-nav navbar-left">
                        <?php
                        if(!isset($_SESSION['id'])){
                            ?>
                        <li id="singIn"><a href="login.php"><span class="glyphicon glyphicon-log-in" ></span> تسجيل الدخول</a></li>
                        <li><a href="singup.php"><span class="glyphicon glyphicon-user"></span> التسجيل بالموقع</a></li>
                        <?php
                        } else {
                            ?>
                        <li><a href="#"><?= $_SESSION['name']?></a></li>
                        <li id="singIn"><a href="logout.php"><span class="glyphicon glyphicon-log-out" ></span> تسجيل الخروج</a></li>
                        <?php
                        }
                        ?>
                        
                    </ul>                    
                </div>
            </div>
        </nav>
        <!-- end navbar -->
