create table `amendments` (
	`id` int(10) not null auto_increment, 
	`content` text not null, 
	`author` int(10) not null, 
	foreign key(`author`) references `profiles`(`id`), 
	primary key(`id`));