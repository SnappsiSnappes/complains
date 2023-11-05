<script>
    // #!! переключатель темы, не переносить вниз
    (function() {
        var theme = localStorage.getItem('theme');
        if (theme) {
            document.documentElement.setAttribute('data-bs-theme', theme);
        }
    })();

    $(document).ready(function() {
        $('#themeSwitcher').click(function() {
            var theme = $('html').attr('data-bs-theme') === 'dark' ? 'light' : 'dark';
            $('html').attr('data-bs-theme', theme);
            localStorage.setItem('theme', theme);
        });
    });
</script>

<?
require_once 'database.php';
$obj_list = $getter->get_msg($_SESSION['login'], true);
$msg_count = count($obj_list);



?>
<nav class="navbar navbar-expand-lg bg-body-tertiary">
    <div class="container-fluid">
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarTogglerDemo01" aria-controls="navbarTogglerDemo01" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarTogglerDemo01">

            <ul class="navbar-nav me-auto mb-2 mb-lg-0 nav-underline">
                <li class="nav-item">
                    <a class="nav-link " href="setter.php">Новая жалоба</a>
                </li>

                <?
                $users = ['super_user'];

                if(in_array($_SESSION['privilege'],$users)){
                echo <<<EOT
                <li class="nav-item">
                    <a class="nav-link " aria-current="page" href="getter.php">Поиск жалоб</a>
                </li>




                <li class="nav-item">
                    <a class="nav-link " href="com1_controller.php">Магазины com1</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link " href="com2_controller.php">Магазины com2</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link " href="Active_controller.php">Опубликованные жалобы</a>
                </li>


                EOT;
                } ?>


            </ul>

            <form class='px-2 p-2' action='msg.php' method='post'>
                <button class='btn btn-sm btn-outline-secondary ' type='submit'>
                    Сообщения

                    <span class="badge text-bg-secondary"><? echo $msg_count; ?></span>

                </button>
            </form>
            <form class='px-2 ' action='Personal_controller.php' method='post'>
                <button class='btn btn-sm btn-outline-secondary ' type='submit'>
                    посмотреть мои жалобы
                </button>
            </form>
            <span class="navbar-text ">
                <? if (isset($_SESSION['login'])) {
                    echo "      
                <form class='px-2 p-2' action='logout.php' method='post'>
                    <button class='btn btn-sm btn-outline-secondary' type='submit'>Выйти</button>
                </form>";
                } else {
                    echo "      
                    <form class='px-2 p-2' action='login.php' method='post'>
                        <button class='btn btn-sm btn-outline-secondary' type='submit'>Войти</button>
                    </form>";
                } ?>
            </span>


            <button id="themeSwitcher" class="m-2 px-2 mx-2 btn btn-sm btn-outline-secondary"><svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor" class="bi bi-brightness-alt-high" viewBox="0 0 16 16">
                    <path d="M8 3a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-1 0v-2A.5.5 0 0 1 8 3zm8 8a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1 0-1h2a.5.5 0 0 1 .5.5zm-13.5.5a.5.5 0 0 0 0-1h-2a.5.5 0 0 0 0 1h2zm11.157-6.157a.5.5 0 0 1 0 .707l-1.414 1.414a.5.5 0 1 1-.707-.707l1.414-1.414a.5.5 0 0 1 .707 0zm-9.9 2.121a.5.5 0 0 0 .707-.707L3.05 5.343a.5.5 0 1 0-.707.707l1.414 1.414zM8 7a4 4 0 0 0-4 4 .5.5 0 0 0 .5.5h7a.5.5 0 0 0 .5-.5 4 4 0 0 0-4-4zm0 1a3 3 0 0 1 2.959 2.5H5.04A3 3 0 0 1 8 8z" />
                </svg>
            </button>
            <span class="navbar-text px-2 p-2">



                <a class=" nav-link disabled " aria-disabled="true"><? if (isset($_SESSION['login'])) {
                                                                        echo 'Логин: ' . $_SESSION['login'] . '<br>' . '  привилегии: ' . $_SESSION['privilege'];
                                                                    } else {
                                                                        echo 'Вы не вошли в систему';
                                                                    } ?></a>
            </span>


            <!--         <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            Dropdown
          </a>
          <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="#">Action</a></li>
            <li><a class="dropdown-item" href="#">Another action</a></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item" href="#">Something else here</a></li>
          </ul>
        </li> -->
            <!--         <li class="nav-item">
          <a class="nav-link disabled" aria-disabled="true">Disabled</a>
        </li> -->

            <!--       <form class="d-flex" role="search">
        <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
        <button class="btn btn-outline-success" type="submit">Search</button>
      </form>
 -->
        </div>
    </div>

</nav>
</div>


<script>
    document.addEventListener("DOMContentLoaded", function() {
        var navItems = document.querySelectorAll('.nav-item .nav-link');
        var buttonMSG = document.querySelector("form[action='msg.php'] button");
        var buttonPers = document.querySelector("form[action='Personal_controller.php'] button");
        var currentLocation = window.location.href;

        for (var i = 0; i < navItems.length; i++) {
            if (currentLocation.startsWith(navItems[i].href)) {
                navItems[i].classList.add('active');
            }
        }

        if (currentLocation.includes('msg.php')) {
            buttonMSG.classList.add('active');
        }

        if (currentLocation.includes('Personal_controller.php')) {
            buttonPers.classList.add('active');
        }
    });
</script>