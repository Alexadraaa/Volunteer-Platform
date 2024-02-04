drop database if exists web;
create database web;
use web;

CREATE TABLE users (
    user_id INT AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    lastname VARCHAR(255) NOT NULL,
    address VARCHAR(255) NOT NULL,
    phone VARCHAR(255) NOT NULL,
    username VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'rescuer', 'civilian') NOT NULL,
    hush varchar(32) ,
    primary key(user_id)
);


create table administrator(
ad_id int not null,
primary key(ad_id) ,
constraint adminid foreign key(ad_id) references users(user_id)
on delete cascade on update cascade
);


create table civilian(
c_id int not null,
primary key (c_id),
constraint cid foreign key(c_id) references users(user_id) 
on delete cascade on update cascade
);

create table base (
product_id int not null auto_increment,
category varchar(30) not null,
product varchar(30) not null unique,
num int not null,
primary key (product_id)
);

create table announcements (
    an_id int not null auto_increment,
    an_ad_id int not null,
    an_product_id int not null,
    announcement_content varchar(255), 
	announcement_date date default CURRENT_DATE, 
	is_uploaded boolean not null ,
    primary key(an_id),
    constraint announcementid foreign key(an_ad_id) references administrator(ad_id) on delete cascade on update cascade,
    constraint announceprid foreign key(an_product_id) references base(product_id) on delete cascade on update cascade
);

create table announcement_product_mapping (
    mapping_id int not null auto_increment,
    an_id int not null,
    an_product_id int not null,
    primary key(mapping_id),
    constraint mapping_announcement_fk foreign key(an_id) references announcements(an_id) on delete cascade on update cascade,
    constraint mapping_product_fk foreign key(an_product_id) references base(product_id) on delete cascade on update cascade
);


 create table vehicle (
ve_id int not null auto_increment,
ve_username varchar(30) not null unique,
ve_state enum('fortosi','ekfortosi','ontheroad','onhold'),
primary key(ve_id)
);

create table rescuer (
resc_id int not null,
resc_ve_id int not null,
primary key(resc_id),
constraint sid foreign key(resc_id) references users(user_id) 
on delete cascade on update cascade,
constraint vehid foreign key(resc_ve_id) references vehicle(ve_id)
on delete cascade on update cascade
);

create table tasks(
t_id int not null auto_increment,
t_state enum('done','inprocess') not null,
t_date date not null,
t_vehicle int,
primary key(t_id),
constraint taskveh foreign key(t_vehicle) references vehicle(ve_id)
on delete cascade on update cascade
);

CREATE TABLE orders (
    or_id INT NOT NULL AUTO_INCREMENT,
    or_c_id INT NOT NULL,
    or_date DATE NOT NULL DEFAULT CURRENT_DATE,
    or_type ENUM('Αίτημα', 'Προσφορά') ,
	order_state ENUM('Σε επεξεργασία', 'Παραδόθηκε', 'Προς Παράδοση') NOT NULL,
or_task_id int,
    PRIMARY KEY(or_id),
    CONSTRAINT ordc FOREIGN KEY(or_c_id) REFERENCES users(user_id) ON DELETE CASCADE ON UPDATE CASCADE,
constraint ortaskid foreign key(or_task_id) references tasks(t_id) 
on delete cascade on update cascade
);

create table offers(
o_id int not null auto_increment,
o_c_id int not null,
o_an_id int not null,
o_pr_id int not null,
o_number int not null,
o_or_id int not null,    
primary key(o_id),
constraint ciid foreign key(o_c_id) references civilian(c_id)
on delete cascade on update cascade,
constraint anid foreign key(o_an_id) references announcements(an_id)
on delete cascade on update cascade,
constraint oorid foreign key(o_or_id) references orders(or_id)
on delete cascade on update cascade ,
constraint oprid foreign key(o_pr_id) references base(product_id)
on delete cascade on update cascade
);


create table requests(
re_id int not null auto_increment,
re_c_id int not null,
re_number int not null,
re_pr_id int not null,
re_or_id int not null,
primary key(re_id),
constraint civid foreign key(re_c_id) references civilian(c_id)
on delete cascade on update cascade,
constraint prid foreign key(re_pr_id) references base(product_id)
on delete cascade on update cascade,
constraint orid foreign key(re_or_id) references orders(or_id)
on delete cascade on update cascade
);



