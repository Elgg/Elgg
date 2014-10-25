INSERT INTO prefix_sometable (`key`) VALUES ('Value 1');
UPDATE prefix_sometable SET `key` = 'Value 2' WHERE `key` = 'Value 1';
INSERT INTO prefix_sometable (`key`) VALUES ('Value 3');
