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
-- テーブルの構造 `point_gift_table`
--

CREATE TABLE IF NOT EXISTS `point_gift_table` (
  `point_gift_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `point` int(11) NOT NULL COMMENT '必要ポイント',
  PRIMARY KEY (`point_gift_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='ギフト情報' AUTO_INCREMENT=6 ;

--
-- テーブルのデータのダンプ `point_gift_table`
--

INSERT INTO `point_gift_table` (`point_gift_id`, `name`, `point`) VALUES
(1, 'コーラ', 100),
(2, 'USB', 2000),
(3, '傘', 500),
(4, 'お茶', 100),
(5, 'サイダー', 100);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