CREATE TABLE markers (
    marker_id int not NULL auto_increment,
    latitude decimal(10, 6) NOT NULL,
    longitude decimal(10, 6) NOT NULL,
    marker_type enum('activeTaskCar', 'inactiveTaskCar', 'activeDonation', 'inactiveDonation', 'activeRequest', 'inactiveRequest','base') NOT NULL,
    or_id INT,
ve_id int,
    primary key(marker_id),
constraint vemarkid foreign key(ve_id) references vehicle(ve_id) on delete cascade on update cascade,
    foreign key(or_id) references orders(or_id) on delete set null
);

create table contact (
    contact_id int not null auto_increment,
    c_id int not null,
    message text not null,
    contact_date timestamp default current_timestamp,
    primary key (contact_id),
    constraint contact_civilian_fk foreign key (c_id) references civilian (c_id) on delete cascade on update cascade
);

insert into users values
('1001','Marianthi','Thodi','Sostratou 15','6987562335','MThodi','Marianthi','admin',null),
('1002','Alexandra','Kagiouli','Karaiskaki 225','6996558657','AKag','Alexandra','admin',null),
('1003','Alexis','Giannoutsos','Ellinos Stratiotou 50','6984521424','AGian','Alexis','admin',null),
('2001','Evaggelia','Kolampa','Xeilonos Patreos 25','6989563635','EvaKol','Evaggelia','rescuer',null),
('2002','Elpida','Kokkali','Ellinos Stratiotou 65','6989455226','ElpKo','Elpida','rescuer',null),
('2003','Erifili','Karagianni','Erissou 6','6952341126','EriKar','Erifili','rescuer',null),
('2004','Marileni','Valavani','Agias sofias 15','6989784515','MarVal','Marileni','rescuer',null),
('2005','Danai','Batsouli','Agias Sofias 17','6956231224','DanB','Danai','rescuer',null),
('2006','Alexia','Diamantopoulou','Ellinos Stratiotou 56','6953233629','AlDiam','Alexia','rescuer',null),
('2007','Fotis','Kalioras','Agias sofias 32','6984512335','FotKal','Fotis','rescuer',null),
('2008','Panos','Kapetanidis','Ellinos Stratiotou 87','6987453226','PanKap','Panos','rescuer',null),
('2009','Antonis','Lykourinas','Agias sofias 51','6987562336','AntLyk','Antonis','rescuer',null),
('2010','Theodora','Vaso','Xeilonos Patreos 23','6989562341','TheodV','Theodora','rescuer',null),
('2011','Eleni','Gallous','Xeilonos Patreos 14','6989564578','EleniG','Eleni','rescuer',null),
('2012','Aristeidis','Votsi','Karaiskaki 28','6985231425','ArisV','Aristeidis','rescuer',null),
('2013','Elton','Pietri','Karaiskaki 24','6985755326','EltonP','Elton','rescuer',null),
('2014','Sertzio','Dasi','Kanakri 78','6985452632','SertzD','Sertzio','rescuer',null),
('2015','Eneas','Lepouri','Ellinos Stratiotou 65','6956368947','EniL','Eneas','rescuer',null),
('2016','Jonathan','Chacon','Kanakari 336','6989562336','JonCh','Jonathan','rescuer',null),
('2017','Maria','Anastasopoulou','Kolokotroni 15','6989563625','MarAnast','Maria','rescuer',null),
('2018','Markella','Magouli','Kolokotroni 78','6936235425','MarkeMag','Markella','rescuer',null),
('2019','Clara','Bencomo','Kolokotroni 56','6956592631','ClaraBen','Clara','rescuer',null),
('2020','Alice','Karagianni','Kolokotroni 25','6986425312','AliceKar','Aliki','rescuer',null),
('3001','Athina', 'Vamvaka','Korinthou 15',6951525356,'AthVam','Athina','civilian',null),
('3002','Nikol', 'Mitsoula','Agiou Andrea 105',6956587859,'NikMi','Nikol','civilian',null),
('3003','Aggeliki', 'Sideri','Gounari 108',6956632414,'AggSid','Aggeliki','civilian',null),
('3004','Xristina', 'Kallidi','Kanakari 56',6996332546,'ChrisKal','Christina','civilian',null),
('3005','Maria', 'Aggelidi','Gounari 227',6963332545,'MarAgg','MariaAgg','civilian',null),
('3006','Nikoleta', 'Zervidi','Karaiskaki 108',6998741235,'NikZer','Nikoleta','civilian',null),
('3007','Christina', 'Nikodimou','Karaiskaki 105',6995232214,'ChristN','ChristinaN','civilian',null),
('3008','Dionisios', 'Xenakis','Patreos 50',6989552336,'DionXen','Dionisis','civilian',null),
('3009','Stefanos', 'Xenopoulos','Patreos 22',6996335544,'StefXen','Stefanos','civilian',null),
('3010','Stefanos', 'Doutsi','Karaiskaki 120',6987554885,'StefDou','StefanosD','civilian',null),
('3011','Tzeni', 'Kurti','Korinthou 225',6993221245,'TzKour','Tzeni','civilian',null),
('3012','Marianneta','Daskalopoulou','Sostratou 19',6998885223,'MarDas','Marianneta','civilian',null),
('3013','Stavros','Daskalopoulos','Amerikis 80',6998774455,'StavDas','Stavros','civilian',null),
('3014','Fotini','Lampropoulou','Amerikis 100',6925665541,'FotLamp','Foteini','civilian',null),
('3015','Marika', 'Rokka','Korinthou 127',6933665245,'MarRo','Marika','civilian',null),
('3016','Eugenia', 'Katintzarou','Karaiskaki 103',6936223545,'EvgKat','Evgenia','civilian',null),
('3017','Katerina','Lakkou','Sostratou 17',6933662211,'KatLak','Katerina','civilian',null),
('3018','Elpida', 'Kati','Gounari 15',6965871245,'ElpKat','ElpidaKat','civilian',null),
('3019','Fotoula', 'Zaxaropoulou','Maizonos 65',6985552321,'FotZax','Fotoula','civilian',null),
('3020','Stavroula', 'Liaskou','Maizonos 235',6932111425,'StavLiask','Stavroula','civilian',null);

