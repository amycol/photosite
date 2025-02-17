CREATE USER 'photosite-go'@'localhost' IDENTIFIED BY 'gAFjxLWY9isu0lAklDTozN';
CREATE USER 'photosite-php'@'localhost' IDENTIFIED BY 'KbQ9wujH8rgXvVCGAcfkzN';
CREATE USER 'admin'@'%' IDENTIFIED BY 'admin';


GRANT INSERT, UPDATE, DELETE, SELECT on photosite.* TO 'photosite-go'@'localhost';
GRANT SELECT on photosite.* TO 'photosite-php'@'localhost';
GRANT CREATE, ALTER, DROP, INSERT, UPDATE, DELETE, SELECT, REFERENCES, RELOAD on *.* TO 'admin'@'%' WITH GRANT OPTION;
