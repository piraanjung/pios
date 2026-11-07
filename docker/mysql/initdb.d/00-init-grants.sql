-- Grant full privileges to pios user across all databases (enables multi-tenant dynamic database creation/switching)
GRANT ALL PRIVILEGES ON *.* TO 'pios'@'%' WITH GRANT OPTION;
FLUSH PRIVILEGES;
