CREATE SCHEMA autoschools;

CREATE TABLE autoschools.student_entry ( 
	entry_id             int UNSIGNED NOT NULL  AUTO_INCREMENT,
	student_id           int UNSIGNED   ,
	school_id            int    ,
	entry_time           varchar(20)    ,
	additional__info     text    ,
	CONSTRAINT pk_student_entry PRIMARY KEY ( entry_id ),
	CONSTRAINT pk_student_entry_0 UNIQUE ( student_id ) 
 ) engine=InnoDB;

CREATE TABLE autoschools.student_to_group ( 
	group_id             int UNSIGNED   ,
	student_id           int UNSIGNED   ,
	CONSTRAINT pk_student_to_group UNIQUE ( student_id ) 
 ) engine=InnoDB;

CREATE TABLE autoschools.theory ( 
	theory_id            int UNSIGNED NOT NULL  AUTO_INCREMENT,
	teacher_id           int    ,
	group_id             int    ,
	room                 varchar(20)    ,
	start_time           varchar(20)    ,
	end_time             varchar(20)    ,
	CONSTRAINT pk_theory PRIMARY KEY ( theory_id ),
	CONSTRAINT pk_theory_0 UNIQUE ( group_id ) ,
	CONSTRAINT pk_theory_1 UNIQUE ( teacher_id ) 
 ) engine=InnoDB;

CREATE TABLE autoschools.`group` ( 
	group_id             int UNSIGNED NOT NULL  AUTO_INCREMENT,
	autoschool_id        int    ,
	name                 varchar(100)    ,
	practice_start       datetime    ,
	practice_days        int    ,
	practice_teacher     int UNSIGNED   ,
	practice_meetpoint   text    ,
	practice_reserv_count int    ,
	CONSTRAINT pk_group PRIMARY KEY ( group_id ),
	CONSTRAINT idx_group UNIQUE ( practice_teacher ) ,
	CONSTRAINT fk_group_0 FOREIGN KEY ( group_id ) REFERENCES autoschools.theory( group_id ) ON DELETE NO ACTION ON UPDATE NO ACTION
 ) engine=InnoDB;

CREATE TABLE autoschools.autoschool ( 
	id                   int UNSIGNED NOT NULL  AUTO_INCREMENT,
	name                 varchar(100)    ,
	contacts             text    ,
	info                 text    ,
	price                varchar(20)    ,
	studentcode          varchar(10)    ,
	teachercode          varchar(10)    ,
	CONSTRAINT pk_autoschool PRIMARY KEY ( id )
 ) engine=InnoDB;

CREATE TABLE autoschools.practice ( 
	practice_id          int UNSIGNED NOT NULL  AUTO_INCREMENT,
	student_id           int UNSIGNED   ,
	lesson               int    ,
	day                  date    ,
	CONSTRAINT pk_practice PRIMARY KEY ( practice_id ),
	CONSTRAINT pk_practice_0 UNIQUE ( student_id ) 
 ) engine=InnoDB;

CREATE TABLE autoschools.`user` ( 
	id                   int UNSIGNED NOT NULL  AUTO_INCREMENT,
	login                varchar(100)    ,
	password             varchar(64)    ,
	role                 varchar(20)    ,
	autoschol_id         int    ,
	email                varchar(100)    ,
	name                 varchar(200)    ,
	telephone            varchar(30)    ,
	address              text    ,
	CONSTRAINT pk_user PRIMARY KEY ( id ),
	CONSTRAINT pk_user_0 UNIQUE ( autoschol_id ) 
 ) engine=InnoDB;

ALTER TABLE autoschools.autoschool ADD CONSTRAINT fk_autoschool FOREIGN KEY ( id ) REFERENCES autoschools.`user`( autoschol_id ) ON DELETE NO ACTION ON UPDATE NO ACTION;

ALTER TABLE autoschools.autoschool ADD CONSTRAINT fk_autoschool_0 FOREIGN KEY ( id ) REFERENCES autoschools.`group`( group_id ) ON DELETE NO ACTION ON UPDATE NO ACTION;

ALTER TABLE autoschools.practice ADD CONSTRAINT fk_practice FOREIGN KEY ( student_id ) REFERENCES autoschools.`user`( id ) ON DELETE NO ACTION ON UPDATE NO ACTION;

ALTER TABLE autoschools.`user` ADD CONSTRAINT fk_user FOREIGN KEY ( id ) REFERENCES autoschools.practice( student_id ) ON DELETE NO ACTION ON UPDATE NO ACTION;

ALTER TABLE autoschools.`user` ADD CONSTRAINT fk_user_0 FOREIGN KEY ( id ) REFERENCES autoschools.student_entry( student_id ) ON DELETE NO ACTION ON UPDATE NO ACTION;

ALTER TABLE autoschools.`user` ADD CONSTRAINT fk_user_1 FOREIGN KEY ( id ) REFERENCES autoschools.student_to_group( student_id ) ON DELETE NO ACTION ON UPDATE NO ACTION;

ALTER TABLE autoschools.`user` ADD CONSTRAINT fk_user_2 FOREIGN KEY ( id ) REFERENCES autoschools.theory( theory_id ) ON DELETE NO ACTION ON UPDATE NO ACTION;

ALTER TABLE autoschools.`user` ADD CONSTRAINT fk_user_3 FOREIGN KEY ( id ) REFERENCES autoschools.`group`( practice_teacher ) ON DELETE NO ACTION ON UPDATE NO ACTION;

ALTER TABLE autoschools.`user` ADD CONSTRAINT fk_user_4 FOREIGN KEY ( id ) REFERENCES autoschools.student_entry( student_id ) ON DELETE NO ACTION ON UPDATE NO ACTION;

ALTER TABLE autoschools.`user` ADD CONSTRAINT fk_user_5 FOREIGN KEY ( id ) REFERENCES autoschools.theory( teacher_id ) ON DELETE NO ACTION ON UPDATE NO ACTION;

