--■ドリンクテーブル
CREATE TABLE DRINK_TBL
(
    DRINK_ID INT NOT NULL AUTO_INCREMENT PRIMARY KEY COMMENT 'ドリンクID',
    DRINK_NAME NVARCHAR(100) COMMENT 'ドリンク名',
    PRICE INT COMMENT '価格',
    INSERT_DATE DATETIME COMMENT '登録日',
    UPDATE_DATE DATETIME COMMENT '更新日',
    OPEN_STATUS INT COMMENT '公開ステータス',
    UPLOAD_FILE_NAME NVARCHAR(255) COMMENT 'ファイル名'
)
DEFAULT charset=utf8
COMMENT 'ドリンクテーブル';

--DROP TABLE DRINK_TBL;

INSERT INTO DRINK_TBL (DRINK_NAME, PRICE, INSERT_DATE, UPDATE_DATE, OPEN_STATUS, UPLOAD_FILE_NAME) VALUES
("ドクターペッパー", 130, now(), now(), 1, "test.jpeg");

--在庫テーブル
CREATE TABLE STOCK_TBL
(
  DRINK_ID INT COMMENT 'ドリンクID',
  STOCK_COUNT INT COMMENT '在庫数',
  INSERT_DATE DATETIME COMMENT '登録日',
  UPDATE_DATE DATETIME COMMENT '更新日'
)
DEFAULT charset=utf8
COMMENT '在庫テーブル';

INSERT INTO STOCK_TBL (DRINK_ID, STOCK_COUNT, INSERT_DATE, UPDATE_DATE) VALUES
(1, 100, now(), now());

--購入履歴
CREATE TABLE BUY_HISTORY_TBL (
  DRINK_ID INT COMMENT 'ドリンクID',
  BUY_DATE DATETIME COMMENT '購入日'
)
DEFAULT charset=utf8
COMMENT '購入履歴テーブル';

--DROP TABLE BUY_HISTORY_TBL;

INSERT INTO BUY_HISTORY_TBL (DRINK_ID, BUY_DATE) VALUES
(1, now());