-- MegaForce Management System Database Backup
-- Database: sams_db
-- Generated: August 22, 2026 03:31 PM
-- ---------------------------------------------

-- -------------------------------------
-- Table: attendance
-- -------------------------------------

DROP TABLE IF EXISTS `attendance`;
CREATE TABLE `attendance` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `guard_id` int(11) NOT NULL,
  `attendance_date` date NOT NULL,
  `time_in` time DEFAULT NULL,
  `time_out` time DEFAULT NULL,
  `status` enum('Present','Late','Absent','Leave') DEFAULT 'Present',
  `remarks` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `guard_id` (`guard_id`),
  CONSTRAINT `attendance_ibfk_1` FOREIGN KEY (`guard_id`) REFERENCES `guards` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `attendance` VALUES ('1', '20', '2026-08-05', '06:40:00', '17:03:00', 'Present', 'early Burgman but useless', '2026-08-05 16:47:36');
INSERT INTO `attendance` VALUES ('2', '17', '2026-08-06', '08:30:00', '17:02:00', 'Present', 'Holiday', '2026-08-06 08:31:36');
INSERT INTO `attendance` VALUES ('3', '22', '2026-08-10', '00:00:00', '00:00:00', 'Present', '', '2026-08-10 09:56:36');

-- -------------------------------------
-- Table: audit_logs
-- -------------------------------------

DROP TABLE IF EXISTS `audit_logs`;
CREATE TABLE `audit_logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `action` varchar(255) NOT NULL,
  `module` varchar(100) NOT NULL,
  `details` text DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `audit_logs_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=182 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `audit_logs` VALUES ('1', '2', 'Added User', 'User Management', 'Created user: Changes123', '::1', '2026-08-04 15:42:36');
