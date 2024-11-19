/*
SQLyog Ultimate v13.1.1 (64 bit)
MySQL - 10.4.24-MariaDB : Database - uas
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`uas` /*!40100 DEFAULT CHARACTER SET utf8mb4 */;

USE `uas`;

/*Table structure for table `categories` */

DROP TABLE IF EXISTS `categories`;

CREATE TABLE `categories` (
  `category_id` int(11) NOT NULL AUTO_INCREMENT,
  `category_name` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`category_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4;

/*Data for the table `categories` */

insert  into `categories`(`category_id`,`category_name`) values 
(1,'Accessories'),
(2,'Laptop'),
(3,'Kulkas'),
(4,'Kipas Angin'),
(5,'Mesin Cuci'),
(6,'Televisi');

/*Table structure for table `clients` */

DROP TABLE IF EXISTS `clients`;

CREATE TABLE `clients` (
  `client_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `contact_person` varchar(255) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  PRIMARY KEY (`client_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4;

/*Data for the table `clients` */

insert  into `clients`(`client_id`,`name`,`contact_person`,`phone`,`email`,`address`) values 
(1,'Tech Retailers','Eve Green','333-222-1111','eve@techretailers.com','101 Commerce Blvd, City E'),
(2,'Home Electronics','Frank White','444-555-6666','frank@homeelectronics.com','202 Market St, City F'),
(3,'Office Supplies Plus','Grace Black','777-888-9999','grace@officesupplies.com','303 Business Rd, City G'),
(4,'Gadget World','Henry Adams','222-333-4444','henry@gadgetworld.com','404 Innovation Ln, City H');

/*Table structure for table `products` */

DROP TABLE IF EXISTS `products`;

CREATE TABLE `products` (
  `product_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `category` int(11) DEFAULT NULL,
  `brand` varchar(50) DEFAULT NULL,
  `model_number` varchar(50) DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `stock_quantity` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`product_id`),
  KEY `category` (`category`),
  CONSTRAINT `products_ibfk_1` FOREIGN KEY (`category`) REFERENCES `categories` (`category_id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4;

/*Data for the table `products` */

insert  into `products`(`product_id`,`name`,`category`,`brand`,`model_number`,`price`,`stock_quantity`) values 
(1,'Charger Fast Charging',1,'TechBrand','TB-FC100',15.99,50),
(2,'Wireless Earbuds',1,'SoundPlus','SP-WE200',45.00,30),
(3,'Power Bank 10,000mAh',1,'ChargeMax','CM-PB10K',25.50,20),
(4,'HDMI Cable 2m',1,'CablePro','CP-HDMI2',10.00,100),
(5,'Bluetooth Speaker',2,'SoundPlus','SP-BS500',55.75,15);

/*Table structure for table `suppliers` */

DROP TABLE IF EXISTS `suppliers`;

CREATE TABLE `suppliers` (
  `supplier_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `contact_person` varchar(100) DEFAULT NULL,
  `phone` varchar(15) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `address` text DEFAULT NULL,
  PRIMARY KEY (`supplier_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4;

/*Data for the table `suppliers` */

insert  into `suppliers`(`supplier_id`,`name`,`contact_person`,`phone`,`email`,`address`) values 
(1,'Tech Supplies Co.','Alice Johnson','123-456-7890','alice@techsupplies.com','123 Tech Street, City A'),
(2,'SoundPlus Distributors','Bob Smith','987-654-3210','bob@soundplus.com','456 Music Lane, City B'),
(3,'ChargeMax Supplier','Carol Lee','555-666-7777','carol@chargemax.com','789 Power Road, City C'),
(4,'CablePro Wholesale','David Brown','444-333-2222','david@cablepro.com','101 Connect Ave, City D');

/*Table structure for table `transactions` */

DROP TABLE IF EXISTS `transactions`;

CREATE TABLE `transactions` (
  `transaction_id` int(11) NOT NULL AUTO_INCREMENT,
  `product_id` int(11) NOT NULL,
  `transaction_type` enum('IN','OUT') NOT NULL,
  `quantity` int(11) NOT NULL,
  `transaction_date` datetime NOT NULL DEFAULT current_timestamp(),
  `supplier_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`transaction_id`),
  KEY `product_id` (`product_id`),
  KEY `supplier_id` (`supplier_id`),
  CONSTRAINT `transactions_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`) ON DELETE CASCADE,
  CONSTRAINT `transactions_ibfk_2` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`supplier_id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4;

/*Data for the table `transactions` */

insert  into `transactions`(`transaction_id`,`product_id`,`transaction_type`,`quantity`,`transaction_date`,`supplier_id`) values 
(1,1,'IN',20,'2024-11-01 10:00:00',1),
(2,2,'IN',15,'2024-11-02 14:00:00',2),
(3,3,'IN',10,'2024-11-03 09:30:00',3),
(4,4,'OUT',5,'2024-11-04 11:00:00',NULL),
(5,5,'OUT',2,'2024-11-05 15:45:00',NULL),
(6,1,'OUT',10,'2024-11-06 12:00:00',NULL),
(7,3,'IN',25,'2024-11-07 08:00:00',3),
(8,2,'OUT',3,'2024-11-08 10:30:00',NULL);

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
