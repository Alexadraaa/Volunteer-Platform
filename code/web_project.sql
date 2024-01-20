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

create table vehicle (
ve_id int not null auto_increment,
ve_username varchar(30) not null unique,
ve_state enum('fortosi','ekfortosi','ontheroad'),
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


CREATE TABLE orders (
    or_id INT NOT NULL AUTO_INCREMENT,
    or_c_id INT NOT NULL,
    or_date DATE NOT NULL DEFAULT CURRENT_DATE,
    or_type ENUM('Αίτημα', 'Προσφορά') ,
	order_state ENUM('Σε επεξεργασία', 'Παραδόθηκε', 'Προς Παράδοση') NOT NULL,
    PRIMARY KEY(or_id),
    CONSTRAINT ordc FOREIGN KEY(or_c_id) REFERENCES users(user_id) ON DELETE CASCADE ON UPDATE CASCADE
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


insert into users values
('1001','Marianthi','Thodi','Sostratou 15','6987562335','MThodi','Marianthi','admin',null),
('1002','Alexandra','Kagiouli','Karaiskaki 225','6996558657','AKag','Alexandra','admin',null),
('1003','Alexis','Giannoutsos','Ellinos Stratiotou 50','6984521424','AGian','Alexis','admin',null),
('2001','Evaggelia','Kolampa','Xeilonos Patreos 25','6989563635','EvaKol','Evaggelia','rescuer',null),
('2002','Elpida','Kokkali','Ellinos Stratiotou 65','6989455226','ElpKo','Elpida','rescuer',null),
('2003','Erifili','Karagianni','Erissou 6','6952341126','EriKar','Erifili','rescuer',null),
('3017','Katerina','Lakkou','Sostratou 17',6933662211,'KatLak','Katerina','civilian',null),
('3018','Elpida', 'Kati','Gounari 15',6965871245,'ElpKat','ElpidaKat','civilian',null),
('3019','Fotoula', 'Zaxaropoulou','Maizonos 65',6985552321,'FotZax','Fotoula','civilian',null),
('3020','Stavroula', 'Liaskou','Maizonos 235',6932111425,'StavLiask','Stavroula','civilian',null);

insert into administrator values
('1001'),
('1002'),
('1003');

insert into civilian values
(3017),
(3018),
(3019),
(3020);


insert into base values
('zymarika','makaronia',500,300),
('zymarika','ryzi',500,400),
('zymarika','lazania',500,200),
('zymarika','xylopites',500,250),
('zymarika','kritharaki',500,300),
('zymarika','traxanas',500,450),
('konserves','kalampoki',500,200),
('konserves','tonos',500,250),
('konserves','giganteskon',500,300),
('konserves','ntolmantakia',500,150),
('konserves','sardeles',500,450),
('konserves','pikles',500,470),
('ospria','fakes',500,300),
('ospria','revithia',500,350),
('ospria','fasolia',500,250),
('galaktokomika','gala',500,300),
('galaktokomika','tyri',500,350),
('galaktokomika','giaourti',500,250),
('psomi','xwriatiko',500,200),
('psomi','tost',500,300),
('psomi','olikis',500,150),
('psomi','friganies',500,350),
('alantika','loukaniko',500,400),
('alantika','zampon',500,300),
('alantika','galopoula',500,250),
('alantika','aeros',500,210);

insert into vehicle values
(620,'alekos','fortosi');

insert into rescuer values
(2001,620);

/*
CREATE INDEX idx_username ON users (username);
CREATE INDEX idx_address ON users (address);
CREATE INDEX idx_role ON users (role);
CREATE INDEX idx_username ON vehicle (ve_username);


grant all privileges on web.* to 'Mthodi'@'%'; 
grant all privileges on web.* to 'AKag'@'%'; 
grant all privileges on web.* to 'AGian'@'%'; 
*/
/* ενσταση για τα autocrement της αλεξ , αυτο με το να διαχωριζουμε τους users και τα προιοντα
και με βαζη τα id τους , πχ ολοι οι civilians 3000 κατι id ή rescuer id 2000 κατι δεν θα δουλεψει
διοτι οταν θα προσθετεις στην βαση πχ εναν rescuer και ο τελευταιος που εχει προσθετει ειναι civilian
πχ 3001 ο επομενος θα αποθηκευτει ως 3002 και οχι ως 2000 κατι .*/


/*
create table orders(
or_id int not null auto_increment,
or_o_id int,
or_re_id int,
or_date date not null,
or_type enum('offer','request'),
or_ve_username varchar(25) ,
primary key(or_id),
constraint ordo foreign key(or_o_id) references offers(o_id)
on delete cascade on update cascade,
constraint ordre foreign key(or_re_id) references requests(re_id)
on delete cascade on update cascade,
constraint orveusername foreign key(or_ve_username) references vehicle(ve_username)
on delete cascade on update cascade,
constraint orid foreign key(or_id) references tasks(t_id) 
on delete cascade on update cascade
);*/