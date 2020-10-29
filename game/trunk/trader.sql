/*

	GROUP 27
	trader.sql

 */

DROP TABLE Users CASCADE;
DROP TABLE Messages CASCADE;
DROP TABLE Ship;
DROP TABLE Port CASCADE;
DROP TABLE Market CASCADE;
DROP TABLE Inventory;
DROP TABLE Item CASCADE;
DROP TABLE Trades;
DROP TABLE PortLocation;
DROP TABLE PortServices;

DROP TABLE Battles;
DROP TABLE ShipType;

CREATE TABLE Users (
	username text unique primary key NOT NULL , 
	password text NOT NULL, 
	online boolean,
	lastseen timestamp NOT NULL

);

CREATE TABLE Messages (
	number serial,
	sender text,
	receiver text,
	unread boolean DEFAULT true,
	sent timestamp NOT NULL,
	subject text,
	message text
  
);


CREATE TABLE Item(
	item text primary key,
	description text,
	item_type text,
	imageurl text
);

CREATE TABLE Port (
	number integer NOT NULL primary key,
	name text NOT NULL,
	x integer NOT NULL,
	y integer NOT NULL,
	food boolean,
	upgrade boolean,
	repair boolean,
	weapons boolean,
	q_food integer DEFAULT 0,
	q_weapons integer DEFAULT 0
);

CREATE TABLE PortServices (
	number integer NOT NULL,
	product text NOT NULL,
	quantity integer DEFAULT 0,
	foreign key(number) REFERENCES Port,
	foreign key(product) REFERENCES Item(item)
	 
);


CREATE TABLE Ship(
	username text NOT NULL, 
	foreign key(username) REFERENCES Users,
	shipname text NOT NULL,
	primary key(username),
	shiptype text NOT NULL,
	x integer NOT NULL,
	y integer NOT NULL,
	crew integer,
	points integer,
	number integer REFERENCES Port,
	health integer DEFAULT 100
);

CREATE TABLE ShipType(
	shiptype text,
	rank integer,
	price integer,
	imageurl text,
	primary key(shiptype)
	
);


CREATE TABLE Market  (
	port integer,
	foreign key(port) REFERENCES Port(number),
	offer_id serial primary key ,
	timeposted timestamp NOT NULL,
	username text NOT NULL,
	item text NOT NULL,
	foreign key(username) REFERENCES Users,
	foreign key(item) REFERENCES Item,
	asking_price integer,
	quantity integer,
	selling boolean DEFAULT TRUE
);


CREATE TABLE Inventory(
	username text REFERENCES Users,
	item text REFERENCES Item,
	quantity integer
);



CREATE TABLE PortLocation(
	x real NOT NULL,
	y real NOT NULL,
	number integer REFERENCES Port
);


CREATE TABLE Trades (
	time timestamp,
	item text NOT NULL,
	foreign key(item) REFERENCES Item,
	quantity integer,
	price integer
);

CREATE TABLE Battles (
	challenger text REFERENCES Users(username),
	opponent text REFERENCES Users(username),
	primary key(challenger, opponent),
	x real,
	y real,
	lastbattle timestamp
);



/* players of the game */ 

INSERT INTO Users VALUES('Daryl','abc',true,LOCALTIMESTAMP);
INSERT INTO Users VALUES('Hakan','blah',false,LOCALTIMESTAMP);
INSERT INTO Users VALUES('Will','def',true,LOCALTIMESTAMP);
INSERT INTO Users VALUES('dmack','abc',true,LOCALTIMESTAMP);

INSERT INTO Item VALUES ('food','Provide your crew with energy','good','images/fruit-marked60-60.jpg');
INSERT INTO Item VALUES ('weapons','Improve your chances in battle','good','images/cannon-lg60-60.jpg');
INSERT INTO Item VALUES ('repair','Return your ship to health','service','images/spanner60-60.jpg');
INSERT INTO Item VALUES ('upgrade','Add ship features','service','images/up-arrow6060.gif');
INSERT INTO Item VALUES ('crew','Recruit extra deck hands','service','images/stickman6060.jpg');

