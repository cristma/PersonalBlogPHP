CREATE TABLE `project_category_rel` (
	`id`       INT(10) NOT NULL AUTO_INCREMENT, 
	`project`  INT(10) NOT NULL, 
	`category` INT(10) NOT NULL, 
	
	FOREIGN KEY(`project`)  REFERENCES `projects`(`id`)   ON DELETE CASCADE, 
	FOREIGN KEY(`category`) REFERENCES `categories`(`id`) ON DELETE CASCADE, 
	PRIMARY KEY(`id`));
	
CREATE INDEX `project_by_category` ON `project_category_rel`(`category`);
CREATE INDEX `category_by_project` ON `project_category_rel`(`project`);