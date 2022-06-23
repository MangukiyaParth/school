<?php
defined('BASEPATH') or exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
$route['default_controller'] = 'welcome';
$route['student/paper-summary/(:any)'] = 'Student/PaperSummary/$1';
$route['specialisation/specialisation-summary/(:any)'] = 'Specialisation/SpecialisationSummary/$1';
$route['percentage/percentage-wise-summary/(:any)'] = 'Percentage/PercentageWiseSummary/$1';
$route['percentage/semester-wise-summary'] = 'Percentage/SemesterWiseSummary';

$route['document/user-wise-document/(:any)'] = 'Document/UserWiseDocument/$1';

$route['reminders/reminder-list/(:any)'] = 'Reminders/ReminderList/$1';
$route['reminders/add-reminder/(:any)'] = 'Reminders/addReminder/$1';
$route['reminders/save-reminder'] = 'Reminders/saveReminder';
$route['reminders/delete-reminder/(:any)'] = 'Reminders/DeleteReminder/$1';

$route['assignstaff/list/(:any)'] = 'AssignStaff/AssignStaffList/$1';
$route['assignstaff/add/(:any)'] = 'AssignStaff/AddStaff/$1';
$route['assignstaff/save'] = 'AssignStaff/SaveStaffAssign';
$route['assignstaff/get-papers'] = 'AssignStaff/GetPapers';

$route['internalmarks/list/(:any)'] = 'InternalMarks/ListInternalMarks/$1';
$route['internalmarks/add/(:any)'] = 'InternalMarks/AddInternalMarks/$1';
$route['internalmarks/save'] = 'InternalMarks/SaveInternalMarks';
$route['internalmarks/get-papers'] = 'InternalMarks/GetPapers'; 
$route['internalmarks/paper-wise-student'] = 'InternalMarks/GetPaperWiseStudents';
$route['internalmarks/get-students'] = 'InternalMarks/GetStudents'; 
$route['internalmarks/download-excel'] = 'InternalMarks/downloadExcel'; 
$route['internalmarks/download-pdf'] = 'InternalMarks/downloadPDF'; 

$route['404_override'] = '';
$route['translate_uri_dashes'] = false;