drop database if exists testing_final;
create database testing_final;
use testing_final;

CREATE TABLE users (
    user_id INT AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    lastname VARCHAR(255) NOT NULL,
    address VARCHAR(255) NOT NULL,
    phone VARCHAR(255) NOT NULL,
    username VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'rescuer', 'civilian') NOT NULL,
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
ve_state enum('ontheroad','onhold'),
primary key(ve_id)
);

create table rescuer (
resc_id int not null,
resc_ve_id int ,
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
('1001','Marianthi','Thodi','Sostratou 15','6987562335','MThodi','Marianthi','admin'),
('1002','Alexandra','Kagiouli','Karaiskaki 225','6996558657','AKag','Alexandra','admin'),
('1003','Alexis','Giannoutsos','Ellinos Stratiotou 50','6984521424','AGian','Alexis','admin'),
('2001','Evaggelia','Kolampa','Xeilonos Patreos 25','6989563635','EvaKol','Evaggelia','rescuer'),
('2002','Elpida','Kokkali','Ellinos Stratiotou 65','6989455226','ElpKo','Elpida','rescuer'),
('2003','Erifili','Karagianni','Erissou 6','6952341126','EriKar','Erifili','rescuer'),
('2004','Marileni','Valavani','Agias sofias 15','6989784515','MarVal','Marileni','rescuer'),
('2005','Danai','Batsouli','Agias Sofias 17','6956231224','DanB','Danai','rescuer'),
('2006','Alexia','Diamantopoulou','Ellinos Stratiotou 56','6953233629','AlDiam','Alexia','rescuer'),
('2007','Fotis','Kalioras','Agias sofias 32','6984512335','FotKal','Fotis','rescuer'),
('2008','Panos','Kapetanidis','Ellinos Stratiotou 87','6987453226','PanKap','Panos','rescuer'),
('2009','Antonis','Lykourinas','Agias sofias 51','6987562336','AntLyk','Antonis','rescuer'),
('2010','Theodora','Vaso','Xeilonos Patreos 23','6989562341','TheodV','Theodora','rescuer'),
('2011','Eleni','Gallous','Xeilonos Patreos 14','6989564578','EleniG','Eleni','rescuer'),
('2012','Aristeidis','Votsi','Karaiskaki 28','6985231425','ArisV','Aristeidis','rescuer'),
('2013','Elton','Pietri','Karaiskaki 24','6985755326','EltonP','Elton','rescuer'),
('2014','Sertzio','Dasi','Kanakri 78','6985452632','SertzD','Sertzio','rescuer'),
('2015','Eneas','Lepouri','Ellinos Stratiotou 65','6956368947','EniL','Eneas','rescuer'),
('2016','Jonathan','Chacon','Kanakari 336','6989562336','JonCh','Jonathan','rescuer'),
('2017','Maria','Anastasopoulou','Kolokotroni 15','6989563625','MarAnast','Maria','rescuer'),
('2018','Markella','Magouli','Kolokotroni 78','6936235425','MarkeMag','Markella','rescuer'),
('2019','Clara','Bencomo','Kolokotroni 56','6956592631','ClaraBen','Clara','rescuer'),
('2020','Alice','Karagianni','Kolokotroni 25','6986425312','AliceKar','Aliki','rescuer'),
('3001','Athina', 'Vamvaka','Korinthou 15',6951525356,'AthVam','Athina','civilian'),
('3002','Nikol', 'Mitsoula','Agiou Andrea 105',6956587859,'NikMi','Nikol','civilian'),
('3003','Aggeliki', 'Sideri','Gounari 108',6956632414,'AggSid','Aggeliki','civilian'),
('3004','Xristina', 'Kallidi','Kanakari 56',6996332546,'ChrisKal','Christina','civilian'),
('3005','Maria', 'Aggelidi','Gounari 227',6963332545,'MarAgg','MariaAgg','civilian'),
('3006','Nikoleta', 'Zervidi','Karaiskaki 108',6998741235,'NikZer','Nikoleta','civilian'),
('3007','Christina', 'Nikodimou','Karaiskaki 105',6995232214,'ChristN','ChristinaN','civilian'),
('3008','Dionisios', 'Xenakis','Patreos 50',6989552336,'DionXen','Dionisis','civilian'),
('3009','Stefanos', 'Xenopoulos','Patreos 22',6996335544,'StefXen','Stefanos','civilian'),
('3010','Stefanos', 'Doutsi','Karaiskaki 120',6987554885,'StefDou','StefanosD','civilian'),
('3011','Tzeni', 'Kurti','Korinthou 225',6993221245,'TzKour','Tzeni','civilian'),
('3012','Marianneta','Daskalopoulou','Sostratou 19',6998885223,'MarDas','Marianneta','civilian'),
('3013','Stavros','Daskalopoulos','Amerikis 80',6998774455,'StavDas','Stavros','civilian'),
('3014','Fotini','Lampropoulou','Amerikis 100',6925665541,'FotLamp','Foteini','civilian'),
('3015','Marika', 'Rokka','Korinthou 127',6933665245,'MarRo','Marika','civilian'),
('3016','Eugenia', 'Katintzarou','Karaiskaki 103',6936223545,'EvgKat','Evgenia','civilian'),
('3017','Katerina','Lakkou','Sostratou 17',6933662211,'KatLak','Katerina','civilian'),
('3018','Elpida', 'Kati','Gounari 15',6965871245,'ElpKat','ElpidaKat','civilian'),
('3019','Fotoula', 'Zaxaropoulou','Maizonos 65',6985552321,'FotZax','Fotoula','civilian'),
('3020','Stavroula', 'Liaskou','Maizonos 235',6932111425,'StavLiask','Stavroula','civilian');

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


insert into base(category,product,num) values
('zymarika','makaronia',300),
('zymarika','ryzi',400),
('zymarika','lazania',200),
('zymarika','xylopites',250),
('zymarika','kritharaki',300),
('zymarika','traxanas',450),
('konserves','kalampoki',200),
('konserves','tonos',250),
('konserves','giganteskon',300),
('konserves','ntolmantakia',150),
('konserves','sardeles',450),
('konserves','pikles',470),
('ospria','fakes',300),
('ospria','revithia',350),
('ospria','fasolia',250),
('galaktokomika','gala',300),
('galaktokomika','tyri',350),
('galaktokomika','giaourti',250),
('psomi','xwriatiko',200),
('psomi','tost',300),
('psomi','olikis',150),
('psomi','friganies',350),
('alantika','loukaniko',400),
('alantika','zampon',300),
('alantika','galopoula',250),
('alantika','kotopoulo',300),
('alantika','aeros',210);



insert into vehicle values
(620,'alekos','onhold'),
(null,'alexis','onhold'),
(null,'marianthi','onhold'),
(null,'loser','onhold'),
(null,'duck','onhold'),
(null,'tortilla','onhold'),
(null,'burito','onhold'),
(null,'lol','onhold'),
(null,'noname','onhold'),
(null,'jo','onhold'),
(null,'koukla','onhold'),
(null,'lisa','onhold'),
(null,'tyn','onhold'),
(null,'miko','onhold'),
(null,'drama','onhold'),
(null,'bb','onhold'),
(null,'omar','onhold'),
(null,'chulo','onhold'),
(null,'red','onhold'),
(null,'loca','onhold');

insert into rescuer values
(2001,620),
(2002,null),
(2003,null),
(2004,null),
(2005,null),
(2006,null),
(2007,null),
(2008,null),
(2009,null),
(2010,null),
(2011,null),
(2012,null),
(2013,null),
(2014,null),
(2015,null),
(2016,null),
(2017,null),
(2018,null),
(2019,null),
(2020,null);

insert into markers values
(null,38.245823, 21.735651,'base',null,null),
(null,38.239507700045884, 21.737859780583516,"inactiveTaskCar",null,620),
(null,38.2592440858477, 21.74206245174889,"inactiveTaskCar",null,621),
(null,38.24487733457579, 21.737573887138097,"inactiveTaskCar",null,622),
(null,38.25750699261597, 21.740772480584425,"inactiveTaskCar",null,623),
(null,38.257574438003864, 21.741119097776387,"inactiveTaskCar",null,624),
(null,38.25849211396602, 21.742052065241904,"inactiveTaskCar",null,625),
(null,38.256472443775195, 21.74388259592688,"inactiveTaskCar",null,626),
(null,38.26102906012175, 21.742858840105352,"inactiveTaskCar",null,627),
(null,38.25685331738669, 21.743902780584275,"inactiveTaskCar",null,628),
(null,38.239532980299444, 21.737891967090434,"inactiveTaskCar",null,629);

CREATE INDEX idx_username ON users (username);
CREATE INDEX idx_address ON users (address);
CREATE INDEX idx_role ON users (role);
CREATE INDEX idx_username ON vehicle (ve_username);
