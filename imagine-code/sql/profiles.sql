create table `profiles` (
	`id` int(10) not null auto_increment, 
	`f_name` varchar(64) not null default 'Unknown', 
	`l_name` varchar(64) not null default 'Name', 
	`email` varchar(64) not null, 
	`user` int(10) not null, 
	foreign key(`user`) references `users`(`id`), 
	unique key(`user`), 
	primary key(`id`));