insert into administrator values
('1001'),
('1002'),
('1003');

insert into civilian values
(3001),
(3002),
(3003),
(3004),
(3005),
(3006),
(3007),
(3008),
(3009),
(3010),
(3011),
(3012),
(3013),
(3014),
(3015),
(3016),
(3017),
(3018),
(3019),
(3020);



insert into base values
(null,'zymarika','makaronia',300),
(null,'zymarika','ryzi',400),
(null,'zymarika','lazania',200),
(null,'zymarika','xylopites',250),
(null,'zymarika','kritharaki',300),
(null,'zymarika','traxanas',450),
(null,'konserves','kalampoki',200),
(null,'konserves','tonos',250),
(null,'konserves','giganteskon',300),
(null,'konserves','ntolmantakia',150),
(null,'konserves','sardeles',450),
(null,'konserves','pikles',470),
(null,'ospria','fakes',300),
(null,'ospria','revithia',350),
(null,'ospria','fasolia',250),
(null,'galaktokomika','gala',300),
(null,'galaktokomika','tyri',350),
(null,'galaktokomika','giaourti',250),
(null,'psomi','xwriatiko',200),
(null,'psomi','tost',300),
(null,'psomi','olikis',150),
(null,'psomi','friganies',350),
(null,'alantika','loukaniko',400),
(null,'alantika','zampon',300),
(null,'alantika','galopoula',250),
(null,'alantika','aeros',210);

insert into vehicle values
(620,'alekos','fortosi'),
(null,'alexis','ekfortosi'),
(null,'marianthi','ontheroad'),
(null,'loser','fortosi'),
(null,'duck','ekfortosi'),
(null,'tortilla','ekfortosi'),
(null,'burito','fortosi'),
(null,'lol','ontheroad'),
(null,'noname','ontheroad'),
(null,'jo','ontheroad'),
(null,'koukla','ontheroad'),
(null,'lisa','ekfortosi'),
(null,'tyn','fortosi'),
(null,'miko','ontheroad'),
(null,'drama','ekfortosi'),
(null,'bb','ekfortosi'),
(null,'omar','fortosi'),
(null,'chulo','ontheroad'),
(null,'red','ontheroad'),
(null,'loca','ontheroad');

insert into rescuer values
(2001,620),
(2002,621),
(2003,622),
(2004,623),
(2005,624),
(2006,625),
(2007,626),
(2008,627),
(2009,628),
(2010,629),
(2011,630),
(2012,631),
(2013,632),
(2014,633),
(2015,634),
(2016,635),
(2017,636),
(2018,637),
(2019,638),
(2020,639);

