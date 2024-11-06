<header class="header-section">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-3">
                <div class="logo">
                    <a href="./index.php">
                        <img src="../assets/img/fzlogo.png" alt="" />
                    </a>
                </div>
            </div>
            <div class="col-lg-6">
                <nav class="nav-menu">
                    <ul>
                        <li class="<?= basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : '' ?>">
                            <a href="./index.php">Home</a>
                        </li>
                        <li class="<?= basename($_SERVER['PHP_SELF']) == 'about-us.php' ? 'active' : '' ?>">
                            <a href="./about-us.php">About Us</a>
                        </li>
                        <li class="<?= basename($_SERVER['PHP_SELF']) == 'contact.php' ? 'active' : '' ?>">
                            <a href="./contact.php">Contact</a>
                        </li>
                        <li class="<?= basename($_SERVER['PHP_SELF']) == 'services.php' ? 'active' : '' ?>">
                            <a href="./services.php">Services</a>
                        </li>
                        <li class="<?= basename($_SERVER['PHP_SELF']) == 'team.php' ? 'active' : '' ?>">
                            <a href="./team.php">Our Team</a>
                        </li>
                        <li>
                            <a href="#">Pages</a>
                            <ul class="dropdown">
                                <li class="<?= basename($_SERVER['PHP_SELF']) == 'about-us.php' ? 'active' : '' ?>">
                                    <a href="./about-us.php">About us</a>
                                </li>
                                <li class="<?= basename($_SERVER['PHP_SELF']) == 'bmi-calculator.php' ? 'active' : '' ?>">
                                    <a href="./bmi-calculator.php">Bmi calculate</a>
                                </li>
                                <li class="<?= basename($_SERVER['PHP_SELF']) == 'team.php' ? 'active' : '' ?>">
                                    <a href="./team.php">Our team</a>
                                </li>
                                <li class="<?= basename($_SERVER['PHP_SELF']) == 'gallery.php' ? 'active' : '' ?>">
                                    <a href="./gallery.php">Gallery</a>
                                </li>
                                <li class="<?= basename($_SERVER['PHP_SELF']) == 'blog.php' ? 'active' : '' ?>">
                                    <a href="./blog.php">Our blog</a>
                                </li>
                                <li class="<?= basename($_SERVER['PHP_SELF']) == '404.php' ? 'active' : '' ?>">
                                    <a href="./404.php">404</a>
                                </li>
                            </ul>
                        </li>
                        <li class="<?= basename($_SERVER['PHP_SELF']) == 'login.php' ? 'active' : '' ?>">
                        <a href="./login.php">Login</a>
                    </li>
                    </ul>
                </nav>
            </div>
            <div class="col-lg-3">
                <div class="top-option">
                    <div class="col-lg-6"></div>
                    <div class="to-social">
                        <a href="#"><i class="fa fa-facebook"></i></a>
                        <a href="#"><i class="fa fa-twitter"></i></a>
                        <a href="#"><i class="fa fa-youtube-play"></i></a>
                        <a href="#"><i class="fa fa-instagram"></i></a>
                    </div>
                </div>
            </div>
        </div>
        <div class="canvas-open">
            <i class="fa fa-bars"></i>
        </div>
    </div>
</header>
