CREATE TABLE `project_comment_rel` (
	`id`      INT(10) NOT NULL AUTO_INCREMENT, 
	`project` INT(10) NOT NULL, 
	`comment` INT(10) NOT NULL, 
	
	FOREIGN KEY(`project`) REFERENCES `projects`(`id`) ON DELETE CASCADE, 
	FOREIGN KEY(`comment`) REFERENCES `comments`(`id`) ON DELETE CASCADE, 
	UNIQUE KEY(`comment`), 
	PRIMARY KEY(`id`));
	
CREATE INDEX `comment_by_project`        ON `project_comment_rel`(`project`);
CREATE UNIQUE INDEX `project_by_comment` ON `project_comment_rel`(`comment`);