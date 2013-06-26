--
--  Comment Meta Language Constructs:
--
--  #IfNotTable
--    argument: table_name
--    behavior: if the table_name does not exist,  the block will be executed

--  #IfTable
--    argument: table_name
--    behavior: if the table_name does exist, the block will be executed

--  #IfMissingColumn
--    arguments: table_name colname
--    behavior:  if the colname in the table_name table does not exist,  the block will be executed

--  #IfNotColumnType
--    arguments: table_name colname value
--    behavior:  If the table table_name does not have a column colname with a data type equal to value, then the block will be executed

--  #IfNotRow
--    arguments: table_name colname value
--    behavior:  If the table table_name does not have a row where colname = value, the block will be executed.

--  #IfNotRow2D
--    arguments: table_name colname value colname2 value2
--    behavior:  If the table table_name does not have a row where colname = value AND colname2 = value2, the block will be executed.

--  #IfNotRow3D
--    arguments: table_name colname value colname2 value2 colname3 value3
--    behavior:  If the table table_name does not have a row where colname = value AND colname2 = value2 AND colname3 = value3, the block will be executed.

--  #IfNotRow4D
--    arguments: table_name colname value colname2 value2 colname3 value3 colname4 value4
--    behavior:  If the table table_name does not have a row where colname = value AND colname2 = value2 AND colname3 = value3 AND colname4 = value4, the block will be executed.

--  #IfNotRow2Dx2
--    desc:      This is a very specialized function to allow adding items to the list_options table to avoid both redundant option_id and title in each element.
--    arguments: table_name colname value colname2 value2 colname3 value3
--    behavior:  The block will be executed if both statements below are true:
--               1) The table table_name does not have a row where colname = value AND colname2 = value2.
--               2) The table table_name does not have a row where colname = value AND colname3 = value3.

--  #IfNotIndex
--    desc:      This function will allow adding of indexes/keys.
--    arguments: table_name colname
--    behavior:  If the index does not exist, it will be created

--  #EndIf
--    all blocks are terminated with and #EndIf statement.

