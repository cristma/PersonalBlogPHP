CREATE TABLE `profiles` (
	`id`    INT(10)     NOT NULL AUTO_INCREMENT, 
	`fname` VARCHAR(32) NOT NULL DEFAULT 'Unknown', 
	`lname` VARCHAR(32) NOT NULL DEFAULT 'User', 
	`email` VARCHAR(64) NOT NULL, 
	`user`  INT(10)     NOT NULL, 

	UNIQUE  KEY(`email`), 
	UNIQUE  KEY(`user`), 
	FOREIGN KEY(`user`) REFERENCES `users`(`id`) ON DELETE CASCADE, 
	PRIMARY KEY(`id`));
	
CREATE UNIQUE INDEX `profiles_by_user`  ON `profiles`(`user`);
CREATE UNIQUE INDEX `profiles_by_email` ON `profiles`(`email`);