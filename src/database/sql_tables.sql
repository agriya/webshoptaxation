CREATE TABLE `taxations`
(
	`id` INT NOT NULL AUTO_INCREMENT ,
	`user_id` INT ,     
	`tax_name` VARCHAR(255) ,
	`tax_description` MEDIUMTEXT ,
	`tax_fee` FLOAT ,
	`fee_type` ENUM('percentage','flat') ,
	PRIMARY KEY (`id`)
);


CREATE TABLE `product_taxes`
(
	`id` INT NOT NULL AUTO_INCREMENT ,
	`tax_id` INT ,
	`product_id` INT ,
	`user_id` INT ,
	`tax_fee` FLOAT ,
	`fee_type` ENUM('percentage','flat') ,
	PRIMARY KEY (`id`)  
);