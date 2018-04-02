CREATE DATABASE `phptest`;

GRANT SELECT, INSERT, UPDATE, DELETE
  ON `phptest`.*
  TO 'news_rw'@'localhost'
  IDENTIFIED BY 'pass';

USE `phptest`;

CREATE TABLE `news` (
  `id` INT(10) NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(511) NOT NULL,
  `body` MEDIUMTEXT NOT NULL,
  `created_at` DATETIME DEFAULT NULL,
  `is_deleted` TINYINT(1) DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `idx_is_deleted` (`is_deleted`)
);

CREATE TABLE `comment` (
  `id` INT(10) NOT NULL AUTO_INCREMENT,
  `body` MEDIUMTEXT NOT NULL,
  `created_at` DATETIME DEFAULT NULL,
  `news_id` INT(10) NOT NULL,
  `is_deleted` TINYINT(1) DEFAULT 0,
  FOREIGN KEY(`news_id`) REFERENCES `news`(`id`),
  PRIMARY KEY (`id`),
  KEY `idx_news_id_is_deleted` (`news_id`, `is_deleted`)
);

LOCK TABLES `news` WRITE;
INSERT INTO `news` (`id`, `title`, `body`, `created_at`) VALUES
(1, 'news 1', 'this is the description of our fist news', '2016-11-30 14:18:23'),
(2, 'news 2', 'this is the description of our second news', '2016-11-30 14:24:23'),
(3, 'news 3', 'this is the description of our third news', '2016-12-01 04:33:23'),
(4, 'Lorem Ipsum ni Dolor Sit Amen', '<p>Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Vestibulum tortor quam, feugiat vitae, ultricies eget, tempor sit amet, ante. Donec eu libero sit amet quam egestas semper. Aenean ultricies mi vitae est. Mauris placerat eleifend leo. Quisque sit amet est et sapien ullamcorper pharetra. Vestibulum erat wisi, condimentum sed, commodo vitae, ornare sit amet, wisi. Aenean fermentum, elit eget tincidunt condimentum, eros ipsum rutrum orci, sagittis tempus lacus enim ac dui. Donec non enim in turpis pulvinar facilisis. Ut felis. Praesent dapibus, neque id cursus faucibus, tortor neque egestas augue, eu vulputate magna eros eu erat. Aliquam erat volutpat. Nam dui mi, tincidunt quis, accumsan porttitor, facilisis luctus, metus</p>', '2018-04-01 10:48:50'),
(5, 'Phasellus Ultrices nulla Quis Nibh', '<p>Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Vestibulum tortor quam, feugiat vitae, ultricies eget, tempor sit amet, ante. Donec eu libero sit amet quam egestas semper. Aenean ultricies mi vitae est. Mauris placerat eleifend leo. Quisque sit amet est et sapien ullamcorper pharetra. Vestibulum erat wisi, condimentum sed, commodo vitae, ornare sit amet, wisi. Aenean fermentum, elit eget tincidunt condimentum, eros ipsum rutrum orci, sagittis tempus lacus enim ac dui. Donec non enim in turpis pulvinar facilisis. Ut felis. Praesent dapibus, neque id cursus faucibus, tortor neque egestas augue, eu vulputate magna eros eu erat. Aliquam erat volutpat. Nam dui mi, tincidunt quis, accumsan porttitor, facilisis luctus, metus</p>\n\n<ul>\n   <li>Morbi in sem quis dui placerat ornare. Pellentesque odio nisi, euismod in, pharetra a, ultricies in, diam. Sed arcu. Cras consequat.</li>\n   <li>Praesent dapibus, neque id cursus faucibus, tortor neque egestas augue, eu vulputate magna eros eu erat. Aliquam erat volutpat. Nam dui mi, tincidunt quis, accumsan porttitor, facilisis luctus, metus.</li>\n   <li>Phasellus ultrices nulla quis nibh. Quisque a lectus. Donec consectetuer ligula vulputate sem tristique cursus. Nam nulla quam, gravida non, commodo a, sodales sit amet, nisi.</li>\n   <li>Pellentesque fermentum dolor. Aliquam quam lectus, facilisis auctor, ultrices ut, elementum vulputate, nunc.</li>\n</ul>', '2018-04-01 10:51:31'),
(6, 'Maecenas Ipsum velit Consectetuer', '<script>alert(''This should be stripped'');</script><p>Maecenas ipsum velit, consectetuer eu, lobortis ut, dictum at, dui. In rutrum. Sed ac dolor sit amet purus malesuada congue. In laoreet, magna id viverra tincidunt, sem odio bibendum justo, vel imperdiet sapien wisi sed libero. Suspendisse sagittis ultrices augue. Mauris metus. Nunc dapibus tortor vel mi dapibus sollicitudin. Etiam posuere lacus quis dolor. Praesent id justo in neque elementum ultrices. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos hymenaeos. In convallis. Fusce suscipit libero eget elit. Praesent vitae arcu tempor neque lacinia pretium. Morbi imperdiet, mauris ac auctor dictum, nisl ligula egestas nulla, et sollicitudin sem purus in lacus.</p>\n\n<p>Maecenas ipsum velit, consectetuer eu, lobortis ut, dictum at, dui. In rutrum. Sed ac dolor sit amet purus malesuada congue. In laoreet, magna id viverra tincidunt, sem odio bibendum justo, vel imperdiet sapien wisi sed libero. Suspendisse sagittis ultrices augue. Mauris metus. Nunc dapibus tortor vel mi dapibus sollicitudin. Etiam posuere lacus quis dolor. Praesent id justo in neque elementum ultrices. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos hymenaeos. In convallis. Fusce suscipit libero eget elit. Praesent vitae arcu tempor neque lacinia pretium. Morbi imperdiet, mauris ac auctor dictum, nisl ligula egestas nulla, et sollicitudin sem purus in lacus.</p>', '2018-04-01 21:39:19');
UNLOCK TABLES;

LOCK TABLES `comment` WRITE;
INSERT INTO `comment` (`id`, `body`, `created_at`, `news_id`) VALUES
(1, 'i like this news', '2016-11-30 14:21:23', 1),
(2, 'i have no opinion about that', '2016-11-30 14:24:08', 1),
(3, 'are you kidding me ?', '2016-11-30 14:28:06', 1),
(4, 'this is so true', '2016-11-30 17:21:23', 2),
(5, 'trolololo', '2016-11-30 17:39:25', 2),
(6, 'luke i am your father', '2016-11-30 14:34:06', 3),
(7, 'In convallis. Fusce suscipit libero eget elit. Praesent vitae arcu tempor neque lacinia pretium. Morbi imperdiet, mauris ac auctor dictum, nisl ligula egestas nulla, et sollicitudin sem purus in lacus.', '2018-04-01 22:30:12', 6);
UNLOCK TABLES;
