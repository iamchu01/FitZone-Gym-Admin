<?php
$directoryURI = $_SERVER['REQUEST_URI'];
$path = parse_url($directoryURI, PHP_URL_PATH);
$components = explode('/', $path);
$page = $components[2];
?>
<!-- Sidebar -->
<div class="sidebar" id="sidebar">
  <div class="sidebar-inner slimscroll">
    <div id="sidebar-menu" class="sidebar-menu">


      <ul class="sidebar-vertical">
        <li class="menu-title">
          <span>Main</span>
        </li>


        <li class="<?php echo ($page == 'admin-dashboard.php') ? 'active' : ''; ?>" href="admin-dashboard.php">
          <a href="admin-dashboard.php"><i class="la la-home"></i> <span>Dashboard</span></a>
        </li>
        <hr style="border: 0; border-top: 1px solid #A0A0A0; margin: 10px 0;">

        <!-- //* CALENDAR -->
        <li class="<?php echo ($page == 'events.php') ? 'active' : ''; ?>" href="events.php">
          <a href="events.php"><i class="la la-calendar"></i> <span>Calendar</span></a>
        </li>


        <!-- <hr style="border: 0; border-top: 1px solid #A0A0A0; margin: 10px 0;"> -->

        <!-- //* ATTENDANCE -->
        <!-- <li class="<?php echo ($page == 'admin-dashboard.php') ?: ''; ?>">
                    <a href="attendance-tracking.php"> <i class="la la-calendar-check"></i> <span>Attendance</span></a>
                </li> -->

        <hr style="border: 0; border-top: 1px solid #A0A0A0; margin: 10px 0;">


        <!-- //* file maintinance -->
        <li class=" submenu">
          <a href="#"><i class="la la-box"></i> <span> File Maintenance</span> <span class="menu-arrow"></span></a>
          <ul style="display: none;">
            <li><a class="<?php echo ($page == 'add-instructor.php') ? 'active' : ''; ?>"
                href="add-instructor.php">Instructors</a></li>
            <!-- <li><a class="<?php echo ($page == 'employees-list.php') ? 'active' : ''; ?>" href="employees-list.php">Members</a></li> -->
            <li><a class="<?php echo ($page == 'add-member.php') ? 'active' : ''; ?>" href="add-member.php">Members</a>
            </li>
            <li><a class="<?php echo ($page == 'create-membership-plan.php') ? 'active' : ''; ?>"
                href="create-membership-plan.php">Membership</a>
            </li>
            <li><a class="<?php echo ($page == 'create-payment-method.php') ? 'active' : ''; ?>"
                href="create-payment-method.php">Payment
                Methods</a>
            </li>
            <li>
              <a class="<?php echo ($page == 'gym_equipment.php' || $page == 'admin.php' || $page == 'add_product.php' || $page == 'product.php' || $page == 'categorie.php') ? 'active' : ''; ?>"
                href="admin.php">Inventory Management</a>

            </li>
            <li><a class="<?php echo ($page == 'store.php') ? 'active' : ''; ?>" href="store.php">E-Store Management</a>
            </li>

            <!-- <li><a class="<?php echo ($page == 'category-list.php') ? 'active' : ''; ?>" href="category-list.php">Category List</a></li>                  -->
            <li><a class="<?php echo ($page == 'offered-programs.php') ? 'active' : ''; ?>"
                href="offered-programs.php">Offered Programs</a></li>
            <li><a class="<?php echo ($page == 'targeted-exercise.php') ? 'active' : ''; ?>"
                href="targeted-exercise.php">Targeted Exercise</a></li>
          </ul>
        </li>


        <!-- //* INVENTORY -->

        <!-- //* CUSTOMER MANAGEMENT -->
        <!-- <li class="submenu">
                    <a href="#" ><i class="la la-users"></i> <span>Customer Management</span> <span class="menu-arrow"></span></a>
                    <ul style="display: none;">
                        <li><a class="<?php echo ($page == 'employees-list.php') ? 'active' : ''; ?>" href="employees-list.php">Add Member</a></li>
                        <li><a class="<?php echo ($page == 'enrollment.php') ? 'active' : ''; ?>" href="enrollment.php">Enrollment</a></li>
                        <li><a class="<?php echo ($page == 'renewal-and-cancellation.php') ? 'active' : ''; ?>" href="renewal-and-cancellation.php">Renewal & Cancellations</a></li>
                        <li><a class="<?php echo ($page == 'client-profile.php') ? 'active' : ''; ?>" href="client-profile.php">Profile Overview</a></li>
                        <li><a class="<?php echo ($page == 'attendance-tracking.php') ? 'active' : ''; ?>" href="attendance-tracking.php">Attendance Tracking</a></li>
                        <li><a class="<?php echo ($page == 'personalized-recommendation.php') ? 'active' : ''; ?>" href="personalized-recommendation.php">Personalized Recommendation</a></li>
                   </ul>
                </li> -->

        <hr style="border: 0; border-top: 1px solid #A0A0A0; margin: 10px 0;">

        <!-- //* TRANSACTION -->
        <li class="submenu">
          <a href="#"><i class="la la-rocket"></i> <span>Transaction</span> <span class="menu-arrow"></span></a>
          <ul style="display: none;">
            <li><a class="<?php echo ($page == 'pos.php') ? 'active' : ''; ?>" href="pos.php">Point of Sale</a></li>
            <li><a class="<?php echo ($page == 'stock-out.php') ? 'active' : ''; ?>" href="stock-out.php">Stock Out</a>
            </li>
            <li><a class="<?php echo ($page == 'stock-in.php') ? 'active' : ''; ?>" href="stock-in.php">Stock in</a>
            </li>
            <li><a class="<?php echo ($page == 'walk-in.php') ? 'active' : ''; ?>" href="walk-in.php">Walk In</a></li>
            <li><a class="<?php echo ($page == 'attendance-tracking.php') ? 'active' : ''; ?>"
                href="attendance-tracking.php">Attendance</a></li>
          </ul>
        </li>
        <hr style="border: 0; border-top: 1px solid #A0A0A0; margin: 10px 0;">


        <!-- //* UTILITIES -->
        <li class="submenu">
          <a href="#"><i class="la la-tools"></i> <span>Utilities</span> <span class="menu-arrow"></span></a>
          <ul style="display: none;">
            <li><a class="<?php echo ($page == 'discount.php') ? 'active' : ''; ?>" href="discount.php">Discounts</a>
            </li>
            <li><a class="<?php echo ($page == 'media.php') ? 'active' : ''; ?>" href="media.php">Media</a></li>
            
            <li><a class="<?php echo ($page == 'archive.php') ? 'active' : ''; ?>" href="archive.php">Archive</a></li>

            <li><a class="<?php echo ($page == 'renewal-and-cancellation.php') ? 'active' : ''; ?>"
                href="renewal-and-cancellation.php">Renewal & Cancellations</a>
            </li>

            <li><a class="<?php echo ($page == '#') ? 'active' : ''; ?>"
                href="#">Membership Settings</a>
            </li>

          </ul>
        </li>
        <hr style="border: 0; border-top: 1px solid #A0A0A0; margin: 10px 0;">


        <!-- //* REPORTS -->
        <li class="submenu">
          <a href="#"><i class="la la-chart-bar"></i> <span>Reports</span> <span class="menu-arrow"></span></a>
          <ul style="display: none;">
            <li><a class="<?php echo ($page == 'sales-reports.php') ? 'active' : ''; ?>" href="sales-reports.php">Sales
                Reports</a></li>
            <li><a class="<?php echo ($page == 'inventory-reports.php') ? 'active' : ''; ?>"
                href="inventory-reports.php">Inventory Reports</a></li>
            <li><a class="<?php echo ($page == 'attendance-reports.php') ? 'active' : ''; ?>"
                href="attendance-reports.php">Attendance Reports</a></li>
            <li><a class="<?php echo ($page == 'payments.php') ? 'active' : ''; ?>" href="payments.php">Payments</a>
            </li>
          </ul>
        </li>
        <hr style="border: 0; border-top: 1px solid #A0A0A0; margin: 10px 0;">

        <!-- //* YOU CAN DELETE THIS -->
        <li class="submenu">
          <a href="#"><i class="la la-database"></i> <span> Backup & Restore </span> <span
              class="menu-arrow"></span></a>
          <ul style="display: none;">
            <li><a class="<?php echo ($page == 'backup-and-restore.php') ? 'active' : ''; ?>"
                href="backup-and-restore.php">Backup & Restore</a></li>
            <li><a class="<?php echo ($page == 'tax-settings.php') ? 'active' : ''; ?>" href="tax-settings.php">Tax
                Settings</a></li>
          </ul>
        </li>
        <!-- //* YOU CAN DELETE THIS -->
      </ul>

    </div>
  </div>
</div>
