
create database DB202DWESProyectoTema4;
use DB202DWESProyectoTema4;


create user 'User202DWESProyectoTema4'@'%' IDENTIFIED BY 'paso';
grant all privileges on DB202DWESProyectoTema4.* to 'User202DWESProyectoTema4'@'%';


CREATE TABLE IF NOT EXISTS Departamento(
CodDepartamento varchar(3) PRIMARY KEY,
DescDepartamento varchar(255) NOT NULL,
FechaBaja date NULL,
VolumenNegocio float NULL
)engine=innodb;