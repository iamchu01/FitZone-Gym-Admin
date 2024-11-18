<?php
$directoryURI = $_SERVER['REQUEST_URI'];
$path = parse_url($directoryURI, PHP_URL_PATH);
$components = explode('/', $path);
$page = $components[2];
?>
<!-- jQuery -->
<script src="assets/js/jquery-3.6.1.min.js"></script>

<!-- feather -->
<script src="assets/js/feather.min.js"></script>

<!-- Bootstrap Core JS -->
<script src="assets/js/bootstrap.bundle.min.js"></script>

<!-- Theme Settings JS -->
<script src="assets/js/layout.js"></script>
<script src="assets/js/theme-settings.js"></script>
<script src="assets/js/greedynav.js"></script>

<!-- Slimscroll JS -->
<script src="assets/js/jquery.slimscroll.min.js"></script>

<?php if ($page == 'admin-dashboard.php') { ?>
  <!-- Chart JS -->
  <script src="assets/plugins/morris/morris.min.js"></script>
  <script src="assets/plugins/raphael/raphael.min.js"></script>
  <script src="assets/js/chart.js"></script>
  <script src="assets/js/greedynav.js"></script>
  
  
<?php }
if ($page == 'sales-reports.php' || $page == 'stock-in.php' || $page == 'inventory-reports.php' || $page == 'stock-out.php' || $page == 'product-list.php' || $page == 'categorie.php' || $page == 'product.php' || $page == 'events.php' || $page == 'add-instructor.php' || $page == 'add-member.php' || $page == 'employees-list.php' || $page == 'leaves.php' || $page == 'leaves-employee.php' || $page == 'leave-settings.php' || $page == 'attendance.php' || $page == 'attendance-employee.php' || $page == 'designations.php' || $page == 'timesheet.php' || $page == 'shift-scheduling.php' || $page == 'shift-list.php' || $page == 'overtime.php' || $page == 'clients.php' || $page == 'clients-list.php' || $page == 'client-profile.php' || $page == 'projects.php' || $page == 'project-list.php' || $page == 'project-view.php' || $page == 'tasks.php' || $page == 'task-board.php' || $page == 'tickets.php' || $page == 'ticket-view.php' || $page == 'estimates.php' || $page == 'create-estimate.php' || $page == 'edit-estimate.php' || $page == 'invoices.php' || $page == 'create-invoice.php' || $page == 'edit-invoice.php' || $page == 'expenses.php' || $page == 'provident-fund.php' || $page == 'taxes.php' || $page == 'salary.php' || $page == 'payroll-items.php' || $page == 'policies.php' || $page == 'expense-reports.php' || $page == 'invoice-reports.php' || $page == 'payments-reports.php' || $page == 'project-reports.php' || $page == 'task-reports.php' || $page == 'user-reports.php' || $page == 'employee-reports.php' || $page == 'payslip-reports.php' || $page == 'attendance-reports.php' || $page == 'leave-reports.php' || $page == 'daily-reports.php' || $page == 'performance-indicator.php' || $page == 'performance.php' || $page == 'performance-appraisal.php' || $page == 'goal-tracking.php' || $page == 'goal-type.php' || $page == 'training.php' || $page == 'trainers.php' || $page == 'training-type.php' || $page == 'promotion.php' || $page == 'resignation.php' || $page == 'termination.php' || $page == 'assets.php' || $page == 'user-all-jobs.php' || $page == 'saved-jobs.php' || $page == 'applied-jobs.php' || $page == 'job-details.php' || $page == 'job-apptitude.php' || $page == 'questions.php' || $page == 'offered-jobs.php' || $page == 'visited-jobs.php' || $page == 'archived-jobs.php' || $page == 'jobs.php' || $page == 'job-applicants.php' || $page == 'manage-resumes.php' || $page == 'shortlist-candidates.php' || $page == 'interview-questions.php' || $page == 'offer_approvals.php' || $page == 'experiance-level.php' || $page == 'candidates.php' || $page == 'schedule-timing.php' || $page == 'aptitude-result.php' || $page == 'users.php' || $page == 'settings.php' || $page == 'localization.php' || $page == 'email-settings.php' || $page == 'performance-setting.php' || $page == 'approval-setting.php' || $page == 'toxbox-setting.php' || $page == 'cron-setting.php' || $page == 'profile.php' || $page == 'subscriptions.php' || $page == 'subscribed-companies.php' || $page == 'components.php' || $page == 'form-horizontal.php' || $page == 'form-vertical.php' || $page == 'add-instructor.php' || $page == 'add-member.php' || $page == 'create-membership-plan.php' || $page == 'create-payment-method.php') { ?>
  <!-- Select2 JS -->
  <script src="assets/js/select2.min.js"></script>

<?php }
if ($page == 'sales-reports.php' || $page == 'stock-in.php' || $page == 'inventory-reports.php' || $page == 'stock-out.php' || $page == 'product-list.php' || $page == 'categorie.php' || $page == 'product.php' || $page == 'events.php' || $page == 'add-instructor.php' || $page == 'add-member.php' || $page == 'employees-list.php' || $page == 'holidays.php' || $page == 'leaves.php' || $page == 'leaves-employee.php' || $page == 'leave-settings.php' || $page == 'attendance.php' || $page == 'attendance-employee.php' || $page == 'timesheet.php' || $page == 'shift-scheduling.php' || $page == 'shift-list.php' || $page == 'overtime.php' || $page == 'projects.php' || $page == 'project-list.php' || $page == 'project-view.php' || $page == 'tasks.php' || $page == 'task-board.php' || $page == 'tickets.php' || $page == 'estimates.php' || $page == 'create-estimate.php' || $page == 'edit-estimate.php' || $page == 'invoices.php' || $page == 'create-invoice.php' || $page == 'edit-invoice.php' || $page == 'expenses.php' || $page == 'categories.php' || $page == 'sub-category.php' || $page == 'budgets.php' || $page == 'budget-expenses.php' || $page == 'budget-revenues.php' || $page == 'salary.php' || $page == 'payroll-items.php' || $page == 'expense-reports.php' || $page == 'expense-reports.php' || $page == 'payments-reports.php' || $page == 'employee-reports.php' || $page == 'payslip-reports.php' || $page == 'leave-reports.php' || $page == 'daily-reports.php' || $page == 'performance-indicator.php' || $page == 'performance-appraisal.php' || $page == 'goal-tracking.php' || $page == 'training.php' || $page == 'promotion.php' || $page == 'resignation.php' || $page == 'termination.php' || $page == 'assets.php' || $page == 'job-details.php' || $page == 'jobs.php' || $page == 'job-applicants.php' || $page == 'manage-resumes.php' || $page == 'shortlist-candidates.php' || $page == 'interview-questions.php' || $page == 'offer_approvals.php' || $page == 'experiance-level.php' || $page == 'candidates.php' || $page == 'schedule-timing.php' || $page == 'aptitude-result.php' || $page == 'users.php' || $page == 'profile.php' || $page == 'components.php' || $page == 'add-instructor.php' || $page == 'add-member.php' || $page == 'create-membership-plan.php' || $page == 'create-payment-method.php') { ?>
  <!-- Datetimepicker JS -->
  <script src="assets/js/moment.min.js"></script>
  <script src="assets/js/bootstrap-datetimepicker.min.js"></script>

<?php }
if ($page == 'sales-reports.php' || $page == 'stock-in.php' || $page == 'inventory-reports.php' || $page == 'stock-out.php' || $page == 'product-list.php' || $page == 'categorie.php' || $page == 'product.php' || $page == 'events.php' || $page == 'employees.php' || $page == 'employees-list.php') { ?>
  <!-- Calendar JS -->
  <script src="assets/js/jquery-ui.min.js"></script>
  <script src="assets/js/fullcalendar.min.js"></script>
  <script src="assets/js/jquery.fullcalendar.js"></script>

<?php }
if ($page == 'sales-reports.php' || $page == 'stock-in.php' || $page == 'inventory-reports.php' || $page == 'stock-out.php' || $page == 'product-list.php' || $page == 'categorie.php' || $page == 'product.php' || $page == 'add-instructor.php' || $page == 'leaves.php' || $page == 'add-member.php' || $page == 'leaves-employee.php' || $page == 'departments.php' || $page == 'designations.php' || $page == 'timesheet.php' || $page == 'shift-scheduling.php' || $page == 'shift-list.php' || $page == 'overtime.php' || $page == 'clients-list.php' || $page == 'project-list.php' || $page == 'leads.php' || $page == 'tickets.php' || $page == 'payments.php' || $page == 'expenses.php' || $page == 'provident-fund.php' || $page == 'salary.php' || $page == 'payroll-items.php' || $page == 'policies.php' || $page == 'expense-reports.php' || $page == 'expense-reports.php' || $page == 'payments-reports.php' || $page == 'project-reports.php' || $page == 'task-reports.php' || $page == 'user-reports.php' || $page == 'employee-reports.php' || $page == 'payslip-reports.php' || $page == 'attendance-reports.php' || $page == 'leave-reports.php' || $page == 'daily-reports.php' || $page == 'performance-indicator.php' || $page == 'performance-appraisal.php' || $page == 'goal-tracking.php' || $page == 'goal-type.php' || $page == 'training.php' || $page == 'trainers.php' || $page == 'training-type.php' || $page == 'promotion.php' || $page == 'resignation.php' || $page == 'termination.php' || $page == 'assets.php' || $page == 'user-all-jobs.php' || $page == 'saved-jobs.php' || $page == 'applied-jobs.php' || $page == 'job-details.php' || $page == 'job-apptitude.php' || $page == 'questions.php' || $page == 'offered-jobs.php' || $page == 'visited-jobs.php' || $page == 'archived-jobs.php' || $page == 'jobs.php' || $page == 'job-applicants.php' || $page == 'manage-resumes.php' || $page == 'shortlist-candidates.php' || $page == 'interview-questions.php' || $page == 'offer_approvals.php' || $page == 'experiance-level.php' || $page == 'candidates.php' || $page == 'schedule-timing.php' || $page == 'aptitude-result.php' || $page == 'users.php' || $page == 'leave-type.php' || $page == 'subscribed-companies.php' || $page == 'data-tables.php') { ?>
  <!-- Datatable JS -->
  <script src="assets/js/jquery.dataTables.min.js"></script>
  <script src="assets/js/dataTables.bootstrap4.min.js"></script>

<?php }
if ($page == 'leave-settings.php') { ?>
  <!-- Multiselect JS -->
  <script src="assets/js/multiselect.min.js"></script>

<?php }
if ($page == 'client-profile.php' || $page == 'project-view.php' || $page == 'tasks.php') { ?>
  <!-- Task JS -->
  <script src="assets/js/task.js"></script>

<?php }
if ($page == 'projects.php' || $page == 'project-list.php' || $page == 'tasks.php') { ?>
  <!-- Ck Editor JS -->
  <script src="assets/js/ckeditor.js"></script>

<?php }
if ($page == 'user-dashboard.php' || $page == 'jobs-dashboard.php') { ?>
  <!-- Chart JS -->
  <script src="assets/js/Chart.min.js"></script>
  <script src="assets/js/line-chart.js"></script>

<?php }
if ($page == 'profile.php' || $page == 'components.php') { ?>
  <!-- Tagsinput JS -->
  <script src="assets/plugins/bootstrap-tagsinput/bootstrap-tagsinput.min.js"></script>

<?php }
if ($page == 'components.php') { ?>
  <!-- Sticky-kit -->
  <script src="assets/plugins/sticky-kit-master/dist/sticky-kit.min.js"></script>

<?php }
if ($page == 'form-mask.php') { ?>
  <!-- Mask JS -->
  <script src="assets/js/jquery.maskedinput.min.js"></script>
  <script src="assets/js/mask.js"></script>

<?php }
if ($page == 'form-validation.php') { ?>
  <!-- Form Validation JS -->
  <script src="assets/js/form-validation.js"></script>
<?php } ?>
<!-- Custom JS -->
<script src="assets/js/app.js"></script>
