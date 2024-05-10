--admin table
create table admin(username varchar(20), user_password varchar(10),id int PRIMARY KEY);


--vehicle table
create table vehicle(vehicleno varchar(15) primary key, vehicletype varchar(10) not null, brand varchar(10),presentmeter int not null, nextmeter int not null);

--customer table
create table customer(customerid int primary key, phnno varchar(10),name varchar(50));

--billing table
create table billing(bill_no int primary key identity(1,1),date datetime default CURRENT_TIMESTAMP, total_amt smallmoney not null, vehicle_no varchar(15) not null, customer_id int,
foreign key (vehicle_no) references vehicle(vehicleno),
foreign key (customer_id) references customer(customerid));


--service table
create table service(s_id int primary key identity(1,1), service_des varchar(1000), price smallmoney not null);

--service bill  relationship
create table service_bill_rel(bill_no int,serv_id int,
primary key(bill_no,serv_id),
foreign key (bill_no) references billing(bill_no),
foreign key (serv_id) references service(s_id));


--category table
create table category(cat_id int primary key, category_name varchar(20));

--brand table
create table brand(brand_id int primary key, brand varchar(30));

--product table
create table product(p_id int primary key identity(1,1),productname varchar(100),brand_id int ,cat_id int ,part_no int,me_no int,price smallmoney not null,stock_qt int not null,
foreign key (brand_id) references  brand(brand_id),
foreign key (cat_id) references category(cat_id));



--product bill relationship
create table product_bill_rel(p_id int, bill_no int, primary key(p_id,bill_no),
foreign key (p_id) references product(p_id),
foreign key(bill_no) references billing(bill_no));


/*
--query to be used in fetch  php query
select billing.bill_no, billing.vehicle_no,billing.date,vehicle.vehicletype,vehicle.presentmeter,vehicle.nextmeter,billing.customer_id,billing.total_amt
from billing,vehicle
where billing.vehicle_no=vehicle.vehicleno;

or use this procedure,
exec record_view_bills;


*/
create procedure record_view_bills
as
begin
select billing.bill_no, billing.vehicle_no,billing.date,vehicle.vehicletype,vehicle.presentmeter,vehicle.nextmeter,billing.customer_id,billing.total_amt
from billing,vehicle
where billing.vehicle_no=vehicle.vehicleno;
end;