#IfNotTable menu_items
CREATE TABLE `menu_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `menu_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `title_safe` varchar(255) NOT NULL,
  `code` varchar(100) NOT NULL,
  `pdValue` int(11) NOT NULL,
  `content` varchar(255) NOT NULL,
  `parent_id` int(11) NOT NULL,
  `frame` varchar(100) NOT NULL,
  `lft` int(11) NOT NULL,
  `rgt` int(11) NOT NULL,
  `nlevel` int(11) NOT NULL,
  `sAccess` varchar(100) NOT NULL,
  `lAccess` varchar(100) NOT NULL,
  `published` tinyint(4) NOT NULL,
  `type` varchar(255) NOT NULL,
  `class_name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM;
INSERT INTO `menu_items` (`id`, `menu_id`, `title`, `title_safe`, `code`, `pdValue`, `content`, `parent_id`, `frame`, `lft`, `rgt`, `nlevel`, `sAccess`, `lAccess`, `published`, `type`, `class_name`) VALUES
(1, 1, 'Calendar', 'calendar', 'cal', 0, 'main/main_info.php', 0, 'RTop', 1, 2, 1, '', '', 0, '', ' '),
(2, 1, 'Messages', 'messages', 'msg', 0, 'main/messages/messages.php?form_active=1', 0, 'RBot', 3, 4, 1, '', '', 1, '', ''),
(3, 1, 'Check Lab Results', 'check-lab-results', 'lab', 0, 'orders/lab_exchange.php', 0, 'Rtop', 5, 6, 1, '', '', 0, '', ''),
(4, 1, 'Portal Activity', 'portal-activity', 'app', 0, '../myportal/index.php', 0, 'RTop', 7, 8, 1, '', '', 0, '', ''),
(5, 1, 'Patient/Client', 'patient-client', 'pat', 0, '#', 0, '', 9, 30, 1, '', '', 1, '', ''),
(6, 1, 'Patients', 'patients', 'fin', 0, 'main/finder/dynamic_finder.php', 5, 'RTop', 10, 11, 2, '', '', 1, '', ''),
(7, 1, 'New/Search', 'new-search', 'new', 0, 'new/new.php', 5, 'RTop', 12, 13, 2, '', '', 1, '', ''),
(8, 1, 'Summary', 'summary', 'dem', 1, 'patient_file/summary/demographics.php', 5, 'RTop', 14, 15, 2, '', '', 1, '', ''),
(9, 1, 'Visits', 'visits', '', 0, '#', 5, '', 16, 23, 2, '', '', 1, '', ''),
(10, 1, 'Create Visit', 'create-visit', 'nen', 1, 'forms/newpatient/new.php?autoloaded=1&calenc=', 9, 'RBot', 17, 18, 3, '', '', 1, '', ''),
(11, 1, 'Current', 'current', 'enc', 2, 'patient_file/encounter/encounter_top.php', 9, 'RBot', 19, 20, 3, '', '', 1, '', ''),
(12, 1, 'Visit History', 'visit-history', 'ens', 1, 'patient_file/history/encounters.php', 9, 'RBot', 21, 22, 3, '', '', 1, '', ''),
(13, 1, 'Records', 'records', '', 0, '#', 5, '', 24, 27, 2, '', '', 1, '', ''),
(14, 1, 'Patient Record Request', 'patient-record-request', 'prq', 1, 'patient_file/transaction/record_request.php', 13, 'RTop', 25, 26, 3, '', '', 1, '', ''),
(15, 1, 'Visit Form', 'visit-form', '', 0, '#', 5, '', 28, 29, 2, '', '', 1, '', ''),
(16, 1, 'Fees', 'fees', 'fee', 0, '#', 0, '', 31, 46, 1, '', '', 1, '', ''),
(17, 1, 'Fee Sheet', 'fee-sheet', 'cod', 2, 'patient_file/encounter/load_form.php?formname=fee_sheet', 16, 'RBot', 32, 33, 2, '', '', 1, '', ''),
(18, 1, 'Charges', 'charges', 'cod', 2, 'patient_file/encounter/encounter_bottom.php', 16, 'RBot', 34, 35, 2, '', '', 1, '', ''),
(19, 1, 'Payment', 'payment', 'pay', 1, 'patient_file/front_payment.php', 16, 'RBot', 36, 37, 2, '', '', 1, '', ''),
(20, 1, 'Checkout', 'checkout', 'bil', 1, 'patient_file/pos_checkout.php?framed=1', 16, 'RBot', 38, 39, 2, '', '', 1, '', ''),
(21, 1, 'Billing', 'billing', 'bil', 0, 'billing/billing_report.php', 16, 'RTop', 40, 41, 2, '', '', 1, '', ''),
(22, 1, 'Batch Payments', 'batch-payments', 'npa', 0, 'billing/new_payment.php', 16, 'RTop', 42, 43, 2, '', '', 1, '', ''),
(23, 1, 'EDI History', 'edi-history', 'edi', 0, 'billing/edih_view.php', 16, 'RTop', 44, 45, 2, 'acct', 'eob', 1, '', ''),
(24, 1, 'Inventory', 'inventory', 'inv', 0, '#', 0, '', 47, 52, 1, 'admin', 'drugs', 0, '', ''),
(25, 1, 'Management', 'management', 'adm', 0, 'drugs/drug_inventory.php', 24, 'RTop', 48, 49, 2, '', '', 1, '', ''),
(26, 1, 'Destroyed', 'destroyed', '', 0, 'destroyed_drugs_report.php', 24, '', 50, 51, 2, '', '', 1, '', ''),
(27, 1, 'Procedures', 'procedures', 'pro', 0, '#', 0, '', 53, 68, 1, '', '', 1, '', ''),
(28, 1, 'Providers', 'providers', 'orl', 0, 'orders/procedure_provider_list.php', 27, 'RTop', 54, 55, 2, '', '', 1, '', ''),
(29, 1, 'Configuration', 'configuration', 'ort', 0, 'orders/types.php', 27, 'RTop', 56, 57, 2, '', '', 1, '', ''),
(30, 1, 'Load Compendium', 'load-compendium', 'orc', 0, 'orders/load_compendium.php', 27, 'RTop', 58, 59, 2, '', '', 1, '', ''),
(31, 1, 'Pending Review', 'pending-review', 'orp', 1, 'orders/orders_results.php?review=1', 27, 'RTop', 60, 61, 2, '', '', 1, '', ''),
(32, 1, 'Patient Results', 'patient-results', 'orr', 1, 'orders/orders_results.php', 27, 'RTop', 62, 63, 2, '', '', 1, '', ''),
(33, 1, 'Batch Results', 'batch-results', 'orb', 0, 'orders/orders_results.php?batch=1', 27, 'RTop', 64, 65, 2, '', '', 1, '', ''),
(34, 1, 'Electronic Reports', 'electronic-reports', 'ore', 0, 'orders/list_reports.php', 27, 'RTop', 66, 67, 2, '', '', 1, '', ''),
(35, 1, 'New Crop', 'new-crop', '', 0, '#', 0, '', 69, 74, 1, '', '', 0, '', ''),
(36, 1, 'e-Rx', 'e-rx', 'erx', 1, 'eRx.php', 35, 'RTop', 70, 71, 2, '', '', 1, '', ''),
(37, 1, 'e-Rx Renewal', 'e-rx-renewal', 'err', 0, 'eRx.php?page=status', 35, 'RTop', 72, 73, 2, '', '', 1, '', ''),
(38, 1, 'Administration', 'administration', 'adm', 0, '#', 0, '', 75, 132, 1, 'admin', 'super', 1, '', ''),
(39, 1, 'Globals', 'globals', 'adm', 0, 'super/edit_globals.php', 38, 'RTop', 76, 77, 2, 'admin', 'super', 1, '', ''),
(40, 1, 'Facilities', 'facilities', 'adm', 0, 'usergroup/facilities.php', 38, 'RTop', 78, 79, 2, 'admin', 'users', 1, '', ''),
(41, 1, 'Users', 'users', 'adm', 0, 'usergroup/usergroup_admin.php', 38, 'RTop', 80, 81, 2, 'admin', 'users', 1, '', ''),
(42, 1, 'Address Book', 'address-book', 'adm', 0, 'usergroup/addrbook_list.php', 38, 'RTop', 82, 83, 2, 'admin', 'practice', 1, '', ''),
(43, 1, 'Practice', 'practice', 'adm', 0, '../controller.php?practice_settings&pharmacy&action=list', 38, 'RTop', 84, 85, 2, 'admin', 'practice', 1, '', ''),
(44, 1, 'Codes', 'codes', 'sup', 0, 'patient_file/encounter/superbill_custom_full.php', 38, 'RTop', 86, 87, 2, 'admin', 'superbill ', 1, '', ''),
(45, 1, 'Menu', 'menu', 'adm', 0, 'super/edit_menu.php', 38, 'RTop', 88, 89, 2, 'admin', 'super', 1, '', ''),
(46, 1, 'Layouts', 'layouts', 'adm', 0, 'super/edit_layout.php', 38, 'RTop', 90, 91, 2, 'admin', 'super', 1, '', ''),
(47, 1, 'Lists', 'lists', 'adm', 0, 'super/edit_list.php', 38, 'RTop', 92, 93, 2, 'admin', 'super', 1, '', ''),
(48, 1, 'ACL', 'acl', 'adm', 0, 'usergroup/adminacl.php', 38, 'RTop', 94, 95, 2, 'admin', 'acl', 1, '', ''),
(49, 1, 'Files', 'files', 'adm', 0, 'super/manage_site_files.php', 38, 'RTop', 96, 97, 2, 'admin', 'super', 1, '', ''),
(50, 1, 'Backup', 'backup', 'adm', 0, 'main/backup.php', 38, 'RTop', 98, 99, 2, 'admin', 'super', 1, '', ''),
(51, 1, 'Rules', 'rules', 'adm', 0, 'super/rules/index.php?action=browse!list', 38, 'RTop', 100, 101, 2, 'admin', 'super', 1, '', ''),
(52, 1, 'Alerts', 'alerts', 'adm', 0, 'super/rules/index.php?action=alerts!listactmgr', 38, 'RTop', 102, 103, 2, 'admin', 'super', 1, '', ''),
(53, 1, 'Patient Reminders', 'patient-reminders', 'adm', 0, 'patient_file/reminder/patient_reminders.php?mode=admin&patient_id=', 38, 'RTop', 104, 105, 2, 'admin', 'super', 1, '', ''),
(54, 1, 'De Identification', 'de-identification', 'adm', 0, 'de_identification_forms/de_identification_screen1.php', 38, 'RTop', 106, 107, 2, 'admin', 'super', 1, '', ''),
(55, 1, 'Re Identification', 're-identification', 'adm', 0, 'de_identification_forms/re_identification_input_screen.php', 38, 'RTop', 108, 109, 2, 'admin', 'super', 1, '', ''),
(56, 1, 'Export', 'export', 'adm', 0, 'main/ippf_export.php', 38, 'RTop', 110, 111, 2, 'admin', 'super', 1, '', ''),
(57, 1, 'Other', 'other', '', 0, '#', 38, '', 112, 131, 2, 'admin', 'super', 1, '', ''),
(58, 1, 'Language', 'language', 'adm', 0, 'language/language.php', 57, 'RTop', 113, 114, 3, 'admin', 'language', 1, '', ''),
(59, 1, 'Forms', 'forms', 'adm', 0, 'forms_admin/forms_admin.php', 57, 'RTop', 115, 116, 3, 'admin', 'forms', 1, '', ''),
(60, 1, 'Calendar', 'calendar', 'adm', 0, 'main/calendar/index.php?module=PostCalendar&type=admin&func=modifyconfig', 57, 'RTop', 117, 118, 3, 'admin', 'calendar', 1, '', ''),
(61, 1, 'Logs', 'logs', 'adm', 0, 'logview/logview.php', 57, 'RTop', 119, 120, 3, 'admin', 'users', 1, '', ''),
(62, 1, 'eRx Logs', 'erx-logs', 'adm', 0, 'logview/erx_logview.php', 57, 'RTop', 121, 122, 3, 'admin', 'users', 1, '', ''),
(63, 1, 'Database', 'database', 'adm', 0, '../phpmyadmin/index.php', 57, 'RTop', 123, 124, 3, 'admin', 'database', 1, '', ''),
(64, 1, 'Certificates', 'certificates', 'adm', 0, 'usergroup/ssl_certificates_admin.php', 57, 'RTop', 125, 126, 3, 'admin', 'users', 1, '', ''),
(65, 1, 'External Data Loads', 'external-data-loads', 'adm', 0, '../interface/code_systems/dataloads_ajax.php', 57, 'RTop', 127, 128, 3, 'admin', 'super', 1, '', ''),
(66, 1, 'Merge Patients', 'merge-patients', 'adm', 0, 'patient_file/merge_patients.php', 57, 'RTop', 129, 130, 3, 'admin', 'super', 1, '', ''),
(67, 1, 'Reports', 'reports', 'rep', 0, '#', 0, '', 133, 244, 1, '', '', 1, '', ''),
(68, 1, 'Clients', 'clients', '', 0, '#', 67, '', 134, 145, 2, '', '', 1, '', ''),
(69, 1, 'List', 'list', 'rep', 0, 'reports/patient_list.php', 68, 'RTop', 135, 136, 3, '', '', 1, '', ''),
(70, 1, 'Rx', 'rx', 'rep', 0, 'reports/prescriptions_report.php', 68, 'RTop', 137, 138, 3, 'patients', 'med', 1, '', ''),
(71, 1, 'Clinical', 'clinical', 'rep', 0, 'reports/clinical_reports.php', 68, 'RTop', 139, 140, 3, 'patients', 'med', 1, '', ''),
(72, 1, 'Referrals', 'referrals', 'rep', 0, 'reports/referrals_report.php', 68, 'RTop', 141, 142, 3, '', '', 1, '', ''),
(73, 1, 'Immunization Registry', 'immunization-registry', 'rep', 0, 'reports/immunization_report.php', 68, 'RTop', 143, 144, 3, '', '', 1, '', ''),
(74, 1, 'Clinic', 'clinic', '', 0, '#', 67, '', 146, 157, 2, '', '', 1, '', ''),
(75, 1, 'Report Results', 'report-results', 'rep', 0, 'reports/report_results.php', 74, 'RTop', 147, 148, 3, '', '', 1, '', ''),
(76, 1, 'Standard Measures', 'standard-measures', 'rep', 0, 'reports/cqm.php?type=standard', 74, 'RTop', 149, 150, 3, '', '', 1, '', ''),
(77, 1, 'Quality Measures (CQM)', 'quality-measures-cqm', 'rep', 0, 'reports/cqm.php?type=cqm', 74, 'RTop', 151, 152, 3, '', '', 1, '', ''),
(78, 1, 'Automated Measures (AMC)', 'automated-measures-amc', 'rep', 0, 'reports/cqm.php?type=amc', 74, 'RTop', 153, 154, 3, '', '', 1, '', ''),
(79, 1, 'AMC Tracking', 'amc-tracking', 'rep', 0, 'reports/amc_tracking.php', 74, 'RTop', 155, 156, 3, '', '', 1, '', ''),
(80, 1, 'Visits', 'visits', '', 0, '#', 67, '', 158, 179, 2, '', '', 1, '', ''),
(81, 1, 'Appointments', 'appointments', 'rep', 0, 'reports/appointments_report.php', 80, 'RTop', 159, 160, 3, '', '', 1, '', ''),
(82, 1, 'Encounters', 'encounters', 'rep', 0, 'reports/encounters_report.php', 80, 'RTop', 161, 162, 3, '', '', 1, '', ''),
(83, 1, 'Appt-Enc', 'appt-enc', 'rep', 0, 'reports/appt_encounter_report.php', 80, 'RTop', 163, 164, 3, '', '', 1, '', ''),
(84, 1, 'Superbill', 'superbill', 'rep', 0, 'reports/custom_report_range.php', 80, 'RTop', 165, 166, 3, '', '', 1, '', ''),
(85, 1, 'Eligibility', 'eligibility', 'rep', 0, 'reports/edi_270.php', 80, 'RTop', 167, 168, 3, '', '', 1, '', ''),
(86, 1, 'Eligibility Response', 'eligibility-response', 'rep', 0, 'reports/edi_271.php', 80, 'RTop', 169, 170, 3, '', '', 1, '', ''),
(87, 1, 'Chart Activity', 'chart-activity', 'rep', 0, 'reports/chart_location_activity.php', 80, 'RTop', 171, 172, 3, '', '', 1, '', ''),
(88, 1, 'Charts Out', 'charts-out', 'rep', 0, 'reports/charts_checked_out.php', 80, 'RTop', 173, 174, 3, '', '', 1, '', ''),
(89, 1, 'Services', 'services', 'rep', 0, 'reports/services_by_category.php', 80, 'RTop', 175, 176, 3, '', '', 1, '', ''),
(90, 1, 'Syndromic Surveillance', 'syndromic-surveillance', 'rep', 0, 'reports/non_reported.php', 80, 'RTop', 177, 178, 3, '', '', 1, '', ''),
(91, 1, 'Financial', 'financial', '', 0, '#', 67, '', 180, 193, 2, 'acct', 'rep_a', 1, '', ''),
(92, 1, 'Sales', 'sales', 'rep', 0, 'reports/sales_by_item.php', 91, 'RTop', 181, 182, 3, '', '', 1, '', ''),
(93, 1, 'Cash Rec', 'cash-rec', 'rep', 0, 'billing/sl_receipts_report.php', 91, 'RTop', 183, 184, 3, '', '', 1, '', ''),
(94, 1, 'Front Rec', 'front-rec', 'rep', 0, 'reports/front_receipts_report.php', 91, 'RTop', 185, 186, 3, '', '', 1, '', ''),
(95, 1, 'Pmt Method', 'pmt-method', 'rep', 0, 'reports/receipts_by_method_report.php', 91, 'RTop', 187, 188, 3, '', '', 1, '', ''),
(96, 1, 'Collections', 'collections', 'rep', 0, 'reports/collections_report.php', 91, 'RTop', 189, 190, 3, '', '', 1, '', ''),
(97, 1, 'Financial Summary by Service Code', 'financial-summary-by-service-code', 'rep', 0, 'reports/svc_code_financial_report.php', 91, 'RTop', 191, 192, 3, '', '', 1, '', ''),
(98, 1, 'Inventory', 'inventory', '', 0, '#', 67, '', 194, 201, 2, '', '', 1, '', ''),
(99, 1, 'List', 'list', 'rep', 0, 'reports/inventory_list.php', 98, 'RTop', 195, 196, 3, '', '', 1, '', ''),
(100, 1, 'Activity', 'activity', 'rep', 0, 'reports/inventory_activity.php', 98, 'RTop', 197, 198, 3, '', '', 1, '', ''),
(101, 1, 'Transactions', 'transactions', 'rep', 0, 'reports/inventory_transactions.php', 98, 'RTop', 199, 200, 3, '', '', 1, '', ''),
(102, 1, 'Procedures', 'procedures', '', 0, '#', 67, '', 202, 209, 2, '', '', 1, '', ''),
(103, 1, 'Pending Res', 'pending-res', '', 0, '../orders/pending_orders.php', 102, '', 203, 204, 3, '', '', 1, '', ''),
(104, 1, 'Pending F/U', 'pending-f-u', '', 0, '../orders/pending_followup.php', 102, '', 205, 206, 3, '', '', 1, '', ''),
(105, 1, 'Statistics', 'statistics', '', 0, '../orders/procedure_stats.php', 102, '', 207, 208, 3, '', '', 1, '', ''),
(106, 1, 'Insurance', 'insurance', '', 0, '#', 67, '', 210, 217, 2, '', '', 1, '', ''),
(107, 1, 'Distribution', 'distribution', 'rep', 0, 'reports/insurance_allocation_report.php', 106, 'RTop', 211, 212, 3, '', '', 1, '', ''),
(108, 1, 'Indigents', 'indigents', 'rep', 0, 'billing/indigent_patients_report.php', 106, 'RTop', 213, 214, 3, '', '', 1, '', ''),
(109, 1, 'Unique SP', 'unique-sp', 'rep', 0, 'reports/unique_seen_patients_report.php', 106, 'RTop', 215, 216, 3, '', '', 1, '', ''),
(110, 1, 'Statistics', 'statistics', '', 0, '#', 67, '', 218, 229, 2, '', '', 1, '', ''),
(111, 1, 'IPPF Stats', 'ippf-stats', '', 0, 'ippf_statistics.php?t=i', 110, '', 219, 220, 3, '', '', 1, '', ''),
(112, 1, 'GCAC Stats', 'gcac-stats', '', 0, 'ippf_statistics.php?t=g', 110, '', 221, 222, 3, '', '', 1, '', ''),
(113, 1, 'MA Stats', 'ma-stats', '', 0, 'ippf_statistics.php?t=m', 110, '', 223, 224, 3, '', '', 1, '', ''),
(114, 1, 'CYP', 'cyp', '', 0, 'ippf_cyp_report.php', 110, '', 225, 226, 3, '', '', 1, '', ''),
(115, 1, 'Daily Record', 'daily-record', '', 0, 'ippf_daily.php', 110, '', 227, 228, 3, '', '', 1, '', ''),
(116, 1, 'Blank Forms', 'blank-forms', '', 0, '#', 67, '', 230, 237, 2, '', '', 1, '', ''),
(117, 1, 'Demographics', 'demographics', '', 0, '../patient_file/summary/demographics_print.php', 116, '', 231, 232, 3, '', '', 1, '', ''),
(118, 1, 'Superbill/Fee Sheet', 'superbill-fee-sheet', '', 0, '../patient_file/printed_fee_sheet.php', 116, '', 233, 234, 3, '', '', 1, '', ''),
(119, 1, 'Referral', 'referral', '', 0, '../patient_file/transaction/print_referral.php', 116, '', 235, 236, 3, '', '', 1, '', ''),
(120, 1, 'Services', 'services', '', 0, '#', 67, '', 238, 243, 2, 'admin', 'super', 1, '', ''),
(121, 1, 'Background Services', 'background-services', 'rep', 0, 'reports/background_services.php', 120, 'RTop', 239, 240, 3, '', '', 1, '', ''),
(122, 1, 'Direct Message Log', 'direct-message-log', 'rep', 0, 'reports/direct_message_log.php', 120, 'RTop', 241, 242, 3, '', '', 1, '', ''),
(123, 1, 'Miscellaneous', 'miscellaneous', 'mis', 0, '#', 0, '', 245, 268, 1, '', '', 1, '', ''),
(124, 1, 'Patient Education', 'patient-education', 'ped', 0, 'reports/patient_edu_web_lookup.php', 123, 'RTop', 246, 247, 2, '', '', 1, '', ''),
(125, 1, 'Authorizations', 'authorizations', 'aun', 0, 'main/authorizations/authorizations.php', 123, 'RTop', 248, 249, 2, '', '', 1, '', ''),
(126, 1, 'Fax/Scan', 'fax-scan', 'fax', 0, 'fax/faxq.php', 123, 'RTop', 250, 251, 2, '', '', 1, '', ''),
(127, 1, 'Addr Book', 'addr-book', 'adb', 0, 'usergroup/addrbook_list.php', 123, 'RTop', 252, 253, 2, '', '', 1, '', ''),
(128, 1, 'Order Catalog', 'order-catalog', 'ort', 0, 'orders/types.php', 123, 'RTop', 254, 255, 2, '', '', 1, '', ''),
(129, 1, 'Chart Tracker', 'chart-tracker', 'cht', 0, '../custom/chart_tracker.php', 123, 'RTop', 256, 257, 2, '', '', 1, '', ''),
(130, 1, 'Ofc Notes', 'ofc-notes', 'ono', 0, 'main/onotes/office_comments.php', 123, 'RTop', 258, 259, 2, '', '', 1, '', ''),
(131, 1, 'BatchCom', 'batchcom', 'adm', 0, 'usergroup/admin_frameset.php', 123, 'RTop', 260, 261, 2, '', '', 1, '', ''),
(132, 1, 'Password', 'password', 'pwd', 0, 'usergroup/user_info.php', 123, 'RTop', 262, 263, 2, '', '', 1, '', ''),
(133, 1, 'Preferences', 'preferences', 'prf', 0, 'super/edit_globals.php?mode=user', 123, 'RTop', 264, 265, 2, '', '', 1, '', ''),
(134, 1, 'New Documents', 'new-documents', 'adm', 0, '../controller.php?document&list&patient_id=00', 123, 'RTop', 266, 267, 2, 'patients', 'docs', 1, '', '');
#EndIf



#IfNotTable menus
CREATE TABLE `menus` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `menu_name` varchar(255) NOT NULL,
  `safe_name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 ;
INSERT INTO `menus` (`id`, `menu_name`, `safe_name`) VALUES
(1, 'Main Menu', 'main_menu');
#EndIf