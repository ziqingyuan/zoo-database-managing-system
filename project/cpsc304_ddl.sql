drop table if exists Reservation cascade;
drop table if exists show_purchase cascade;
drop table if exists medical_record cascade;
drop table if exists Specialist cascade;
drop table if exists cast_assignment cascade;
drop table if exists zoo_keeper cascade;
drop table if exists animals_cast cascade;
drop table if exists Pass_info cascade;
drop table if exists ticket_info cascade;
drop table if exists Entrance_fee cascade;
drop table if exists Customer cascade;
drop table if exists show_info cascade;
drop table if exists show_fee cascade;
drop table if exists Theater cascade;
drop table if exists animal_relation cascade;
drop table if exists animal_info cascade;
drop table if exists breeder cascade;
drop table if exists Employee cascade;
drop table if exists animal_class cascade;
drop table if exists LivingSpace cascade;
drop table if exists District cascade;

CREATE TABLE Customer(
                         customer_ID	INTEGER PRIMARY KEY,
                         customer_name	CHAR(20),
                         customer_age		TINYINT UNSIGNED ,
                         phone_num		CHAR(15),
                         address			CHAR(50),
                         UNIQUE (phone_num));

#EntranceFee(entrance_ID, fee_type)in
CREATE TABLE Entrance_fee(
                             entrance_ID		INTEGER PRIMARY KEY,
                             fee_type		CHAR(20));

#Pass_info(entrance_ID, pass_type, start_date, end_date)
CREATE TABLE Pass_info(
                          entrance_ID		INTEGER  PRIMARY KEY,
                          fee			SMALLINT UNSIGNED,
                          pass_type		CHAR(15),
                          start_date		DATE,
                          end_date		DATE,
                          FOREIGN KEY (entrance_ID)
                              REFERENCES Entrance_fee(entrance_ID)
                              ON DELETE NO ACTION
                              ON UPDATE CASCADE);

#Ticket(entrance_ID, fee)
CREATE TABLE ticket_info(
                            entrance_ID		INTEGER PRIMARY KEY,
                            fee			SMALLINT UNSIGNED,
                            FOREIGN KEY (entrance_ID)
                                REFERENCES Entrance_fee(entrance_ID)
                                ON DELETE NO ACTION
                                ON UPDATE CASCADE);

#Reservation(customer_ID, entrance_ID, reserve_Date)
CREATE TABLE Reservation(
                            customer_ID		INTEGER,
                            entrance_ID		INTEGER,
                            reserve_date		DATE,
                            PRIMARY KEY (customer_ID, entrance_ID),
                            FOREIGN KEY (customer_ID)
                                REFERENCES Customer(customer_ID)
                                ON DELETE NO ACTION
                                ON UPDATE CASCADE,
                            FOREIGN KEY (entrance_ID)
                                REFERENCES Entrance_fee(entrance_ID)
                                ON DELETE NO ACTION
                                ON UPDATE CASCADE);

#show_fee(show_name, price)
CREATE TABLE show_fee(
                         show_name		CHAR(50) PRIMARY KEY,
                         price			SMALLINT UNSIGNED);

#District(district_ID, district_name)
CREATE TABLE District(
                         district_ID		INTEGER PRIMARY KEY,
                         district_name		CHAR(20) UNIQUE);

#Theater(theater_ID, district_ID, theater_name, theater_capacity)
CREATE TABLE Theater(
                        theater_ID		INTEGER,
                        theater_name		CHAR(20) UNIQUE,
                        theater_capacity 	INTEGER,
                        district_ID		INTEGER,
                        PRIMARY KEY (theater_ID, district_ID),
                        FOREIGN KEY  (district_ID)
                            REFERENCES District(district_ID)
                            ON DELETE CASCADE
                            ON UPDATE CASCADE);

