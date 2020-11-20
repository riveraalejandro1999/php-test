use app;

create table clientes(
    id int primary key not null auto_increment,
    nombre varchar(100) not null,
    apellido varchar(100) not null
);

create table tarjetas(
    id int primary key not null auto_increment,
    numero_tarjeta int not null unique
);
create table cuentas(
    id int primary key not null auto_increment,
    numero_cuenta varchar(100) not null unique,
    monto float not null
);
create table cliente_tarjeta(
    id int primary key not null auto_increment,
    cliente_id int not null,
    tarjeta_id int not null,
    foreign key (cliente_id) references clientes(id),
    foreign key (tarjeta_id) references tarjetas(id)
);
create table cliente_cuenta(
    id int primary key not null auto_increment,
    cliente_id int not null,
    cuenta_id int not null,
    foreign key (cliente_id) references clientes(id),
    foreign key (cuenta_id) references cuentas(id)
);