INSERT INTO `audit_logs` VALUES ('2', '2', 'Added User', 'User Management', 'Created user: Lion King', '::1', '2026-08-04 15:49:15');
INSERT INTO `audit_logs` VALUES ('3', '2', 'Added User', 'User Management', 'Created user: 1', '::1', '2026-08-04 15:52:06');
INSERT INTO `audit_logs` VALUES ('4', '2', 'Deleted User', 'User Management', 'Deleted user: ', '::1', '2026-08-04 16:06:10');
INSERT INTO `audit_logs` VALUES ('5', '2', 'Updated User', 'User Management', 'Updated user: Changes12', '::1', '2026-08-04 16:07:23');
INSERT INTO `audit_logs` VALUES ('6', '2', 'Deleted User', 'User Management', 'Deleted user: ', '::1', '2026-08-04 16:14:09');
INSERT INTO `audit_logs` VALUES ('7', '2', 'Added User', 'User Management', 'Created user: 4124', '::1', '2026-08-05 09:13:40');
INSERT INTO `audit_logs` VALUES ('8', '2', 'Deleted User', 'User Management', 'Deleted user: ', '::1', '2026-08-05 09:13:53');
INSERT INTO `audit_logs` VALUES ('9', '2', 'Updated User', 'User Management', 'Updated user: adsala', '::1', '2026-08-05 09:14:07');
INSERT INTO `audit_logs` VALUES ('10', '2', 'Added User', 'User Management', 'Created user: ', '::1', '2026-08-05 09:46:27');
INSERT INTO `audit_logs` VALUES ('11', '2', 'Added client', 'client Management', 'Created client: Jhun Casupanan', '::1', '2026-08-05 09:47:59');
INSERT INTO `audit_logs` VALUES ('12', '2', 'Added Detachment', 'Detachment Management', 'Created detachment: ate', '::1', '2026-08-05 09:50:46');
INSERT INTO `audit_logs` VALUES ('13', '2', 'Added guard', 'Guard Management', 'Created guard: Jhun Balanquit', '::1', '2026-08-05 09:52:45');
INSERT INTO `audit_logs` VALUES ('14', '2', 'Updated Client', 'Client Management', 'Updated client: Jhun Casupanan', '::1', '2026-08-05 09:57:26');
INSERT INTO `audit_logs` VALUES ('15', '2', 'Updated Guard', 'Guard Management', 'Updated guard: Jhun Balanquit', '::1', '2026-08-05 09:58:39');
INSERT INTO `audit_logs` VALUES ('16', '2', 'Updated Detachment', 'Detachment Management', 'Updated detachment: Ayala Mall', '::1', '2026-08-05 10:00:24');
INSERT INTO `audit_logs` VALUES ('17', '2', 'Deleted User', 'User Management', 'Deleted user: ', '::1', '2026-08-05 10:04:34');
INSERT INTO `audit_logs` VALUES ('18', '2', 'Deleted Client', 'Client Management', 'Deleted client: ', '::1', '2026-08-05 10:07:14');
INSERT INTO `audit_logs` VALUES ('19', '2', 'Deleted Guard', 'Guard Management', 'Deleted guard:  ', '::1', '2026-08-05 10:15:38');
INSERT INTO `audit_logs` VALUES ('20', '2', 'Added guard', 'Guard Management', 'Created guard: Jhun Balanquit', '::1', '2026-08-05 10:20:39');
INSERT INTO `audit_logs` VALUES ('21', '2', 'Deleted Detachment', 'Detachment Management', 'Deleted detachment: ', '::1', '2026-08-05 10:23:14');
INSERT INTO `audit_logs` VALUES ('22', '2', 'Deleted User', 'User Management', 'Deleted user: mother', '::1', '2026-08-05 10:32:28');
INSERT INTO `audit_logs` VALUES ('23', '2', 'Deleted Guard', 'Guard Management', 'Deleted guard: Jhun Balanquit', '::1', '2026-08-05 10:33:30');
INSERT INTO `audit_logs` VALUES ('24', '2', 'Deleted Detachment', 'Detachment Management', 'Deleted detachment: Garden Tower', '::1', '2026-08-05 10:34:29');
INSERT INTO `audit_logs` VALUES ('25', '2', 'Deleted User', 'User Management', 'Deleted user: Dong Asul', '::1', '2026-08-05 10:46:42');
INSERT INTO `audit_logs` VALUES ('26', '2', 'Deleted User', 'User Management', 'Deleted user: ', '::1', '2026-08-05 10:56:52');
INSERT INTO `audit_logs` VALUES ('27', '2', 'Deleted User', 'User Management', 'Deleted user: Mario Super', '::1', '2026-08-05 11:06:15');
INSERT INTO `audit_logs` VALUES ('28', '2', 'Updated Client', 'Client Management', 'Updated client: MEGAFORCE', '::1', '2026-08-05 11:12:16');
INSERT INTO `audit_logs` VALUES ('29', '2', 'Updated Detachment', 'Detachment Management', 'Updated detachment: Garden Tower', '::1', '2026-08-05 11:14:03');
INSERT INTO `audit_logs` VALUES ('30', '2', 'Deleted Client', 'Client Management', 'Deleted client: MEGAFORCE', '::1', '2026-08-05 11:14:10');
INSERT INTO `audit_logs` VALUES ('31', '8', 'Added client', 'client Management', 'Created client: MEGAFORCE', '::1', '2026-08-05 13:08:48');
INSERT INTO `audit_logs` VALUES ('32', '8', 'Added Detachment', 'Detachment Management', 'Created detachment: MONTE', '::1', '2026-08-05 13:12:53');
INSERT INTO `audit_logs` VALUES ('33', '8', 'Assigned Guard', 'Guard Management', 'Assigned guard: Mark Tahimik to detachment ID: 8', '::1', '2026-08-05 13:53:17');
INSERT INTO `audit_logs` VALUES ('34', '8', 'Assigned Guard', 'Guard Management', 'Assigned guard: Mark Tahimik to Garden Tower', '::1', '2026-08-05 14:03:39');
INSERT INTO `audit_logs` VALUES ('35', '8', 'Assigned Guard', 'Guard Management', 'Assigned guard: Jhun Balanquit to Garden Tower', '::1', '2026-08-05 14:06:48');
INSERT INTO `audit_logs` VALUES ('36', '8', 'Assigned Guard', 'Guard Management', 'Assigned guard: Lems LAmuan to AYALA HEIGHTS', '::1', '2026-08-05 14:06:54');
INSERT INTO `audit_logs` VALUES ('37', '8', 'Assigned Guard', 'Guard Management', 'Assigned guard: JOAN VALDEZ to Garden Tower', '::1', '2026-08-05 14:06:59');
INSERT INTO `audit_logs` VALUES ('38', '8', 'Assigned Guard', 'Guard Management', 'Assigned guard: Mark Tahimik to ', '::1', '2026-08-05 14:30:51');
INSERT INTO `audit_logs` VALUES ('39', '8', 'Assigned Guard', 'Guard Management', 'Assigned guard: Mark Tahimik to MONTE', '::1', '2026-08-05 14:44:59');
INSERT INTO `audit_logs` VALUES ('40', '8', 'Updated Detachment', 'Detachment Management', 'Updated detachment: MONTE', '::1', '2026-08-05 14:53:36');
INSERT INTO `audit_logs` VALUES ('41', '8', 'Assigned Guard', 'Guard Management', 'Assigned guard: Jhun Balanquit to ', '::1', '2026-08-05 15:00:43');
INSERT INTO `audit_logs` VALUES ('42', '8', 'Assigned Guard', 'Guard Management', 'Assigned guard: Mark Tahimik to AYALA HEIGHTS', '::1', '2026-08-05 15:01:11');
INSERT INTO `audit_logs` VALUES ('43', '8', 'Assigned Guard', 'Guard Management', 'Assigned guard: Jhun Balanquit to MONTE', '::1', '2026-08-05 15:01:46');
INSERT INTO `audit_logs` VALUES ('44', '8', 'Assigned Guard', 'Guard Management', 'Assigned guard: Mark Tahimik to MONTE', '::1', '2026-08-05 15:01:51');
INSERT INTO `audit_logs` VALUES ('45', '8', 'Assigned Guard', 'Guard Management', 'Assigned guard: Jhun Balanquit to MONTE', '::1', '2026-08-05 15:09:38');
INSERT INTO `audit_logs` VALUES ('46', '8', 'Updated Detachment', 'Detachment Management', 'Updated detachment: MONTE', '::1', '2026-08-05 15:15:29');
INSERT INTO `audit_logs` VALUES ('47', '8', 'Updated Detachment', 'Detachment Management', 'Updated detachment: FORCE TOWER', '::1', '2026-08-05 15:16:38');
INSERT INTO `audit_logs` VALUES ('48', '8', 'Assigned Guard', 'Guard Management', 'Assigned guard: Jhun Balanquit to FORCE TOWER', '::1', '2026-08-05 15:17:22');
INSERT INTO `audit_logs` VALUES ('49', '2', 'Updated User', 'User Management', 'Updated user: DONGSKIE SALUTA', '::1', '2026-08-05 15:21:59');
INSERT INTO `audit_logs` VALUES ('50', '2', 'Added Attendance', 'Attendance', 'Recorded attendance for Jhun Balanquit ', '::1', '2026-08-05 16:47:36');
INSERT INTO `audit_logs` VALUES ('51', '2', 'Added Attendance', 'Attendance', 'Recorded attendance for Lems LAmuan ', '::1', '2026-08-06 08:31:36');
INSERT INTO `audit_logs` VALUES ('52', '2', 'Geberal Payroll', 'Payroll', 'Generated payroll for Jhun Balanquit ', '::1', '2026-08-06 15:21:44');
INSERT INTO `audit_logs` VALUES ('53', '2', 'Geberal Payroll', 'Payroll', 'Generated payroll for Lems LAmuan ', '::1', '2026-08-06 15:26:03');
INSERT INTO `audit_logs` VALUES ('54', '2', 'Updated User', 'User Management', 'Updated user: DONGSKIE SALUTA', '::1', '2026-08-07 09:16:57');
INSERT INTO `audit_logs` VALUES ('55', '2', 'Updated Payroll', 'Payroll', 'Updated payroll for Lems LAmuan', '::1', '2026-08-07 11:01:10');
INSERT INTO `audit_logs` VALUES ('56', '2', 'Assigned Guard', 'Guard Management', 'Assigned guard: Lems Lamuan to FORCE TOWER', '::1', '2026-08-07 11:02:06');
INSERT INTO `audit_logs` VALUES ('57', '2', 'General Payroll', 'Payroll', 'Generated payroll for JOAN VALDEZ ', '::1', '2026-08-07 11:03:12');
INSERT INTO `audit_logs` VALUES ('58', '2', 'DELETED payroll', 'Payroll', 'Deleted payroll forJOAN VALDEZ', '::1', '2026-08-08 13:40:52');
INSERT INTO `audit_logs` VALUES ('59', '2', 'General Payroll', 'Payroll', 'Generated payroll for Mark Tahimik ', '::1', '2026-08-08 14:04:38');
INSERT INTO `audit_logs` VALUES ('60', '2', 'General Payroll', 'Payroll', 'Generated payroll for JOAN VALDEZ ', '::1', '2026-08-08 14:05:05');
INSERT INTO `audit_logs` VALUES ('61', '2', 'Updated Payroll', 'Payroll', 'Updated payroll for JOAN VALDEZ', '::1', '2026-08-08 14:05:33');
INSERT INTO `audit_logs` VALUES ('62', '2', 'DELETED payroll', 'Payroll', 'Deleted payroll forJOAN VALDEZ', '::1', '2026-08-08 14:05:40');
INSERT INTO `audit_logs` VALUES ('63', '2', 'Updated Payroll', 'Payroll', 'Updated payroll for Jhun Balanquit', '::1', '2026-08-08 15:08:16');
INSERT INTO `audit_logs` VALUES ('64', '2', 'DELETED payroll', 'Payroll', 'Deleted payroll forMark Tahimik', '::1', '2026-08-08 15:10:08');
INSERT INTO `audit_logs` VALUES ('65', '2', 'General Payroll', 'Payroll', 'Generated payroll for JOAN VALDEZ ', '::1', '2026-08-08 15:24:18');
INSERT INTO `audit_logs` VALUES ('66', '2', 'DELETED payroll', 'Payroll', 'Deleted payroll forJOAN VALDEZ', '::1', '2026-08-08 15:24:40');
INSERT INTO `audit_logs` VALUES ('67', '2', 'Updated Payroll', 'Payroll', 'Updated payroll for Jhun Balanquit', '::1', '2026-08-08 15:26:51');
INSERT INTO `audit_logs` VALUES ('68', '2', 'General Payroll', 'Payroll', 'Generated payroll for JOAN VALDEZ ', '::1', '2026-08-08 15:28:05');
INSERT INTO `audit_logs` VALUES ('69', '2', 'Updated Payroll', 'Payroll', 'Updated payroll for JOAN VALDEZ', '::1', '2026-08-08 15:28:22');
INSERT INTO `audit_logs` VALUES ('70', '2', 'Updated Payroll', 'Payroll', 'Updated payroll for JOAN VALDEZ', '::1', '2026-08-08 15:33:31');
INSERT INTO `audit_logs` VALUES ('71', '2', 'General Payroll', 'Payroll', 'Generated payroll for Mark Tahimik ', '::1', '2026-08-08 15:34:15');
INSERT INTO `audit_logs` VALUES ('72', '2', 'Updated Payroll', 'Payroll', 'Updated payroll for JOAN VALDEZ', '::1', '2026-08-08 15:35:12');
INSERT INTO `audit_logs` VALUES ('73', '2', 'Updated Payroll', 'Payroll', 'Updated payroll for Mark Tahimik', '::1', '2026-08-08 15:35:26');
INSERT INTO `audit_logs` VALUES ('74', '2', 'DELETED payroll', 'Payroll', 'Deleted payroll forJOAN VALDEZ', '::1', '2026-08-08 15:35:33');
INSERT INTO `audit_logs` VALUES ('75', '2', 'General Payroll', 'Payroll', 'Generated payroll for JOAN VALDEZ ', '::1', '2026-08-08 15:38:33');
INSERT INTO `audit_logs` VALUES ('76', '2', 'DELETED payroll', 'Payroll', 'Deleted payroll forJOAN VALDEZ', '::1', '2026-08-08 15:39:04');
INSERT INTO `audit_logs` VALUES ('77', '2', 'General Payroll', 'Payroll', 'Generated payroll for Jhun Balanquit ', '::1', '2026-08-08 15:45:43');
INSERT INTO `audit_logs` VALUES ('78', '2', 'General Payroll', 'Payroll', 'Generated payroll for Jhun Balanquit ', '::1', '2026-08-08 15:46:37');
INSERT INTO `audit_logs` VALUES ('79', '2', 'General Payroll', 'Payroll', 'Generated payroll for Lems Lamuan ', '::1', '2026-08-08 15:47:22');
INSERT INTO `audit_logs` VALUES ('80', '2', 'DELETED payroll', 'Payroll', 'Deleted payroll forJhun Balanquit', '::1', '2026-08-08 15:48:21');
INSERT INTO `audit_logs` VALUES ('81', '2', 'DELETED payroll', 'Payroll', 'Deleted payroll forMark Tahimik', '::1', '2026-08-08 15:48:25');
INSERT INTO `audit_logs` VALUES ('82', '2', 'Updated Payroll', 'Payroll', 'Updated payroll for Jhun Balanquit', '::1', '2026-08-08 16:53:41');
INSERT INTO `audit_logs` VALUES ('83', '2', 'Updated Payroll', 'Payroll', 'Updated payroll for Jhun Balanquit', '::1', '2026-08-10 09:04:00');
INSERT INTO `audit_logs` VALUES ('84', '2', 'General Payroll', 'Payroll', 'Generated payroll for Mark Tahimik ', '::1', '2026-08-10 09:56:11');
INSERT INTO `audit_logs` VALUES ('85', '2', 'Added Attendance', 'Attendance', 'Recorded attendance for Mark Tahimik ', '::1', '2026-08-10 09:56:36');
INSERT INTO `audit_logs` VALUES ('86', '2', 'General Payroll', 'Payroll', 'Generated payroll for Mark Tahimik ', '::1', '2026-08-10 09:57:33');
INSERT INTO `audit_logs` VALUES ('87', '2', 'General Payroll', 'Payroll', 'Generated payroll for Jhun Balanquit ', '::1', '2026-08-10 14:23:42');
INSERT INTO `audit_logs` VALUES ('88', '2', 'General Payroll', 'Payroll', 'Generated payroll for Lems Lamuan ', '::1', '2026-08-10 14:24:03');
INSERT INTO `audit_logs` VALUES ('89', '2', 'General Payroll', 'Payroll', 'Generated payroll for Mark Tahimik ', '::1', '2026-08-10 14:24:22');
INSERT INTO `audit_logs` VALUES ('90', '2', 'General Payroll', 'Payroll', 'Generated payroll for JOAN VALDEZ ', '::1', '2026-08-10 14:24:41');
INSERT INTO `audit_logs` VALUES ('91', '2', 'DELETED payroll', 'Payroll', 'Deleted payroll forLems Lamuan', '::1', '2026-08-10 14:26:02');
INSERT INTO `audit_logs` VALUES ('92', '2', 'DELETED payroll', 'Payroll', 'Deleted payroll forJhun Balanquit', '::1', '2026-08-10 14:26:04');
INSERT INTO `audit_logs` VALUES ('93', '2', 'DELETED payroll', 'Payroll', 'Deleted payroll forLems Lamuan', '::1', '2026-08-10 14:26:06');
INSERT INTO `audit_logs` VALUES ('94', '2', 'DELETED payroll', 'Payroll', 'Deleted payroll forMark Tahimik', '::1', '2026-08-10 14:26:07');
INSERT INTO `audit_logs` VALUES ('95', '2', 'DELETED payroll', 'Payroll', 'Deleted payroll forMark Tahimik', '::1', '2026-08-10 14:26:09');
INSERT INTO `audit_logs` VALUES ('96', '2', 'DELETED payroll', 'Payroll', 'Deleted payroll forJhun Balanquit', '::1', '2026-08-10 14:26:10');
INSERT INTO `audit_logs` VALUES ('97', '2', 'DELETED payroll', 'Payroll', 'Deleted payroll forLems Lamuan', '::1', '2026-08-10 14:26:12');
INSERT INTO `audit_logs` VALUES ('98', '2', 'DELETED payroll', 'Payroll', 'Deleted payroll forMark Tahimik', '::1', '2026-08-10 14:26:13');
INSERT INTO `audit_logs` VALUES ('99', '2', 'DELETED payroll', 'Payroll', 'Deleted payroll forJOAN VALDEZ', '::1', '2026-08-10 14:26:15');
INSERT INTO `audit_logs` VALUES ('100', '2', 'DELETED payroll', 'Payroll', 'Deleted payroll forJhun Balanquit', '::1', '2026-08-10 14:26:16');
INSERT INTO `audit_logs` VALUES ('101', '2', 'General Payroll', 'Payroll', 'Generated payroll for Jhun Balanquit ', '::1', '2026-08-10 14:26:48');
INSERT INTO `audit_logs` VALUES ('102', '2', 'General Payroll', 'Payroll', 'Generated payroll for Jhun Balanquit ', '::1', '2026-08-10 14:27:24');
INSERT INTO `audit_logs` VALUES ('103', '2', 'General Payroll', 'Payroll', 'Generated payroll for Jhun Balanquit ', '::1', '2026-08-10 14:27:49');
INSERT INTO `audit_logs` VALUES ('104', '2', 'General Payroll', 'Payroll', 'Generated payroll for Jhun Balanquit ', '::1', '2026-08-10 14:28:18');
INSERT INTO `audit_logs` VALUES ('105', '2', 'General Payroll', 'Payroll', 'Generated payroll for Jhun Balanquit ', '::1', '2026-08-10 14:28:51');
INSERT INTO `audit_logs` VALUES ('106', '2', 'General Payroll', 'Payroll', 'Generated payroll for Jhun Balanquit ', '::1', '2026-08-10 14:29:14');
INSERT INTO `audit_logs` VALUES ('107', '2', 'General Payroll', 'Payroll', 'Generated payroll for Jhun Balanquit ', '::1', '2026-08-10 14:29:41');
INSERT INTO `audit_logs` VALUES ('108', '2', 'General Payroll', 'Payroll', 'Generated payroll for Lems Lamuan ', '::1', '2026-08-10 15:02:55');
INSERT INTO `audit_logs` VALUES ('109', '2', 'General Payroll', 'Payroll', 'Generated payroll for Lems Lamuan ', '::1', '2026-08-10 15:11:45');
INSERT INTO `audit_logs` VALUES ('110', '2', 'Assigned Guard', 'Guard Management', 'Assigned guard: Jhun Balanquit to FORCE TOWER', '::1', '2026-08-11 08:35:05');
INSERT INTO `audit_logs` VALUES ('111', '2', 'Updated Payroll', 'Payroll', 'Updated payroll for Jhun Balanquit', '::1', '2026-08-11 09:02:07');
INSERT INTO `audit_logs` VALUES ('112', '2', 'Updated Payroll', 'Payroll', 'Updated payroll for Jhun Balanquit', '::1', '2026-08-11 09:03:45');
INSERT INTO `audit_logs` VALUES ('113', '2', 'Updated Payroll', 'Payroll', 'Updated payroll for Jhun Balanquit', '::1', '2026-08-11 09:05:43');
INSERT INTO `audit_logs` VALUES ('114', '2', 'Updated Payroll', 'Payroll', 'Updated payroll for Jhun Balanquit', '::1', '2026-08-11 09:07:13');
INSERT INTO `audit_logs` VALUES ('115', '2', 'Updated Payroll', 'Payroll', 'Updated payroll for Jhun Balanquit', '::1', '2026-08-11 09:08:09');
INSERT INTO `audit_logs` VALUES ('116', '2', 'Updated Payroll', 'Payroll', 'Updated payroll for Jhun Balanquit', '::1', '2026-08-11 09:08:51');
INSERT INTO `audit_logs` VALUES ('117', '2', 'Updated Payroll', 'Payroll', 'Updated payroll for Jhun Balanquit', '::1', '2026-08-11 09:09:34');
INSERT INTO `audit_logs` VALUES ('118', '2', 'Updated Payroll', 'Payroll', 'Updated payroll for Jhun Balanquit', '::1', '2026-08-12 08:03:07');
INSERT INTO `audit_logs` VALUES ('119', '2', 'Updated Payroll', 'Payroll', 'Updated payroll for Jhun Balanquit', '::1', '2026-08-12 08:03:15');
INSERT INTO `audit_logs` VALUES ('120', '2', 'Updated Payroll', 'Payroll', 'Updated payroll for Jhun Balanquit', '::1', '2026-08-12 08:04:11');
INSERT INTO `audit_logs` VALUES ('121', '2', 'Updated Payroll', 'Payroll', 'Updated payroll for Jhun Balanquit', '::1', '2026-08-12 08:04:25');
INSERT INTO `audit_logs` VALUES ('122', '2', 'Updated Payroll', 'Payroll', 'Updated payroll for Jhun Balanquit', '::1', '2026-08-12 08:09:18');
INSERT INTO `audit_logs` VALUES ('123', '2', 'Updated Payroll', 'Payroll', 'Updated payroll for Jhun Balanquit', '::1', '2026-08-12 08:09:29');
INSERT INTO `audit_logs` VALUES ('124', '2', 'Updated Payroll', 'Payroll', 'Updated payroll for Lems Lamuan', '::1', '2026-08-12 08:09:35');
INSERT INTO `audit_logs` VALUES ('125', '2', 'Updated Payroll', 'Payroll', 'Updated payroll for Jhun Balanquit', '::1', '2026-08-12 08:09:38');
INSERT INTO `audit_logs` VALUES ('126', '2', 'Updated Payroll', 'Payroll', 'Updated payroll for Jhun Balanquit', '::1', '2026-08-12 08:09:45');
INSERT INTO `audit_logs` VALUES ('127', '2', 'Updated Payroll', 'Payroll', 'Updated payroll for Jhun Balanquit', '::1', '2026-08-12 08:09:49');
INSERT INTO `audit_logs` VALUES ('128', '2', 'Updated Payroll', 'Payroll', 'Updated payroll for Jhun Balanquit', '::1', '2026-08-12 08:11:22');
INSERT INTO `audit_logs` VALUES ('129', '2', 'Updated Payroll', 'Payroll', 'Updated payroll for Lems Lamuan', '::1', '2026-08-12 08:11:24');
INSERT INTO `audit_logs` VALUES ('130', '2', 'Updated Payroll', 'Payroll', 'Updated payroll for Lems Lamuan', '::1', '2026-08-12 08:11:28');
INSERT INTO `audit_logs` VALUES ('131', '2', 'Updated Payroll', 'Payroll', 'Updated payroll for Jhun Balanquit', '::1', '2026-08-12 08:11:31');
INSERT INTO `audit_logs` VALUES ('132', '2', 'Updated Payroll', 'Payroll', 'Updated payroll for Jhun Balanquit', '::1', '2026-08-12 08:11:35');
INSERT INTO `audit_logs` VALUES ('133', '2', 'Updated Payroll', 'Payroll', 'Updated payroll for Jhun Balanquit', '::1', '2026-08-12 08:11:39');
INSERT INTO `audit_logs` VALUES ('134', '2', 'Updated Payroll', 'Payroll', 'Updated payroll for Jhun Balanquit', '::1', '2026-08-12 08:11:42');
INSERT INTO `audit_logs` VALUES ('135', '2', 'Updated Payroll', 'Payroll', 'Updated payroll for Jhun Balanquit', '::1', '2026-08-12 08:20:36');
INSERT INTO `audit_logs` VALUES ('136', '2', 'Updated Payroll', 'Payroll', 'Updated payroll for Jhun Balanquit', '::1', '2026-08-12 08:20:38');
INSERT INTO `audit_logs` VALUES ('137', '2', 'Updated Payroll', 'Payroll', 'Updated payroll for Jhun Balanquit', '::1', '2026-08-12 08:20:42');
INSERT INTO `audit_logs` VALUES ('138', '2', 'Updated Payroll', 'Payroll', 'Updated payroll for Jhun Balanquit', '::1', '2026-08-12 08:20:45');
INSERT INTO `audit_logs` VALUES ('139', '2', 'Updated Payroll', 'Payroll', 'Updated payroll for Jhun Balanquit', '::1', '2026-08-12 08:20:49');
INSERT INTO `audit_logs` VALUES ('140', '2', 'Updated Payroll', 'Payroll', 'Updated payroll for Jhun Balanquit', '::1', '2026-08-12 08:29:58');
INSERT INTO `audit_logs` VALUES ('141', '2', 'Updated Payroll', 'Payroll', 'Updated payroll for Jhun Balanquit', '::1', '2026-08-12 08:30:56');
INSERT INTO `audit_logs` VALUES ('142', '2', 'Updated Payroll', 'Payroll', 'Updated payroll for Jhun Balanquit', '::1', '2026-08-12 08:33:15');
INSERT INTO `audit_logs` VALUES ('143', '2', 'Updated Payroll', 'Payroll', 'Updated payroll for Jhun Balanquit', '::1', '2026-08-12 08:33:20');
INSERT INTO `audit_logs` VALUES ('144', '2', 'Updated Payroll', 'Payroll', 'Updated payroll for Jhun Balanquit', '::1', '2026-08-12 08:33:24');
INSERT INTO `audit_logs` VALUES ('145', '2', 'Updated Payroll', 'Payroll', 'Updated payroll for Jhun Balanquit', '::1', '2026-08-12 08:33:26');
INSERT INTO `audit_logs` VALUES ('146', '2', 'Updated Payroll', 'Payroll', 'Updated payroll for Jhun Balanquit', '::1', '2026-08-12 08:33:30');
INSERT INTO `audit_logs` VALUES ('147', '2', 'Updated Payroll', 'Payroll', 'Updated payroll for Lems Lamuan', '::1', '2026-08-12 08:41:04');
INSERT INTO `audit_logs` VALUES ('148', '2', 'Updated Payroll', 'Payroll', 'Updated payroll for Jhun Balanquit', '::1', '2026-08-12 08:48:11');
INSERT INTO `audit_logs` VALUES ('149', '2', 'DELETED payroll', 'Payroll', 'Deleted payroll forJhun Balanquit', '::1', '2026-08-12 08:49:10');
INSERT INTO `audit_logs` VALUES ('150', '2', 'DELETED payroll', 'Payroll', 'Deleted payroll forJhun Balanquit', '::1', '2026-08-12 09:02:32');
INSERT INTO `audit_logs` VALUES ('151', '2', 'Added User', 'User Management', 'Created user: Tama', '::1', '2026-08-12 09:16:33');
INSERT INTO `audit_logs` VALUES ('152', '2', 'Added User', 'User Management', 'Created user: Mali', '::1', '2026-08-12 09:17:43');
INSERT INTO `audit_logs` VALUES ('153', '2', 'Added User', 'User Management', 'Created user: sample', '::1', '2026-08-12 09:18:47');
INSERT INTO `audit_logs` VALUES ('154', '2', 'Added User', 'User Management', 'Created user: sample2', '::1', '2026-08-12 09:19:20');
INSERT INTO `audit_logs` VALUES ('155', '2', 'Updated Payroll', 'Payroll', 'Updated payroll for Jhun Balanquit', '::1', '2026-08-12 10:40:18');
INSERT INTO `audit_logs` VALUES ('156', '2', 'Updated Payroll', 'Payroll', 'Updated payroll for Jhun Balanquit', '::1', '2026-08-12 11:05:12');
INSERT INTO `audit_logs` VALUES ('157', '2', 'Updated Payroll Status', 'Payroll', 'Changed payroll status forLems Lamuan from Approved', '::1', '2026-08-12 11:05:27');
INSERT INTO `audit_logs` VALUES ('158', '2', 'Updated Payroll', 'Payroll', 'Updated payroll for Jhun Balanquit', '::1', '2026-08-12 11:05:46');
INSERT INTO `audit_logs` VALUES ('159', '2', 'Updated Payroll Status', 'Payroll', 'Changed payroll status forJhun Balanquit from Checked', '::1', '2026-08-12 11:07:55');
INSERT INTO `audit_logs` VALUES ('160', '2', 'Updated Payroll', 'Payroll', 'Updated payroll for Jhun Balanquit', '::1', '2026-08-12 11:08:05');
INSERT INTO `audit_logs` VALUES ('161', '2', 'Updated Payroll Status', 'Payroll', 'Changed payroll status forJhun Balanquit from Checked', '::1', '2026-08-12 11:08:07');
INSERT INTO `audit_logs` VALUES ('162', '2', 'Updated Payroll', 'Payroll', 'Updated payroll for Jhun Balanquit', '::1', '2026-08-12 11:08:57');
INSERT INTO `audit_logs` VALUES ('163', '2', 'Updated Payroll Status', 'Payroll', 'Changed payroll status forJhun Balanquit from Checked', '::1', '2026-08-12 11:18:43');
INSERT INTO `audit_logs` VALUES ('164', '2', 'Updated Payroll', 'Payroll', 'Updated payroll for Jhun Balanquit', '::1', '2026-08-12 11:25:47');
INSERT INTO `audit_logs` VALUES ('165', '2', 'Updated Payroll Status', 'Payroll', 'Changed payroll status forJhun Balanquit from Checked', '::1', '2026-08-12 11:27:16');
INSERT INTO `audit_logs` VALUES ('166', '2', 'Updated Payroll', 'Payroll', 'Updated payroll for Jhun Balanquit', '::1', '2026-08-12 11:27:27');
INSERT INTO `audit_logs` VALUES ('167', '2', 'Updated Payroll', 'Payroll', 'Updated payroll for Jhun Balanquit', '::1', '2026-08-12 13:27:10');
INSERT INTO `audit_logs` VALUES ('168', '2', 'Updated Payroll', 'Payroll', 'Updated payroll for Jhun Balanquit', '::1', '2026-08-12 16:32:13');
INSERT INTO `audit_logs` VALUES ('169', '2', 'Assigned Guard', 'Guard Management', 'Assigned guard: JOAN VALDEZ to Garden Tower', '::1', '2026-08-15 16:24:54');
INSERT INTO `audit_logs` VALUES ('170', '2', 'Updated Payroll', 'Payroll', 'Updated payroll for Jhun Balanquit', '::1', '2026-08-20 15:28:07');
INSERT INTO `audit_logs` VALUES ('171', '2', 'Updated Payroll Status', 'Payroll', 'Changed payroll status forJhun Balanquit from Checked', '::1', '2026-08-20 15:44:30');
INSERT INTO `audit_logs` VALUES ('172', '2', 'General Payroll', 'Payroll', 'Generated payroll for JOAN VALDEZ ', '::1', '2026-08-22 08:51:55');
INSERT INTO `audit_logs` VALUES ('173', '2', 'Updated Payroll', 'Payroll', 'Updated payroll for JOAN VALDEZ', '::1', '2026-08-22 09:17:06');
INSERT INTO `audit_logs` VALUES ('174', '2', 'Updated Payroll', 'Payroll', 'Updated payroll for JOAN VALDEZ', '::1', '2026-08-22 09:17:49');
INSERT INTO `audit_logs` VALUES ('175', '2', 'Updated Payroll', 'Payroll', 'Updated payroll for Jhun Balanquit', '::1', '2026-08-22 09:18:52');
INSERT INTO `audit_logs` VALUES ('176', '2', 'Updated Payroll Status', 'Payroll', 'Changed payroll status for Jhun Balanquit from Draft to Checked', '::1', '2026-08-22 10:08:02');
INSERT INTO `audit_logs` VALUES ('177', '2', 'Updated Payroll', 'Payroll', 'Updated payroll for Jhun Balanquit', '::1', '2026-08-22 10:08:43');
INSERT INTO `audit_logs` VALUES ('178', '2', 'Updated Payroll Status', 'Payroll', 'Changed payroll status for Jhun Balanquit from Draft to Checked', '::1', '2026-08-22 10:30:56');
INSERT INTO `audit_logs` VALUES ('179', '2', 'Update Security Settings', 'Security Settings', 'Changed session timeout to 30 minutes', '::1', '2026-08-22 11:25:42');
INSERT INTO `audit_logs` VALUES ('180', '2', 'Created Database Backup', 'Backup', 'Created database backup: sams_db_backup_2026-08-22_14-03-20.sql', '::1', '2026-08-22 14:03:20');
INSERT INTO `audit_logs` VALUES ('181', '2', 'Deleted Database Backup', 'Backup', 'Deleted database backup: sams_db_backup_2026-08-22_14-03-20.sql', '::1', '2026-08-22 15:31:33');

