--Search Index
CREATE TABLE IF NOT EXISTS `prefix_search_index`(
    `guid` INT NOT NULL,
    `subtype` VARCHAR( 32 ) NOT NULL,
    `string` TEXT NOT NULL,
    PRIMARY KEY (`guid`,`subtype`),
    KEY `guid` (`guid`),
    KEY `subtype` (`subtype`),
    FULLTEXT KEY `string` (`string`)
) ENGINE = MYISAM DEFAULT CHARSET=utf8;

