
DROP TABLE Ship;
DROP TABLE Port CASCADE; 
DROP TABLE PortLocation;



CREATE TABLE Port (
	number integer NOT NULL primary key,
	name text NOT NULL ,
	x integer NOT NULL,
	y integer NOT NULL,
	food boolean,
	upgrade boolean,
	repair boolean,
	weapons boolean
);



CREATE TABLE Ship(
	username text NOT NULL, 
	foreign key(username) REFERENCES Users,
	shipname text NOT NULL,
	primary key(username),
	shiptype text NOT NULL REFERENCES Shiptype,
	x integer NOT NULL,
	y integer NOT NULL,
	crew integer,
	colour text REFERENCES Colour,
	points integer, 
	number integer REFERENCES Port
);

CREATE TABLE PortLocation(
	x real NOT NULL,
	y real NOT NULL,
	number integer REFERENCES Port
);






INSERT INTO Ship VALUES ('Will','toon army','Pirate',12,18,90,'Blue',123712,NULL);
INSERT INTO Ship VALUES ('Daryl','Titanic','Cruise',15,15,6,'Pink',20000,NULL);
INSERT INTO Ship VALUES ('Hakan','A bad ship','Battle',43,34,5,'Red',32474,NULL);

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