INSERT INTO Inventory VALUES ('Hakan','food',250);
INSERT INTO Inventory VALUES ('Daryl','food',250);
INSERT INTO Inventory VALUES ('Will','food',250);
INSERT INTO Inventory VALUES ('dmack','food',250);

INSERT INTO Inventory VALUES ('Hakan','weapons',5);
INSERT INTO Inventory VALUES ('Daryl','weapons',5);
INSERT INTO Inventory VALUES ('Will','weapons',5);
INSERT INTO Inventory VALUES ('dmack','weapons',5);

INSERT INTO ShipType VALUES ('Pirate', 1, 0, 'map/ships/Pirate.png');
INSERT INTO ShipType VALUES ('Cruise', 2, 5000, 'map/ships/Cruise.png');
INSERT INTO ShipType VALUES ('Battle', 3, 10000, 'map/ships/Battle.png');

INSERT INTO Ship VALUES ('Will','Apollo 19','Pirate',26,11,1,30000,Null);
INSERT INTO Ship VALUES ('Daryl','RMS Titanic','Cruise',29,15,1,1000,Null);
INSERT INTO Ship VALUES ('Hakan','Princess Alice','Battle',25,30,1,1000,Null);
INSERT INTO Ship VALUES ('dmack','HMS Queen Elizabeth','Battle',29,16,5,1000,Null);

INSERT INTO Port VALUES (0,'London, UK',1891,386,true,true,true,false);
INSERT INTO Port VALUES (1,'San Diego, USA',627,559,true,false,true,false);
INSERT INTO Port VALUES (2,'Durban, South Africa',2191,1243,false,true,true,false);
INSERT INTO Port VALUES (3,'Sydney, Australia',3450,1307,true,true,false,false);
INSERT INTO Port VALUES (4,'Rio Grande, Brazil',1307,1283,true,true,true,true);
INSERT INTO Port VALUES (5,'Halifax, Canada',1194,458,true,true,false,true);
INSERT INTO Port VALUES (6,'Sisimiut, Greenland',1334,246,false,false,true,true);
INSERT INTO Port VALUES (7,'Chennai, India',2707,775,true,false,false,false);
INSERT INTO Port VALUES (8,'Chiba, Japan',3348,536,false,true,true,false);
INSERT INTO Port VALUES (9,'Manila, Phillipines',3148,777,true,true,true,false);
INSERT INTO Port VALUES (10,'Provideniya, Russia',3702,206,true,true,true,false);
INSERT INTO Port VALUES (11,'Izmir, Turkey',2177,524,true,true,true,false);

--INSERT INTO Market (username,item,quantity,timeposted,asking_price,port) VALUES ('Daryl','food',50,'2008-05-23 11:12:34',150,1);
--INSERT INTO Market (username,item,quantity,timeposted,asking_price,port) VALUES ('Will','food',78,'2008-05-23 11:15:34',240,4);
--INSERT INTO Market (username,item,quantity,timeposted,asking_price,port) VALUES ('Hakan','food',23,'2008-05-23 11:10:34',180,4);
--INSERT INTO Market (username,item,quantity,timeposted,asking_price,port) VALUES ('Hakan','food',23,'2008-05-23 11:10:34',180,5);
--INSERT INTO Market (username,item,quantity,timeposted,asking_price,port) VALUES ('Hakan','food',23,'2008-05-23 11:10:34',180,6);

INSERT INTO PortServices VALUES (0,'food',50);
INSERT INTO PortServices VALUES (1,'food',50);
INSERT INTO PortServices VALUES (3,'food',50);
INSERT INTO PortServices VALUES (4,'food',50);
INSERT INTO PortServices VALUES (5,'food',50);
INSERT INTO PortServices VALUES (7,'food',50);
INSERT INTO PortServices VALUES (9,'food',50);
INSERT INTO PortServices VALUES (10,'food',50);
INSERT INTO PortServices VALUES (11,'food',50);

INSERT INTO PortServices VALUES (4,'weapons',25);
INSERT INTO PortServices VALUES (5,'weapons',25);
INSERT INTO PortServices VALUES (6,'weapons',25);

