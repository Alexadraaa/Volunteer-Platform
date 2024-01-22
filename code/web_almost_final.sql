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
max_num int not null,
num int not null,
primary key (product_id)
);

create table announcements (
an_id int not null auto_increment,
an_product varchar(30) not null,
an_number_of_prod int not null,   
an_ad_id int not null,
an_product_id int not null,
primary key(an_id),
constraint announcementid foreign key(an_ad_id) references administrator(ad_id)
on delete cascade on update cascade,
constraint announceprid foreign key(an_product_id) references base(product_id)
on delete cascade on update cascade
);

CREATE TABLE IF NOT EXISTS markers (
    idmarker INT AUTO_INCREMENT,
    latitude DOUBLE NOT NULL,
    longitude DOUBLE NOT NULL,
    type enum('request','offer','vehicle') NOT NULL,
    activity enum('active','inactive')  DEFAULT 'inactive',
    primary key(idmarker)
    );

 create table vehicle (
ve_id int not null,
ve_username varchar(30) not null unique,
ve_state enum('fortosi','ekfortosi','ontheroad'),
primary key(ve_id),
     CONSTRAINT veid foreign key(ve_id) REFERENCES markers(idmarker) 
     on delete cascade on update cascade
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
t_state enum('done','onhold','inprocess') not null,
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
o_id int not null,
o_c_id int not null,
o_an_id int not null,
o_product varchar(30) not null,
o_number int not null,
o_ve_state enum('inprocess','onhold') not null,
o_or_id int not null,    
primary key(o_id),
constraint ciid foreign key(o_c_id) references civilian(c_id)
on delete cascade on update cascade,
constraint anid foreign key(o_an_id) references announcements(an_id)
on delete cascade on update cascade,
    constraint oidd foreign key(o_id) REFERENCES markers(idmarker)
    on delete cascade on update CASCADE,
constraint oorid foreign key(o_or_id) references orders(or_id)
    on delete cascade on update cascade 
);

create table requests(
re_id int not null,
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
on delete cascade on update cascade,
 constraint reqid foreign key(re_id) references markers(idmarker)
    on delete cascade on update cascade 
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
(200,'zymarika','makaronia',500,300),
(201,'zymarika','ryzi',500,400),
(202,'zymarika','lazania',500,200),
(203,'zymarika','xylopites',500,250),
(204,'zymarika','kritharaki',500,300),
(205,'zymarika','traxanas',500,450),
(206,'konserves','kalampoki',500,200),
(207,'konserves','tonos',500,250),
(208,'konserves','giganteskon',500,300),
(209,'konserves','ntolmantakia',500,150),
(210,'konserves','sardeles',500,450),
(211,'konserves','pikles',500,470),
(212,'ospria','fakes',500,300),
(213,'ospria','revithia',500,350),
(214,'ospria','fasolia',500,250),
(215,'galaktokomika','gala',500,300),
(216,'galaktokomika','tyri',500,350),
(217,'galaktokomika','giaourti',500,250),
(218,'psomi','xwriatiko',500,200),
(219,'psomi','tost',500,300),
(220,'psomi','olikis',500,150),
(221,'psomi','friganies',500,350),
(222,'alantika','loukaniko',500,400),
(223,'alantika','zampon',500,300),
(224,'alantika','galopoula',500,250),
(225,'alantika','aeros',500,210),
(226,'gluka','melomakarona',500,320),
(227,'gluka','ryzogalo',500,410),
(228,'gluka','zele',500,340),
(229,'gluka','mpaklavas',500,250),
(230,'pota','nero',500,350),
(231,'pota','mpyres',500,100),
(232,'pota','xymos',500,360),
(233,'pota','soda',500,310),
(null,'eidi ugeiinis','xarti',500,250),
(null,'eidi ugeiinis','odontovourtes',500,200),
(null,'eidi ugeiinis','odontokremes',500,300),
(null,'eidi ugeiinis','sapouni',500,400),
(null,'rouxa','mplouzes',500,200),
(null,'rouxa','pantelonia',500,250),
(null,'rouxa','esorouxa',500,350),
(null,'rouxa','kaltses',500,200),
(null,'rouxa','papoutsia',500,400),
(null,'leuka eidi','sentonia',500,200),
(null,'leuka eidi','petsetes',500,300),
(null,'leuka eidi','koubertes',500,350);








