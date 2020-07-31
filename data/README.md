Volcar data.mysql en una base de datos.

Volcar los datos de los ficheros en las tablas creadas:

LOAD DATA LOCAL INFILE 'laboratorio.csv'  INTO TABLE laboratorio LINES TERMINATED BY '\n' IGNORE 1 LINES (nombre) SET id=NULL;
LOAD DATA LOCAL INFILE 'host.csv'  INTO TABLE host FIELDS TERMINATED BY  ',' LINES TERMINATED BY '\n' IGNORE 1 LINES (ip,nombre,mac,id_laboratorio) SET id=NULL;
