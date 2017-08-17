-- Insert first value
INSERT INTO prefix_sometable (`key`) VALUES ('Value 1');

-- Update first value
UPDATE prefix_sometable
    SET `key` = 'Value 2'
    WHERE `key` = 'Value 1'
;

# And some other comment

-- Insert third value
INSERT INTO prefix_sometable (`key`) VALUES ('Value 3');