create table `users` (
	`id` int(10) not null auto_increment, 
	`username` varchar(64) not null, 
	`password` varchar(64) not null, 
	`active` int(1) not null default 0, 
	unique key(`username`), 
	primary key(`id`));