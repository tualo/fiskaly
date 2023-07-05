DELIMITER ;

create table if not exists fiskaly_environments (
    id varchar(255) not null primary key,
    val longtext not null
);

insert ignore into fiskaly_environments (id, val) values ('api_key', 'api_key');
insert ignore into fiskaly_environments (id, val) values ('api_secret', 'api_secret');
