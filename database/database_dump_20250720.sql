BEGIN TRANSACTION;
CREATE TABLE IF NOT EXISTS "academic_sessions" (
	"id"	integer NOT NULL,
	"name"	varchar NOT NULL,
	"start_date"	date NOT NULL,
	"end_date"	date NOT NULL,
	"is_active"	tinyint(1) NOT NULL DEFAULT '0',
	"created_at"	datetime,
	"updated_at"	datetime,
	"is_current"	tinyint(1) NOT NULL DEFAULT '0',
	PRIMARY KEY("id" AUTOINCREMENT)
);
CREATE TABLE IF NOT EXISTS "assessments" (
	"id"	integer NOT NULL,
	"name"	varchar NOT NULL,
	"max_marks"	integer NOT NULL,
	"weightage"	integer NOT NULL,
	"created_at"	datetime,
	"updated_at"	datetime,
	"academic_session_id"	integer NOT NULL,
	"subject_id"	integer NOT NULL,
	"assessment_date"	date,
	"class_section_id"	integer,
	PRIMARY KEY("id" AUTOINCREMENT),
	FOREIGN KEY("academic_session_id") REFERENCES "academic_sessions"("id") on delete cascade on update no action,
	FOREIGN KEY("class_section_id") REFERENCES "class_sections"("id") on delete cascade,
	FOREIGN KEY("subject_id") REFERENCES "subjects"("id") on delete no action on update no action
);
CREATE TABLE IF NOT EXISTS "assignments" (
	"id"	integer NOT NULL,
	"title"	varchar NOT NULL,
	"description"	text,
	"created_at"	datetime,
	"updated_at"	datetime,
	"subject_id"	integer NOT NULL,
	"class_section_id"	integer NOT NULL,
	"teacher_id"	integer,
	"assessment_id"	integer NOT NULL,
	PRIMARY KEY("id" AUTOINCREMENT),
	FOREIGN KEY("assessment_id") REFERENCES "assessments"("id") on delete cascade,
	FOREIGN KEY("class_section_id") REFERENCES "class_sections"("id") on delete cascade on update no action,
	FOREIGN KEY("subject_id") REFERENCES "subjects"("id") on delete cascade on update no action,
	FOREIGN KEY("teacher_id") REFERENCES "users"("id") on delete set null on update no action
);
CREATE TABLE IF NOT EXISTS "cache" (
	"key"	varchar NOT NULL,
	"value"	text NOT NULL,
	"expiration"	integer NOT NULL,
	PRIMARY KEY("key")
);
CREATE TABLE IF NOT EXISTS "cache_locks" (
	"key"	varchar NOT NULL,
	"owner"	varchar NOT NULL,
	"expiration"	integer NOT NULL,
	PRIMARY KEY("key")
);
CREATE TABLE IF NOT EXISTS "class_section_subject" (
	"class_section_id"	integer NOT NULL,
	"subject_id"	integer NOT NULL,
	"created_at"	datetime,
	"updated_at"	datetime,
	"teacher_id"	integer,
	PRIMARY KEY("class_section_id","subject_id"),
	FOREIGN KEY("class_section_id") REFERENCES "class_sections"("id") on delete cascade on update no action,
	FOREIGN KEY("subject_id") REFERENCES "subjects"("id") on delete cascade on update no action,
	FOREIGN KEY("teacher_id") REFERENCES "users"("id") on delete set null
);
CREATE TABLE IF NOT EXISTS "class_section_user" (
	"user_id"	integer NOT NULL,
	"class_section_id"	integer NOT NULL,
	PRIMARY KEY("user_id","class_section_id"),
	FOREIGN KEY("class_section_id") REFERENCES "class_sections"("id") on delete cascade,
	FOREIGN KEY("user_id") REFERENCES "users"("id") on delete cascade
);
CREATE TABLE IF NOT EXISTS "class_sections" (
	"id"	integer NOT NULL,
	"name"	varchar NOT NULL,
	"created_at"	datetime,
	"updated_at"	datetime,
	"teacher_id"	integer,
	"academic_session_id"	integer,
	"grading_scale_id"	integer,
	PRIMARY KEY("id" AUTOINCREMENT),
	FOREIGN KEY("academic_session_id") REFERENCES "academic_sessions"("id") on delete no action on update no action,
	FOREIGN KEY("grading_scale_id") REFERENCES "grading_scales"("id") on delete set null,
	FOREIGN KEY("teacher_id") REFERENCES "users"("id") on delete no action on update no action
);
CREATE TABLE IF NOT EXISTS "class_student" (
	"id"	integer NOT NULL,
	"class_id"	integer NOT NULL,
	"user_id"	integer NOT NULL,
	"created_at"	datetime,
	"updated_at"	datetime,
	PRIMARY KEY("id" AUTOINCREMENT),
	FOREIGN KEY("class_id") REFERENCES "classes"("id") on delete cascade,
	FOREIGN KEY("user_id") REFERENCES "users"("id") on delete cascade
);
CREATE TABLE IF NOT EXISTS "classes" (
	"id"	integer NOT NULL,
	"name"	varchar NOT NULL,
	"teacher_id"	integer,
	"academic_session_id"	integer NOT NULL,
	"created_at"	datetime,
	"updated_at"	datetime,
	PRIMARY KEY("id" AUTOINCREMENT),
	FOREIGN KEY("academic_session_id") REFERENCES "academic_sessions"("id") on delete cascade on update no action,
	FOREIGN KEY("teacher_id") REFERENCES "users"("id") on delete set null on update no action
);
CREATE TABLE IF NOT EXISTS "enrollments" (
	"user_id"	integer NOT NULL,
	"class_section_id"	integer NOT NULL,
	"created_at"	datetime,
	"updated_at"	datetime,
	PRIMARY KEY("user_id","class_section_id"),
	FOREIGN KEY("class_section_id") REFERENCES "class_sections"("id") on delete cascade,
	FOREIGN KEY("user_id") REFERENCES "users"("id") on delete cascade
);
CREATE TABLE IF NOT EXISTS "failed_jobs" (
	"id"	integer NOT NULL,
	"uuid"	varchar NOT NULL,
	"connection"	text NOT NULL,
	"queue"	text NOT NULL,
	"payload"	text NOT NULL,
	"exception"	text NOT NULL,
	"failed_at"	datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
	PRIMARY KEY("id" AUTOINCREMENT)
);
CREATE TABLE IF NOT EXISTS "grades" (
	"id"	integer NOT NULL,
	"grading_scale_id"	integer NOT NULL,
	"grade_name"	varchar NOT NULL,
	"min_score"	integer NOT NULL,
	"max_score"	integer NOT NULL,
	"remark"	varchar,
	"created_at"	datetime,
	"updated_at"	datetime,
	PRIMARY KEY("id" AUTOINCREMENT),
	FOREIGN KEY("grading_scale_id") REFERENCES "grading_scales"("id") on delete cascade
);
CREATE TABLE IF NOT EXISTS "grading_scales" (
	"id"	integer NOT NULL,
	"name"	varchar NOT NULL,
	"description"	text,
	"created_at"	datetime,
	"updated_at"	datetime,
	PRIMARY KEY("id" AUTOINCREMENT)
);
CREATE TABLE IF NOT EXISTS "job_batches" (
	"id"	varchar NOT NULL,
	"name"	varchar NOT NULL,
	"total_jobs"	integer NOT NULL,
	"pending_jobs"	integer NOT NULL,
	"failed_jobs"	integer NOT NULL,
	"failed_job_ids"	text NOT NULL,
	"options"	text,
	"cancelled_at"	integer,
	"created_at"	integer NOT NULL,
	"finished_at"	integer,
	PRIMARY KEY("id")
);
CREATE TABLE IF NOT EXISTS "jobs" (
	"id"	integer NOT NULL,
	"queue"	varchar NOT NULL,
	"payload"	text NOT NULL,
	"attempts"	integer NOT NULL,
	"reserved_at"	integer,
	"available_at"	integer NOT NULL,
	"created_at"	integer NOT NULL,
	PRIMARY KEY("id" AUTOINCREMENT)
);
CREATE TABLE IF NOT EXISTS "migrations" (
	"id"	integer NOT NULL,
	"migration"	varchar NOT NULL,
	"batch"	integer NOT NULL,
	PRIMARY KEY("id" AUTOINCREMENT)
);
CREATE TABLE IF NOT EXISTS "password_reset_tokens" (
	"email"	varchar NOT NULL,
	"token"	varchar NOT NULL,
	"created_at"	datetime,
	PRIMARY KEY("email")
);
CREATE TABLE IF NOT EXISTS "results" (
	"id"	integer NOT NULL,
	"user_id"	integer NOT NULL,
	"assessment_id"	integer NOT NULL,
	"class_id"	integer NOT NULL,
	"score"	numeric NOT NULL,
	"remarks"	text,
	"created_at"	datetime,
	"updated_at"	datetime,
	"remark"	varchar,
	"teacher_id"	integer,
	"class_section_id"	integer,
	"comments"	text,
	PRIMARY KEY("id" AUTOINCREMENT),
	FOREIGN KEY("assessment_id") REFERENCES "assessments"("id") on delete cascade on update no action,
	FOREIGN KEY("class_id") REFERENCES "class_sections"("id") on delete cascade on update no action,
	FOREIGN KEY("class_section_id") REFERENCES "class_sections"("id") on delete set null,
	FOREIGN KEY("teacher_id") REFERENCES "users"("id") on delete set null,
	FOREIGN KEY("user_id") REFERENCES "users"("id") on delete cascade on update no action
);
CREATE TABLE IF NOT EXISTS "sessions" (
	"id"	varchar NOT NULL,
	"user_id"	integer,
	"ip_address"	varchar,
	"user_agent"	text,
	"payload"	text NOT NULL,
	"last_activity"	integer NOT NULL,
	PRIMARY KEY("id")
);
CREATE TABLE IF NOT EXISTS "settings" (
	"id"	integer NOT NULL,
	"key"	varchar NOT NULL,
	"value"	text,
	"created_at"	datetime,
	"updated_at"	datetime,
	PRIMARY KEY("id" AUTOINCREMENT)
);
CREATE TABLE IF NOT EXISTS "subject_user" (
	"user_id"	integer NOT NULL,
	"subject_id"	integer NOT NULL,
	PRIMARY KEY("user_id","subject_id"),
	FOREIGN KEY("subject_id") REFERENCES "subjects"("id") on delete cascade,
	FOREIGN KEY("user_id") REFERENCES "users"("id") on delete cascade
);
CREATE TABLE IF NOT EXISTS "subjects" (
	"id"	integer NOT NULL,
	"name"	varchar NOT NULL,
	"code"	varchar NOT NULL,
	"description"	text,
	"created_at"	datetime,
	"updated_at"	datetime,
	PRIMARY KEY("id" AUTOINCREMENT)
);
CREATE TABLE IF NOT EXISTS "users" (
	"id"	integer NOT NULL,
	"name"	varchar NOT NULL,
	"email"	varchar NOT NULL,
	"role"	varchar NOT NULL DEFAULT 'student',
	"email_verified_at"	datetime,
	"password"	varchar NOT NULL,
	"remember_token"	varchar,
	"created_at"	datetime,
	"updated_at"	datetime,
	"avatar"	varchar,
	"profile_photo_path"	varchar,
	PRIMARY KEY("id" AUTOINCREMENT)
);
INSERT INTO "academic_sessions" VALUES (1,'Mid-Term Exam','2025-07-25 00:00:00','2025-08-02 00:00:00',0,'2025-07-19 14:08:48','2025-07-19 14:08:48',1);
INSERT INTO "assessments" VALUES (4,'Biology Mid Term',100,100,'2025-07-19 14:45:20','2025-07-19 14:45:20',1,5,'2025-07-19',NULL);
INSERT INTO "assessments" VALUES (6,'Computer Science Mid Term',100,50,'2025-07-19 14:47:53','2025-07-19 14:47:53',1,8,'2025-07-26',NULL);
INSERT INTO "assignments" VALUES (4,'Biology Mid Term',NULL,'2025-07-19 14:45:20','2025-07-19 14:45:20',5,2,6,4);
INSERT INTO "assignments" VALUES (6,'Computer Science Mid Term',NULL,'2025-07-19 14:47:53','2025-07-19 14:47:53',8,1,6,6);
INSERT INTO "class_section_subject" VALUES (2,5,'2025-07-19 14:18:16','2025-07-19 14:22:27',6);
INSERT INTO "class_section_subject" VALUES (2,4,'2025-07-19 14:18:16','2025-07-19 14:22:27',6);
INSERT INTO "class_section_subject" VALUES (2,8,'2025-07-19 14:18:16','2025-07-19 14:22:28',6);
INSERT INTO "class_section_subject" VALUES (1,4,'2025-07-19 14:46:45','2025-07-19 14:46:45',6);
INSERT INTO "class_section_subject" VALUES (1,8,'2025-07-19 14:46:45','2025-07-19 14:46:45',6);
INSERT INTO "class_section_subject" VALUES (1,2,'2025-07-19 14:46:45','2025-07-19 14:46:45',6);
INSERT INTO "class_sections" VALUES (1,'12D','2025-07-19 14:09:58','2025-07-19 14:09:58',NULL,1,2);
INSERT INTO "class_sections" VALUES (2,'12E','2025-07-19 14:18:15','2025-07-19 14:18:15',NULL,1,2);
INSERT INTO "class_sections" VALUES (3,'Grade 9 - Section A','2025-07-20 05:55:53','2025-07-20 05:55:53',NULL,1,1);
INSERT INTO "class_sections" VALUES (4,'Grade 9 - Section B','2025-07-20 05:55:53','2025-07-20 05:55:53',NULL,1,1);
INSERT INTO "class_sections" VALUES (5,'Grade 10 - STEM','2025-07-20 05:55:54','2025-07-20 05:55:54',NULL,1,1);
INSERT INTO "class_sections" VALUES (6,'Grade 11 - Humanities','2025-07-20 05:55:54','2025-07-20 05:55:54',NULL,1,2);
INSERT INTO "enrollments" VALUES (15,2,NULL,NULL);
INSERT INTO "enrollments" VALUES (17,2,NULL,NULL);
INSERT INTO "enrollments" VALUES (19,2,NULL,NULL);
INSERT INTO "enrollments" VALUES (21,2,NULL,NULL);
INSERT INTO "enrollments" VALUES (19,1,NULL,NULL);
INSERT INTO "enrollments" VALUES (20,1,NULL,NULL);
INSERT INTO "enrollments" VALUES (21,1,NULL,NULL);
INSERT INTO "enrollments" VALUES (22,1,NULL,NULL);
INSERT INTO "enrollments" VALUES (23,1,NULL,NULL);
INSERT INTO "enrollments" VALUES (24,1,NULL,NULL);
INSERT INTO "grades" VALUES (1,1,'Distinction',75,100,'Outstanding Achievement','2025-07-19 13:57:17','2025-07-19 13:57:17');
INSERT INTO "grades" VALUES (2,1,'Merit',60,74,'Commendable Achievement','2025-07-19 13:57:17','2025-07-19 13:57:17');
INSERT INTO "grades" VALUES (3,1,'Credit',50,59,'Satisfactory Achievement','2025-07-19 13:57:17','2025-07-19 13:57:17');
INSERT INTO "grades" VALUES (4,1,'Pass',40,49,'Acceptable Achievement','2025-07-19 13:57:17','2025-07-19 13:57:17');
INSERT INTO "grades" VALUES (5,1,'Fail',0,39,'Unsatisfactory','2025-07-19 13:57:17','2025-07-19 13:57:17');
INSERT INTO "grades" VALUES (6,2,'1',80,100,'Distinction','2025-07-19 13:57:17','2025-07-19 13:57:17');
INSERT INTO "grades" VALUES (7,2,'2',75,79,'Distinction','2025-07-19 13:57:17','2025-07-19 13:57:17');
INSERT INTO "grades" VALUES (8,2,'3',70,74,'Merit','2025-07-19 13:57:17','2025-07-19 13:57:17');
INSERT INTO "grades" VALUES (9,2,'4',65,69,'Merit','2025-07-19 13:57:17','2025-07-19 13:57:17');
INSERT INTO "grades" VALUES (10,2,'5',60,64,'Credit','2025-07-19 13:57:17','2025-07-19 13:57:17');
INSERT INTO "grades" VALUES (11,2,'6',50,59,'Credit','2025-07-19 13:57:17','2025-07-19 13:57:17');
INSERT INTO "grades" VALUES (12,2,'7',45,49,'Pass','2025-07-19 13:57:17','2025-07-19 13:57:17');
INSERT INTO "grades" VALUES (13,2,'8',40,44,'Pass','2025-07-19 13:57:17','2025-07-19 13:57:17');
INSERT INTO "grades" VALUES (14,2,'9',0,39,'Unsatisfactory','2025-07-19 13:57:17','2025-07-19 13:57:17');
INSERT INTO "grading_scales" VALUES (1,'Junior Secondary (Formative)','Standard grading for Grades 8-9 based on percentages.','2025-07-19 13:57:17','2025-07-19 13:57:17');
INSERT INTO "grading_scales" VALUES (2,'Senior Secondary (Exam)','Standard grading for Grades 10-12 based on a 1-9 numeric scale.','2025-07-19 13:57:17','2025-07-19 13:57:17');
INSERT INTO "migrations" VALUES (1,'0001_01_01_000000_create_users_table',1);
INSERT INTO "migrations" VALUES (2,'0001_01_01_000001_create_cache_table',1);
INSERT INTO "migrations" VALUES (3,'0001_01_01_000002_create_jobs_table',1);
INSERT INTO "migrations" VALUES (4,'2025_06_14_095701_create_subjects_table',1);
INSERT INTO "migrations" VALUES (5,'2025_06_14_095703_create_academic_sessions_table',1);
INSERT INTO "migrations" VALUES (6,'2025_06_14_095712_create_classes_table',1);
INSERT INTO "migrations" VALUES (7,'2025_06_14_151613_create_course_user_table',1);
INSERT INTO "migrations" VALUES (8,'2025_06_15_100000_create_assessments_table',1);
INSERT INTO "migrations" VALUES (9,'2025_06_15_100001_create_results_table',1);
INSERT INTO "migrations" VALUES (10,'2025_06_15_135126_create_settings_table',1);
INSERT INTO "migrations" VALUES (11,'2025_07_03_034223_add_is_current_to_academic_sessions_table',1);
INSERT INTO "migrations" VALUES (12,'2025_07_08_041952_add_avatar_to_users_table',1);
INSERT INTO "migrations" VALUES (13,'2025_07_09_033910_drop_student_id_from_results_table',1);
INSERT INTO "migrations" VALUES (14,'2025_07_12_023153_create_class_section_user_table',1);
INSERT INTO "migrations" VALUES (15,'2025_07_12_025359_add_subject_id_to_class_section_user_table',1);
INSERT INTO "migrations" VALUES (16,'2025_07_12_045859_create_class_section_subject_table',1);
INSERT INTO "migrations" VALUES (17,'2025_07_12_052213_create_class_sections_table',1);
INSERT INTO "migrations" VALUES (18,'2025_07_12_054251_drop_subject_id_from_classes_table',1);
INSERT INTO "migrations" VALUES (19,'2025_07_12_101009_create_enrollments_table',1);
INSERT INTO "migrations" VALUES (20,'2025_07_12_104532_create_grading_scales_table',1);
INSERT INTO "migrations" VALUES (21,'2025_07_12_174533_add_subject_id_to_assessments_table',1);
INSERT INTO "migrations" VALUES (22,'2025_07_12_232119_add_teacher_id_to_class_sections_table',1);
INSERT INTO "migrations" VALUES (23,'2025_07_12_232354_add_academic_session_id_to_class_sections_table',1);
INSERT INTO "migrations" VALUES (24,'2025_07_12_233846_add_class_id_to_assessments_table',1);
INSERT INTO "migrations" VALUES (25,'2025_07_14_134702_add_grading_scale_id_to_class_sections_table',1);
INSERT INTO "migrations" VALUES (26,'2025_07_14_143456_add_remark_to_results_table',1);
INSERT INTO "migrations" VALUES (27,'2025_07_15_045002_add_profile_photo_path_to_users_table',1);
INSERT INTO "migrations" VALUES (28,'2025_07_15_054306_create_grading_scales_table',1);
INSERT INTO "migrations" VALUES (29,'2025_07_17_205212_create_assignments_table',1);
INSERT INTO "migrations" VALUES (30,'2025_07_19_141126_add_timestamps_to_class_section_subject_table',2);
INSERT INTO "migrations" VALUES (31,'2025_07_19_141917_add_teacher_id_to_class_section_subject_table',3);
INSERT INTO "migrations" VALUES (32,'2025_07_19_142146_create_subject_user_table',4);
INSERT INTO "migrations" VALUES (33,'2025_07_19_142656_add_assessment_date_to_assessments_table',5);
INSERT INTO "migrations" VALUES (34,'2025_07_19_142934_add_subject_id_to_assignments_table',6);
INSERT INTO "migrations" VALUES (35,'2025_07_19_143349_add_class_section_id_to_assignments_table',7);
INSERT INTO "migrations" VALUES (36,'2025_07_19_143533_add_teacher_id_to_assignments_table',8);
INSERT INTO "migrations" VALUES (37,'2025_07_19_143650_add_assessment_id_to_assignments_table',9);
INSERT INTO "migrations" VALUES (38,'2025_07_18_225941_add_assessment_id_to_assignments_table',10);
INSERT INTO "migrations" VALUES (39,'2025_07_20_060418_add_class_section_id_to_assessments_table',10);
INSERT INTO "migrations" VALUES (40,'2025_07_20_065140_add_teacher_and_class_to_results_table',11);
INSERT INTO "migrations" VALUES (41,'2025_07_20_065507_add_comments_to_results_table',12);
INSERT INTO "migrations" VALUES (42,'2025_07_20_065605_rename_class_id_to_class_section_id_in_results_table',13);
INSERT INTO "sessions" VALUES ('GM5IeKRE0j4MRzcx6q6nDSGLTb0wHAcxsb65uCIX',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Herd/1.20.2 Chrome/120.0.6099.291 Electron/28.2.5 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiZTcxV09PUklHUVBVOVFvVjgyTDJ2ZFkydzdRV0ZYOWtTWXJUWFFhRyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzc6Imh0dHBzOi8vci1zeXN0ZW0tMy50ZXN0Lz9oZXJkPXByZXZpZXciO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1752987573);
INSERT INTO "sessions" VALUES ('2K9HKWWGT1tB3518HdSRHRs4Ygg3EXOizHYymPvw',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Herd/1.20.2 Chrome/120.0.6099.291 Electron/28.2.5 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiN1d2SUFXMXlwbnhuMldCNHNNN3RvSUZidWhMVTdBeUVaRmVRQU5tbCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzc6Imh0dHBzOi8vci1zeXN0ZW0tMy50ZXN0Lz9oZXJkPXByZXZpZXciO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1752987863);
INSERT INTO "sessions" VALUES ('lTxOZidIyLJkVNvIfzJXDcgdB3M5BqBa9qdj7urA',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Herd/1.20.2 Chrome/120.0.6099.291 Electron/28.2.5 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiRHg0NGI1dUdLRXc2VEtXY3l0M1QxQU5pU0s1Sjh4NzBUU1UwaExMVSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzc6Imh0dHBzOi8vci1zeXN0ZW0tMy50ZXN0Lz9oZXJkPXByZXZpZXciO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1752988318);
INSERT INTO "sessions" VALUES ('zHCxPAtFy4y9gQuKFJKBQ3wVIro1qdwDGVuJ2ZHj',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Herd/1.20.2 Chrome/120.0.6099.291 Electron/28.2.5 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoibUZpQkxmd2twWUpyUlpiNkFKOEs3dEdSTXJmVVZwMm13SGJNeTJpMCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzc6Imh0dHBzOi8vci1zeXN0ZW0tMy50ZXN0Lz9oZXJkPXByZXZpZXciO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1752988614);
INSERT INTO "sessions" VALUES ('entzfw3JJZqiXBmEG8qNb0oP3VWPQoHSw5Fi5Ypa',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Herd/1.20.2 Chrome/120.0.6099.291 Electron/28.2.5 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiR3Q4UEJCRXRFYmpORDJhOHZpZW5aTXZDUHhjR0JETFk4bnFwY0M1RSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzc6Imh0dHBzOi8vci1zeXN0ZW0tMy50ZXN0Lz9oZXJkPXByZXZpZXciO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1752988862);
INSERT INTO "sessions" VALUES ('KOIiC8Usf37eR9g2geox3EFoCxQRnpiThsfQAnLv',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Herd/1.20.2 Chrome/120.0.6099.291 Electron/28.2.5 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiWkpmZGFDN0UyR0NOZllWTUkzR2tBZTN2Vk40TGxEUURrOGpENWZwZSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzc6Imh0dHBzOi8vci1zeXN0ZW0tMy50ZXN0Lz9oZXJkPXByZXZpZXciO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1752988863);
INSERT INTO "sessions" VALUES ('Eau716R9aKcGt5NXYWdVjOWuEK0UrC0x2VflIyDK',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Herd/1.20.2 Chrome/120.0.6099.291 Electron/28.2.5 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiSTd4QUZwUGZ5bzBBRFowWFY2THFVMGZvTDRlSUc0UjZ3eWlPR2VmVyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzc6Imh0dHBzOi8vci1zeXN0ZW0tMy50ZXN0Lz9oZXJkPXByZXZpZXciO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1752989128);
INSERT INTO "sessions" VALUES ('pv2IrbJKWx2tx4y1Erd8DupvB41Vfc2Eju0gfgs1',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Herd/1.20.2 Chrome/120.0.6099.291 Electron/28.2.5 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiaVEzRGF0RW53R0o0MlBrQUxvZzVKNmZ6TnRIcGYydVBtRllqTmFjOCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzc6Imh0dHBzOi8vci1zeXN0ZW0tMy50ZXN0Lz9oZXJkPXByZXZpZXciO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1752989128);
INSERT INTO "sessions" VALUES ('ULxNDkuH4ICX1NuxAUqYdFniZXGaxcVERAWzIgbi',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Herd/1.20.2 Chrome/120.0.6099.291 Electron/28.2.5 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoic3k0eG5zZmt4Y1JhaWNRUjdoV1huSVhvUFg0N2U4ajdVWUxBMnA4RSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzc6Imh0dHBzOi8vci1zeXN0ZW0tMy50ZXN0Lz9oZXJkPXByZXZpZXciO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1752989206);
INSERT INTO "sessions" VALUES ('3rbQ04jOauasu5qCe1m8aenl5PwOopbvXqvzsE9I',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Herd/1.20.2 Chrome/120.0.6099.291 Electron/28.2.5 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoieGxDTGluTXVYQkdhRjlBUVgxNlpsNW12WnVKWlpKMDdHYlB2OE1jaCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzc6Imh0dHBzOi8vci1zeXN0ZW0tMy50ZXN0Lz9oZXJkPXByZXZpZXciO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1752989206);
INSERT INTO "sessions" VALUES ('Jik28PP3IqYL0bdCAr6uf13KzwhY3iiyLPxR3WRb',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Herd/1.20.2 Chrome/120.0.6099.291 Electron/28.2.5 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiNklieHIzQjV6YzlGUE9zTHhuS3FQMkFGaVc4NnRMSXpyVVU4T1NjYiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzc6Imh0dHBzOi8vci1zeXN0ZW0tMy50ZXN0Lz9oZXJkPXByZXZpZXciO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1752989207);
INSERT INTO "sessions" VALUES ('cuBs0VYFVpE9S2AxehU7WDnem11n0mOWJhefdacG',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Herd/1.20.2 Chrome/120.0.6099.291 Electron/28.2.5 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiNUVLRWxFczAzZ3lLV0hqbFk0eURYSWdXT2lXMUUxTTdwYkRaN0NNSiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzc6Imh0dHBzOi8vci1zeXN0ZW0tMy50ZXN0Lz9oZXJkPXByZXZpZXciO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1752989221);
INSERT INTO "sessions" VALUES ('JlfWTt4NNE68gbTMqlE9G6uO9hDtDYs4B9Sxqsef',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Herd/1.20.2 Chrome/120.0.6099.291 Electron/28.2.5 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoibkdxd0Q3Tm1qelpmd3pkZDdNN0pWOVpPNzdsbTNuczVHOGxqdWFiaiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzc6Imh0dHBzOi8vci1zeXN0ZW0tMy50ZXN0Lz9oZXJkPXByZXZpZXciO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1752989636);
INSERT INTO "sessions" VALUES ('b4nR0uBp5f9AnnY8S1A7frlXD2ZjEvsj3e2bXlrB',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Herd/1.20.2 Chrome/120.0.6099.291 Electron/28.2.5 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiTW90SU5yemZUUUlpb2xGcHZKUkxJem05UHhMZ0d5NjNLUVFoazFkNiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzc6Imh0dHBzOi8vci1zeXN0ZW0tMy50ZXN0Lz9oZXJkPXByZXZpZXciO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1752989637);
INSERT INTO "sessions" VALUES ('Pcf0h9sNoss4obvgDgdEvUwaPUFt8BoIeWLHDIJe',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Herd/1.20.2 Chrome/120.0.6099.291 Electron/28.2.5 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiYjJ0WWhTbllRZEZhMFVDcnY4TGp1UHQzWjhWYnNQdDN6RnlXRDc0VyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzc6Imh0dHBzOi8vci1zeXN0ZW0tMy50ZXN0Lz9oZXJkPXByZXZpZXciO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1752989644);
INSERT INTO "sessions" VALUES ('EscDZT4CT8vvVo7oH0Wx6eSePpNuVkBilr7Fdz55',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Herd/1.20.2 Chrome/120.0.6099.291 Electron/28.2.5 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoidXZ4Z1VPMUxUbVB1WFA1Q2pnUDhLUGp6RHBaRzdTc1Zuak5LVGUyVyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzc6Imh0dHBzOi8vci1zeXN0ZW0tMy50ZXN0Lz9oZXJkPXByZXZpZXciO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1752989644);
INSERT INTO "sessions" VALUES ('GI68Ahju176Ox2Ic76WSfO2rgNzFXhiFgcgxxbLb',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Herd/1.20.2 Chrome/120.0.6099.291 Electron/28.2.5 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoibkl3TXBxSHRVOHpZU0VDWE1aWE0wUDF0UkRhU3JXMmlBQmRoc0ZFZCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzc6Imh0dHBzOi8vci1zeXN0ZW0tMy50ZXN0Lz9oZXJkPXByZXZpZXciO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1752989654);
INSERT INTO "sessions" VALUES ('51dzn8gKsGaEJ6Uom0zPAoKE2p6T4gyRhLubMdf3',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Herd/1.20.2 Chrome/120.0.6099.291 Electron/28.2.5 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiMk5RaWtidDBNQ0Q1TFVYdm1Hc1FrN1puOVpQbndZZ2NWSlRUdlNiYiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzc6Imh0dHBzOi8vci1zeXN0ZW0tMy50ZXN0Lz9oZXJkPXByZXZpZXciO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1752989655);
INSERT INTO "sessions" VALUES ('MvUutgtUPtyfv97b9GYhK2lLoBqbw78So5qgSLN8',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Herd/1.20.2 Chrome/120.0.6099.291 Electron/28.2.5 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiODgzNmZiUFNUZXV0aGJGUUZyR1d3Mkd3VFQzY0JoZFM5MFowc1Z4cyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzc6Imh0dHBzOi8vci1zeXN0ZW0tMy50ZXN0Lz9oZXJkPXByZXZpZXciO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1752989655);
INSERT INTO "sessions" VALUES ('jVdOanXl8of3tp2fU74AMouCrrAvlD5tEXT8D6p6',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Herd/1.20.2 Chrome/120.0.6099.291 Electron/28.2.5 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiaHBXc3prWUhZYjIzSzhoZnhmeHFoY0x4WWlsS25mcm9XOWlZM1hBMSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzc6Imh0dHBzOi8vci1zeXN0ZW0tMy50ZXN0Lz9oZXJkPXByZXZpZXciO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1752989726);
INSERT INTO "sessions" VALUES ('hhflIKFw4r55B1byR9jpnO9EeOCpDM1zUWrZi4SD',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Herd/1.20.2 Chrome/120.0.6099.291 Electron/28.2.5 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiRHBJdzlDZEdNSUx5VVRybEc4WE85eVVwMGF4WWg3STU0YVZ1R2dIWiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzc6Imh0dHBzOi8vci1zeXN0ZW0tMy50ZXN0Lz9oZXJkPXByZXZpZXciO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1752989727);
INSERT INTO "sessions" VALUES ('1W4oOwk3JCU9rKvXNuwh44ygN4vrS4kpbCfgUQ9e',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Herd/1.20.2 Chrome/120.0.6099.291 Electron/28.2.5 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiRnFIcjhrenZpeTlnSFpYcDFYMnIwSGNTYmNHWUgyYmpwQ0pxVnRVQyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzc6Imh0dHBzOi8vci1zeXN0ZW0tMy50ZXN0Lz9oZXJkPXByZXZpZXciO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1752989727);
INSERT INTO "sessions" VALUES ('nN5mni87AUwhiE42QKJc2tXSEALzyWYNzbL8xW71',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Herd/1.20.2 Chrome/120.0.6099.291 Electron/28.2.5 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiM1FvMEs1dXdvbW02RENxT21Xb3dsWjRqNkJWR3RRYVgwUXY0UkJiZiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzc6Imh0dHBzOi8vci1zeXN0ZW0tMy50ZXN0Lz9oZXJkPXByZXZpZXciO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1752989734);
INSERT INTO "sessions" VALUES ('valr4J2sWrFNPgG4SYUxCtwl9GY6d4zqcJcElggR',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Herd/1.20.2 Chrome/120.0.6099.291 Electron/28.2.5 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiZHFHN0EyVXVOYldGOUJoMmVBbmw3aFFRN3ZSUXRMaHJMT0ZvRjFpTSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzc6Imh0dHBzOi8vci1zeXN0ZW0tMy50ZXN0Lz9oZXJkPXByZXZpZXciO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1752989734);
INSERT INTO "sessions" VALUES ('c9zxASRfWdu0sxBsaJpm4vU4LTxUYQrl5Cf7qSKh',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Herd/1.20.2 Chrome/120.0.6099.291 Electron/28.2.5 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiTmw2OGE2RjZDaGszNlZPVG1SdFFRVHVDU2NBUXhUTVB0eDA3bjY4diI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzc6Imh0dHBzOi8vci1zeXN0ZW0tMy50ZXN0Lz9oZXJkPXByZXZpZXciO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1752989735);
INSERT INTO "sessions" VALUES ('PTzvpOAK7KPp2IuV0ovnBjVYsRwVEWTwrCE7w2BH',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Herd/1.20.2 Chrome/120.0.6099.291 Electron/28.2.5 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiZHNhTzRPdTRlRE9vdUtVMlFsQTVCaWIzeVhNZmV1TVRoc0dodWhadSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzc6Imh0dHBzOi8vci1zeXN0ZW0tMy50ZXN0Lz9oZXJkPXByZXZpZXciO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1752989762);
INSERT INTO "sessions" VALUES ('2oxf2pW3sFHemozWUgG3bPt5u60ECrWz9fEGBQ4E',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Herd/1.20.2 Chrome/120.0.6099.291 Electron/28.2.5 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiaXRlSTRQWVh1Q0lWMHZ6bVdLZGU0MGxWcU4xQ2l1Mkk3bWJ5ajJEYiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzc6Imh0dHBzOi8vci1zeXN0ZW0tMy50ZXN0Lz9oZXJkPXByZXZpZXciO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1752989763);
INSERT INTO "sessions" VALUES ('9nt43C1lqE9s4ARHZUxEOg0izcabDBeyXpVsLnjG',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Herd/1.20.2 Chrome/120.0.6099.291 Electron/28.2.5 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoieDhtWWM0NXBrcG9MamZYR3E1V3RuRzRBaVhiY3lVUE5PNEhnS01jTiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzc6Imh0dHBzOi8vci1zeXN0ZW0tMy50ZXN0Lz9oZXJkPXByZXZpZXciO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1752989763);
INSERT INTO "sessions" VALUES ('Tf874GMIP3GhDXpRA9evaxMlAiQ1K2FALbXgzWiX',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Herd/1.20.2 Chrome/120.0.6099.291 Electron/28.2.5 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiYkxGZGg1Y1RxanRkT3V3N0RvMWZPVnBZUjZadmVwc1M5a1A4UVdiOSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzc6Imh0dHBzOi8vci1zeXN0ZW0tMy50ZXN0Lz9oZXJkPXByZXZpZXciO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1752990014);
INSERT INTO "sessions" VALUES ('r8f4X7PBGak8QwEjpKy8AyM4GmyKdoql4bKgKRCZ',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Herd/1.20.2 Chrome/120.0.6099.291 Electron/28.2.5 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiamcwaUNDZGU4TWhyNUdwdEVVU3RKa0RscVBjN2lFWllzWktJRzlJNyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzc6Imh0dHBzOi8vci1zeXN0ZW0tMy50ZXN0Lz9oZXJkPXByZXZpZXciO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1752990014);
INSERT INTO "sessions" VALUES ('wNKuOFEccz5dUaDRjfA6cVddNdRv6i1JnQCpmzOd',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Herd/1.20.2 Chrome/120.0.6099.291 Electron/28.2.5 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiZEQwTEFFYmxmU3BMRHRNZU5TOUJQc0I1ZGk1bkNyZkFLQ1MyNDV4dyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzc6Imh0dHBzOi8vci1zeXN0ZW0tMy50ZXN0Lz9oZXJkPXByZXZpZXciO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1752990014);
INSERT INTO "sessions" VALUES ('F0X1rowRqGuP5OGsWR4BunFcI2R2RTaQMd1HLeMe',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Herd/1.20.2 Chrome/120.0.6099.291 Electron/28.2.5 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoid0lNQ3pXZGx5aTRVUkRoeVBJM2JJTTV0N3lvUnNVaWFsVlNVdmxsZCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzc6Imh0dHBzOi8vci1zeXN0ZW0tMy50ZXN0Lz9oZXJkPXByZXZpZXciO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1752990019);
INSERT INTO "sessions" VALUES ('x5Kfcp6J0Dq3T0uY1D6eoyKM3V5sZSHVNCzkgp4N',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Herd/1.20.2 Chrome/120.0.6099.291 Electron/28.2.5 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiSjFKR2FsNmF1YTVaSldOZVp3UEM3RlhYOFlURXY1Uk13V2RtYVoxdSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzc6Imh0dHBzOi8vci1zeXN0ZW0tMy50ZXN0Lz9oZXJkPXByZXZpZXciO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1752990020);
INSERT INTO "sessions" VALUES ('Pg6Zcrkzmt75k4dSp9ibNRhFCewN6t3tnReD5hkT',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Herd/1.20.2 Chrome/120.0.6099.291 Electron/28.2.5 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiTjJxdUtiTnlYZzkwMkVaRlhNUmc0SXRZQm1BM0Q1WXE5MlVkOW9MQiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzc6Imh0dHBzOi8vci1zeXN0ZW0tMy50ZXN0Lz9oZXJkPXByZXZpZXciO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1752990020);
INSERT INTO "sessions" VALUES ('XCeLubRnuZHrZHNqPmjrHMtzFGx3ULp6XhKhY2M2',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Herd/1.20.2 Chrome/120.0.6099.291 Electron/28.2.5 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiUHZZUDBLVVdzSFJnTlhzTGxEY1hjaUJKSjFBaGp2aFRuenFvUGdDTSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzc6Imh0dHBzOi8vci1zeXN0ZW0tMy50ZXN0Lz9oZXJkPXByZXZpZXciO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1752990037);
INSERT INTO "sessions" VALUES ('9rfF7BzpDvp00WkQPeUBcxh3s2Q5nDEOz1AhhwY6',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Herd/1.20.2 Chrome/120.0.6099.291 Electron/28.2.5 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoia1NNR3pyMTlEREZQTHdOTGtVMTdmblJrZ1FGOWtuNUFDNUFpUXA1WiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzc6Imh0dHBzOi8vci1zeXN0ZW0tMy50ZXN0Lz9oZXJkPXByZXZpZXciO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1752990209);
INSERT INTO "sessions" VALUES ('Qn02ixAWimDzPZ0wkF6dcGDBLeViW1XV5is00zMN',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Herd/1.20.2 Chrome/120.0.6099.291 Electron/28.2.5 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiWFkxWnpDVFdscUpnNzBjdHNVeWpkeDVOWG5SSXR3N2QwcFBXMHFKSiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzc6Imh0dHBzOi8vci1zeXN0ZW0tMy50ZXN0Lz9oZXJkPXByZXZpZXciO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1752990216);
INSERT INTO "sessions" VALUES ('3bPk6xxAdLnNYHVpgpviyuBFHTogXoWuFOS8rpA1',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Herd/1.20.2 Chrome/120.0.6099.291 Electron/28.2.5 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiYjJGTmdKcHJwRXlPVnA5TWI0WFN6MUZiS1RuY2dLZ1E1UFg5QUVQbiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzc6Imh0dHBzOi8vci1zeXN0ZW0tMy50ZXN0Lz9oZXJkPXByZXZpZXciO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1752990375);
INSERT INTO "sessions" VALUES ('t2IMZNT5gl98Cydh6rUNAs6xfZ0tjsQt1qLpCew6',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Herd/1.20.2 Chrome/120.0.6099.291 Electron/28.2.5 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiUjFLNXJ6V01kUlh0bFV1R2ZWbTdZdmpMT0JvSXZiWE1QZTlta2pPdCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzc6Imh0dHBzOi8vci1zeXN0ZW0tMy50ZXN0Lz9oZXJkPXByZXZpZXciO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1752990376);
INSERT INTO "sessions" VALUES ('tKcgHrfxIzYR69tp8s9EcGAMt6RiPA24SxwJO53v',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Herd/1.20.2 Chrome/120.0.6099.291 Electron/28.2.5 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiOTZndTFRUlBCWkw1TllDd1I2bTJiY25UREk0RnJpSDNmbE5mMDh0VSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzc6Imh0dHBzOi8vci1zeXN0ZW0tMy50ZXN0Lz9oZXJkPXByZXZpZXciO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1752990553);
INSERT INTO "sessions" VALUES ('xeKvMriOcgScD9jBBrFI4uS99lKtkKJ9G1LLx2vf',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Herd/1.20.2 Chrome/120.0.6099.291 Electron/28.2.5 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiZmk5dktibTV4NE5MT0JTR2N3SlRiemRZYVhsY3pBekh4NnZmTld4biI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzc6Imh0dHBzOi8vci1zeXN0ZW0tMy50ZXN0Lz9oZXJkPXByZXZpZXciO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1752990605);
INSERT INTO "sessions" VALUES ('TLM5RMd7yqDWSK5XjCHLjUos4UiM5Lrz0jI9wDVD',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Herd/1.20.2 Chrome/120.0.6099.291 Electron/28.2.5 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiNko5d2plbUF6UG4xOVV6QjJ6NWEzSkNYWnFVZEhwTmVDTUlTMmZBOCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzc6Imh0dHBzOi8vci1zeXN0ZW0tMy50ZXN0Lz9oZXJkPXByZXZpZXciO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1752990610);
INSERT INTO "sessions" VALUES ('nZdeqREIn7cjNkZXhqWiRMb63Fb9XAW8wHMxW0cm',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Herd/1.20.2 Chrome/120.0.6099.291 Electron/28.2.5 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoibmI1RGZLQjJXQkh3THp4VnhxcTB3V0VMS3Q0VzNCek53MjcxelpWSSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzc6Imh0dHBzOi8vci1zeXN0ZW0tMy50ZXN0Lz9oZXJkPXByZXZpZXciO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1752990796);
INSERT INTO "sessions" VALUES ('k64eQyDrubC5zJ9NmFH71OOvdsGj3ZVtMjpSDVQT',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Herd/1.20.2 Chrome/120.0.6099.291 Electron/28.2.5 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiemFHSEVVQ0F0S2ZoaDlvd3pLU0N3R1NIUG5hU1JWVXNXTXIxUjFSdSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzc6Imh0dHBzOi8vci1zeXN0ZW0tMy50ZXN0Lz9oZXJkPXByZXZpZXciO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1752990796);
INSERT INTO "sessions" VALUES ('NH7mgVE4LjPsNmIDI7A44GjZHmes8mO1lXjZRBmS',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Herd/1.20.2 Chrome/120.0.6099.291 Electron/28.2.5 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiTjhweUdOSHVqejYyZmdrQ3p4RXdHdU51dGZZZGNVd203VmFsY0hXQSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzc6Imh0dHBzOi8vci1zeXN0ZW0tMy50ZXN0Lz9oZXJkPXByZXZpZXciO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1752990801);
INSERT INTO "sessions" VALUES ('0rDrtrEfQE6CZDeO1EEUdfZbosx9ezlu5igwft0P',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Herd/1.20.2 Chrome/120.0.6099.291 Electron/28.2.5 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiSkRCSmpibVlEaGdWMjlvbHZEQmVRcnB0WmtVUm5Id2lKTHJ1d05CcyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzc6Imh0dHBzOi8vci1zeXN0ZW0tMy50ZXN0Lz9oZXJkPXByZXZpZXciO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1752990802);
INSERT INTO "sessions" VALUES ('ifpRKNMJIrg88YkWFGLpeEciaux0XRaoM9nIAEat',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Herd/1.20.2 Chrome/120.0.6099.291 Electron/28.2.5 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiYUd3djdwMDl0Qm5US2I4Q1p0dG9TSnQ2SzhMQ1V5Wmt1R1V2RmJkUyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzc6Imh0dHBzOi8vci1zeXN0ZW0tMy50ZXN0Lz9oZXJkPXByZXZpZXciO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1752990802);
INSERT INTO "sessions" VALUES ('cuHEJnX8LXnX6zUCOt0fycBPSUBOxcgPvL86pszM',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Herd/1.20.2 Chrome/120.0.6099.291 Electron/28.2.5 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiUVU5aFpZSFRtZnI1V05XdVZ3WW1vWmtPMG54ckZubDhUMjJQeTVtNCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzc6Imh0dHBzOi8vci1zeXN0ZW0tMy50ZXN0Lz9oZXJkPXByZXZpZXciO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1752990803);
INSERT INTO "sessions" VALUES ('ZDstQO1DQHN5GwB9hL2E6sg8nNkDNFjG76x56fng',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Herd/1.20.2 Chrome/120.0.6099.291 Electron/28.2.5 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiMUFJNDRLNHJycjBieW5XcXNlY2RzZDZ0dTVCcUMySnRqaVJVd2dUTSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzc6Imh0dHBzOi8vci1zeXN0ZW0tMy50ZXN0Lz9oZXJkPXByZXZpZXciO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1752990803);
INSERT INTO "sessions" VALUES ('oLYmxrIaEkrFsL3ZFsQFB47Sr8USboEQ9qNgfdtW',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Herd/1.20.2 Chrome/120.0.6099.291 Electron/28.2.5 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoic3R6Y3B5dGhpRjlLQ0FQWW1yRTU4bndSUVdBZDVSaFBXNDdMVloxaiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzc6Imh0dHBzOi8vci1zeXN0ZW0tMy50ZXN0Lz9oZXJkPXByZXZpZXciO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1752990923);
INSERT INTO "sessions" VALUES ('MKz0sR5bOUjXohZjXlH7EOw5S6vWCZxhJcb1JZgJ',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Herd/1.20.2 Chrome/120.0.6099.291 Electron/28.2.5 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoibExNM2xnWDJRNmE1SlFuQkYyd3hubEpNTERYaWczcGZleGtRbWFNNSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzc6Imh0dHBzOi8vci1zeXN0ZW0tMy50ZXN0Lz9oZXJkPXByZXZpZXciO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1752990923);
INSERT INTO "sessions" VALUES ('WWd6kuTgLnN3zn5ykoC20XIj2Tokzx0AC0eiUoLP',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Herd/1.20.2 Chrome/120.0.6099.291 Electron/28.2.5 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiSHMzQWQ0eWg2cGs0S1hmaExTd3pZeUxjc3k5dm11akFsd1NoR0NxdiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzc6Imh0dHBzOi8vci1zeXN0ZW0tMy50ZXN0Lz9oZXJkPXByZXZpZXciO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1752990925);
INSERT INTO "sessions" VALUES ('qX98EJneoSY3oWehcoVijapcYWSkfZ9lQTA2yHjN',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Herd/1.20.2 Chrome/120.0.6099.291 Electron/28.2.5 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiZ0FQOVJZS1NZRkpTa1NpbXRpbTRwM2pZa2VEYkVuU092c2x4SXpWWiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzc6Imh0dHBzOi8vci1zeXN0ZW0tMy50ZXN0Lz9oZXJkPXByZXZpZXciO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1752990929);
INSERT INTO "sessions" VALUES ('Sk487k4WHH23xOPnLc8dcad4GLpZQqq7g7eMpPkQ',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Herd/1.20.2 Chrome/120.0.6099.291 Electron/28.2.5 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiNXVLaHlMNmlUVlZCdmRtdWM2VER5SnlIRHdHZUpkdVM1V0ZVcHphUSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzc6Imh0dHBzOi8vci1zeXN0ZW0tMy50ZXN0Lz9oZXJkPXByZXZpZXciO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1752990929);
INSERT INTO "sessions" VALUES ('cwuJj6BZZVf0R9qMlxKeaSUs378No8LAXOk84Euq',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Herd/1.20.2 Chrome/120.0.6099.291 Electron/28.2.5 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoicFVtMDh3TTdyZ1lvQ25sMW5Nbk9qdjEwUkl4YlpRVERGMUZ4S1R4bSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzc6Imh0dHBzOi8vci1zeXN0ZW0tMy50ZXN0Lz9oZXJkPXByZXZpZXciO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1752990930);
INSERT INTO "sessions" VALUES ('QF2wcYS9FYBtAKYrKuGnsHKSm1OvRlyxWxFNbsUL',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Herd/1.20.2 Chrome/120.0.6099.291 Electron/28.2.5 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiRmpOOTZWWk52WDVoaEY3cFp1WHFRS2Npd3FYMXNTc1pJODhsellNbSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzc6Imh0dHBzOi8vci1zeXN0ZW0tMy50ZXN0Lz9oZXJkPXByZXZpZXciO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1752990937);
INSERT INTO "sessions" VALUES ('TBR4roUDJXso7WIfzKXyy6r1rT1qM3ezMeuU9n9o',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Herd/1.20.2 Chrome/120.0.6099.291 Electron/28.2.5 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiRnI1RzVMTGsxMVI1UU1Rc1o3MkNrbTY2UHNqZ1NsMVVHZ1Vyd3NMbyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzc6Imh0dHBzOi8vci1zeXN0ZW0tMy50ZXN0Lz9oZXJkPXByZXZpZXciO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1752990980);
INSERT INTO "sessions" VALUES ('ieSjBkoGW5gieWy5UFrrs7NFS4UFv8oODXTiC5rV',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Herd/1.20.2 Chrome/120.0.6099.291 Electron/28.2.5 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoibFVHUTJ0YktwSVQ4VFlxZ0JYNnZZOEZZM1haM0lPaVY3UDE5UVZzMCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzc6Imh0dHBzOi8vci1zeXN0ZW0tMy50ZXN0Lz9oZXJkPXByZXZpZXciO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1752990980);
INSERT INTO "sessions" VALUES ('d967XQuVl2i5imIDN7wryxJ9GPTfPPlGLIidpGuQ',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Herd/1.20.2 Chrome/120.0.6099.291 Electron/28.2.5 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoib0xQZmNjRlkxVWRLcTEwem9OQUJaeXNWM2hjR25reE5CZUJySEJsVyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzc6Imh0dHBzOi8vci1zeXN0ZW0tMy50ZXN0Lz9oZXJkPXByZXZpZXciO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1752990981);
INSERT INTO "sessions" VALUES ('k3KaZwKgqv8H6WmDAHoIZd4Tg0unLx66QIiI3qbt',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Herd/1.20.2 Chrome/120.0.6099.291 Electron/28.2.5 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiclNwcXB5VFJ5RE1EYUNOOFRzSE9IQWxzcUp0RWpYR3R0d0I5bnR1cyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzc6Imh0dHBzOi8vci1zeXN0ZW0tMy50ZXN0Lz9oZXJkPXByZXZpZXciO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1752990981);
INSERT INTO "sessions" VALUES ('tj1mbjz5ppyo3CRgbrHvPsrjUHvPOOfHJkGBDg5h',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Herd/1.20.2 Chrome/120.0.6099.291 Electron/28.2.5 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiUVZ3dUp1d2tWaVRaWnI4Z2pXa1RGbzMzRFpDZ1JlSkQ1YzRSWFh4TiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzc6Imh0dHBzOi8vci1zeXN0ZW0tMy50ZXN0Lz9oZXJkPXByZXZpZXciO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1752991202);
INSERT INTO "sessions" VALUES ('o9CdAh0ymXafpXfXuHvFb91NG7stNlZQDuEuF1fb',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Herd/1.20.2 Chrome/120.0.6099.291 Electron/28.2.5 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiTVFHSWpBYUozN2NsUTlZTnplV2Faa0hxck9yMmYzSFM0a0Y3ZDRFdyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzc6Imh0dHBzOi8vci1zeXN0ZW0tMy50ZXN0Lz9oZXJkPXByZXZpZXciO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1752991202);
INSERT INTO "sessions" VALUES ('Uo6H90Z6n3i8zr8GWnz2HDvgthM9mFLWA58vQqUm',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Herd/1.20.2 Chrome/120.0.6099.291 Electron/28.2.5 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiTlNpd2pnaGtISDhGQndqc2hkMEJkV3kyaGt0N2twVEJEWTJMU2lrNSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzc6Imh0dHBzOi8vci1zeXN0ZW0tMy50ZXN0Lz9oZXJkPXByZXZpZXciO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1752991231);
INSERT INTO "sessions" VALUES ('FBcgdGxLdOJH7ddLQ71TYaesPOa2vvwhVktYhiB4',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Herd/1.20.2 Chrome/120.0.6099.291 Electron/28.2.5 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiMlhsMGYxdFBzWGJ1Z3ZVdVRoWFUwckwydUZtVkZoNlZINWxHRnhCTSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzc6Imh0dHBzOi8vci1zeXN0ZW0tMy50ZXN0Lz9oZXJkPXByZXZpZXciO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1752991299);
INSERT INTO "sessions" VALUES ('M4PWPdpdXupDPev16EymOohsOzPrP7mHWVVAvfgI',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Herd/1.20.2 Chrome/120.0.6099.291 Electron/28.2.5 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiekxxQjF5RUpqNFV4cjhoVW44UncwNjlwT2hxVUd2VGhFbGExMDlIcyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzc6Imh0dHBzOi8vci1zeXN0ZW0tMy50ZXN0Lz9oZXJkPXByZXZpZXciO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1752991299);
INSERT INTO "sessions" VALUES ('SzlHuElWw3Ndys4K3iHjyUB7Wx1GzZEoVfHOA3aR',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Herd/1.20.2 Chrome/120.0.6099.291 Electron/28.2.5 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiVDFDNGRCNTBzUDBlSHF5bHRZSEczWDRQZWFDTlljZ3Q4OXk5bFI5WSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzc6Imh0dHBzOi8vci1zeXN0ZW0tMy50ZXN0Lz9oZXJkPXByZXZpZXciO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1752991299);
INSERT INTO "sessions" VALUES ('MVbvGxbSEcqgj4HUOFMHRYxQtnKTrDfO2BRktttv',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Herd/1.20.2 Chrome/120.0.6099.291 Electron/28.2.5 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiUUdzUG1WV1hhZEQwMlMwMFdJclZYNmtJNVVFZEszNFJjTWVySXJKTSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzc6Imh0dHBzOi8vci1zeXN0ZW0tMy50ZXN0Lz9oZXJkPXByZXZpZXciO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1752991300);
INSERT INTO "sessions" VALUES ('tWTgxYXwSVBrMdoWO3X1VqYBgFZyRwAYrRmMNF4s',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Herd/1.20.2 Chrome/120.0.6099.291 Electron/28.2.5 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoieGc4SlRRT3pzQzdFVDViSTdjckp1eDN2ZWFranBFN0tKQkEzcGNWSiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzc6Imh0dHBzOi8vci1zeXN0ZW0tMy50ZXN0Lz9oZXJkPXByZXZpZXciO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1752991300);
INSERT INTO "sessions" VALUES ('Ur6hQDU09C7AvUjsrByMClnrzdIyv4TvHwF0Yhxc',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Herd/1.20.2 Chrome/120.0.6099.291 Electron/28.2.5 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiOHMzYjlCQ3J5anNHWkdEU2Via3JDb1BqUnRQMFdWeEV5TURJUFgycSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzc6Imh0dHBzOi8vci1zeXN0ZW0tMy50ZXN0Lz9oZXJkPXByZXZpZXciO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1752991310);
INSERT INTO "sessions" VALUES ('7tkcM9eiqhNSaXCVv2bHmk322gdyor53IRX9JD1S',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Herd/1.20.2 Chrome/120.0.6099.291 Electron/28.2.5 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiSGFOb0xWMDdmYjJzNEZMakdTek5yaHpaVEcwRFQ4RTh5b2tjWFFhTSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzc6Imh0dHBzOi8vci1zeXN0ZW0tMy50ZXN0Lz9oZXJkPXByZXZpZXciO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1752991310);
INSERT INTO "sessions" VALUES ('ZaNcAsiGp8vagHdtrnVtYKeX1dJDp5wP0pjjGANs',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Herd/1.20.2 Chrome/120.0.6099.291 Electron/28.2.5 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiRUxzTktIYk5MT0lZcWxheG5waVZsNEx3dW92R0c4V1VoRDN5T2JUUCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzc6Imh0dHBzOi8vci1zeXN0ZW0tMy50ZXN0Lz9oZXJkPXByZXZpZXciO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1752992098);
INSERT INTO "sessions" VALUES ('Ab5qRUmm8KjnxLkE7rARTyyjd2jwygTN5EUfC9hW',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Herd/1.20.2 Chrome/120.0.6099.291 Electron/28.2.5 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiZ0lIWGJacDVXZzdlaG1XTGJNdWVnOUZ4eVJKZ1djeld2Qm9qT1BsZSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzc6Imh0dHBzOi8vci1zeXN0ZW0tMy50ZXN0Lz9oZXJkPXByZXZpZXciO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1752992098);
INSERT INTO "sessions" VALUES ('QeoHz1CpdJp1alL7Lt5ksCxSpN4SsWNlDG6K7eKJ',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Herd/1.20.2 Chrome/120.0.6099.291 Electron/28.2.5 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoidFBOMWI5ZjdvSFRiaVpTVzk3NWVFWE5HRWoyTUw4dVBXaWNKcXpOUSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzc6Imh0dHBzOi8vci1zeXN0ZW0tMy50ZXN0Lz9oZXJkPXByZXZpZXciO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1752992099);
INSERT INTO "sessions" VALUES ('bRbbG2VxY98a8hJT2ymjI0g4w5gEyUAtdF5mNNw6',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Herd/1.20.2 Chrome/120.0.6099.291 Electron/28.2.5 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiSm5CTmlleE1tRnFtNzFwTkJzSXY2ZlRFS09PRlFlQ0VnODRXWFhrOCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzc6Imh0dHBzOi8vci1zeXN0ZW0tMy50ZXN0Lz9oZXJkPXByZXZpZXciO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1752992100);
INSERT INTO "sessions" VALUES ('kKXI7Dz3EKANni3BwUBlKaUPhIsN4pCAcI4CybeO',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Herd/1.20.2 Chrome/120.0.6099.291 Electron/28.2.5 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiSWVHMVFXSGM1VGJKYWVBVEE2MG5VVEpHVjJmZmFvb1dGcWxKMEZ4eiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzc6Imh0dHBzOi8vci1zeXN0ZW0tMy50ZXN0Lz9oZXJkPXByZXZpZXciO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1752992100);
INSERT INTO "sessions" VALUES ('7902A9PYHY0PrXHrPkSlHEmSqgRoutQvFaBP1p0A',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Herd/1.20.2 Chrome/120.0.6099.291 Electron/28.2.5 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiZmM5VWJyMHk5RjQzRFdNTG5yT3k4cEJnU3pFd0xSWUxIMmtQanlkRSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzc6Imh0dHBzOi8vci1zeXN0ZW0tMy50ZXN0Lz9oZXJkPXByZXZpZXciO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1752992270);
INSERT INTO "sessions" VALUES ('BcNvSaoyIVxokEtZSM1HigC0936qLj9hewmVdS8V',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Herd/1.20.2 Chrome/120.0.6099.291 Electron/28.2.5 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiR0ZIaHJMUGo5SURzTWRQS0FDNkQ3VlZka0VJSHRXSVhpdGg0cGZ4ZCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzc6Imh0dHBzOi8vci1zeXN0ZW0tMy50ZXN0Lz9oZXJkPXByZXZpZXciO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1752992270);
INSERT INTO "sessions" VALUES ('Yt5Ql2bumFOSUtpWoiC8uGt8MqzIbIp6T4zYHz43',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Herd/1.20.2 Chrome/120.0.6099.291 Electron/28.2.5 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiclBCYW9qQjFhOXNQbklMSmpUSTdqWFBVSW52S0dRQ1BVQzBEZTdFNiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzc6Imh0dHBzOi8vci1zeXN0ZW0tMy50ZXN0Lz9oZXJkPXByZXZpZXciO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1752992271);
INSERT INTO "sessions" VALUES ('j3NPWjjfX38JMeMnblGXzjotpZLnTsiyx3kabSr5',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Herd/1.20.2 Chrome/120.0.6099.291 Electron/28.2.5 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoid2h5Tmg0c21CeDU0V25DTW5EREpmVlBpc1VhWVhiSWVFZ0F2MGVXMSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzc6Imh0dHBzOi8vci1zeXN0ZW0tMy50ZXN0Lz9oZXJkPXByZXZpZXciO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1752992272);
INSERT INTO "sessions" VALUES ('30t5Qj2FuhOqIMTpDCcChrLAk2GN4RiMfUDy6TFi',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Herd/1.20.2 Chrome/120.0.6099.291 Electron/28.2.5 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiRm5xbk1QdEpxbUs4MTl5UllGYWR4RkJGSWMwMWVreElEOERqSjhnSiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzc6Imh0dHBzOi8vci1zeXN0ZW0tMy50ZXN0Lz9oZXJkPXByZXZpZXciO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1752992273);
INSERT INTO "sessions" VALUES ('OgliAgiZJNxEuYpue2KVba5xMFNjRZyLNGoXNDa9',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Herd/1.20.2 Chrome/120.0.6099.291 Electron/28.2.5 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiTDJlQVNMZ1Rvb1hsRFNyWGM4YXh4dUJGMXNCTWhwUmlIZG9rQWNjMyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzc6Imh0dHBzOi8vci1zeXN0ZW0tMy50ZXN0Lz9oZXJkPXByZXZpZXciO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1752992312);
INSERT INTO "sessions" VALUES ('MNcIrEImHUxI7F8pwoEsJe4SOhp8o49DhCqbusZv',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Herd/1.20.2 Chrome/120.0.6099.291 Electron/28.2.5 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiSDNKSlJPdExJQllyYU1DbFJNN21BdDdSUXhabU9OdFgxME02aEVmTiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzc6Imh0dHBzOi8vci1zeXN0ZW0tMy50ZXN0Lz9oZXJkPXByZXZpZXciO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1752992312);
INSERT INTO "sessions" VALUES ('BDsxFPJLCigLlek402V85HIl7sqVmHxnIitrvJtA',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Herd/1.20.2 Chrome/120.0.6099.291 Electron/28.2.5 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiODlTV3F1T0RwWEFkbmRnblU4SzZCM0w1clNUMXl4eXRwWjJ3UGUxdCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzc6Imh0dHBzOi8vci1zeXN0ZW0tMy50ZXN0Lz9oZXJkPXByZXZpZXciO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1752992312);
INSERT INTO "sessions" VALUES ('9J2YGH955TLN6M0suMfXglYcZmyvnAQzAbaE7vqO',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Herd/1.20.2 Chrome/120.0.6099.291 Electron/28.2.5 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiT0dLVWpGZURUcHRwU0dnN1dnVW5pTDZ3RW5XRmFYRFlZQVZObG03RyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzc6Imh0dHBzOi8vci1zeXN0ZW0tMy50ZXN0Lz9oZXJkPXByZXZpZXciO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1752992359);
INSERT INTO "sessions" VALUES ('rpIzlWKXwITck4zRwvaTCbCVDQByXlIKtnRBvqhc',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Herd/1.20.2 Chrome/120.0.6099.291 Electron/28.2.5 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiMlFXbnhWSTN2VGV1ZWF0SlJIOTRBdEFRTmRZOGlYWURvR0U1OHZGbyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzc6Imh0dHBzOi8vci1zeXN0ZW0tMy50ZXN0Lz9oZXJkPXByZXZpZXciO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1752992363);
INSERT INTO "sessions" VALUES ('3vy7qb3DIiH0R3gEQw4PMtCkfCEeJ71F9OtaYWzr',6,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36','YTo1OntzOjY6Il90b2tlbiI7czo0MDoiQ3Z4R1FkR3BzaWJwRDNMSTcxOTFOTmhRZ2p3cld3eEFSME5UTDlZdyI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NTU6Imh0dHBzOi8vci1zeXN0ZW0tMy50ZXN0L3RlYWNoZXIvYXNzaWdubWVudHMvNi9idWxrLWVkaXQiO31zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aTo2O3M6NDoiYXV0aCI7YToxOntzOjIxOiJwYXNzd29yZF9jb25maXJtZWRfYXQiO2k6MTc1Mjk5MjM3NTt9fQ==',1752994954);
INSERT INTO "sessions" VALUES ('lobADWCd8uD6U5x3OzrKGiuHmJt1YCDZJfthptxy',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Herd/1.20.2 Chrome/120.0.6099.291 Electron/28.2.5 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiYWNNR0tZSGVTTDlkNnVneXVkTTZhQU4xVGtrYnpZWWM5Y05UMmQxViI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzc6Imh0dHBzOi8vci1zeXN0ZW0tMy50ZXN0Lz9oZXJkPXByZXZpZXciO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1752992375);
INSERT INTO "sessions" VALUES ('MK3EgHdzzaROOGFAQdQ8mm70BOOLh2NoZCMM7NQC',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Herd/1.20.2 Chrome/120.0.6099.291 Electron/28.2.5 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoibjBoY3FhUEhIc2F3aVpsdXJiRWhubWhmYnU1WDhrWnUxTWlWVGJXTyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzc6Imh0dHBzOi8vci1zeXN0ZW0tMy50ZXN0Lz9oZXJkPXByZXZpZXciO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1752992378);
INSERT INTO "sessions" VALUES ('BOsqqJu8O4vbOAIA26pidChCDO2hbQRw19BFwebG',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Herd/1.20.2 Chrome/120.0.6099.291 Electron/28.2.5 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoic1d3cUFidGRpSzkyRFo3aDZVM0Mwc05BZ3VvcEFYMkdoQmJ4ZlRvdiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzc6Imh0dHBzOi8vci1zeXN0ZW0tMy50ZXN0Lz9oZXJkPXByZXZpZXciO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1752992389);
INSERT INTO "sessions" VALUES ('GUhRNNYTrvgIX0SMZsrhEMeaabdSbsyStNn9tY9i',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Herd/1.20.2 Chrome/120.0.6099.291 Electron/28.2.5 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiNlZjMndVYmRUZWo1TmZuTzdYeDlMNU5rMDF0OUh6blFVYnJ2N2pJWCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzc6Imh0dHBzOi8vci1zeXN0ZW0tMy50ZXN0Lz9oZXJkPXByZXZpZXciO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1752992390);
INSERT INTO "sessions" VALUES ('wn8bbBxdX9w6Uf5lKJAQVqr0SkTjuQRckpoIuSrQ',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Herd/1.20.2 Chrome/120.0.6099.291 Electron/28.2.5 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoicmo0SmNJU051U1NoRjZTaE9wOThERERzOHJqY1hTeVhiV1ZnNjEwaCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzc6Imh0dHBzOi8vci1zeXN0ZW0tMy50ZXN0Lz9oZXJkPXByZXZpZXciO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1752992521);
INSERT INTO "sessions" VALUES ('3bGSoLW4H4SgEsXoatOfaIeRyPLk6AdwWDxvWIPd',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Herd/1.20.2 Chrome/120.0.6099.291 Electron/28.2.5 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiQWZrSHptVDNHS3JXS0VZcFE2Y2M4RTNkeDhRMERUWmtwSnptTlJEbiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzc6Imh0dHBzOi8vci1zeXN0ZW0tMy50ZXN0Lz9oZXJkPXByZXZpZXciO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1752992525);
INSERT INTO "sessions" VALUES ('Nxk6PmOUjEMKJ2xCry4kjLQMm3RUgg34SybNuzcU',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Herd/1.20.2 Chrome/120.0.6099.291 Electron/28.2.5 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiY2xKS3ZPejlEQkpLUVZQQXRyS2VlcXFVNWpRRDU1YjNOMDcybG9NVyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzc6Imh0dHBzOi8vci1zeXN0ZW0tMy50ZXN0Lz9oZXJkPXByZXZpZXciO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1752992527);
INSERT INTO "sessions" VALUES ('sIKpme593pgeHhF1JaOo6cxtYrcJxzPVx8ccHzlg',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Herd/1.20.2 Chrome/120.0.6099.291 Electron/28.2.5 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiVWk0MUtGbzlsOUJiRDRWNnltZlJuWnlmdkQ3cDdHRllPUXZ1R0ZFUyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzc6Imh0dHBzOi8vci1zeXN0ZW0tMy50ZXN0Lz9oZXJkPXByZXZpZXciO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1752992527);
INSERT INTO "sessions" VALUES ('JpYASdg8t6efNr0NUJKD1ljkJJIGKgZP4iaDLTUi',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Herd/1.20.2 Chrome/120.0.6099.291 Electron/28.2.5 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiZEtEOU9kTjg3cDdDdGFZR09MQ29oVmNwNGRESnBoYkdzR0RsMzYwVyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzc6Imh0dHBzOi8vci1zeXN0ZW0tMy50ZXN0Lz9oZXJkPXByZXZpZXciO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1752992698);
INSERT INTO "sessions" VALUES ('fd8Omp1zHuoBHJkAkLX2YsAIhu5XwQhbZ1gUoVjS',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Herd/1.20.2 Chrome/120.0.6099.291 Electron/28.2.5 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoidk1sdnZmZTh0Q2xJd3I2UHhOVXFRcEp3Sk45MTVjYXd4RHp2R3FrUSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzc6Imh0dHBzOi8vci1zeXN0ZW0tMy50ZXN0Lz9oZXJkPXByZXZpZXciO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1752992699);
INSERT INTO "sessions" VALUES ('eHQSc5WXh2nMfmyvLXYFajlCjaXqdrG1CuLcLOaz',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Herd/1.20.2 Chrome/120.0.6099.291 Electron/28.2.5 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiaERYSFd3UGJjMFRQbjZubWtHOWJHQUREQVh0c0d2QlVabVYyRjV1RyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzc6Imh0dHBzOi8vci1zeXN0ZW0tMy50ZXN0Lz9oZXJkPXByZXZpZXciO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1752992849);
INSERT INTO "sessions" VALUES ('NhmAF9A58LtHDaZ8lLe5Aa3bRAEh7SPtUSP1TuM0',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Herd/1.20.2 Chrome/120.0.6099.291 Electron/28.2.5 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoia2xaZEd3aXh5NWNQTlpsT3FBc1BvNWlGanluUk9tcHNSVEdETkdYNCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzc6Imh0dHBzOi8vci1zeXN0ZW0tMy50ZXN0Lz9oZXJkPXByZXZpZXciO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1752992850);
INSERT INTO "sessions" VALUES ('Lku2MF9OA92LSYcTUmrc8a6mFcu2NEx0124pCkCh',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Herd/1.20.2 Chrome/120.0.6099.291 Electron/28.2.5 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiR0o1aVJaTW5HYnBHOENDajJ4MzVEVENQZmViTU5uaUh4bWJueUg1OSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzc6Imh0dHBzOi8vci1zeXN0ZW0tMy50ZXN0Lz9oZXJkPXByZXZpZXciO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1752992914);
INSERT INTO "sessions" VALUES ('ajTIzUif0psTKCaLEct61rFsQm95VaX1XF6JbqEM',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Herd/1.20.2 Chrome/120.0.6099.291 Electron/28.2.5 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoieVlYZWFVSUo0SGZ5OHQzR2tjMmJhMDI2M2lCOThOT2oxNzNrdWpCOCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzc6Imh0dHBzOi8vci1zeXN0ZW0tMy50ZXN0Lz9oZXJkPXByZXZpZXciO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1752992915);
INSERT INTO "sessions" VALUES ('m4FnogRuySp8R8OHx79ga3njNC7mnhTbL6x2Ziwe',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Herd/1.20.2 Chrome/120.0.6099.291 Electron/28.2.5 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoid0lVOG9iZEx4alhoSkEyblRTZTJZd0RZZXJWZ09Bb2JMRzhEUldEdSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzc6Imh0dHBzOi8vci1zeXN0ZW0tMy50ZXN0Lz9oZXJkPXByZXZpZXciO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1752992916);
INSERT INTO "sessions" VALUES ('MFr9YEuec8iIX7oSbILiTSUmcNSz4ABxWkMFb2A1',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Herd/1.20.2 Chrome/120.0.6099.291 Electron/28.2.5 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoidkhhWDVSM3dzUXJhSlc1bmgyVXRJSm1EYjQzR3VIVWpkNVZ4bHMydiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzc6Imh0dHBzOi8vci1zeXN0ZW0tMy50ZXN0Lz9oZXJkPXByZXZpZXciO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1752992939);
INSERT INTO "sessions" VALUES ('bd5YtwHhEEEao5UOCktX4xQVauoA6sH5HCTVTfb3',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Herd/1.20.2 Chrome/120.0.6099.291 Electron/28.2.5 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiWVJOTkhCY29FRDNIcVk1NUoyT2tqYmRVRWhtdmpJVzNNbVF0SGJKeiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzc6Imh0dHBzOi8vci1zeXN0ZW0tMy50ZXN0Lz9oZXJkPXByZXZpZXciO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1752992950);
INSERT INTO "sessions" VALUES ('pebbiKZIifCvzJYaGGCiGN4BVANXbgu605IDAVMI',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Herd/1.20.2 Chrome/120.0.6099.291 Electron/28.2.5 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiVEZFdmt6VmpSTmFXeW9Ja0NSQlhMckpJWnYwUmNmZzFVYjZkaVppdyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzc6Imh0dHBzOi8vci1zeXN0ZW0tMy50ZXN0Lz9oZXJkPXByZXZpZXciO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1752992951);
INSERT INTO "sessions" VALUES ('DM2Id9LD1diNuhlTTstBNjubeBbKOzOwbcolLR3e',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Herd/1.20.2 Chrome/120.0.6099.291 Electron/28.2.5 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiMjRPSFE1Vmdac3piMkUxRVkwT0p2MW1VWVdrWnROY1dDdDZxNjhBYSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzc6Imh0dHBzOi8vci1zeXN0ZW0tMy50ZXN0Lz9oZXJkPXByZXZpZXciO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1752992951);
INSERT INTO "sessions" VALUES ('oav5VtEql09Axkmibgmhn5V5xQHiKER4MNKZVFwD',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Herd/1.20.2 Chrome/120.0.6099.291 Electron/28.2.5 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiYTdOeWhQUGs1T2s2UWV4V01MbURWVkZQNHJuZklXN3VhM3NLUHpEUyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzc6Imh0dHBzOi8vci1zeXN0ZW0tMy50ZXN0Lz9oZXJkPXByZXZpZXciO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1752992951);
INSERT INTO "sessions" VALUES ('rEZrXNNnAjUxbVpVk0h8NHKf9gGFC3CSowGvS3xa',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Herd/1.20.2 Chrome/120.0.6099.291 Electron/28.2.5 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoid0dwNzA2TTZDQ1hxbVBhUVA2M3JUclF4TW1DMmpWV0ppNWtRbFM0YiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzc6Imh0dHBzOi8vci1zeXN0ZW0tMy50ZXN0Lz9oZXJkPXByZXZpZXciO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1752992952);
INSERT INTO "sessions" VALUES ('cYGKzuu1Is6BQgiqM7AJFfw880JT3j2ixDp4Agdf',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Herd/1.20.2 Chrome/120.0.6099.291 Electron/28.2.5 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiYWF2SkpidEV5VDVucHMxR1BlVmNPVFlYRDJCSDA2OW83YjY4Q1JGOSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzc6Imh0dHBzOi8vci1zeXN0ZW0tMy50ZXN0Lz9oZXJkPXByZXZpZXciO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1752993066);
INSERT INTO "sessions" VALUES ('YZiqpbGrp3AILYyhb6V7091UsBtySG8cperFDMCx',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Herd/1.20.2 Chrome/120.0.6099.291 Electron/28.2.5 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoib3dMZ3g5dkxPSUpqQ2FsOGJXdmNlbXF0ZjdLdG1oNTdoZjRuZzB5biI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzc6Imh0dHBzOi8vci1zeXN0ZW0tMy50ZXN0Lz9oZXJkPXByZXZpZXciO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1752993066);
INSERT INTO "sessions" VALUES ('imVSTqmUkUIRqHrTgEyAghH0YEyZYbF5oAaxaLOq',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Herd/1.20.2 Chrome/120.0.6099.291 Electron/28.2.5 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoibzhwQTI1ZHpEZk5yTWZ3SWo1ZG5GRXFheUp1WFQ4aUhiM3l6TkJieCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzc6Imh0dHBzOi8vci1zeXN0ZW0tMy50ZXN0Lz9oZXJkPXByZXZpZXciO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1752993078);
INSERT INTO "sessions" VALUES ('OIVdqIDM9gvljuKYVgnXtLMObQRKwLLBvLhXXTRv',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Herd/1.20.2 Chrome/120.0.6099.291 Electron/28.2.5 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoic2hwcVY1SDZEdlZUZWhVclZpNExXS090S2FIaWtHTjNFTVViOElISSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzc6Imh0dHBzOi8vci1zeXN0ZW0tMy50ZXN0Lz9oZXJkPXByZXZpZXciO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1752993079);
INSERT INTO "sessions" VALUES ('xBloJ2UZFvB9rxn3Usm7pSOuPYQaCPuXA5pq2FLV',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Herd/1.20.2 Chrome/120.0.6099.291 Electron/28.2.5 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiUkZhSTBjVG8yOUZrckdOUndlaXltMG1LQjF2QzdaSUU1SU15bGVBQyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzc6Imh0dHBzOi8vci1zeXN0ZW0tMy50ZXN0Lz9oZXJkPXByZXZpZXciO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1752993080);
INSERT INTO "sessions" VALUES ('yxu5B3N5nd9Het14UwJBL5VbHoyx5bNZTKb7Nvya',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Herd/1.20.2 Chrome/120.0.6099.291 Electron/28.2.5 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoibDA1Q2pWWW5uckpaMjRGRDJUd3JUQ1RyNlY1UXBRY3FSTE84bjJyQyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzc6Imh0dHBzOi8vci1zeXN0ZW0tMy50ZXN0Lz9oZXJkPXByZXZpZXciO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1752993189);
INSERT INTO "sessions" VALUES ('wgztbUMkS8Iud8f96EdB9aRBV1qJXW0cMucldlPV',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Herd/1.20.2 Chrome/120.0.6099.291 Electron/28.2.5 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiVDF6UGJtTktXTWp4Y3VCMTBGeXBoTmIzRGxjOWp0UG9yVVM0SFlJZCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzc6Imh0dHBzOi8vci1zeXN0ZW0tMy50ZXN0Lz9oZXJkPXByZXZpZXciO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1752993189);
INSERT INTO "sessions" VALUES ('oik01MygMHvUO7XHFiZFi58xx2DGYDw1lBInNfHt',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Herd/1.20.2 Chrome/120.0.6099.291 Electron/28.2.5 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiU1JWSVVNVVIzcnJKdTh4MXBiRmtRTEZmVTExUHVQMzRJaldWUExaTiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzc6Imh0dHBzOi8vci1zeXN0ZW0tMy50ZXN0Lz9oZXJkPXByZXZpZXciO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1752993190);
INSERT INTO "sessions" VALUES ('FU65RpXvSwnzwF8KNLL5XqUfEuNh0NSh4T6q7ZlO',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Herd/1.20.2 Chrome/120.0.6099.291 Electron/28.2.5 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiUUVFYkF1ajlMRkdjOUFyN1R6NnBubFFQOUFPTm8yZkRaeW1taEFCbSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzc6Imh0dHBzOi8vci1zeXN0ZW0tMy50ZXN0Lz9oZXJkPXByZXZpZXciO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1752993190);
INSERT INTO "sessions" VALUES ('zhzhioSQWPS6AfCHfYLpOri0W3R4lKQ48pkji8qc',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Herd/1.20.2 Chrome/120.0.6099.291 Electron/28.2.5 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiazFkekk3T1paMFh0QnpKY3FxUFVaSmFvS3duaDlETGgwRFVYWmpUZiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzc6Imh0dHBzOi8vci1zeXN0ZW0tMy50ZXN0Lz9oZXJkPXByZXZpZXciO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1752993305);
INSERT INTO "sessions" VALUES ('19mIL7g8rwtreuOS0sz5PLKDtDCjd5yGmIRVkjwz',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Herd/1.20.2 Chrome/120.0.6099.291 Electron/28.2.5 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoib2dBajUxY0xNbkR0WmpUdEdROGtjWXVSRklYblNsbXVFeEpvTlJrcyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzc6Imh0dHBzOi8vci1zeXN0ZW0tMy50ZXN0Lz9oZXJkPXByZXZpZXciO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1752993306);
INSERT INTO "sessions" VALUES ('WiscyHqr3UEmNEPCKRqsjdA9B5M5H1qh8Wp123WP',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Herd/1.20.2 Chrome/120.0.6099.291 Electron/28.2.5 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiQ05XY3BMWWpZWmwyaTVONGpaS2NsQkVuQU0za1BsSXE2d1drRDJhNSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzc6Imh0dHBzOi8vci1zeXN0ZW0tMy50ZXN0Lz9oZXJkPXByZXZpZXciO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1752993306);
INSERT INTO "sessions" VALUES ('A2YjOHdFibLpxtgTcEEG8TUPMr2vsK7DyPG4nmrC',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Herd/1.20.2 Chrome/120.0.6099.291 Electron/28.2.5 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiNWtZQm9vYlNBRlZuUEthcnNxemhnVEtCV21oUkhzQUFXRHJOZG9MSCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzc6Imh0dHBzOi8vci1zeXN0ZW0tMy50ZXN0Lz9oZXJkPXByZXZpZXciO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1752993310);
INSERT INTO "sessions" VALUES ('Tk3kx6twG9pSPF1jaKvi1RwIOsCs4aKsPn09UTq0',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Herd/1.20.2 Chrome/120.0.6099.291 Electron/28.2.5 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiNFN0cmRlZE5yQVFVME1qWGlKTmhzSGg2ZGRJWm9UQjZibnpreXB3YSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzc6Imh0dHBzOi8vci1zeXN0ZW0tMy50ZXN0Lz9oZXJkPXByZXZpZXciO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1752993334);
INSERT INTO "sessions" VALUES ('F7fPEobkkksJXjnEtsPOdwXDDIJPsXbhFVlAwHSU',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Herd/1.20.2 Chrome/120.0.6099.291 Electron/28.2.5 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiTVl0Z2NVZW56VWdoNHR0aFJVYU51aERtYnl4ZjlnYUZXTWtsQ3RmaiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzc6Imh0dHBzOi8vci1zeXN0ZW0tMy50ZXN0Lz9oZXJkPXByZXZpZXciO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1752993340);
INSERT INTO "sessions" VALUES ('fHCP0fErkXvBp2AY5NlHpAGocmo3q731UCx92N9X',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Herd/1.20.2 Chrome/120.0.6099.291 Electron/28.2.5 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiNWcxOWpabk5ZTjk0OEszSFlBdGhiV0Jxc2FTeVFXV25rZXAxZGxBTCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzc6Imh0dHBzOi8vci1zeXN0ZW0tMy50ZXN0Lz9oZXJkPXByZXZpZXciO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1752993340);
INSERT INTO "sessions" VALUES ('rSI3YDUXPcobnhiQD6wYbCf65TdScp3PjMEus0nU',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Herd/1.20.2 Chrome/120.0.6099.291 Electron/28.2.5 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiQ1FqR0VReTFHUDlqbzduTVozdWtrTnkwT0tad1FmeTBERjl0Y2pTMSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzc6Imh0dHBzOi8vci1zeXN0ZW0tMy50ZXN0Lz9oZXJkPXByZXZpZXciO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1752993341);
INSERT INTO "sessions" VALUES ('3Obzws6V90aIbycgrcwRZ4GluLda8K8vSTeQRhii',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Herd/1.20.2 Chrome/120.0.6099.291 Electron/28.2.5 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiYThXdHFVVkRyYUs0YWpWSXVBdEhVb1hOSlZDTU96dUNvUFl4RVFoVCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzc6Imh0dHBzOi8vci1zeXN0ZW0tMy50ZXN0Lz9oZXJkPXByZXZpZXciO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1752993485);
INSERT INTO "sessions" VALUES ('rnKe9zgpixhb31GDC3IWPPiAsn0XscflmXD4HLaA',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Herd/1.20.2 Chrome/120.0.6099.291 Electron/28.2.5 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiMFc1anVXNzNHdjNaZUpEVzU2dHJBU0hUMm4wTDFuOVZEZXdPcGpyYyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzc6Imh0dHBzOi8vci1zeXN0ZW0tMy50ZXN0Lz9oZXJkPXByZXZpZXciO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1752993485);
INSERT INTO "sessions" VALUES ('fFm7if64IAsfxwMZSrEW1auXmXPIlQMWXOHQOq6y',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Herd/1.20.2 Chrome/120.0.6099.291 Electron/28.2.5 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoicUVRWk05ZXNmSmE4c2RaTHNGUjJtZkdWYjdoT1lnR2xSNTFTRXIxNCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzc6Imh0dHBzOi8vci1zeXN0ZW0tMy50ZXN0Lz9oZXJkPXByZXZpZXciO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1752993486);
INSERT INTO "sessions" VALUES ('2x9SI6gK8ffqDZq1zN8obmhsSYgvJFQxRhWrqCbW',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Herd/1.20.2 Chrome/120.0.6099.291 Electron/28.2.5 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiZkJ5M0IzbEdQeFdydkNrMEtwODBUVjRZbFdid2JGajhIY0ZEaGp3eiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzc6Imh0dHBzOi8vci1zeXN0ZW0tMy50ZXN0Lz9oZXJkPXByZXZpZXciO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1752993487);
INSERT INTO "sessions" VALUES ('9Rk2145Im33q9jYAsRuCg6udLtRUUCxkIfh2DY3W',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Herd/1.20.2 Chrome/120.0.6099.291 Electron/28.2.5 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiUkIybnZibE1Tc1NHMkJYQzFSbkNid3pZaXR0VkFRSFdyYzBNdkY4ZSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzc6Imh0dHBzOi8vci1zeXN0ZW0tMy50ZXN0Lz9oZXJkPXByZXZpZXciO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1752993487);
INSERT INTO "sessions" VALUES ('eaThgXruPs1q3ohG21Zgfcb6ECkPUJ2uxc6h9Tg5',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Herd/1.20.2 Chrome/120.0.6099.291 Electron/28.2.5 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiUUlST25ZQ2Y0eHpvdDRyS0c2NnpRbTU3R3BPYVJ4ZUFIVFlDNElEYyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzc6Imh0dHBzOi8vci1zeXN0ZW0tMy50ZXN0Lz9oZXJkPXByZXZpZXciO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1752993598);
INSERT INTO "sessions" VALUES ('JOvARa6t7lBEVdkxM7l6h5ecf8NQPcIVSoBGknrg',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Herd/1.20.2 Chrome/120.0.6099.291 Electron/28.2.5 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiMnRaQTVJdmVoUGx0S2lSd1RJazA4V0xVczEyYXNEcmduSWxkcVZZWCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzc6Imh0dHBzOi8vci1zeXN0ZW0tMy50ZXN0Lz9oZXJkPXByZXZpZXciO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1752993598);
INSERT INTO "sessions" VALUES ('yCLLohkdHZJZ0ws9a3JTZ6ps7hbdv08nSlHUOY0M',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Herd/1.20.2 Chrome/120.0.6099.291 Electron/28.2.5 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiRkRVdkc1SWxKR1czVEhqaDJDSTVoMzRLN0xVM2s5cnpNa0hSRTVaMCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzc6Imh0dHBzOi8vci1zeXN0ZW0tMy50ZXN0Lz9oZXJkPXByZXZpZXciO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1752993599);
INSERT INTO "sessions" VALUES ('grDAxX92mrkXTF84KLLJ28VhFzK0EfRMyXYRZSR9',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Herd/1.20.2 Chrome/120.0.6099.291 Electron/28.2.5 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoic0JWd0ZLMnE4endhYzZBWFRMTlBlWFhpRlkwVGhlN25ieXZKdWdTbCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzc6Imh0dHBzOi8vci1zeXN0ZW0tMy50ZXN0Lz9oZXJkPXByZXZpZXciO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1752993599);
INSERT INTO "sessions" VALUES ('ndTPcVKTFsPuYzNilJYgzJKaJ8PdM9lKGWCdOzI0',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Herd/1.20.2 Chrome/120.0.6099.291 Electron/28.2.5 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiTzBYd0FRcXZrbGczOXpFcHB6NDl6Z2hDNFBXdFdNOGxqTUw3cGdTYiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzc6Imh0dHBzOi8vci1zeXN0ZW0tMy50ZXN0Lz9oZXJkPXByZXZpZXciO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1752993644);
INSERT INTO "sessions" VALUES ('4rcS8gB4neFQclQjYoQECA98ojeu0UG0v5kYc00O',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Herd/1.20.2 Chrome/120.0.6099.291 Electron/28.2.5 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiRTRWU3JycE1jSFhRUHFhbjNKSWNSVmhNRDRxc1N6MXhsck1ZMk05OCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzc6Imh0dHBzOi8vci1zeXN0ZW0tMy50ZXN0Lz9oZXJkPXByZXZpZXciO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1752993644);
INSERT INTO "sessions" VALUES ('RnEHcep9pQvOCFb9PJy3DdpDKFuSeShce2a3Pyo6',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Herd/1.20.2 Chrome/120.0.6099.291 Electron/28.2.5 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiaEZHaVpjaUFGSFdjaloxblZsckxxWnpKT0lCNnlNRzk3TnpxbmFVcCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzc6Imh0dHBzOi8vci1zeXN0ZW0tMy50ZXN0Lz9oZXJkPXByZXZpZXciO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1752993645);
INSERT INTO "sessions" VALUES ('ncjXQjQ8qNuHCgYPmffbfL4TfOozhrW8sifwVRz0',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Herd/1.20.2 Chrome/120.0.6099.291 Electron/28.2.5 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoid1NnYzR0VVR4WnBHUGxPVDltU2JvY1NVS252THFmcUNRWHVGVkVRQyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzc6Imh0dHBzOi8vci1zeXN0ZW0tMy50ZXN0Lz9oZXJkPXByZXZpZXciO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1752993646);
INSERT INTO "sessions" VALUES ('EsQ3jLQHYvGdqa3ICIbNf2nLEUbukVVh7bnQyo5V',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Herd/1.20.2 Chrome/120.0.6099.291 Electron/28.2.5 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiVlhRS2tmTmMzcGk2TW1venNIVjFGV1JwQjNLT3hQaTdHMTFnRW43NSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzc6Imh0dHBzOi8vci1zeXN0ZW0tMy50ZXN0Lz9oZXJkPXByZXZpZXciO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1752993646);
INSERT INTO "sessions" VALUES ('kfoHRxyU4Arat5xBPe3QrANQeGVImv6b4bVW6eHh',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Herd/1.20.2 Chrome/120.0.6099.291 Electron/28.2.5 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiUE5FZVFmQ0hMb2ZRMmxxOUxyR3pxajUxMm5kUWplWmNhVTVHVjFLNiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzc6Imh0dHBzOi8vci1zeXN0ZW0tMy50ZXN0Lz9oZXJkPXByZXZpZXciO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1752993646);
INSERT INTO "sessions" VALUES ('gRUh3aoAnB796vPa507dAQytBYc1vWSnbsxLUVB4',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Herd/1.20.2 Chrome/120.0.6099.291 Electron/28.2.5 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiQ0hCMGV5TWpkbjdoeUNoTWtkMGdnQVg4NVJXeXdQOGhpWW5ZNG44TCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzc6Imh0dHBzOi8vci1zeXN0ZW0tMy50ZXN0Lz9oZXJkPXByZXZpZXciO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1752993725);
INSERT INTO "sessions" VALUES ('dIGBWsWO2hXcaqbaSNmn3orlmtYrubEdKr7mjn5z',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Herd/1.20.2 Chrome/120.0.6099.291 Electron/28.2.5 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiZW5CaFBXNGs0UlRoRk9TU3hVTmhlWGt6bFdCS1lJcHNyTWVRWWVWOCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzc6Imh0dHBzOi8vci1zeXN0ZW0tMy50ZXN0Lz9oZXJkPXByZXZpZXciO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1752993726);
INSERT INTO "sessions" VALUES ('BPa2iThLe8V1oR6lR1mtfxz8ZixLIYovhq0AVc4K',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Herd/1.20.2 Chrome/120.0.6099.291 Electron/28.2.5 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoieWZmTzZhOVNtYklLWFFjeWFkZTNLZkxGVk9UY0tQQ0ZYbHozY0cxYSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzc6Imh0dHBzOi8vci1zeXN0ZW0tMy50ZXN0Lz9oZXJkPXByZXZpZXciO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1752993731);
INSERT INTO "sessions" VALUES ('qGFcCD6OJB4JazlibmlIeHO095sgT2pVIAXoYZrL',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Herd/1.20.2 Chrome/120.0.6099.291 Electron/28.2.5 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiS2RWT0RBWVlYSGZNeXhSTVcyWUJaTUJPMnE2ZW9SYkdkU21IcWZpeCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzc6Imh0dHBzOi8vci1zeXN0ZW0tMy50ZXN0Lz9oZXJkPXByZXZpZXciO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1752993731);
INSERT INTO "sessions" VALUES ('8s4nDPsZnkUsy67UkYzrhvoWMOFBKmKHYofr7FAH',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Herd/1.20.2 Chrome/120.0.6099.291 Electron/28.2.5 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiMkVCZldxcmZEMkppSHc5SHR0QkVkbUJrdWV3N0tYQjBsdHdGVmV3ZCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzc6Imh0dHBzOi8vci1zeXN0ZW0tMy50ZXN0Lz9oZXJkPXByZXZpZXciO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1752993732);
INSERT INTO "sessions" VALUES ('KbPAkTDGI4xNKTfNsGO2nQRbYa13d8RzOGcAUeY0',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Herd/1.20.2 Chrome/120.0.6099.291 Electron/28.2.5 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoibFB0d05XN0dMVmcxSUVKSFVjclM5S3FMQ0tuN3VOOFBtamZiMnA2TiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzc6Imh0dHBzOi8vci1zeXN0ZW0tMy50ZXN0Lz9oZXJkPXByZXZpZXciO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1752993756);
INSERT INTO "sessions" VALUES ('Uqc1fzt412A1LB5KAt5rlLG2rRlAK67VAeR7zK4c',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Herd/1.20.2 Chrome/120.0.6099.291 Electron/28.2.5 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiV1VXdDY0TjBQZ2R5UzZxRGZTNzM0THlWVVhCWk9LZmhtQ1Bhck40diI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzc6Imh0dHBzOi8vci1zeXN0ZW0tMy50ZXN0Lz9oZXJkPXByZXZpZXciO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1752993757);
INSERT INTO "sessions" VALUES ('epWdECA0ZQx98F95zXULYDTiXdw6nKDsg37WWkfG',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Herd/1.20.2 Chrome/120.0.6099.291 Electron/28.2.5 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiakRGQk9QZGd3RjA4V1hBaTd6cEFNMEVhaUdLRDFZUjhjcGdneE5JViI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzc6Imh0dHBzOi8vci1zeXN0ZW0tMy50ZXN0Lz9oZXJkPXByZXZpZXciO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1752993757);
INSERT INTO "sessions" VALUES ('9IWCekMnS4tYUHjIMUoHGHYVt2GavWTryOU6iqE7',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Herd/1.20.2 Chrome/120.0.6099.291 Electron/28.2.5 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiYjlkdXBNdHhFOTBTd2pJV0U2N09wWHNpWGVlMXl1V2htZDZSdmdWNiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzc6Imh0dHBzOi8vci1zeXN0ZW0tMy50ZXN0Lz9oZXJkPXByZXZpZXciO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1752993838);
INSERT INTO "sessions" VALUES ('exotZy0Acx11SWZ4Nm7N6HoLhzD104RoKH3k452T',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Herd/1.20.2 Chrome/120.0.6099.291 Electron/28.2.5 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiQ3NaOG5kVW5TYUFDY1pyTGRheGZxdlliYTFNVm5rZG1XS0x3dXppciI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzc6Imh0dHBzOi8vci1zeXN0ZW0tMy50ZXN0Lz9oZXJkPXByZXZpZXciO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1752993838);
INSERT INTO "sessions" VALUES ('GRyv9h0YolkcOQBSw1sTWH8T53GZ4RAAJrt3be42',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Herd/1.20.2 Chrome/120.0.6099.291 Electron/28.2.5 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiQVBiWndiMjlRVDZVdmFNOXFZaFFxaGNTMmk1eTY1NlRQUFZTclBJQiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzc6Imh0dHBzOi8vci1zeXN0ZW0tMy50ZXN0Lz9oZXJkPXByZXZpZXciO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1752993881);
INSERT INTO "sessions" VALUES ('V18UNlQTqQAVi9PDwAyMaqoJ6VT5a9xU7Uoiaxs4',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Herd/1.20.2 Chrome/120.0.6099.291 Electron/28.2.5 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiOWV4cmZnaFhTU3J5T2ZVSlN0WHltRW5YNnIwYnliTm45ZFgzaEFUUCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzc6Imh0dHBzOi8vci1zeXN0ZW0tMy50ZXN0Lz9oZXJkPXByZXZpZXciO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1752993888);
INSERT INTO "sessions" VALUES ('iaeK2XEbhOug8psxxTyAoPxR04N341Kt9UAPOiq2',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Herd/1.20.2 Chrome/120.0.6099.291 Electron/28.2.5 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiOG5ZTTlPOFR5ODI1MzFHTEFUcHhhSlVPU083NFpFQnBDM3NoVHVqSCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzc6Imh0dHBzOi8vci1zeXN0ZW0tMy50ZXN0Lz9oZXJkPXByZXZpZXciO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1752993888);
INSERT INTO "sessions" VALUES ('qrUjVVgCVlB5rMbT5aHeI0H9xEsIhPj9lswp0knW',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Herd/1.20.2 Chrome/120.0.6099.291 Electron/28.2.5 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiSzVid1cyUFA1eWY1VDh0enNPR2xvc0JLeVV2bzJWMFBibXhreEthRCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzc6Imh0dHBzOi8vci1zeXN0ZW0tMy50ZXN0Lz9oZXJkPXByZXZpZXciO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1752993889);
INSERT INTO "sessions" VALUES ('g7s7W8ef99uX58QQw1CCc6BhCOoow6tV7p6SH030',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Herd/1.20.2 Chrome/120.0.6099.291 Electron/28.2.5 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiN1VRamhRZGRJckhob2NrU0RBZXpzN1FOSXhOaU1KS3B4YmtzbEx5cyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzc6Imh0dHBzOi8vci1zeXN0ZW0tMy50ZXN0Lz9oZXJkPXByZXZpZXciO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1752993889);
INSERT INTO "sessions" VALUES ('OQ1VMaXJyd6p3OcOPM5fqtaCDehVDTkKoROut9Vi',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Herd/1.20.2 Chrome/120.0.6099.291 Electron/28.2.5 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiQmc2bExtWk9aNmUwMTVrZGFmSkh0VXl5ekVoaU9waVJwaWl6NDMzWSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzc6Imh0dHBzOi8vci1zeXN0ZW0tMy50ZXN0Lz9oZXJkPXByZXZpZXciO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1752994220);
INSERT INTO "sessions" VALUES ('bntEBVY2dVZKZu2qIGz4M6R0OeoKj2qyZU91KR06',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Herd/1.20.2 Chrome/120.0.6099.291 Electron/28.2.5 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiV1BsU21zMUFIOVpBbmswdWV3elpZVG5XWjdkbkJzZmJrVnVrMFZzTiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzc6Imh0dHBzOi8vci1zeXN0ZW0tMy50ZXN0Lz9oZXJkPXByZXZpZXciO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1752994220);
INSERT INTO "sessions" VALUES ('qYCPnoHK4ZipWHLEBwenkySypWDmBOBJV1p9U0Wi',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Herd/1.20.2 Chrome/120.0.6099.291 Electron/28.2.5 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiVlU3a2lhenE3R1lZaGo5ZDljU1NNcXJ5SEY0NVdibEhCODV3clhkVCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzc6Imh0dHBzOi8vci1zeXN0ZW0tMy50ZXN0Lz9oZXJkPXByZXZpZXciO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1752994221);
INSERT INTO "sessions" VALUES ('qJDoJIon2xEs1whNNYU5uWxxtL1m0KAyI5I2zaAu',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Herd/1.20.2 Chrome/120.0.6099.291 Electron/28.2.5 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoidHZJOFl2TmZUTEtVekVPQ3laS3pwVjY1MEg1Q1NsanRYT2x0djhVSSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzc6Imh0dHBzOi8vci1zeXN0ZW0tMy50ZXN0Lz9oZXJkPXByZXZpZXciO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1752994225);
INSERT INTO "sessions" VALUES ('UrT6SfoCu051lDB19EL3yNjUAeycH7w4jxmCRJZm',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Herd/1.20.2 Chrome/120.0.6099.291 Electron/28.2.5 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiWkhTZXNRSDROdWFINXRnTUlwdlMwbHJlMUtocWlTaHFjYzBFZ0JrMyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzc6Imh0dHBzOi8vci1zeXN0ZW0tMy50ZXN0Lz9oZXJkPXByZXZpZXciO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1752994225);
INSERT INTO "sessions" VALUES ('jPPfqs6A85pSPtOk3XtaEdJg17Niw25TEXOn5QFE',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Herd/1.20.2 Chrome/120.0.6099.291 Electron/28.2.5 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiZGVoVk84d3ZCWVdyM204YUdXSkRjTVRhUzVUT283cGdmTUxEWmlweiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzc6Imh0dHBzOi8vci1zeXN0ZW0tMy50ZXN0Lz9oZXJkPXByZXZpZXciO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1752994226);
INSERT INTO "sessions" VALUES ('CiRvufW3x0OOm4G9KBltlXYTNwSpGypb3Z67pe2a',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Herd/1.20.2 Chrome/120.0.6099.291 Electron/28.2.5 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiN29pTUI0dmtPRHhybHh0VmRqV2JIS0pVeXZ6aGQwUEFBbU9icW5DdSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzc6Imh0dHBzOi8vci1zeXN0ZW0tMy50ZXN0Lz9oZXJkPXByZXZpZXciO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1752994254);
INSERT INTO "sessions" VALUES ('uWqiKWUHoiYUhKwUxlKwp0Z8dBt8wcD9tefyUJKx',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Herd/1.20.2 Chrome/120.0.6099.291 Electron/28.2.5 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiZlRscGE5NkJjazQ2Y2JNQ0pDS29rWnpNRTBLT1NhSGgyVXRMUk9UYyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzc6Imh0dHBzOi8vci1zeXN0ZW0tMy50ZXN0Lz9oZXJkPXByZXZpZXciO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1752994255);
INSERT INTO "sessions" VALUES ('5SFQ5BF8iOLwxoRbOEnxoygCMxvwVI2hkcxiaK2O',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Herd/1.20.2 Chrome/120.0.6099.291 Electron/28.2.5 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiZXFlcERaRjJuYkxOVW5oYnhQZUx3YVFRTDh5SkNPb2RHdkZlNFZOdiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzc6Imh0dHBzOi8vci1zeXN0ZW0tMy50ZXN0Lz9oZXJkPXByZXZpZXciO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1752994255);
INSERT INTO "sessions" VALUES ('A4PtqdsXBh8bMmgk4GwoKDaXEj3w76XMvTncjxL6',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Herd/1.20.2 Chrome/120.0.6099.291 Electron/28.2.5 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoidlFlSTgzSzhUZGJmY0pheHI4YXdjVndDYUZSZDhGU2NwN0JYWFZZSSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzc6Imh0dHBzOi8vci1zeXN0ZW0tMy50ZXN0Lz9oZXJkPXByZXZpZXciO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1752994463);
INSERT INTO "sessions" VALUES ('2hOFEsfPUxL93wioOwdXioGGewi6tMJvdcaTeMAb',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Herd/1.20.2 Chrome/120.0.6099.291 Electron/28.2.5 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiUTVqNTRqMldLeUVmRDk2b3Z3aWlVa25NU2k0TFY1OVF0RlRWYVp3bCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzc6Imh0dHBzOi8vci1zeXN0ZW0tMy50ZXN0Lz9oZXJkPXByZXZpZXciO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1752994463);
INSERT INTO "sessions" VALUES ('PzQ6V0Pr75ypizYzlE15llVCB0gwHcvyE87rhqfV',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Herd/1.20.2 Chrome/120.0.6099.291 Electron/28.2.5 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoidWRTak40UnRQZUEyNmVCQTZJSDFGTnpxYWNHOHRtUHh0ZkFGdTkySyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzc6Imh0dHBzOi8vci1zeXN0ZW0tMy50ZXN0Lz9oZXJkPXByZXZpZXciO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1752994464);
INSERT INTO "sessions" VALUES ('OaqUw6C50hC46lHWidmi31krN243OFen4GuUmVr4',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Herd/1.20.2 Chrome/120.0.6099.291 Electron/28.2.5 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiOHdNdDA3b3k0Q211RUZnS3RBR011ZTlIVERGaUVWaUh3OTRBWEYyRyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzc6Imh0dHBzOi8vci1zeXN0ZW0tMy50ZXN0Lz9oZXJkPXByZXZpZXciO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1752994465);
INSERT INTO "sessions" VALUES ('DXQLIb3JsIbFcelWSElExRJJB9sf0koozuMDWgBv',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Herd/1.20.2 Chrome/120.0.6099.291 Electron/28.2.5 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoidjVGUzhOSm1ZVmUxT0p6Z3h2VFRLb0R6QnlpVlIzREU2N0dCQmx0OSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzc6Imh0dHBzOi8vci1zeXN0ZW0tMy50ZXN0Lz9oZXJkPXByZXZpZXciO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1752994465);
INSERT INTO "subjects" VALUES (1,'Mathematics','MATH',NULL,'2025-07-19 14:08:18','2025-07-19 14:08:18');
INSERT INTO "subjects" VALUES (2,'English','ENG',NULL,'2025-07-19 14:08:19','2025-07-19 14:08:19');
INSERT INTO "subjects" VALUES (3,'Physics','PHY',NULL,'2025-07-19 14:08:19','2025-07-19 14:08:19');
INSERT INTO "subjects" VALUES (4,'Chemistry','CHEM',NULL,'2025-07-19 14:08:19','2025-07-19 14:08:19');
INSERT INTO "subjects" VALUES (5,'Biology','BIO',NULL,'2025-07-19 14:08:19','2025-07-19 14:08:19');
INSERT INTO "subjects" VALUES (6,'History','HIST',NULL,'2025-07-19 14:08:19','2025-07-19 14:08:19');
INSERT INTO "subjects" VALUES (7,'Geography','GEO',NULL,'2025-07-19 14:08:19','2025-07-19 14:08:19');
INSERT INTO "subjects" VALUES (8,'Computer Science','CS',NULL,'2025-07-19 14:08:19','2025-07-19 14:08:19');
INSERT INTO "subjects" VALUES (9,'Physical Education','PE',NULL,'2025-07-19 14:08:19','2025-07-19 14:08:19');
INSERT INTO "subjects" VALUES (10,'Science','SCI101',NULL,'2025-07-20 05:45:50','2025-07-20 05:45:50');
INSERT INTO "subjects" VALUES (11,'Art','ART101',NULL,'2025-07-20 05:45:50','2025-07-20 05:45:50');
INSERT INTO "users" VALUES (1,'east','east@test.com','admin','2025-07-19 13:57:15','$2y$12$pyvYNe5IU/YXWO4N4jh9tu2E7WRE1bZale1u5L0KP63MyBUGMymtq',NULL,'2025-07-19 13:57:16','2025-07-19 13:57:16',NULL,NULL);
INSERT INTO "users" VALUES (2,'teach1','teach1@test.com','teacher','2025-07-19 13:57:16','$2y$12$nk2K9atPze/Q0Fdb8.6EeuqkN5j/alZ7ckyvYgi7wT1lcavDU16Wm',NULL,'2025-07-19 13:57:16','2025-07-19 13:57:16',NULL,NULL);
INSERT INTO "users" VALUES (3,'student1','stude@test.com','student','2025-07-19 13:57:16','$2y$12$KFcSr2qMQdo89rPnR6NiGOvj3Ij5AsGZq5wJFIbbBQ82aKJwlysMW',NULL,'2025-07-19 13:57:17','2025-07-19 13:57:17',NULL,NULL);
INSERT INTO "users" VALUES (4,'Admin One','admin.one@example.com','admin','2025-07-19 14:07:48','$2y$12$ekS.b19.kwY4aJlpHC3v.usi1WvuhUxESqWOsmV.RjII0K2r72RtC',NULL,'2025-07-19 14:07:48','2025-07-19 14:07:48',NULL,NULL);
INSERT INTO "users" VALUES (5,'Admin Two','admin.two@example.com','admin','2025-07-19 14:07:49','$2y$12$jHnfgoX/zYdSnSO3uN/lcOfOBmt8PnoVPbeHdFI8XNjYMgW7E9bWy',NULL,'2025-07-19 14:07:49','2025-07-19 14:07:49',NULL,NULL);
INSERT INTO "users" VALUES (6,'Mukumbwali Milimo','mukumbwali.milimo@example.com','teacher','2025-07-19 14:07:49','$2y$12$qL5iecdifNcqZN7bAS91sOdg4X/wsVQZSspX8wkw5djTfluwvrgSi',NULL,'2025-07-19 14:07:49','2025-07-19 14:07:49',NULL,NULL);
INSERT INTO "users" VALUES (7,'Mulemba Wamundila','mulemba.wamundila@example.com','teacher','2025-07-19 14:07:49','$2y$12$1YEF8vfmYL353YvcQZgeSug6gkscgaCxrEugsxhWB0mV2EEnojbeu',NULL,'2025-07-19 14:07:49','2025-07-19 14:07:49',NULL,NULL);
INSERT INTO "users" VALUES (8,'Mulinga Register','mulinga.register@example.com','teacher','2025-07-19 14:07:50','$2y$12$OMeEfBslaqGfqsBVCd.P8uLKoGqz7NfMTBr3HuiyjGskaZPVhSZgy',NULL,'2025-07-19 14:07:50','2025-07-19 14:07:50',NULL,NULL);
INSERT INTO "users" VALUES (9,'Nakushowa Angela','nakushowa.angela@example.com','teacher','2025-07-19 14:07:50','$2y$12$X1auXa3Mtn/lDoPYSSb8dudUpmcO0.MT9f6tSDCUVxnZHktFngfku',NULL,'2025-07-19 14:07:50','2025-07-19 14:07:50',NULL,NULL);
INSERT INTO "users" VALUES (10,'Nchimunya Chilala','nchimunya.chilala@example.com','teacher','2025-07-19 14:07:50','$2y$12$6T87rfzvVtPcC7moT9Byl.FPmPhsTGSNcb0VpyWGH8RExTzARZIGu',NULL,'2025-07-19 14:07:50','2025-07-19 14:07:50',NULL,NULL);
INSERT INTO "users" VALUES (11,'Njekwa Misozi','njekwa.misozi@example.com','teacher','2025-07-19 14:07:51','$2y$12$3id5JvgSS8nohNOyPacqjecEvf0O4foRNHTV9pIONdXhcdii9FrDe',NULL,'2025-07-19 14:07:51','2025-07-19 14:07:51',NULL,NULL);
INSERT INTO "users" VALUES (12,'Phiri Mekiwe','phiri.mekiwe@example.com','teacher','2025-07-19 14:07:51','$2y$12$36J6YKJCr3U18siCBQJxi./nwKORxrWyOvZzPHiZGBxw0/0fRY2VC',NULL,'2025-07-19 14:07:51','2025-07-19 14:07:51',NULL,NULL);
INSERT INTO "users" VALUES (13,'Sibwaalu Edith','sibwaalu.edith@example.com','teacher','2025-07-19 14:07:51','$2y$12$jrI8XPvRD1u6bK..8xaO1.IYxr67Mzt89.sUYEI.HSJWNuCseSTmS',NULL,'2025-07-19 14:07:51','2025-07-19 14:07:51',NULL,NULL);
INSERT INTO "users" VALUES (14,'Siyauya Kamwi','siyauya.kamwi@example.com','teacher','2025-07-19 14:07:52','$2y$12$3eYYFz2v.Yn.M5fABOZfEuvK6m8AkkUoT5MSDOzVF2suuNtHmQCve',NULL,'2025-07-19 14:07:52','2025-07-19 14:07:52',NULL,NULL);
INSERT INTO "users" VALUES (15,'Banda Olipah','banda.olipah@example.com','student','2025-07-19 14:07:52','$2y$12$LIDZRAj3wIprm2GZKFFED.coDD6YYlmpSxgCWwgPtaeS9q5ItPc7C',NULL,'2025-07-19 14:07:52','2025-07-19 14:07:52',NULL,NULL);
INSERT INTO "users" VALUES (16,'Besa Loveness','besa.loveness@example.com','student','2025-07-19 14:07:52','$2y$12$xXGzNRqXr4jB28/Nrf7tn.wUlystmKd/aiHpPFqKIC/VcfzOqkEmO',NULL,'2025-07-19 14:07:52','2025-07-19 14:07:52',NULL,NULL);
INSERT INTO "users" VALUES (17,'Chileya Joyce','chileya.joyce@example.com','student','2025-07-19 14:07:53','$2y$12$HaEdVBlMGdFPrjXUaHa9OOzrFB7NPOSjoU6R5UV1xrzTG2X/msLjW',NULL,'2025-07-19 14:07:53','2025-07-19 14:07:53',NULL,NULL);
INSERT INTO "users" VALUES (18,'Chipi Sarah','chipi.sarah@example.com','student','2025-07-19 14:07:53','$2y$12$NK0dpNTmlXCRDeA7JNq34eTQxUlEQZL2rOkNGQDRSaJXo6krSuikO',NULL,'2025-07-19 14:07:53','2025-07-19 14:07:53',NULL,NULL);
INSERT INTO "users" VALUES (19,'Chisanga Chidza Blessing','chisanga.blessing@example.com','student','2025-07-19 14:07:53','$2y$12$tep0uU4ItJ.hKVbqlZcnu.aWgHkF4.GOQ3s8s0NdUtyDfVMltufeq',NULL,'2025-07-19 14:07:53','2025-07-19 14:07:53',NULL,NULL);
INSERT INTO "users" VALUES (20,'Kanema Justine','kanema.justine@example.com','student','2025-07-19 14:07:54','$2y$12$fYWI8mH/2K7TO6svGnvbYeW0TEswaN0sF3wJt5/KbjFPutDyKHKNe',NULL,'2025-07-19 14:07:54','2025-07-19 14:07:54',NULL,NULL);
INSERT INTO "users" VALUES (21,'Kapeshi Oscar','kapeshi.oscar@example.com','student','2025-07-19 14:07:54','$2y$12$hm3i7BaVvCFM3/ogJd0R9OVP5z9c7j1sZ5lEk5MgmQYKPgeuiSZwG',NULL,'2025-07-19 14:07:54','2025-07-19 14:07:54',NULL,NULL);
INSERT INTO "users" VALUES (22,'Kaposhi Argent','kaposhi.argent@example.com','student','2025-07-19 14:07:54','$2y$12$h43gplaSdyP1dVaVSjxgUesKIC7TufBOoxZhb3D20HKkPxACXcLxe',NULL,'2025-07-19 14:07:54','2025-07-19 14:07:54',NULL,NULL);
INSERT INTO "users" VALUES (23,'Kayula Shuko Mwenya','kayula.mwenya@example.com','student','2025-07-19 14:07:55','$2y$12$1D.f1.KreXCGiZkeGyGbIuwRhRiQjLWLahpfeorpkkoo7xdNtcLYC',NULL,'2025-07-19 14:07:55','2025-07-19 14:07:55',NULL,NULL);
INSERT INTO "users" VALUES (24,'Makepesi Bridget','makepesi.bridget@example.com','student','2025-07-19 14:07:55','$2y$12$0aHVJ1iTx8.kcIvrEN.RyO95WBmi5jIoyvvW/S8lZ55co0o0hTCpC',NULL,'2025-07-19 14:07:55','2025-07-19 14:07:55',NULL,NULL);
INSERT INTO "users" VALUES (25,'Malambo Emmanuel','malambo.emmanuel@example.com','student','2025-07-19 14:07:55','$2y$12$/zWLl6Vf5/rvNYu24KJ6JO6caiePkL5dYT.z6rMijZSm.UlSy09Sq',NULL,'2025-07-19 14:07:55','2025-07-19 14:07:55',NULL,NULL);
INSERT INTO "users" VALUES (26,'Malambo Innocent','malambo.innocent@example.com','student','2025-07-19 14:07:56','$2y$12$B6cqCdgX9sPGaBzln/cQT.ljJMBNMBoNnllzoQRg8jUlarlGsyruG',NULL,'2025-07-19 14:07:56','2025-07-19 14:07:56',NULL,NULL);
INSERT INTO "users" VALUES (27,'Maleya Beatrice','maleya.beatrice@example.com','student','2025-07-19 14:07:56','$2y$12$L4HneLRhEfO754oJJd/f8.eQqhvRqp5GeFEpCpMKppZtMKqPMKZD6',NULL,'2025-07-19 14:07:56','2025-07-19 14:07:56',NULL,NULL);
INSERT INTO "users" VALUES (28,'Mapenzi Debora','mapenzi.debora@example.com','student','2025-07-19 14:07:56','$2y$12$Bg4lRU0TUT27PwFwU7.hROxcp.ZhwGy5x.r46oQtrTpMbZ9dUQkrS',NULL,'2025-07-19 14:07:56','2025-07-19 14:07:56',NULL,NULL);
INSERT INTO "users" VALUES (29,'Matongo Hilda','matongo.hilda@example.com','student','2025-07-19 14:07:57','$2y$12$Ui8iTaKvUtCLP8sN/bJWouBGUYJn6kLfb8BHyz5kchMeUxAsh.lI.',NULL,'2025-07-19 14:07:57','2025-07-19 14:07:57',NULL,NULL);
INSERT INTO "users" VALUES (30,'Mawaya Vincent','mawaya.vincent@example.com','student','2025-07-19 14:07:57','$2y$12$k..mA13X24f2DuiIjBwi5ex07F0vvnSSZiS03NfWsm1GAgoAx1oq.',NULL,'2025-07-19 14:07:57','2025-07-19 14:07:57',NULL,NULL);
INSERT INTO "users" VALUES (31,'Mbanga Mary','mbanga.mary@example.com','student','2025-07-19 14:07:57','$2y$12$/MtKXpcOiuKWvx9xwxnxVuPL.dbBbS0TMfJfgIZmdhsdSZYoHNxXC',NULL,'2025-07-19 14:07:57','2025-07-19 14:07:57',NULL,NULL);
INSERT INTO "users" VALUES (32,'Mudenda Chrisphine','mudenda.chrisphine@example.com','student','2025-07-19 14:07:57','$2y$12$cMoAPTJC.InLKfWDHbxYKuuDVPe2sliCEdoi6gY1TUtu0lWgH9XOq',NULL,'2025-07-19 14:07:57','2025-07-19 14:07:57',NULL,NULL);
INSERT INTO "users" VALUES (33,'Mudenda Cletus','mudenda.cletus@example.com','student','2025-07-19 14:07:58','$2y$12$xt8exIAlEMwWSMS2YZ5jDeir1aeWhRCLCCAvMOOYSgoDdvdqcHr0i',NULL,'2025-07-19 14:07:58','2025-07-19 14:07:58',NULL,NULL);
INSERT INTO "users" VALUES (34,'Mulongo Covious','mulongo.covious@example.com','student','2025-07-19 14:07:58','$2y$12$LKT60feSjc5exfkisFBoYOTiptxNu2cpSsxWjWboHo3Xe4XiMXota',NULL,'2025-07-19 14:07:58','2025-07-19 14:07:58',NULL,NULL);
INSERT INTO "users" VALUES (35,'Mutokwa Ruth','mutokwa.ruth@example.com','student','2025-07-19 14:07:58','$2y$12$vuLFNY8jYh98jctngRggo.A4hOfXx7GglH0zd1rdfE8WsOeTzoNQC',NULL,'2025-07-19 14:07:58','2025-07-19 14:07:58',NULL,NULL);
INSERT INTO "users" VALUES (36,'Muzwakachana Elinda','muzwakachana.elinda@example.com','student','2025-07-19 14:07:59','$2y$12$qKJi/HZGsHtaD9nnNxAB7O95T3HmaGUnkraBLRx.vQsO0N0PlGqN6',NULL,'2025-07-19 14:07:59','2025-07-19 14:07:59',NULL,NULL);
INSERT INTO "users" VALUES (37,'Mwanakaaba Beauty','mwanakaaba.beauty@example.com','student','2025-07-19 14:07:59','$2y$12$mG/ZyJRYb3dzPDjHt5b5euuKl25oxgQSXPgMl02LriaysqxSitUoy',NULL,'2025-07-19 14:07:59','2025-07-19 14:07:59',NULL,NULL);
INSERT INTO "users" VALUES (38,'Admin User','admin@example.com','admin','2025-07-20 05:43:17','$2y$12$SQniD46EoZvv.xDT0I9Bm.V3OWBjeQ/yF7GHxR2kQD0VVGGpUh8L.',NULL,'2025-07-20 05:40:35','2025-07-20 05:43:17',NULL,NULL);
INSERT INTO "users" VALUES (39,'Math Teacher','teacher.math@example.com','teacher','2025-07-20 05:43:17','$2y$12$pdxVCzKs5k.V8jacONR05./geGUPg8xHwiR08bhTsiWKgfZSht.f.',NULL,'2025-07-20 05:40:36','2025-07-20 05:43:17',NULL,NULL);
INSERT INTO "users" VALUES (40,'History Teacher','teacher.history@example.com','teacher','2025-07-20 05:43:18','$2y$12$12fzgtUR/L8XmWTsQ4deeOlTRJaD9xoLL/hxn/Yy1Oixwvgqh8.m6',NULL,'2025-07-20 05:40:36','2025-07-20 05:43:18',NULL,NULL);
INSERT INTO "users" VALUES (41,'Student One','student.one@example.com','student','2025-07-20 05:41:33','$2y$12$5.IRDIPIUowiKiw1kk7HfeY20Hm1fEJNqzpUoA5nl9zQHW2Mtyqw6',NULL,'2025-07-20 05:40:36','2025-07-20 05:41:33',NULL,NULL);
INSERT INTO "users" VALUES (42,'Student Two','student.two@example.com','student','2025-07-20 05:41:34','$2y$12$WequsRcaSBDLR9aJij.jo.EuH3gLGliTNTMBKf5CHH/GcrJljsgvK',NULL,'2025-07-20 05:40:37','2025-07-20 05:41:34',NULL,NULL);
INSERT INTO "users" VALUES (43,'Student Three','student.three@example.com','student','2025-07-20 05:41:34','$2y$12$GkNV1ZlZfsh02RIfttKKPO.Kttp4GJqIBY/EoWaemta4/62LSARvS',NULL,'2025-07-20 05:40:37','2025-07-20 05:41:34',NULL,NULL);
INSERT INTO "users" VALUES (44,'Student ','stude@example.com','student','2025-07-20 05:43:18','$2y$12$qqYpma7PUJeS3H6.HHfA5.tJx0cSivOV36cHuaSOy.qQJvKqEM2VK',NULL,'2025-07-20 05:43:18','2025-07-20 05:43:18',NULL,NULL);
INSERT INTO "users" VALUES (45,'Three','three@example.com','student','2025-07-20 05:43:19','$2y$12$6lnaulz7jTB1rQuOBIPg3eIJUnq5BXjSk1/FZkkWMazeDSuxOwHPK',NULL,'2025-07-20 05:43:19','2025-07-20 05:43:19',NULL,NULL);
CREATE UNIQUE INDEX IF NOT EXISTS "class_sections_name_unique" ON "class_sections" (
	"name"
);
CREATE UNIQUE INDEX IF NOT EXISTS "failed_jobs_uuid_unique" ON "failed_jobs" (
	"uuid"
);
CREATE UNIQUE INDEX IF NOT EXISTS "grading_scales_name_unique" ON "grading_scales" (
	"name"
);
CREATE INDEX IF NOT EXISTS "jobs_queue_index" ON "jobs" (
	"queue"
);
CREATE UNIQUE INDEX IF NOT EXISTS "results_user_id_assessment_id_unique" ON "results" (
	"user_id",
	"assessment_id"
);
CREATE INDEX IF NOT EXISTS "sessions_last_activity_index" ON "sessions" (
	"last_activity"
);
CREATE INDEX IF NOT EXISTS "sessions_user_id_index" ON "sessions" (
	"user_id"
);
CREATE UNIQUE INDEX IF NOT EXISTS "settings_key_unique" ON "settings" (
	"key"
);
CREATE UNIQUE INDEX IF NOT EXISTS "subjects_code_unique" ON "subjects" (
	"code"
);
CREATE UNIQUE INDEX IF NOT EXISTS "users_email_unique" ON "users" (
	"email"
);
COMMIT;
