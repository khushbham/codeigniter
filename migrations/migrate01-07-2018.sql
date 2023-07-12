update stem_producten set product_ID = product_ID + 100;
update stem_kandidaat_producten set product_ID = product_ID + 100;
update stem_workshops_producten set product_ID = product_ID + 100;
update stem_bestellingen_producten set product_ID = product_ID + 100;
update stem_korting_connecties set product_ID = product_ID + 100 where product_ID != 0;

ALTER TABLE tbl AUTO_INCREMENT = 100;