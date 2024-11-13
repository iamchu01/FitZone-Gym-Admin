<?php
$directoryURI = $_SERVER['REQUEST_URI'];
$path = parse_url($directoryURI, PHP_URL_PATH);
$components = explode('/', $path);
$page = $components[2];
?>
<!-- Bootstrap CSS -->
<link rel="stylesheet" href="assets/css/bootstrap.min.css">

<!-- Fontawesome CSS -->
<link rel="stylesheet" href="assets/plugins/fontawesome/css/fontawesome.min.css">
<link rel="stylesheet" href="assets/plugins/fontawesome/css/all.min.css">

<!-- Lineawesome CSS -->
<link rel="stylesheet" href="assets/css/line-awesome.min.css">
<link rel="stylesheet" href="assets/css/material.css">

<!--JQuery-->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<script src="ph-address-selector.js"></script>


<!-- Fontawesome CSS -->
<link rel="stylesheet" href="assets/css/font-awesome.min.css">

<?php if ($page == 'admin-dashboard.php') { ?>
  <!-- Chart CSS -->
  <link rel="stylesheet" href="assets/plugins/morris/morris.css">

<?php }
if ($page == 'events.php' || $page == 'add-instructor.php' || $page == 'add-member.php' || $page == 'employees-list.php' || $page == 'leaves.php' || $page == 'leaves-employee.php' || $page == 'leave-settings.php' || $page == 'attendance.php' || $page == 'attendance-employee.php' || $page == 'designations.php' || $page == 'timesheet.php' || $page == 'shift-scheduling.php' || $page == 'shift-list.php' || $page == 'overtime.php' || $page == 'clients.php' || $page == 'clients-list.php' || $page == 'client-profile.php' || $page == 'projects.php' || $page == 'project-list.php' || $page == 'project-view.php' || $page == 'tasks.php' || $page == 'task-board.php' || $page == 'tickets.php' || $page == 'ticket-view.php' || $page == 'estimates.php' || $page == 'create-estimate.php' || $page == 'edit-estimate.php' || $page == 'invoices.php' || $page == 'create-invoice.php' || $page == 'edit-invoice.php' || $page == 'expenses.php' || $page == 'provident-fund.php' || $page == 'taxes.php' || $page == 'salary.php' || $page == 'payroll-items.php' || $page == 'policies.php' || $page == 'expense-reports.php' || $page == 'invoice-reports.php' || $page == 'payments-reports.php' || $page == 'project-reports.php' || $page == 'task-reports.php' || $page == 'user-reports.php' || $page == 'employee-reports.php' || $page == 'payslip-reports.php' || $page == 'attendance-reports.php' || $page == 'leave-reports.php' || $page == 'daily-reports.php' || $page == 'performance-indicator.php' || $page == 'performance-indicator.php' || $page == 'performance-appraisal.php' || $page == 'goal-tracking.php' || $page == 'goal-type.php' || $page == 'training.php' || $page == 'trainers.php' || $page == 'training-type.php' || $page == 'promotion.php' || $page == 'resignation.php' || $page == 'termination.php' || $page == 'assets.php' || $page == 'user-all-jobs.php' || $page == 'saved-jobs.php' || $page == 'applied-jobs.php' || $page == 'job-details.php' || $page == 'job-apptitude.php' || $page == 'questions.php' || $page == 'offered-jobs.php' || $page == 'visited-jobs.php' || $page == 'archived-jobs.php' || $page == 'jobs.php' || $page == 'job-applicants.php' || $page == 'manage-resumes.php' || $page == 'shortlist-candidates.php' || $page == 'interview-questions.php' || $page == 'offer_approvals.php' || $page == 'experiance-level.php' || $page == 'candidates.php' || $page == 'schedule-timing.php' || $page == 'aptitude-result.php' || $page == 'users.php' || $page == 'settings.php' || $page == 'localization.php' || $page == 'email-settings.php' || $page == 'performance-setting.php' || $page == 'approval-setting.php' || $page == 'toxbox-setting.php' || $page == 'cron-setting.php' || $page == 'profile.php' || $page == 'subscriptions.php' || $page == 'subscribed-companies.php' || $page == 'components.php' || $page == 'form-horizontal.php' || $page == 'form-vertical.php' || $page == 'add-instructor.php' || $page == 'add-member.php' || $page == 'create-membership-plan.php' || $page == 'create-payment-method.php') { ?>
  <!-- Select2 CSS -->
  <link rel="stylesheet" href="assets/css/select2.min.css">

<?php }
if ($page == 'events.php' || $page == 'add-instructor.php' || $page == 'add-member.php' || $page == 'employees-list.php' || $page == 'holidays.php' || $page == 'leaves.php' || $page == 'leaves-employee.php' || $page == 'leave-settings.php' || $page == 'attendance.php' || $page == 'attendance-employee.php' || $page == 'timesheet.php' || $page == 'shift-scheduling.php' || $page == 'shift-list.php' || $page == 'overtime.php' || $page == 'clients.php' || $page == 'projects.php' || $page == 'project-list.php' || $page == 'project-view.php' || $page == 'tasks.php' || $page == 'task-board.php' || $page == 'tickets.php' || $page == 'estimates.php' || $page == 'create-estimate.php' || $page == 'edit-estimate.php' || $page == 'invoices.php' || $page == 'create-invoice.php' || $page == 'edit-invoice.php' || $page == 'expenses.php' || $page == 'categories.php' || $page == 'sub-category.php' || $page == 'budgets.php' || $page == 'budget-expenses.php' || $page == 'budget-revenues.php' || $page == 'salary.php' || $page == 'payroll-items.php' || $page == 'expense-reports.php' || $page == 'invoice-reports.php' || $page == 'payments-reports.php' || $page == 'employee-reports.php' || $page == 'payslip-reports.php' || $page == 'leave-reports.php' || $page == 'daily-reports.php' || $page == 'performance-indicator.php' || $page == 'performance-appraisal.php' || $page == 'goal-tracking.php' || $page == 'training.php' || $page == 'promotion.php' || $page == 'resignation.php' || $page == 'termination.php' || $page == 'assets.php' || $page == 'job-details.php' || $page == 'jobs.php' || $page == 'job-applicants.php' || $page == 'manage-resumes.php' || $page == 'shortlist-candidates.php' || $page == 'interview-questions.php' || $page == 'offer_approvals.php' || $page == 'experiance-level.php' || $page == 'candidates.php' || $page == 'schedule-timing.php' || $page == 'aptitude-result.php' || $page == 'users.php' || $page == 'profile.php' || $page == 'components.php' || $page == 'add-instructor.php' || $page == 'add-member.php' || $page == 'create-membership-plan.php' || $page == 'create-payment-method.php') { ?>
  <!-- Datetimepicker CSS -->
  <link rel="stylesheet" href="assets/css/bootstrap-datetimepicker.min.css">

<?php }
if ($page == 'events.php' || $page == 'create-membership-plan.php') { ?>
  <!-- Calendar CSS -->
  <link rel="stylesheet" href="assets/css/fullcalendar.min.css">

<?php }
if ($page == 'add-instructor.php' || $page == 'leaves.php' || $page == 'add-member.php' || $page == 'leaves-employee.php' || $page == 'departments.php' || $page == 'designations.php' || $page == 'timesheet.php' || $page == 'shift-scheduling.php' || $page == 'shift-list.php' || $page == 'overtime.php' || $page == 'clients.php' || $page == 'clients-list.php' || $page == 'project-list.php' || $page == 'leads.php' || $page == 'tickets.php' || $page == 'payments.php' || $page == 'expenses.php' || $page == 'provident-fund.php' || $page == 'salary.php' || $page == 'payroll-items.php' || $page == 'policies.php' || $page == 'expense-reports.php' || $page == 'invoice-reports.php' || $page == 'payments-reports.php' || $page == 'project-reports.php' || $page == 'task-reports.php' || $page == 'user-reports.php' || $page == 'employee-reports.php' || $page == 'payslip-reports.php' || $page == 'attendance-reports.php' || $page == 'leave-reports.php' || $page == 'daily-reports.php' || $page == 'performance-indicator.php' || $page == 'performance-appraisal.php' || $page == 'goal-tracking.php' || $page == 'goal-type.php' || $page == 'training.php' || $page == 'trainers.php' || $page == 'training-type.php' || $page == 'promotion.php' || $page == 'resignation.php' || $page == 'termination.php' || $page == 'assets.php' || $page == 'user-all-jobs.php' || $page == 'saved-jobs.php' || $page == 'applied-jobs.php' || $page == 'job-details.php' || $page == 'job-details.php' || $page == 'job-apptitude.php' || $page == 'questions.php' || $page == 'offered-jobs.php' || $page == 'visited-jobs.php' || $page == 'archived-jobs.php' || $page == 'jobs.php' || $page == 'job-applicants.php' || $page == 'manage-resumes.php' || $page == 'shortlist-candidates.php' || $page == 'interview-questions.php' || $page == 'offer_approvals.php' || $page == 'experiance-level.php' || $page == 'candidates.php' || $page == 'schedule-timing.php' || $page == 'aptitude-result.php' || $page == 'users.php' || $page == 'leave-type.php' || $page == 'subscribed-companies.php' || $page == 'data-tables.php' || $page == 'add-instructor.php' || $page == 'add-member.php' || $page == 'data-tables.php' || $page == 'create-membership-plan.php' || $page == 'create-payment-method.php') { ?>
  <!-- Datatable CSS -->
  <link rel="stylesheet" href="assets/css/dataTables.bootstrap4.min.css">

<?php }
if ($page == 'leave-settings.php' || $page == 'profile.php' || $page == 'components.php' || $page == 'create-membership-plan.php') { ?>
  <!-- Tagsinput CSS -->
  <link rel="stylesheet" href="assets/plugins/bootstrap-tagsinput/bootstrap-tagsinput.css">

<?php }
if ($page == 'projects.php' || $page == 'project-list.php' || $page == 'tasks.php') { ?>
  <!-- Ck Editor -->
  <link rel="stylesheet" href="assets/css/ckeditor.css">

<?php }
if ($page == 'profile.php' || $page == 'components.php') { ?>
  <!-- Tagsinput CSS -->
  <link rel="stylesheet" href="assets/plugins/bootstrap-tagsinput/bootstrap-tagsinput.css">
<?php } ?>
<!-- Main CSS -->
<link rel="stylesheet" href="assets/css/style.css">


<!-- Bootstrap Icons CDN for search icon -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

<!-- Bootstrap Icons CDN for the dropdown icon -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
