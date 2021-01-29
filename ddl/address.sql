--住所検索用のSQL
--■住所マスタ
CREATE TABLE ADDRESS_MST
(
  ADDRESS_ID INT NOT NULL AUTO_INCREMENT PRIMARY KEY COMMENT '住所ID' ,
  ZIP NVARCHAR(7) COMMENT '郵便番号' ,
  PREF_KANA NVARCHAR(10) COMMENT '都道府県ｶﾅ',
  LOCAL_GOV_KANA NVARCHAR(30) COMMENT '市区町村ｶﾅ',
  AREA_KANA NVARCHAR(30) COMMENT '町域ｶﾅ',
  PREF NVARCHAR(10) COMMENT '都道府県',
  LOCAL_GOV NVARCHAR(30) COMMENT '市区町村',
  AREA NVARCHAR(30) COMMENT '町域',
  INS_DATE_TIME DATETIME COMMENT '登録日時',
  UPD_DATE_TIME DATETIME COMMENT '更新日時'
)
DEFAULT charset=utf8
COMMENT '住所テーブル';
--DROP TABLE ADDRESS_MST;

--mysqlAdminで住所CSVファイルをインポート⇒address_mst_1にデータが格納される
--address_mst_1よりaddress_mstにSelectInsert
insert into address_mst (
ZIP,
PREF_KANA,
LOCAL_GOV_KANA,
AREA_KANA,
PREF,
LOCAL_GOV,
AREA,
INS_DATE_TIME,
UPD_DATE_TIME
)
select 
  `COL 1` ,
  `COL 2` ,
  `COL 3` ,
  `COL 4` ,
  `COL 5` ,
  `COL 6` ,
  `COL 7`,
  now(),
  now()
from address_mst_1;

--address_mst_1削除
drop table address_mst_1;


