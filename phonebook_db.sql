create database phonebook;
use phonebook;

create table users (
	id int not null primary key AUTO_INCREMENT,
    name varchar(255) not null,
    lastname varchar(255) not null,
    email varchar(255) not null,
    phone varchar(20) not null,
    password varchar(255) not null
);

create table contacts (
	id int not null primary key AUTO_INCREMENT,
    users_key int not null,
    name varchar(255) not null,
    lastname varchar(255) not null,
    email varchar(255),
	phone varchar(255)
);