#show_info(show_ID, scheduled_time, show_name, theater_ID, district_ID)
CREATE TABLE show_info(
                          show_ID		INTEGER PRIMARY KEY,
                          show_name		CHAR(50),
                          schedule_time		DATETIME,
                          theater_ID		INTEGER DEFAULT 0 NOT NULL,
                          district_ID		INTEGER DEFAULT 0 not null,
                          FOREIGN KEY (show_name)
                              REFERENCES show_fee(show_name)
                              ON DELETE CASCADE
                              ON UPDATE CASCADE,
                          FOREIGN KEY (theater_ID, district_ID)
                              REFERENCES Theater(theater_ID,district_ID)
                              ON DELETE SET DEFAULT
                              ON UPDATE CASCADE);

#show_purchase(show_ID, customer_ID)
CREATE TABLE show_purchase(
                              show_ID			INTEGER,
                              customer_ID			INTEGER,
                              PRIMARY KEY (show_ID, customer_ID),
                              FOREIGN KEY (show_ID)
                                  REFERENCES show_info(show_ID)
                                  ON DELETE NO ACTION
                                  ON UPDATE CASCADE,
                              FOREIGN KEY (customer_ID)
                                  REFERENCES Customer(customer_ID)
                                  ON DELETE NO ACTION
                                  ON UPDATE CASCADE);

#LivingSpace(space_ID, district_ID, space_name, capacity)
CREATE TABLE LivingSpace(
                            space_ID		INTEGER,
                            district_ID		INTEGER,
                            space_name		CHAR(20),
                            capacity		INTEGER,
                            PRIMARY KEY (space_ID, district_ID),
                            FOREIGN KEY (district_ID)
                                REFERENCES District(district_ID)
                                ON DELETE CASCADE);

#animal_class(species, genus)
CREATE TABLE animal_class(
                             species			CHAR(30) PRIMARY KEY,
                             genus			CHAR(30) NOT NULL);

#Employee(employee_ID, employee_name, salary, start_date, mentor_ID)
CREATE TABLE Employee(
                         employee_ID		INTEGER PRIMARY KEY,
                         employee_name	CHAR(20),
                         salary			INTEGER,
                         start_date		DATE,
                         mentor_ID	   	INTEGER,
                         status          CHAR(20),
                         foreign key (mentor_ID) references Employee (employee_ID));

#Specialist(employee_ID, specialist_type)
CREATE TABLE Specialist(
                           employee_ID		INTEGER PRIMARY KEY,
                           specialist_type		CHAR(20),
                           s_status		CHAR(20),
                           FOREIGN KEY (employee_ID)
                               REFERENCES Employee(employee_ID));

#breeder(employee_ID)
CREATE TABLE breeder(
                        employee_ID		INTEGER PRIMARY KEY,
                        b_status		CHAR(20),
                        FOREIGN KEY (employee_ID)
                            REFERENCES Employee(employee_ID));

#zoo_keeper(employee_ID)
CREATE TABLE zoo_keeper(
                           employee_ID		INTEGER PRIMARY KEY,
                           z_status		CHAR(20),
                           FOREIGN KEY (employee_ID)
                               REFERENCES Employee(employee_ID));

#animal_info(species, animal_ID, animal_name, age, employee_ID, space_ID, district_ID)
CREATE TABLE animal_info(
                            animal_ID		INTEGER PRIMARY KEY,
                            animal_name		CHAR(20),
                            age			INTEGER,
                            species			CHAR(30),
                            employee_ID		INTEGER DEFAULT 0,
                            space_ID		INTEGER DEFAULT 0,
                            district_ID		INTEGER DEFAULT 0,
                            FOREIGN KEY (species)
                                REFERENCES animal_class(species)
                                ON DELETE SET NULL
                                ON UPDATE CASCADE,
                            FOREIGN KEY (employee_ID)
                                REFERENCES breeder(employee_ID)
                                ON DELETE NO ACTION
                                ON UPDATE CASCADE,
                            FOREIGN KEY (space_ID)
                                REFERENCES LivingSpace(space_ID)
                                ON DELETE SET DEFAULT
                                ON UPDATE CASCADE,
                            FOREIGN KEY (district_ID)
                                REFERENCES LivingSpace(district_ID)
                                ON DELETE SET DEFAULT
                                ON UPDATE CASCADE);

