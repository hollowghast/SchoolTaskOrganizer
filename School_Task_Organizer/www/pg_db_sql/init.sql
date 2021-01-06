
DROP TABLE Task;
DROP TABLE End_User;
DROP TABLE Subject;
DROP TABLE Teacher;
DROP TABLE ClassOfSchool;
DROP TABLE Team;
DROP TABLE Importance;
DROP TABLE Branch;

			CREATE TABLE Task
			(
			TaskID INTEGER PRIMARY KEY,
			TeacherID INTEGER,
			SubjectID INTEGER,
			Description VARCHAR(5000) NOT NULL,
			ClassID INTEGER,
			TeamID INTEGER,
			ImportanceID INTEGER NOT NULL,
			Due_Date DATE,
			From_Date DATE,
			_Added_Date DATE NOT NULL,
			_Created_By INTEGER NOT NULL
			);


CREATE TABLE Teacher
(
TeacherID INTEGER PRIMARY KEY,
Full_Name VARCHAR(255) NOT NULL,
Abbreviation VARCHAR(10) NOT NULL,
ClassID INTEGER
);

CREATE TABLE End_User
(
Username VARCHAR(127) PRIMARY KEY,
SecretPassword VARCHAR(512) NOT NULL,
ClassID INTEGER,
_IP_Address VARCHAR(127) NOT NULL
);

CREATE TABLE Subject
(
SubjectID Integer PRIMARY KEY,
Title VARCHAR(127) NOT NULL,
Abbreviation VARCHAR(15) NOT NULL
);

CREATE TABLE ClassOfSchool
(
ClassID INTEGER PRIMARY KEY,
NameOfClass VARCHAR(31) UNIQUE --i.e.: ahif16
);

CREATE TABLE Team
(
TeamID INTEGER PRIMARY KEY,
NameOfTeam VARCHAR(255) UNIQUE
);

CREATE TABLE Importance
(
ImportanceID INTEGER PRIMARY KEY,
Title VARCHAR(127) UNIQUE
);

CREATE TABLE Branch
(
BranchID INTEGER PRIMARY KEY,
Title VARCHAR(63)
);

ALTER TABLE Task
ADD CONSTRAINT FK_TeacherID
FOREIGN KEY (TeacherID) REFERENCES Teacher(TeacherID);


CREATE SEQUENCE sq_classid
START WITH 1;

SELECT * FROM end_user;

		
INSERT INTO Task(taskid, description, importanceid, _added_date, _created_by)
VALUES(0, 'Task0', 0, CURRENT_TIMESTAMP, 0);

-- For insert of new tasks
CREATE OR REPLACE FUNCTION function_check_or_insert_system_values()
  RETURNS trigger AS $$
BEGIN
	IF NEW.taskid IS NULL THEN
		NEW.taskid = nextval('sq_taskid');
	END IF;

	IF NEW._added_date IS NULL THEN
		 NEW._added_date = now();
	END IF;
	
	IF NEW._created_by IS NULL THEN
		 NEW._created_by = 0;
	END IF;

	IF NEW.done IS NULL THEN
		 NEW.done = FALSE;
	END IF;

	RETURN NEW;
END; $$ LANGUAGE plpgsql;

CREATE TRIGGER tr_system_values BEFORE INSERT OR UPDATE
    ON Task
    FOR EACH ROW
    WHEN NEW._added_date IS NULL OR NEW._created_by IS NULL
	EXECUTE FUNCTION function_check_or_insert_system_values();


INSERT INTO Task(taskid, description, importanceid, _added_date, _created_by)
VALUES(0, 'Task0', 0, CURRENT_TIMESTAMP, 0);

INSERT INTO Task(teacherid, subjectid, description, classid, importanceid, due_date, links, overdue)
VALUES(-1, -1, 'Description',
	  -1, -1, TO_DATE('21.03.2019', 'DD.MM.YYYY'), 'https://cisco.com,https://google.com', FALSE);


