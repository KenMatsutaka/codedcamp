-- phpMyAdmin SQL Dump
-- version 4.0.5
-- http://www.phpmyadmin.net
--
-- ホスト: localhost
-- 生成日時: 2013 年 11 月 26 日 15:04
-- サーバのバージョン: 5.5.34
-- PHP のバージョン: 5.4.21

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- データベース: `codecamp`
--

-- --------------------------------------------------------

--
-- テーブルの構造 `point_customer_table`
--

CREATE TABLE IF NOT EXISTS `point_customer_table` (
  `customer_id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ユーザーID',
  `point` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '保有ポイント',
  PRIMARY KEY (`customer_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='ユーザーの保有ポイント情報' AUTO_INCREMENT=4 ;

--
-- テーブルのデータのダンプ `point_customer_table`
--

INSERT INTO `point_customer_table` (`customer_id`, `point`) VALUES
(1, 900),
(2, 2800),
(3, 200);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