insert into tasks values
(700,'onhold','2023-05-06',621),
(null,'inprocess','2023-07-08',622),
(null,'inprocess','2023-07-08',622),
(null,'inprocess','2023-07-08',622),
(null,'inprocess','2023-06-10',627),
(null,'inprocess','2023-03-11',628),
(null,'inprocess','2023-07-09',629),
(null,'inprocess','2023-08-09',630),
(null,'inprocess','2023-08-09',633),
(null,'inprocess','2023-08-23',637),
(null,'inprocess','2023-11-23',638),
(null,'inprocess','2023-10-21',639),
(null,'inprocess','2023-10-21',639),
(null,'inprocess','2023-10-21',639),
(null,'onhold','2023-10-23',624),
(null,'onhold','2023-10-25',625),
(null,'onhold','2023-10-25',631),
(null,'onhold','2023-10-26',634),
(null,'onhold','2023-12-23',635),
(null,'onhold','2023-12-14',620),
(null,'onhold','2023-12-10',623),
(null,'onhold','2023-10-17',626),
(null,'onhold','2023-11-30',632),
(null,'onhold','2023-11-30',636),
(null,'done','2023-05-15',621),
(null,'done','2023-09-02',620),
(null,'done','2023-06-03',632);


insert into orders values
(null,3001,'2023-07-08','Αίτημα','Σε επεξεργασία',700),
(null,3002,'2023-07-08','Αίτημα','Προς Παράδοση',701),
(null,3003,'2023-07-08','Αίτημα','Προς Παράδοση',701),
(null,3004,'2023-07-08','Αίτημα','Προς Παράδοση',701),
(null,3005,'2023-07-08','Προσφορά','Προς Παράδοση',701),
(null,3003,'2023-07-08','Αίτημα','Προς Παράδοση',702),
(null,3003,'2023-07-08','Προσφορά','Προς Παράδοση',702),
(null,3003,'2023-07-08','Αίτημα','Προς Παράδοση',703),
(null,3003,'2023-07-08','Προσφορά','Προς Παράδοση',703),
(null,3003,'2023-07-08','Προσφορά','Προς Παράδοση',703);

insert into markers values
(null,38.239507700045884, 21.737859780583516,"activeTaskCar",null,620),
(null,38.2592440858477, 21.74206245174889,"activeTaskCar",null,621),
(null,38.24487733457579, 21.737573887138097,"activeTaskCar",null,622),
(null,38.25750699261597, 21.740772480584425,"activeTaskCar",null,623),
(null,38.257574438003864, 21.741119097776387,"activeTaskCar",null,624),
(null,38.25849211396602, 21.742052065241904,"activeTaskCar",null,625),
(null,38.256472443775195, 21.74388259592688,"activeTaskCar",null,626),
(null,38.26102906012175, 21.742858840105352,"activeTaskCar",null,627),
(null,38.25685331738669, 21.743902780584275,"activeTaskCar",null,628),
(null,38.239532980299444, 21.737891967090434,"activeTaskCar",null,629),
(null,38.24000315255063, 21.736653224761707,"activeTaskCar",null,630),
(null,38.24832738841368, 21.740492887139315,"activeTaskCar",null,631),
(null,38.24843538642809, 21.740668380583937,"activeTaskCar",null,632),
(null,38.247355737208416, 21.738247524762084,"activeTaskCar",null,633),
(null,38.259227236916345, 21.74213755359836,"activeTaskCar",null,634),
(null,38.24105842375879, 21.72989515174794,"activeTaskCar",null,635),
(null,38.248690483013256, 21.737376880584055,"activeTaskCar",null,636),
(null,38.24679501245346, 21.739526624762114,"activeTaskCar",null,637),
(null,38.24751891603827, 21.738701273646026,"activeTaskCar",null,638),
(null,38.24844963492557, 21.738042197775947,"activeTaskCar",null,639);

CREATE INDEX idx_username ON users (username);
CREATE INDEX idx_address ON users (address);
CREATE INDEX idx_role ON users (role);
CREATE INDEX idx_username ON vehicle (ve_username);


grant all privileges on web.* to 'Mthodi'@'%'; 
grant all privileges on web.* to 'AKag'@'%'; 
grant all privileges on web.* to 'AGian'@'%'; 



insert into markers(latitude,longitude,marker_type,or_id)VALUES
(38.247393266423245, 21.733235711269046,'activeRequest',2),
(38.2417549079717, 21.737103553597407,'activeRequest',3),
(38.2417549079717, 21.737103553597407,'activeRequest',6),
(38.2417549079717, 21.737103553597407,'activeDonation',7),
(38.2417549079717, 21.737103553597407,'activeRequest',8),
(38.2417549079717, 21.737103553597407,'activeDonation',9),
(38.2417549079717, 21.737103553597407,'activeDonation',10),
(38.24832700943399, 21.73941381126905,'activeRequest',4),
(38.2386470897419, 21.74443998058342,'activeDonation',5),
(38.245823, 21.735651,'base',null);
