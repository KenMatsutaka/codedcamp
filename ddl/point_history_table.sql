-- phpMyAdmin SQL Dump
-- version 4.0.5
-- http://www.phpmyadmin.net
--
-- ホスト: localhost
-- 生成日時: 2013 年 11 月 26 日 15:05
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
-- テーブルの構造 `point_history_table`
--

CREATE TABLE IF NOT EXISTS `point_history_table` (
  `customer_id` int(11) unsigned NOT NULL,
  `point_gift_id` int(11) unsigned NOT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='履歴テーブル';

--
-- テーブルのデータのダンプ `point_history_table`
--

INSERT INTO `point_history_table` (`customer_id`, `point_gift_id`, `created_at`) VALUES
(1, 1, '2013-10-01 15:23:46'),
(1, 5, '2013-10-01 15:25:10'),
(2, 2, '2013-10-02 12:47:52'),
(3, 1, '2013-10-02 20:38:09');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