-- -------------------------------------
-- Table: clients
-- -------------------------------------

DROP TABLE IF EXISTS `clients`;
CREATE TABLE `clients` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `client_name` varchar(100) NOT NULL,
  `contact_person` varchar(100) NOT NULL,
  `contact_no` varchar(20) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `status` enum('Active','Inactive') DEFAULT 'Active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `created_at` (`created_at`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `clients` VALUES ('5', 'TRIMEGA', 'OIC', '099109191919', 'mega_1@gmail.com', 'Cubao', 'Active', '2026-07-31 00:00:00');
INSERT INTO `clients` VALUES ('9', 'UNITED', 'BOGA', '09566343441', 'united@gmail', 'Albany', 'Active', '2026-08-01 10:18:28');
INSERT INTO `clients` VALUES ('13', 'MEGAFORCE', 'JUSTINE', '09919098392', 'jhunbalanquit.megaforce@gmail.com', 'Cubao', 'Active', '2026-08-05 13:08:48');

-- -------------------------------------
-- Table: detachments
-- -------------------------------------

DROP TABLE IF EXISTS `detachments`;
CREATE TABLE `detachments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `detachment_name` varchar(100) NOT NULL,
  `client_id` int(11) NOT NULL,
  `address` text NOT NULL,
  `contact_person` varchar(100) DEFAULT NULL,
  `contact_no` varchar(20) DEFAULT NULL,
  `guards_required` int(11) DEFAULT 0,
  `status` enum('Active','Inactive') DEFAULT 'Active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `client_id` (`client_id`),
  CONSTRAINT `detachments_ibfk_1` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `detachments` VALUES ('6', 'Garden Tower', '5', 'adsasd', 'kokok', '09566343441', '50', 'Inactive', '2026-07-29 13:25:15');
INSERT INTO `detachments` VALUES ('8', 'FORCE TOWER', '13', 'Commonwealth, Quezon City', 'Bernie', '09999999994', '100', 'Active', '2026-08-01 10:20:21');
INSERT INTO `detachments` VALUES ('10', 'MONTE', '5', 'adsasd', 'Jhun Balanquit', '09919098392', '1', 'Inactive', '2026-08-05 13:12:53');

-- -------------------------------------
-- Table: guards
-- -------------------------------------

DROP TABLE IF EXISTS `guards`;
CREATE TABLE `guards` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `employee_no` varchar(30) DEFAULT NULL,
  `firstname` varchar(50) NOT NULL,
  `middlename` varchar(50) DEFAULT NULL,
  `lastname` varchar(50) NOT NULL,
  `suffix` varchar(10) DEFAULT NULL,
  `gender` enum('male','female') DEFAULT NULL,
  `birthdate` date DEFAULT NULL,
  `contact_no` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `date_hired` date DEFAULT NULL,
  `license_no` varchar(50) DEFAULT NULL,
  `license_expiry` date DEFAULT NULL,
  `profile_picture` varchar(255) DEFAULT 'default.PNG',
  `status` enum('Active','Inactive') DEFAULT 'Active',
  `detachment_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `employee_no` (`employee_no`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `guards_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `guards` VALUES ('16', NULL, '8888866', 'JOAN', 'Valderama', 'VALDEZ', NULL, 'male', NULL, '09919098392', NULL, NULL, '0000-00-00', 'N5098', '0000-00-00', '1785216581_image5.jpg', 'Active', '6', '2026-07-28 13:29:41');
INSERT INTO `guards` VALUES ('17', NULL, '3333', 'Lems', 'master', 'Lamuan', NULL, 'male', NULL, '09566343441', NULL, NULL, '0000-00-00', 'N5099', '0000-00-00', '1785223722_image4.jpg', 'Active', '8', '2026-07-28 15:28:42');
INSERT INTO `guards` VALUES ('20', NULL, '4444', 'Jhun', 'Ernesto', 'Balanquit', NULL, 'male', NULL, '09919098392', NULL, NULL, '2026-05-05', '987456321', '2026-08-19', '1785308816_Background.jpg', 'Active', '8', '2026-07-29 15:06:56');
INSERT INTO `guards` VALUES ('22', NULL, '89898', 'Mark', 'salamat', 'Tahimik', NULL, 'male', NULL, '818484848', NULL, NULL, '2026-07-28', 'N5099', '2026-08-29', '1785314504_image1.jpg', 'Active', '10', '2026-07-29 16:41:44');

-- -------------------------------------
-- Table: payroll
-- -------------------------------------

DROP TABLE IF EXISTS `payroll`;
CREATE TABLE `payroll` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `guard_id` int(11) NOT NULL,
  `payroll_from` date NOT NULL,
  `payroll_to` date NOT NULL,
  `days_worked` int(11) DEFAULT 0,
  `rate_per_day` decimal(10,2) DEFAULT 0.00,
  `gross_pay` decimal(10,2) DEFAULT 0.00,
  `deductions` decimal(10,2) DEFAULT 0.00,
  `net_pay` decimal(10,2) DEFAULT 0.00,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('Draft','Checked','Approved') NOT NULL DEFAULT 'Draft',
  PRIMARY KEY (`id`),
  KEY `guard_id` (`guard_id`),
  CONSTRAINT `payroll_ibfk_1` FOREIGN KEY (`guard_id`) REFERENCES `guards` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `payroll` VALUES ('19', '20', '2026-05-01', '2026-05-15', '9', '868.75', '7818.75', '900.00', '6918.75', '2026-08-10 14:26:48', 'Approved');
INSERT INTO `payroll` VALUES ('21', '20', '2026-06-01', '2026-06-15', '12', '868.75', '10425.00', '1385.63', '9039.37', '2026-08-10 14:27:49', 'Approved');
INSERT INTO `payroll` VALUES ('22', '20', '2026-06-16', '2026-06-30', '13', '868.75', '11293.75', '1057.34', '10236.41', '2026-08-10 14:28:18', 'Checked');
INSERT INTO `payroll` VALUES ('23', '20', '2026-07-01', '2026-07-15', '13', '868.75', '11293.75', '857.34', '10436.41', '2026-08-10 14:28:51', 'Approved');
INSERT INTO `payroll` VALUES ('25', '20', '2026-08-01', '2026-08-15', '14', '868.75', '12162.50', '1000.00', '11162.50', '2026-08-10 14:29:41', 'Approved');
INSERT INTO `payroll` VALUES ('27', '17', '2026-07-01', '2026-07-15', '15', '600.00', '9000.00', '1000.00', '8000.00', '2026-08-10 15:11:45', 'Approved');
INSERT INTO `payroll` VALUES ('28', '16', '2026-08-01', '2026-08-15', '13', '654.00', '8502.00', '0.00', '8502.00', '2026-08-22 08:51:55', 'Approved');

-- -------------------------------------
-- Table: roles
-- -------------------------------------

DROP TABLE IF EXISTS `roles`;
CREATE TABLE `roles` (
  `id` int(25) NOT NULL AUTO_INCREMENT,
  `role_name` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `roles` VALUES ('1', 'admin');
INSERT INTO `roles` VALUES ('2', 'HR');
INSERT INTO `roles` VALUES ('3', 'OIC');
INSERT INTO `roles` VALUES ('4', 'Guard');
INSERT INTO `roles` VALUES ('5', 'Client');

-- -------------------------------------
-- Table: security_settings
-- -------------------------------------

DROP TABLE IF EXISTS `security_settings`;
CREATE TABLE `security_settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `session_timeout` int(11) NOT NULL DEFAULT 30,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `security_settings` VALUES ('1', '30');

-- -------------------------------------
-- Table: system_settings
-- -------------------------------------

DROP TABLE IF EXISTS `system_settings`;
CREATE TABLE `system_settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `company_name` varchar(255) NOT NULL,
  `company_address` text DEFAULT NULL,
  `contact_number` varchar(50) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `logo` varchar(255) DEFAULT 'default_logo.png',
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `system_settings` VALUES ('1', 'MegaForce', '4 Albany, Cubao Quezon City', '+6256789431', 'megaforce@gmail.com', '1785394960_image3.jpg', '2026-08-15 16:24:19');

-- -------------------------------------
-- Table: users
-- -------------------------------------

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `role_id` int(11) DEFAULT NULL,
  `fullname` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `profile_picture` varchar(255) DEFAULT NULL,
  `status` enum('Active','Inactive') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `users` VALUES ('2', '1', 'Jhun V. Balanquit', 'jhunbalanquit.megaforce@gmail.com', 'Jhunskie', '$2y$10$hsJEIRZZ2M/LAmOFbK3VxuL.yos1mnqngqC3pWW9FqaahdH8kQMha', '1785548598_JhunProfile.jpg', 'Active', '2026-08-01 09:43:18');
INSERT INTO `users` VALUES ('8', '1', 'DONGSKIE SALUTA', 'mastermega@gmail.com', 'DONGSKIE', '$2y$10$R.VJl.Eebgq8tpXNWt4JbO4gRLYyNMrUlBdrlP9Lbe75CggPNaiou', '1785548667_sample.jpg', 'Active', '2026-08-05 15:21:59');
INSERT INTO `users` VALUES ('15', '5', 'Tama', 'tama@t.c', 'Tamaraw', '$2y$10$mGrDQZzk.23oQirKzJXyTuEG6cxmM4dqF2ZRBnCeUQgy3.oS0AuGq', NULL, 'Active', '2026-08-12 09:16:33');
INSERT INTO `users` VALUES ('16', '2', 'Mali', 'Mali@t.c', 'Malina', '$2y$10$nI84ahLkg2KUFSZFvTgZFegCDvGHH1suFCq3yzyKmeZ1OVWHmJWYa', NULL, 'Active', '2026-08-12 09:17:43');
INSERT INTO `users` VALUES ('17', '4', 'sample', 'sample@t.c', 'sample', '$2y$10$II.SdDbMC2lVCZWQ1wrKOuf5/OXm0h628iiHO5z0iEZNblTsfqFwq', NULL, 'Active', '2026-08-12 09:18:47');
INSERT INTO `users` VALUES ('18', '3', 'sample2', 'sample2@t.c', 'Sample2', '$2y$10$QqHpdkGl31NXc/T2V98YzuuGVRHc.Z7exiihJbw.HCBRk2L3v5aTu', NULL, 'Active', '2026-08-12 09:19:20');

