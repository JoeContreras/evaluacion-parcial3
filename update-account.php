<?php
// Initialize the session
session_start();

// Check if the user is logged in, otherwise redirect to login page
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}

// Include config file
require_once "config.php";

// Define variables and initialize with empty values
$curp = $nombre = $apellido = $direccion = $telefono = $correo = $username = $password = "";
$curp_err = $nombre_err = "";

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {


    // Validate username
    if (empty(trim($_POST["username"]))) {
        $username_err = "Please enter a username.";
    } elseif (!preg_match('/^[a-zA-Z0-9_]+$/', trim($_POST["username"]))) {
        $username_err = "Username can only contain letters, numbers, and underscores.";
    } else {
        // Prepare a select statement
        $sql = "SELECT id FROM users WHERE username = :username";

        if ($stmt = $pdo->prepare($sql)) {
            // Bind variables to the prepared statement as parameters
            $stmt->bindParam(":username", $param_username, PDO::PARAM_STR);

            // Set parameters
            $param_username = trim($_POST["username"]);

            // Attempt to execute the prepared statement
            if ($param_username == htmlspecialchars($_SESSION["username"])) {
                $username = trim($_POST["username"]);
            } else if ($stmt->execute()) {
                if ($stmt->rowCount() == 1) {
                    $username_err = "This username is already taken.";
                } else {
                    $username = trim($_POST["username"]);
                }
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            unset($stmt);
        }
    }

    // Validar correo
    if (empty(trim($_POST["correo"]))) {
        $correo_err = "Porfavor incluir un correo electronico";
    } elseif (!preg_match('/^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/', trim($_POST["correo"]))) {
        $correo_err = "Porfavor incluir un correo electronico valido";
    } else {
        // Prepare a select statement
        $sql = "SELECT id FROM users WHERE correo = :correo";

        if ($stmt = $pdo->prepare($sql)) {
            // Bind variables to the prepared statement as parameters
            $stmt->bindParam(":correo", $param_correo, PDO::PARAM_STR);

            // Set parameters
            $param_correo = trim($_POST["correo"]);

            // Attempt to execute the prepared statement
           if ($param_correo == htmlspecialchars($_SESSION["correo"])) {
                $correo = trim($_POST["correo"]);
            } else if ($stmt->execute()) {
                if ($stmt->rowCount() == 1) {
                    $correo_err = "Este correo electronico ya existe";
                } else {
                    $correo = trim($_POST["correo"]);
                    $correo = filter_var($correo, FILTER_VALIDATE_EMAIL);
                    if ($correo === false) {
                        exit('Invalid Email');
                    }
                }
            } else {
                echo "Algo salio mal. Porfavor intentalo mas tarde.";
            }

            // Close statement
            unset($stmt);
        }
    }


    // Validar curp
    if (empty(trim($_POST["curp"]))) {
        $curp_err = "Porfavor incluir tu CURP";
    } elseif (!preg_match('/^([A-Z][AEIOUX][A-Z]{2}\d{2}(?:0[1-9]|1[0-2])(?:0[1-9]|[12]\d|3[01])[HM](?:AS|B[CS]|C[CLMSH]|D[FG]|G[TR]|HG|JC|M[CNS]|N[ETL]|OC|PL|Q[TR]|S[PLR]|T[CSL]|VZ|YN|ZS)[B-DF-HJ-NP-TV-Z]{3}[A-Z\d])(\d)$/', trim($_POST["curp"]))) {
        $curp_err = "Porfavor incluir un CURP valido";
    } else {
        // Prepare a select statement
        $sql = "SELECT id FROM users WHERE curp = :curp";

        if ($stmt = $pdo->prepare($sql)) {
            // Bind variables to the prepared statement as parameters
            $stmt->bindParam(":curp", $param_curp, PDO::PARAM_STR);

            // Set parameters
            $param_curp = trim($_POST["curp"]);

            if ($param_curp == htmlspecialchars($_SESSION["curp"])) {
                $curp = trim($_POST["curp"]);
            } else if ($stmt->execute()) {
                if ($stmt->rowCount() == 1) {
                    $curp_err = "Este CURP ya esta registrado";
                } else {
                    $curp = trim($_POST["curp"]);
                }
            } else {
                echo "Algo salio mal. Porfavor intentalo mas tarde.";
            }

            // Close statement
            unset($stmt);
        }
    }




    // Validar telefono
    if (empty(trim($_POST["telefono"]))) {
        $telefono_err = "Porfavor incluir un numero de telefono";
    } elseif (!preg_match('/^(\+\d{1,2}\s)?\(?\d{3}\)?[\s.-]?\d{3}[\s.-]?\d{4}$/', trim($_POST["telefono"]))) {
        $telefono_err = "Porfavor incluir un numero telefonico valido";
    } else {
        // Prepare a select statement
        $sql = "SELECT id FROM users WHERE telefono = :telefono";

        if ($stmt = $pdo->prepare($sql)) {
            // Bind variables to the prepared statement as parameters
            $stmt->bindParam(":telefono", $param_telefono, PDO::PARAM_STR);

            // Set parameters
            $param_telefono = trim($_POST["telefono"]);

            if ($param_telefono == htmlspecialchars($_SESSION["telefono"])) {
                $telefono = trim($_POST["telefono"]);
            } else if ($stmt->execute()) {
                if ($stmt->rowCount() == 1) {
                    $telefono_err = "Este numero ya esta registrado";
                } else {
                    $telefono = trim($_POST["telefono"]);
                    if (preg_match('^(\+\d{1,2}\s)?\(?\d{3}\)?[\s.-]?\d{3}[\s.-]?\d{4}$', $telefono)) {
                        $telefono_err = "Porfavor incluir un numero telefonico valido!";
                    }
                }
            } else {
                echo "Algo salio mal. Porfavor intentalo mas tarde.";
            }

            // Close statement
            unset($stmt);
        }
    }



    // validar nombre
    if (empty(trim($_POST["nombre"]))) {
        $nombre_err = "Porfavor incluya su nombre";
    } elseif (strlen(trim($_POST["nombre"])) > 20) {
        $nombre_err = "El nombre no debe superar los 20 caracteres.";
    } else {
        $nombre = trim($_POST["nombre"]);
    }

    // Validar apellido
    if (empty(trim($_POST["apellido"]))) {
        $apellido_err = "Porfavor incluya su apellido";
    } elseif (strlen(trim($_POST["apellido"])) > 30) {
        $apellido_err = "El apellido no debe superar los 30 caracteres.";
    } else {
        $apellido = trim($_POST["apellido"]);
    }

    // Validar direccion
    if (empty(trim($_POST["direccion"]))) {
        $direccion_err = "Porfavor incluya su direccion";
    } elseif (strlen(trim($_POST["direccion"])) > 40) {
        $direccion_err = "La direccion no debe superar los 40 caracteres.";
    } else {
        $direccion = trim($_POST["direccion"]);
    }



    // Check input errors before updating the database
    if (empty($curp_err) && empty($nombre_err)) {
        // Prepare an update statement
        $sql = "UPDATE users SET curp = :curp, nombre= :nombre, apellido= :apellido, telefono= :telefono, direccion= :direccion, correo= :correo, username= :username WHERE id = :id";

        if ($stmt = $pdo->prepare($sql)) {
            // Bind variables to the prepared statement as parameters
            $stmt->bindParam(":curp", $param_curp, PDO::PARAM_STR);
            $stmt->bindParam(":nombre", $param_nombre, PDO::PARAM_STR);
            $stmt->bindParam(":apellido", $param_apellido, PDO::PARAM_STR);
            $stmt->bindParam(":telefono", $param_telefono, PDO::PARAM_STR);
            $stmt->bindParam(":direccion", $param_direccion, PDO::PARAM_STR);
            $stmt->bindParam(":correo", $param_correo, PDO::PARAM_STR);
            $stmt->bindParam(":username", $param_username, PDO::PARAM_STR);
            $stmt->bindParam(":id", $param_id, PDO::PARAM_INT);

            // Set parameters
            /*             $param_password = password_hash($new_password, PASSWORD_DEFAULT); */
            $param_curp = $curp;
            $param_nombre = $nombre;
            $param_apellido = $apellido;
            $param_telefono = $telefono;
            $param_direccion = $direccion;
            $param_correo = $correo;
            $param_username = $username;
            $param_id = $_SESSION["id"];

            // Attempt to execute the prepared statement
            if ($stmt->execute()) {
                // Password updated successfully. Destroy the session, and redirect to login page
                session_destroy();
                header("location: login.php");
                exit();
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            unset($stmt);
        }
    }
        /* 
    // Close connection
    unset($pdo) */;
}
?>




<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags-->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="au theme template">
    <meta name="author" content="Hau Nguyen">
    <meta name="keywords" content="au theme template">

    <!-- Title Page-->
    <title>Dashboard 3</title>

    <!-- Fontfaces CSS-->
    <link href="css/font-face.css" rel="stylesheet" media="all">
    <link href="vendor/font-awesome-4.7/css/font-awesome.min.css" rel="stylesheet" media="all">
    <link href="vendor/font-awesome-5/css/fontawesome-all.min.css" rel="stylesheet" media="all">
    <link href="vendor/mdi-font/css/material-design-iconic-font.min.css" rel="stylesheet" media="all">

    <!-- Bootstrap CSS-->
    <link href="vendor/bootstrap-4.1/bootstrap.min.css" rel="stylesheet" media="all">

    <!-- Vendor CSS-->
    <link href="vendor/animsition/animsition.min.css" rel="stylesheet" media="all">
    <link href="vendor/bootstrap-progressbar/bootstrap-progressbar-3.3.4.min.css" rel="stylesheet" media="all">
    <link href="vendor/wow/animate.css" rel="stylesheet" media="all">
    <link href="vendor/css-hamburgers/hamburgers.min.css" rel="stylesheet" media="all">
    <link href="vendor/slick/slick.css" rel="stylesheet" media="all">
    <link href="vendor/select2/select2.min.css" rel="stylesheet" media="all">
    <link href="vendor/perfect-scrollbar/perfect-scrollbar.css" rel="stylesheet" media="all">

    <!-- Main CSS-->
    <link href="css/theme.css" rel="stylesheet" media="all">

</head>

<body class="animsition">
    <div class="page-wrapper">
        <!-- HEADER DESKTOP-->
        <header class="header-desktop3 d-none d-lg-block">
            <div class="section__content section__content--p35">
                <div class="header3-wrap">
                    <div class="header__logo">
                        <a href="#">
                            <img src="images/icon/logo-white.png" alt="CoolAdmin" />
                        </a>
                    </div>
                    <div class="header__navbar">
                        <ul class="list-unstyled">
                            <li class="has-sub">
                                <a href="#">
                                    <i class="fas fa-tachometer-alt"></i>Dashboard
                                    <span class="bot-line"></span>
                                </a>
                                <ul class="header3-sub-list list-unstyled">
                                    <li>
                                        <a href="index.html">Dashboard 1</a>
                                    </li>
                                    <li>
                                        <a href="index2.html">Dashboard 2</a>
                                    </li>
                                    <li>
                                        <a href="index3.html">Dashboard 3</a>
                                    </li>
                                    <li>
                                        <a href="index4.html">Dashboard 4</a>
                                    </li>
                                </ul>
                            </li>
                            <li>
                                <a href="#">
                                    <i class="fas fa-shopping-basket"></i>
                                    <span class="bot-line"></span>Tienda</a>
                            </li>
                            <li>
                                <a href="#">
                                    <i class="fas fa-trophy"></i>
                                    <span class="bot-line"></span>Productos</a>
                            </li>
                            <li class="has-sub">
                                <a href="#">
                                    <i class="fas fa-desktop"></i>
                                    <span class="bot-line"></span>UI Elements</a>
                            </li>
                        </ul>
                    </div>
                    <div class="header__tool">
                        <div class="header-button-item js-item-menu">
                            <i class="zmdi zmdi-settings"></i>
                            <div class="setting-dropdown js-dropdown">
                                <div class="account-dropdown__body">
                                    <div class="account-dropdown__item">
                                        <a href="./welcome.php">
                                            <i class="zmdi zmdi-account"></i>Account</a>
                                    </div>
                                    <div class="account-dropdown__item">
                                        <a href="./update-password.php">
                                            <i class="zmdi zmdi-settings"></i>Reset Password</a>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <div class="account-wrap">
                            <div class="account-item account-item--style2 clearfix js-item-menu">
                                <div class="image">
                                    <img src="images/icon/avatar-01.jpg" alt="John Doe" />
                                </div>
                                <div class="content">
                                    <a class="js-acc-btn" href="#"><?php echo htmlspecialchars($_SESSION["username"]); ?></a>
                                </div>
                                <div class="account-dropdown js-dropdown">
                                    <div class="info clearfix">
                                        <div class="image">
                                            <a href="#">
                                                <img src="images/icon/avatar-01.jpg" alt="John Doe" />
                                            </a>
                                        </div>
                                        <div class="content">
                                            <h5 class="name">
                                                <a href="#"><?php echo htmlspecialchars($_SESSION["nombre"]); ?> <?php echo htmlspecialchars($_SESSION["apellido"]); ?></a>
                                            </h5>
                                            <span class="email"><?php echo htmlspecialchars($_SESSION["correo"]); ?></span>
                                        </div>
                                    </div>
                                    <div class="account-dropdown__body">
                                        <div class="account-dropdown__item">
                                            <a href="./welcome.php">
                                                <i class="zmdi zmdi-account"></i>Account</a>
                                        </div>
                                        <div class="account-dropdown__item">
                                            <a href="#">
                                                <i class="zmdi zmdi-settings"></i>Setting</a>
                                        </div>
                                    </div>
                                    <div class="account-dropdown__footer">
                                        <a href="./logout.php">
                                            <i class="zmdi zmdi-power"></i>Logout</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </header>
        <!-- END HEADER DESKTOP-->

        <!-- HEADER MOBILE-->
        <header class="header-mobile header-mobile-2 d-block d-lg-none">
            <div class="header-mobile__bar">
                <div class="container-fluid">
                    <div class="header-mobile-inner">
                        <a class="logo" href="index.html">
                            <img src="images/icon/logo-white.png" alt="CoolAdmin" />
                        </a>
                        <button class="hamburger hamburger--slider" type="button">
                            <span class="hamburger-box">
                                <span class="hamburger-inner"></span>
                            </span>
                        </button>
                    </div>
                </div>
            </div>
            <nav class="navbar-mobile">
                <div class="container-fluid">
                    <ul class="navbar-mobile__list list-unstyled">
                        <li class="has-sub">
                            <a class="js-arrow" href="#">
                                <i class="fas fa-tachometer-alt"></i>Dashboard</a>
                            <ul class="navbar-mobile-sub__list list-unstyled js-sub-list">
                                <li>
                                    <a href="index.html">Dashboard 1</a>
                                </li>
                                <li>
                                    <a href="index2.html">Dashboard 2</a>
                                </li>
                                <li>
                                    <a href="index3.html">Dashboard 3</a>
                                </li>
                                <li>
                                    <a href="index4.html">Dashboard 4</a>
                                </li>
                            </ul>
                        </li>
                        <li>
                            <a href="chart.html">
                                <i class="fas fa-chart-bar"></i>Charts</a>
                        </li>
                        <li>
                            <a href="table.html">
                                <i class="fas fa-table"></i>Tables</a>
                        </li>
                        <li>
                            <a href="form.html">
                                <i class="far fa-check-square"></i>Forms</a>
                        </li>
                        <li>
                            <a href="calendar.html">
                                <i class="fas fa-calendar-alt"></i>Calendar</a>
                        </li>
                        <li>
                            <a href="map.html">
                                <i class="fas fa-map-marker-alt"></i>Maps</a>
                        </li>
                        <li class="has-sub">
                            <a class="js-arrow" href="#">
                                <i class="fas fa-copy"></i>Pages</a>
                            <ul class="navbar-mobile-sub__list list-unstyled js-sub-list">
                                <li>
                                    <a href="login.html">Login</a>
                                </li>
                                <li>
                                    <a href="register.html">Register</a>
                                </li>
                                <li>
                                    <a href="forget-pass.html">Forget Password</a>
                                </li>
                            </ul>
                        </li>
                        <li class="has-sub">
                            <a class="js-arrow" href="#">
                                <i class="fas fa-desktop"></i>UI Elements</a>
                            <ul class="navbar-mobile-sub__list list-unstyled js-sub-list">
                                <li>
                                    <a href="button.html">Button</a>
                                </li>
                                <li>
                                    <a href="badge.html">Badges</a>
                                </li>
                                <li>
                                    <a href="tab.html">Tabs</a>
                                </li>
                                <li>
                                    <a href="card.html">Cards</a>
                                </li>
                                <li>
                                    <a href="alert.html">Alerts</a>
                                </li>
                                <li>
                                    <a href="progress-bar.html">Progress Bars</a>
                                </li>
                                <li>
                                    <a href="modal.html">Modals</a>
                                </li>
                                <li>
                                    <a href="switch.html">Switchs</a>
                                </li>
                                <li>
                                    <a href="grid.html">Grids</a>
                                </li>
                                <li>
                                    <a href="fontawesome.html">Fontawesome Icon</a>
                                </li>
                                <li>
                                    <a href="typo.html">Typography</a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </nav>
        </header>
        <div class="sub-header-mobile-2 d-block d-lg-none">
            <div class="header__tool">
                <div class="header-button-item has-noti js-item-menu">
                    <i class="zmdi zmdi-notifications"></i>
                    <div class="notifi-dropdown notifi-dropdown--no-bor js-dropdown">
                        <div class="notifi__title">
                            <p>You have 3 Notifications</p>
                        </div>
                        <div class="notifi__item">
                            <div class="bg-c1 img-cir img-40">
                                <i class="zmdi zmdi-email-open"></i>
                            </div>
                            <div class="content">
                                <p>You got a email notification</p>
                                <span class="date">April 12, 2018 06:50</span>
                            </div>
                        </div>
                        <div class="notifi__item">
                            <div class="bg-c2 img-cir img-40">
                                <i class="zmdi zmdi-account-box"></i>
                            </div>
                            <div class="content">
                                <p>Your account has been blocked</p>
                                <span class="date">April 12, 2018 06:50</span>
                            </div>
                        </div>
                        <div class="notifi__item">
                            <div class="bg-c3 img-cir img-40">
                                <i class="zmdi zmdi-file-text"></i>
                            </div>
                            <div class="content">
                                <p>You got a new file</p>
                                <span class="date">April 12, 2018 06:50</span>
                            </div>
                        </div>
                        <div class="notifi__footer">
                            <a href="#">All notifications</a>
                        </div>
                    </div>
                </div>
                <div class="header-button-item js-item-menu">
                    <i class="zmdi zmdi-settings"></i>
                    <div class="setting-dropdown js-dropdown">
                        <div class="account-dropdown__body">
                            <div class="account-dropdown__item">
                                <a href="#">
                                    <i class="zmdi zmdi-account"></i>Account</a>
                            </div>
                            <div class="account-dropdown__item">
                                <a href="#">
                                    <i class="zmdi zmdi-settings"></i>Setting</a>
                            </div>
                            <div class="account-dropdown__item">
                                <a href="#">
                                    <i class="zmdi zmdi-money-box"></i>Billing</a>
                            </div>
                        </div>
                        <div class="account-dropdown__body">
                            <div class="account-dropdown__item">
                                <a href="#">
                                    <i class="zmdi zmdi-globe"></i>Language</a>
                            </div>
                            <div class="account-dropdown__item">
                                <a href="#">
                                    <i class="zmdi zmdi-pin"></i>Location</a>
                            </div>
                            <div class="account-dropdown__item">
                                <a href="#">
                                    <i class="zmdi zmdi-email"></i>Email</a>
                            </div>
                            <div class="account-dropdown__item">
                                <a href="#">
                                    <i class="zmdi zmdi-notifications"></i>Notifications</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="account-wrap">
                    <div class="account-item account-item--style2 clearfix js-item-menu">
                        <div class="image">
                            <img src="images/icon/avatar-01.jpg" alt="John Doe" />
                        </div>
                        <div class="content">
                            <a class="js-acc-btn" href="#">john doe</a>
                        </div>
                        <div class="account-dropdown js-dropdown">
                            <div class="info clearfix">
                                <div class="image">
                                    <a href="#">
                                        <img src="images/icon/avatar-01.jpg" alt="John Doe" />
                                    </a>
                                </div>
                                <div class="content">
                                    <h5 class="name">
                                        <a href="#">john doe</a>
                                    </h5>
                                    <span class="email">johndoe@example.com</span>
                                </div>
                            </div>
                            <div class="account-dropdown__body">
                                <div class="account-dropdown__item">
                                    <a href="#">
                                        <i class="zmdi zmdi-account"></i>Account</a>
                                </div>
                                <div class="account-dropdown__item">
                                    <a href="#">
                                        <i class="zmdi zmdi-settings"></i>Setting</a>
                                </div>
                                <div class="account-dropdown__item">
                                    <a href="#">
                                        <i class="zmdi zmdi-money-box"></i>Billing</a>
                                </div>
                            </div>
                            <div class="account-dropdown__footer">
                                <a href="#">
                                    <i class="zmdi zmdi-power"></i>Logout</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- END HEADER MOBILE -->

        <!-- PAGE CONTENT-->
        <div class="main-content">
            <div class="section__content section__content--p30">
                <div class="container-fluid">
                    <div class="row">
                        <div class="card">
                            <div class="card-header">
                                <strong>Reset</strong> Password
                            </div>
                            <div class="card-body card-block">
                                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                                    <div class="form-group">
                                        <label for="nf-email" class=" form-control-label">Nuevo CURP</label>
                                        <input type="text" id="nf-email" name="curp" placeholder="CURP" class="form-control" value="<?php echo (!empty($curp)) ? $curp : htmlspecialchars($_SESSION["curp"]); ?>">
                                        <span class="<?php echo (!empty($curp_err)) ? 'alert-danger' : 'help-block'; ?>"><?php echo (!empty($curp_err)) ? $curp_err : 'Porfavor incluya su CURP'; ?></span>
                                    </div>
                                    <div class="form-group">
                                        <label for="nf-password" class=" form-control-label">Nuevo Nombre</label>
                                        <input type="text" id="nf-password" name="nombre" placeholder="Nombre" class="form-control" value="<?php echo (!empty($nombre)) ? $nombre : htmlspecialchars($_SESSION["nombre"]); ?>">
                                        <span class="<?php echo (!empty($nombre_err)) ? 'alert-danger' : 'help-block'; ?>"><?php echo (!empty($nombre_err)) ? $nombre_err : 'Porfavor incluya su Nombre'; ?></span>
                                    </div>
                                    <div class="form-group">
                                        <label for="nf-password" class=" form-control-label">Nuevo Apellido</label>
                                        <input type="text" id="nf-password" name="apellido" placeholder="Apellido" class="form-control" value="<?php echo (!empty($apellido)) ? $apellido : htmlspecialchars($_SESSION["apellido"]); ?>">
                                        <span class="<?php echo (!empty($apellido_err)) ? 'alert-danger' : 'help-block'; ?>"><?php echo (!empty($apellido_err)) ? $apellido_err : 'Porfavor incluya su Apellido'; ?></span>
                                    </div>
                                    <div class="form-group">
                                        <label for="nf-password" class=" form-control-label">Nuevo Telefono</label>
                                        <input type="tel" id="nf-password" name="telefono" placeholder="Numero Telefonico" class="form-control" value="<?php echo (!empty($telefono)) ? $telefono : htmlspecialchars($_SESSION["telefono"]); ?>">
                                        <span class="<?php echo (!empty($telefono_err)) ? 'alert-danger' : 'help-block'; ?>"><?php echo (!empty($telefono_err)) ? $telefono_err : 'Porfavor incluya su Numero Telefonico'; ?></span>
                                    </div>
                                    <div class="form-group">
                                        <label for="nf-password" class=" form-control-label">Nueva Direccion</label>
                                        <input type="text" id="nf-password" name="direccion" placeholder="Direccion" class="form-control" value="<?php echo (!empty($direccion)) ? $direccion : htmlspecialchars($_SESSION["direccion"]); ?>">
                                        <span class="<?php echo (!empty($direccion_err)) ? 'alert-danger' : 'help-block'; ?>"><?php echo (!empty($direccion_err)) ? $direccion_err : 'Porfavor incluya su Direccion'; ?></span>
                                    </div>
                                    <div class="form-group">
                                        <label for="nf-password" class=" form-control-label">Nuevo Email</label>
                                        <input type="text" id="nf-password" name="correo" placeholder="Email" class="form-control" value="<?php echo (!empty($correo)) ? $correo : htmlspecialchars($_SESSION["correo"]); ?>">
                                        <span class="<?php echo (!empty($correo_err)) ? 'alert-danger' : 'help-block'; ?>"><?php echo (!empty($correo_err)) ? $correo_err : 'Porfavor incluya su Correo Electronico'; ?></span>
                                    </div>
                                    <div class="form-group">
                                        <label for="nf-password" class=" form-control-label">Nuevo Nombre de Usuario</label>
                                        <input type="text" id="nf-password" name="username" placeholder="Username" class="form-control" value="<?php echo (!empty($username)) ? $username : htmlspecialchars($_SESSION["username"]); ?>">
                                        <span class="<?php echo (!empty($username_err)) ? 'alert-danger' : 'help-block'; ?>"><?php echo (!empty($username_err)) ? $username_err : 'Porfavor incluya su Nombre de usuario'; ?></span>
                                    </div>
                                    <div class="form-group">
                                        <input class="btn btn-primary btn-sm" type="submit" value="Submit">
                                        <a class="btn btn-danger btn-sm" href="welcome.php">Cancel</a>

                                    </div>
                                </form>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- Jquery JS-->
    <script src="vendor/jquery-3.2.1.min.js"></script>
    <!-- Bootstrap JS-->
    <script src="vendor/bootstrap-4.1/popper.min.js"></script>
    <script src="vendor/bootstrap-4.1/bootstrap.min.js"></script>
    <!-- Vendor JS       -->
    <script src="vendor/slick/slick.min.js">
    </script>
    <script src="vendor/wow/wow.min.js"></script>
    <script src="vendor/animsition/animsition.min.js"></script>
    <script src="vendor/bootstrap-progressbar/bootstrap-progressbar.min.js">
    </script>
    <script src="vendor/counter-up/jquery.waypoints.min.js"></script>
    <script src="vendor/counter-up/jquery.counterup.min.js">
    </script>
    <script src="vendor/circle-progress/circle-progress.min.js"></script>
    <script src="vendor/perfect-scrollbar/perfect-scrollbar.js"></script>
    <script src="vendor/chartjs/Chart.bundle.min.js"></script>
    <script src="vendor/select2/select2.min.js">
    </script>

    <!-- Main JS-->
    <script src="js/main.js"></script>

</body>

</html>
<!-- end document-->