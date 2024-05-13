--admin table
create table admin(username varchar(20), user_password varchar(50),id int PRIMARY KEY identity(1,1));


--vehicle table
create table vehicle(vehicleno varchar(15) primary key, vehicletype varchar(10) not null, brand varchar(20),presentmeter int not null, nextmeter int not null);

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


------------------------------------------------------inserting dummy data and testing------------------------------------------------------------------------

insert into admin(username,user_password)
values('subhanu','#535f523fvs'),('sakith', 'fjkrnfw'),('konara','rwwenk342');


-- Insert into vehicle table
INSERT INTO vehicle (vehicleno, vehicletype, brand, presentmeter, nextmeter) VALUES 
('ABC123', 'Car', 'Toyota', 10000, 12000),
('XYZ789', 'Truck', 'Ford', 5000, 7000),
('DEF456', 'SUV', 'Chevrolet', 20000, 22000),
('GHI789', 'Van', 'Honda', 15000, 17000),
('JKL012', 'Motorcycle', 'Harley Davidson', 8000, 10000);

-- Insert into customer table
INSERT INTO customer (customerid, phnno, name) VALUES 
(1, '1234567890', 'John Doe'),
(2, '9876543210', 'Jane Smith'),
(3, '5556667777', 'Alice Johnson'),
(4, '3332221111', 'Bob Roberts'),
(5, '9998887777', 'Emily Brown');

-- Insert into service table
INSERT INTO service (service_des, price) VALUES 
('Oil Change', 50.00),
('Tire Rotation', 30.00),
('Brake Pad Replacement', 80.00),
('Engine Tune-up', 120.00),
('Wheel Alignment', 60.00);

-- Insert into category table
INSERT INTO category (cat_id, category_name) VALUES 
(1, 'Engine Parts'),
(2, 'Electrical Parts'),
(3, 'Brake System'),
(4, 'Suspension System'),
(5, 'Interior Accessories');

-- Insert into brand table
INSERT INTO brand (brand_id, brand) VALUES 
(1, 'Toyota'),
(2, 'Ford'),
(3, 'Chevrolet'),
(4, 'Honda'),
(5, 'Harley Davidson');

-- Insert into product table
INSERT INTO product (productname, brand_id, cat_id, part_no, me_no, price, stock_qt) VALUES 
('Spark Plug', 1, 1, 1234, 5678, 5.00, 100),
('Alternator', 2, 2, 5678, 9012, 100.00, 50),
('Brake Pad Set', 3, 3, 9876, 5432, 40.00, 80),
('Shock Absorber', 4, 4, 1357, 2468, 80.00, 60),
('Floor Mats', 5, 5, 2468, 1357, 20.00, 120);

--inserting to billing
INSERT INTO billing (total_amt, vehicle_no, customer_id) VALUES 
(150.00, 'ABC123', 1),
(200.00, 'XYZ789', 2),
(80.00, 'DEF456', 3),
(120.00, 'GHI789', 4),
(90.00, 'JKL012', 5);

-- Insert into service_bill_rel table
INSERT INTO service_bill_rel (bill_no, serv_id) VALUES 
(1, 1),
(1, 2),
(2, 3),
(3, 4),
(4, 5);

-- Insert into product_bill_rel table
-- Since product_bill_rel relates products to billing, let's assume for simplicity that each billing record includes all products. So, for each billing record, we'll insert all products.
INSERT INTO product_bill_rel (p_id, bill_no) 
SELECT p_id, 1 FROM product WHERE p_id IN (1, 2, 3, 4, 5);
INSERT INTO product_bill_rel (p_id, bill_no) 
SELECT p_id, 2 FROM product WHERE p_id IN (1, 2, 3, 4, 5);
INSERT INTO product_bill_rel (p_id, bill_no) 
SELECT p_id, 3 FROM product WHERE p_id IN (1, 2, 3, 4, 5);
INSERT INTO product_bill_rel (p_id, bill_no) 
SELECT p_id, 4 FROM product WHERE p_id IN (1, 2, 3, 4, 5);
INSERT INTO product_bill_rel (p_id, bill_no) 
SELECT p_id, 5 FROM product WHERE p_id IN (1, 2, 3, 4, 5);



select * from admin;
select * from billing;
select * from brand;
select * from category;
select * from product;
select * from service;
select * from vehicle;
select * from product_bill_rel;
select * from service_bill_rel;