#animals_cast(show_ID, animal_ID)
CREATE TABLE animals_cast(
                             show_ID		INTEGER,
                             animal_ID		INTEGER,
                             PRIMARY KEY (show_ID, animal_ID),
                             FOREIGN KEY (show_ID)
                                 REFERENCES show_info(show_ID)
                                 ON DELETE CASCADE
                                 ON UPDATE CASCADE,
                             FOREIGN KEY (animal_ID)
                                 REFERENCES animal_info(animal_ID)
                                 ON DELETE CASCADE
                                 ON UPDATE CASCADE);


#animal_relations(animal_ID, kid_ID, relation)
CREATE TABLE animal_relation(
                                animal_ID		INTEGER,
                                kid_ID			INTEGER,
                                relation 		CHAR(20),
                                PRIMARY KEY (animal_ID, kid_ID),
                                FOREIGN KEY (animal_ID)
                                    REFERENCES animal_info(animal_ID)
                                    ON DELETE CASCADE
                                    ON UPDATE CASCADE,
                                FOREIGN KEY (kid_ID)
                                    REFERENCES animal_info(animal_ID)
                                    ON DELETE CASCADE
                                    ON UPDATE CASCADE);


#medical_record(record_ID, animal_ID, employee_ID,record_date,reason)
CREATE TABLE medical_record
(
    record_ID   	INTEGER PRIMARY KEY,
    animal_ID   	INTEGER,
    employee_ID 	INTEGER,
    record_date 	DATETIME,
    reason 		CHAR(20)  NOT NULL,
    FOREIGN KEY (animal_ID)
        REFERENCES animal_info(animal_ID)
        ON DELETE CASCADE
        ON UPDATE CASCADE,
    FOREIGN KEY (employee_ID)
        REFERENCES Specialist(employee_ID)
        ON DELETE NO ACTION
        ON UPDATE CASCADE);
);

#cast_assignments(show_ID, animal_ID, employee_ID)
CREATE TABLE cast_assignment(
                                show_ID		INTEGER,
                                animal_ID		INTEGER,
                                employee_ID		INTEGER,
                                PRIMARY KEY (show_ID, animal_ID, employee_ID),
                                FOREIGN KEY (show_ID, animal_ID)
                                    REFERENCES animals_cast(show_ID, animal_ID)
                                    ON DELETE CASCADE
                                    ON UPDATE CASCADE,
                                FOREIGN KEY (employee_ID)
                                    REFERENCES zoo_keeper(employee_ID)
                                    ON DELETE NO ACTION
                                    ON UPDATE CASCADE);




INSERT INTO Customer
VALUES
    (000123, 'Chris', 18,7788912345, '899 W 12th Ave, Vancouver, BC V5Z 1M9'),
    (000124, 'Zoe', 20,7788912341, '499 W King Edward Ave, Vancouver, BC V5Z 0G9'),
    (000125, 'Jia', 3,7788912346, '5555 Cambie St, Vancouver, BC V5Z 3A3'),
    (000126, 'Zoey', 55,123456789, '4265 Main St, Vancouver, BC V5V 3R1'),
    (000127, 'Christ', 5,1234567890, '3043 Main St, Vancouver, BC V5T 3G6'),
    (000128, 'Alex', 66,1580001234, null);


INSERT INTO Entrance_fee
VALUES
    (111, 'Single Pass'),
    (222, 'Family Pass'),
    (333, 'Single Pass'),
    (444, 'Family Pass'),
    (555, 'Family Pass'),
    (789, 'Single Pass');
;