INSERT INTO PortServices VALUES (0,'repair',1);
INSERT INTO PortServices VALUES (1,'repair',2);
INSERT INTO PortServices VALUES (2,'repair',3);
INSERT INTO PortServices VALUES (4,'repair',4);
INSERT INTO PortServices VALUES (6,'repair',5);
INSERT INTO PortServices VALUES (8,'repair',6);
INSERT INTO PortServices VALUES (9,'repair',7);
INSERT INTO PortServices VALUES (10,'repair',8);
INSERT INTO PortServices VALUES (11,'repair',9);

INSERT INTO PortServices VALUES (0,'upgrade',9);
INSERT INTO PortServices VALUES (2,'upgrade',8);
INSERT INTO PortServices VALUES (3,'upgrade',7);
INSERT INTO PortServices VALUES (4,'upgrade',6);
INSERT INTO PortServices VALUES (5,'upgrade',5);
INSERT INTO PortServices VALUES (8,'upgrade',4);
INSERT INTO PortServices VALUES (9,'upgrade',3);
INSERT INTO PortServices VALUES (10,'upgrade',2);
INSERT INTO PortServices VALUES (11,'upgrade',1);

INSERT INTO PortServices VALUES (4,'crew',7);

INSERT INTO PortLocation VALUES (36,8,0);
INSERT INTO PortLocation VALUES (37,8,0);
INSERT INTO PortLocation VALUES (38,7,0);
INSERT INTO PortLocation VALUES (11,11,1);
INSERT INTO PortLocation VALUES (11,12,1);
INSERT INTO PortLocation VALUES (12,12,1);
INSERT INTO PortLocation VALUES (13,12,1);
INSERT INTO PortLocation VALUES (45,24,2);
INSERT INTO PortLocation VALUES (45,25,2);
INSERT INTO PortLocation VALUES (44,25,2);
INSERT INTO PortLocation VALUES (43,26,2);
INSERT INTO PortLocation VALUES (44,26,2);
INSERT INTO PortLocation VALUES (70,25,3);
INSERT INTO PortLocation VALUES (70,26,3);
INSERT INTO PortLocation VALUES (70,27,3);
INSERT INTO PortLocation VALUES (69,27,3);
INSERT INTO PortLocation VALUES (68,27,3);
INSERT INTO PortLocation VALUES (27,25,4);
INSERT INTO PortLocation VALUES (27,26,4);
INSERT INTO PortLocation VALUES (26,26,4);
INSERT INTO PortLocation VALUES (26,27,4);
INSERT INTO PortLocation VALUES (25,9,5);
INSERT INTO PortLocation VALUES (24,9,5);
INSERT INTO PortLocation VALUES (24,10,5);
INSERT INTO PortLocation VALUES (23,10,5);
INSERT INTO PortLocation VALUES (23,9,5);
INSERT INTO PortLocation VALUES (24,8,5);
INSERT INTO PortLocation VALUES (25,8,5);
INSERT INTO PortLocation VALUES (26,5,6);
INSERT INTO PortLocation VALUES (26,4,6);
INSERT INTO PortLocation VALUES (26,6,6);
INSERT INTO PortLocation VALUES (27,6,6);
INSERT INTO PortLocation VALUES (55,15,7);
INSERT INTO PortLocation VALUES (55,16,7);
INSERT INTO PortLocation VALUES (54,17,7);
INSERT INTO PortLocation VALUES (67,10,8);
INSERT INTO PortLocation VALUES (68,10,8);
INSERT INTO PortLocation VALUES (68,11,8);
INSERT INTO PortLocation VALUES (67,11,8);
INSERT INTO PortLocation VALUES (66,11,8);
INSERT INTO PortLocation VALUES (62,15,9);
INSERT INTO PortLocation VALUES (62,16,9);
INSERT INTO PortLocation VALUES (63,16,9);
INSERT INTO PortLocation VALUES (63,15,9);
INSERT INTO PortLocation VALUES (74,3,10);
INSERT INTO PortLocation VALUES (73,3,10);
INSERT INTO PortLocation VALUES (73,4,10);
INSERT INTO PortLocation VALUES (43,10,11);
INSERT INTO PortLocation VALUES (43,11,11);
INSERT INTO PortLocation VALUES (44,11,11);

