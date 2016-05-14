CREATE TABLE `projects` (
	`id`          INT(10)      NOT NULL AUTO_INCREMENT, 
	`name`        VARCHAR(100) NOT NULL DEFAULT 'Unnamed Project', 
	`excerpt`     TEXT         NOT NULL, 
	`description` TEXT         NOT NULL, 
	`published`   INT(1)       NOT NULL DEFAULT 0, 
	PRIMARY KEY(`id`));
	
CREATE INDEX `project_by_publish` ON `projects`(`published`);