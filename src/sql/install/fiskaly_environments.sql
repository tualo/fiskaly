DELIMITER ;


create table if not exists fiskaly_environments (
    id varchar(255) not null ,
    type enum('live','test') not null default 'test',
    val longtext not null,
    primary key (id, type)
);

-- Insert default values for fiskaly environments
-- The URLs are for the test and live environments of fiskaly
-- 'dsfinvk_base_url' is the base URL for the DSFinV-K API
-- 'sign_base_url' is the base URL for the signature service
insert ignore into fiskaly_environments (id,type,val) values 
('dsfinvk_base_url','test','https://dsfinvk.fiskaly.com/api/v1'),
('sign_base_url','test','https://kassensichv-middleware.fiskaly.com/api/v2'),
('dsfinvk_base_url','live','https://dsfinvk.fiskaly.com/api/v1'),
('sign_base_url','live','https://kassensichv-middleware.fiskaly.com/api/v2');



/*
alter table fiskaly_environments
    add column if not exists type enum('live','test') not null default 'test';

-- change the primary key to include the type
alter table fiskaly_environments  
    drop primary key,
    add primary key (id, type); 

*/
/*
insert ignore into fiskaly_environments (id, val) values ('api_key', 'api_key');
insert ignore into fiskaly_environments (id, val) values ('api_secret', 'api_secret');
*/

create table if not exists fiskaly_tss (
    tss varchar(36) not null,
    id varchar(100) not null,
    primary key (tss, id),
    val longtext not null
);

