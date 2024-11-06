<div class="offcanvas-menu-wrapper">
    <div class="canvas-close">
        <i class="fa fa-close"></i>
    </div>
    <div class="canvas-search search-switch">
        <i class="fa fa-search"></i>
    </div>
    <nav class="canvas-menu mobile-menu">
        <ul>
            <li class="<?= basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : '' ?>">
                <a href="./index.php">Home</a>
            </li>
            <li class="<?= basename($_SERVER['PHP_SELF']) == 'about-us.php' ? 'active' : '' ?>">
                <a href="./about-us.php">About Us</a>
            </li>
            <li class="<?= basename($_SERVER['PHP_SELF']) == 'classes.php' ? 'active' : '' ?>">
                <a href="./classes.php">Classes</a>
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
                    <li class="<?= basename($_SERVER['PHP_SELF']) == 'class-timetable.php' ? 'active' : '' ?>">
                        <a href="./class-timetable.php">Classes timetable</a>
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
            
            <li class="<?= basename($_SERVER['PHP_SELF']) == 'contact.php' ? 'active' : '' ?>">
                <a href="./contact.php">Contact</a>
            </li>
        </ul>
    </nav>
    <div id="mobile-menu-wrap"></div>
    <div class="canvas-social">
        <a href="#"><i class="fa fa-facebook"></i></a>
        <a href="#"><i class="fa fa-twitter"></i></a>
        <a href="#"><i class="fa fa-youtube-play"></i></a>
        <a href="#"><i class="fa fa-instagram"></i></a>
    </div>
</div>