CREATE OR REPLACE FUNCTION function_trigger_teacher()
  RETURNS trigger AS $$
BEGIN
	IF NEW.teacherid IS NULL THEN
		NEW.teacherid = nextval('sq_teacherid');
	END IF;

	RETURN NEW;
END; $$ LANGUAGE plpgsql;

INSERT INTO teacher(full_name, abbreviation)
VALUES('BOGENSPERGER Karin', 'BO');
INSERT INTO teacher(full_name, abbreviation)
VALUES('PABST Gottfried', 'PB');
INSERT INTO teacher(full_name, abbreviation)
VALUES('GUGGERBAUER Gerhard', 'GU');

CREATE OR REPLACE FUNCTION function_trigger_subject()
  RETURNS trigger AS $$
BEGIN
	IF NEW.subjectid IS NULL THEN
		NEW.subjectid = nextval('sq_subjectid');
	END IF;

	RETURN NEW;
END; $$ LANGUAGE plpgsql;

INSERT INTO subject(title, abbreviation)
VALUES('Geografie', 'Geo');
INSERT INTO subject(title, abbreviation)
VALUES('Geschichte', 'GGP');
INSERT INTO subject(title, abbreviation)
VALUES('Mathematik', 'AM');
INSERT INTO subject(title, abbreviation)
VALUES('Englisch', 'E');
INSERT INTO subject(title, abbreviation)
VALUES('Deutsch', 'D');
INSERT INTO subject(title, abbreviation)
VALUES('Religion', 'RK');
INSERT INTO subject(title, abbreviation)
VALUES('System- und Projektplanung', 'SYP');
INSERT INTO subject(title, abbreviation)
VALUES('System- und Projektplanung', 'SYP_S');
INSERT INTO subject(title, abbreviation)
VALUES('Programmieren und Systementwicklung', 'POS');
INSERT INTO subject(title, abbreviation)
VALUES('Programmieren und Systementwicklung', 'POS_S');
INSERT INTO subject(title, abbreviation)
VALUES('Netzwerktechnik', 'NVS');
INSERT INTO subject(title, abbreviation)
VALUES('Netzwerktechnik', 'NVS_U');
INSERT INTO subject(title, abbreviation)
VALUES('Chemie', 'NW2C');
INSERT INTO subject(title, abbreviation)
VALUES('Physik', 'NW2P');
INSERT INTO subject(title, abbreviation)
VALUES('Datenbanken', 'DBI');
INSERT INTO subject(title, abbreviation)
VALUES('Datenbanken', 'DBI_P');
INSERT INTO subject(title, abbreviation)
VALUES('Betriebswirtschaft', 'BWMB');
INSERT INTO subject(title, abbreviation)
VALUES('Rechnungswesen', 'BWMR');


CREATE OR REPLACE FUNCTION function_trigger_class()
  RETURNS trigger AS $$
BEGIN
	IF NEW.classid IS NULL THEN
		NEW.classid = nextval('sq_classid');
	END IF;

	RETURN NEW;
END; $$ LANGUAGE plpgsql;

INSERT INTO classofschool(nameofclass)
VALUES('AHIF16');


CREATE OR REPLACE FUNCTION function_trigger_team()
  RETURNS trigger AS $$
BEGIN
	IF NEW.teamid IS NULL THEN
		NEW.teamid = nextval('sq_teamid');
	END IF;

	RETURN NEW;
END; $$ LANGUAGE plpgsql;

INSERT INTO team(nameofteam)
VALUES('first official team');



CREATE OR REPLACE FUNCTION function_trigger_importance()
  RETURNS trigger AS $$
BEGIN
	IF NEW.importanceid IS NULL THEN
		NEW.importanceid = nextval('sq_importanceid');
	END IF;

	RETURN NEW;
END; $$ LANGUAGE plpgsql;

INSERT INTO importance(title)
VALUES('Somehow');


COMMIT;