INSERT INTO Pass_info
VALUES
    (111, 499, 'seasonal pass', '2022-10-01', '2022-12-01'),
    (222, 499, 'seasonal pass', '2022-10-02', '2022-12-02'),
    (333, 499, 'seasonal pass', '2022-10-03', '2022-12-03'),
    (444, 899, 'yearly pass', '2022-10-04', '2023-10-04'),
    (555, 1299, 'yearly pass', '2022-10-05', '2023-10-05'),
    (789, 1299, 'yearly pass', '2022-10-06', '2023-10-06');

INSERT INTO ticket_info
VALUES
# entrance_ID, fee
(111,188),
(222,188),
(333,188),
(444,188),
(555,188),
(789,188)
;

INSERT INTO Reservation
VALUES
    (000123,111,'2022-09-20'),
    (000124,222,'2022-09-21'),
    (000125,333,'2022-10-21'),
    (000126,444,'2022-10-21'),
    (000127,555,'2022-10-21'),
    (000128,789,'2022-10-21');

INSERT INTO show_fee
VALUES
    ('Orangutan vs Penguin'	,100),
    ('The big cat'			,80	),
    ('dolphin kiss'			,160),
    ('The mole hole'		,20	),
    ('Dances with wolves'	,888)
;

#District
INSERT INTO District
VALUES
    (333, 'Marine District'		),
    (222, 'Desert District'		),
    (777, 'Polar District'		),
    (888, 'Rainforest Dsitrcit'	),
    (666, 'Prairie District'	)
;

#Theater
INSERT INTO Theater
VALUES
    (155, 'Ocean Theater', 		500, 333),
    (156, 'Desert Theater', 	500, 222),
    (187, 'Polar Theater', 		600, 777),
    (188, 'Rainforest Theater', 550, 888),
    (190, 'Prairie Theater', 	450, 666),
    (0, 'Deleted Theater', 	450, 666)
;

#show_info
INSERT INTO show_info
VALUES
    (100, 'Orangutan vs Penguin', '2022-10-18 10:35:09'	, 187, 777),  # oracle datetime format: YYYY-MM-DD hh:mm:ss
    (101, 'The Mole Hole'		, '2022-10-01 5:30:00'	, 190, 666),
    (102, 'Dolphin Kiss'		, '2022-10-02 12:15:00'	, 155, 333),
    (103, 'Dances with wolves'	, '2022-10-23 5:35:35'	, 190, 666),
    (104, 'Dances with wolves'	, '2022-10-01 5:35:35'	, 190, 666)
;

#show_purchase
INSERT INTO show_purchase
VALUES
    (100, 123),
    (101, 124),
    (102, 126),
    (102, 123),
    (100, 128);

#LivingSpace
INSERT INTO LivingSpace
VALUES
    (200, 333, 'Shark Tank', 	 5),
    (201, 333, 'Dophins',	 	 6),
    (202, 777, 'Penguin',	 	10),
    (203, 666, 'Maned Wolf',	 6),
    (204, 222, 'Bactrian Camel', 3),
    (205, 666, 'Mole',			20)
;

#class
INSERT INTO animal_class
VALUES
# species              # genus
('Carcharias'			, 'Carcharodon'),	#great white shark
('Delphinus truncatus'	, 'Tursiops'),	 	#Bottlenose dophine
('Forsteri'				, 'Aptenodytes'), 	#Emperor penguin
('Brachyurus'			, 'Chrysocyon' ), 	#Maned wolf
('Bactrianus' 			,'Camelus'),		#Camel
('Eastern Mole' 		,'Scalopus')		#mole
;


