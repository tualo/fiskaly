DELIMITER ;

create table if not exists fiskaly_environments (
    id varchar(255) not null primary key,
    val longtext not null
);

insert ignore into fiskaly_environments (id, val) values ('api_key', 'api_key');
insert ignore into fiskaly_environments (id, val) values ('api_secret', 'api_secret');


create table if not exists fiskaly_tss (
    tss varchar(36) not null,
    id varchar(100) not null,
     primary key (tss, id),
    val longtext not null
);

create table kassenterminals_client_id (
  kassenterminal varchar(36) primary key,
  tss_client_id varchar(36) not null,
  constraint `fk_kassenterminals_client_id`
  foreign key (kassenterminal)
  references kassenterminals(id)
);