INSERT INTO Employee
    VALUE
    (10 , 'Zachary'	 , NULL, '1900-11-15', NULL, 'resigned'	),
    (11 , 'Peter'	 , NULL, '1910-11-15', NULL, 'resigned'	),
    (12 , 'Walter'	 , NULL, '1920-11-15', NULL, 'resigned'	),
    (13 , 'Judith'	 , NULL, '1930-11-15', NULL, 'resigned'	),
    (120, 'jesus'	 , 5500, '2010-01-01', NULL, 'on-the-job'),
    (122, 'Lauren'	 , 5500, '2010-01-01', 120 , 'on-the-job'),
    (123, 'Alexander', 5500, '2010-01-01', 120 , 'on-the-job'),
    (156, 'Dennis'	 , 3500, '2010-01-01', 120 , 'on-the-job'),
    (158, 'Megan'	 , 3500, '2010-01-01', 120 , 'on-the-job'),
    (159, 'Judith'	 , 3000, '2022-01-01', 122 , 'on-the-job'),
    (160, 'Roger'	 , 3000, '2022-01-01', 122 , 'on-the-job'),
    (161, 'Terry'	 , 3000, '2022-01-01', 123 , 'on-the-job')
;


INSERT INTO Specialist
VALUES
    (122, 'veterinary',	'resigned'		),
    (123, 'veterinary',	'resigned'		),
    (156, 'veterinary',	'on-the-job'	),
    (159, 'veterinary',	'on-the-job'	),
    (161, 'veterinary',	'on-the-job'	)
;


INSERT INTO breeder
VALUES
    (122, 'on-the-job'),
    (123, 'on-the-job'),
    (158, 'on-the-job'),
    (160, 'on-the-job'),
    (161, 'on-the-job')
;


INSERT INTO zoo_keeper
VALUES
    (10 , 'resigned'	),
    (11 , 'resigned'	),
    (12 , 'resigned'	),
    (13 , 'resigned'	),
    (120, 'on-the-job'	)
;


#animal_info
INSERT INTO animal_info
VALUES
# animal id
(232, 'Jasper'		, 40, 'Forsteri'			, 123, 202, 777),
(233, 'Kelly'		, 15, 'Forsteri'			, 123, 202, 777),
(234, 'Bobby'		, 5 , 'Delphinus truncatus'	, 123, 202, 777),
(235, 'Christopher' , 2 , 'Brachyurus'			, 122, 203, 666),
(236, 'Tim Horton'	, 10, 'Bactrianus'			, 158, 204, 222),
(237, 'Jacquline'	, 40, 'Eastern Mole' 		, 122, 205, 666),
(238, 'Patricia'	, 33, 'Forsteri'			, 123, 202, 777),
(239, 'Robert'		, 8	, 'Forsteri'			, 123, 202, 777),
(240, 'Barbara'		, 15, 'Eastern Mole' 		, 122, 205, 666)
;

INSERT INTO animals_cast
VALUES
    (100, 232 ),				#'Orangutan vs Penguin'
(101, 237 ),                #'The Mole Hole'
(102, 234 ),                #'Dolphin Kiss'
(103, 235 ),                 #'Dances with wolves'
(104, 236 );                 #'Dances with wolves'	;


INSERT INTO animal_relation
VALUES
    (232,233,'father'),
    (238,233,'mother'),
    (232,239,'father'),
    (238,239,'mother'),
    (237,240,'mother')
;

INSERT INTO cast_assignment
VALUES
    (100, 232,	120),
    (101, 237,	120),
    (102, 234,	120),
    (103, 235,	120),
    (104, 236,	120)
;

INSERT INTO medical_record
VALUES
    (88810,232,156,'2022-08-31','covid'),
    (88811,233,159,'2022-08-31','fever'),
    (88814,234,156,'2022-10-05','sprained ankle'),
    (88822,236,161,'2022-10-01','infection'),
    (88815,232,161,'2022-10-02','fever'),
    (88816,232,159,'2022-10-31','coughing'),
    (88817,237,156,'2022-11-15','infection'),
    (88818,237,159,'2022-10-31','covid'),
    (88819,237,161,'2022-11-06','infection'),
    (88820,232,122,'2022-11-06','n/a'),
    (88821,232,123,'2022-11-24','too fat');
