CREATE SCHEMA rental;

CREATE  TABLE rental.cache ( 
	`key`                VARCHAR(255)    NOT NULL   PRIMARY KEY,
	value                MEDIUMTEXT    NOT NULL   ,
	expiration           INT    NOT NULL   
 ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE  TABLE rental.cache_locks ( 
	`key`                VARCHAR(255)    NOT NULL   PRIMARY KEY,
	owner                VARCHAR(255)    NOT NULL   ,
	expiration           INT    NOT NULL   
 ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE  TABLE rental.comission ( 
	id                   INT    NOT NULL AUTO_INCREMENT  PRIMARY KEY,
	date_collected       DATE  DEFAULT (curdate())  NOT NULL   ,
	amount               INT  DEFAULT (0)  NOT NULL   
 ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE  TABLE rental.failed_jobs ( 
	id                   BIGINT UNSIGNED   NOT NULL AUTO_INCREMENT  PRIMARY KEY,
	uuid                 VARCHAR(255)    NOT NULL   ,
	connection           TEXT    NOT NULL   ,
	queue                TEXT    NOT NULL   ,
	payload              LONGTEXT    NOT NULL   ,
	exception            LONGTEXT    NOT NULL   ,
	failed_at            TIMESTAMP  DEFAULT (current_timestamp())  NOT NULL   ,
	CONSTRAINT failed_jobs_uuid_unique UNIQUE ( uuid ) 
 ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE  TABLE rental.financial_year ( 
	id                   INT    NOT NULL AUTO_INCREMENT  PRIMARY KEY,
	start                DATE    NOT NULL   ,
	end                  DATE    NOT NULL   ,
	year                 INT    NOT NULL   ,
	CONSTRAINT unq_financial_year_year UNIQUE ( year ) 
 ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE  TABLE rental.job_batches ( 
	id                   VARCHAR(255)    NOT NULL   PRIMARY KEY,
	name                 VARCHAR(255)    NOT NULL   ,
	total_jobs           INT    NOT NULL   ,
	pending_jobs         INT    NOT NULL   ,
	failed_jobs          INT    NOT NULL   ,
	failed_job_ids       LONGTEXT    NOT NULL   ,
	options              MEDIUMTEXT       ,
	cancelled_at         INT       ,
	created_at           INT    NOT NULL   ,
	finished_at          INT       
 ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE  TABLE rental.jobs ( 
	id                   BIGINT UNSIGNED   NOT NULL AUTO_INCREMENT  PRIMARY KEY,
	queue                VARCHAR(255)    NOT NULL   ,
	payload              LONGTEXT    NOT NULL   ,
	attempts             TINYINT UNSIGNED   NOT NULL   ,
	reserved_at          INT UNSIGNED      ,
	available_at         INT UNSIGNED   NOT NULL   ,
	created_at           INT UNSIGNED   NOT NULL   
 ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE INDEX jobs_queue_index ON rental.jobs ( queue );

CREATE  TABLE rental.landlord ( 
	id                   INT    NOT NULL AUTO_INCREMENT  PRIMARY KEY,
	name                 VARCHAR(100)  DEFAULT ('secret')  NOT NULL   ,
	`date onboarded`     DATE  DEFAULT (curdate())     ,
	contact              VARCHAR(15)    NOT NULL   ,
	email                VARCHAR(100)       ,
	location             VARCHAR(100)       
 ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE  TABLE rental.migrations ( 
	id                   INT UNSIGNED   NOT NULL AUTO_INCREMENT  PRIMARY KEY,
	migration            VARCHAR(255)    NOT NULL   ,
	batch                INT    NOT NULL   
 ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE  TABLE rental.months ( 
	id                   INT    NOT NULL AUTO_INCREMENT  PRIMARY KEY,
	name                 VARCHAR(100)    NOT NULL   ,
	CONSTRAINT unq_months_name UNIQUE ( name ) 
 ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE  TABLE rental.password_reset_tokens ( 
	email                VARCHAR(255)    NOT NULL   PRIMARY KEY,
	token                VARCHAR(255)    NOT NULL   ,
	created_at           TIMESTAMP       
 ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE  TABLE rental.period_money ( 
	id                   INT    NOT NULL AUTO_INCREMENT  PRIMARY KEY,
	balance_bf           INT  DEFAULT (0)  NOT NULL   ,
	expected             INT  DEFAULT (0)  NOT NULL   ,
	total_pay            INT  DEFAULT (0)  NOT NULL   ,
	expected_profit      INT  DEFAULT (0)  NOT NULL   ,
	profit_made          INT  DEFAULT (0)  NOT NULL   ,
	year                 INT    NOT NULL   ,
	month                INT    NOT NULL   
 ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE INDEX fk_period_money_financial_year ON rental.period_money ( year );

CREATE INDEX fk_period_money_comission ON rental.period_money ( month );

CREATE  TABLE rental.rooms ( 
	id                   INT    NOT NULL AUTO_INCREMENT  PRIMARY KEY,
	`status`             BOOLEAN  DEFAULT (false)  NOT NULL   ,
	amount               INT  DEFAULT (0)  NOT NULL   ,
	roomcondition        VARCHAR(100)       ,
	remarks              VARCHAR(100)       ,
	landlord             INT    NOT NULL   ,
	location             VARCHAR(100)    NOT NULL   
 ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE INDEX fk_rooms_landlord ON rental.rooms ( landlord );

CREATE  TABLE rental.sessions ( 
	id                   VARCHAR(255)    NOT NULL   PRIMARY KEY,
	user_id              BIGINT UNSIGNED      ,
	ip_address           VARCHAR(45)       ,
	user_agent           TEXT       ,
	payload              LONGTEXT    NOT NULL   ,
	last_activity        INT    NOT NULL   
 ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE INDEX sessions_user_id_index ON rental.sessions ( user_id );

CREATE INDEX sessions_last_activity_index ON rental.sessions ( last_activity );

CREATE  TABLE rental.staff ( 
	id                   INT    NOT NULL AUTO_INCREMENT  PRIMARY KEY,
	name                 VARCHAR(100)    NOT NULL   ,
	`date onboarded`     DATE    NOT NULL   ,
	contact              VARCHAR(20)    NOT NULL   ,
	email                VARCHAR(255)       ,
	location             VARCHAR(100)       
 ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE  TABLE rental.tenants ( 
	id                   INT    NOT NULL AUTO_INCREMENT  PRIMARY KEY,
	name                 VARCHAR(100)    NOT NULL   ,
	date_onboarded       DATE  DEFAULT (curdate())  NOT NULL   ,
	room_id              INT       ,
	contact              VARCHAR(20)       ,
	CONSTRAINT room_id UNIQUE ( room_id ) 
 ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE  TABLE rental.users ( 
	id                   BIGINT UNSIGNED   NOT NULL AUTO_INCREMENT  PRIMARY KEY,
	name                 VARCHAR(255)    NOT NULL   ,
	email                VARCHAR(255)    NOT NULL   ,
	email_verified_at    TIMESTAMP       ,
	password             VARCHAR(255)    NOT NULL   ,
	remember_token       VARCHAR(100)       ,
	created_at           TIMESTAMP       ,
	updated_at           TIMESTAMP       ,
	CONSTRAINT users_email_unique UNIQUE ( email ) 
 ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE  TABLE rental.accs_summary ( 
	id                   INT    NOT NULL AUTO_INCREMENT  PRIMARY KEY,
	balance_bf           INT  DEFAULT (0)  NOT NULL   ,
	expected_gross       INT  DEFAULT (0)  NOT NULL   ,
	total_collected      INT  DEFAULT (0)  NOT NULL   ,
	expected_profit      INT  DEFAULT (0)  NOT NULL   ,
	accumulated_profit   INT  DEFAULT (0)  NOT NULL   ,
	f_year               INT    NOT NULL   ,
	f_month              VARCHAR(100)    NOT NULL   
 ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE INDEX fk_accs_summary_months ON rental.accs_summary ( f_month );

CREATE INDEX fk_accs_summary_financial_year ON rental.accs_summary ( f_year );

CREATE  TABLE rental.balances ( 
	id                   INT    NOT NULL AUTO_INCREMENT  PRIMARY KEY,
	tenant               INT       ,
	landlord             INT       ,
	year                 INT    NOT NULL   ,
	month                VARCHAR(100)    NOT NULL   ,
	balance_bf           INT  DEFAULT (0)  NOT NULL   ,
	balance_due          INT  DEFAULT (0)  NOT NULL   ,
	total_balance        INT  DEFAULT (0)  NOT NULL   
 ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE INDEX fk_balances_months ON rental.balances ( month );

CREATE INDEX fk_balances_financial_year ON rental.balances ( year );

CREATE INDEX fk_balances_landlord ON rental.balances ( landlord );

CREATE INDEX fk_balances_tenants ON rental.balances ( tenant );

CREATE  TABLE rental.disburse_landlord ( 
	id                   INT    NOT NULL AUTO_INCREMENT  PRIMARY KEY,
	amount               INT  DEFAULT (0)  NOT NULL   ,
	month                VARCHAR(100)    NOT NULL   ,
	year                 INT    NOT NULL   ,
	landlord             INT    NOT NULL   ,
	remarks              VARCHAR(100)       ,
	date_paid            TIMESTAMP  DEFAULT (current_timestamp())  NOT NULL   
 ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE INDEX fk_disburse_money_landlord ON rental.disburse_landlord ( landlord );

CREATE  TABLE rental.rooms_payment ( 
	id                   INT    NOT NULL AUTO_INCREMENT  PRIMARY KEY,
	date_paid            TIMESTAMP       ,
	amount               INT    NOT NULL   ,
	tenant               INT       ,
	room                 INT    NOT NULL   ,
	landlord             INT       ,
	comission            INT       ,
	remarks              VARCHAR(100)       ,
	year                 INT    NOT NULL   ,
	month                VARCHAR(100)    NOT NULL   
 ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE INDEX fk_rooms_payment_cache_locks ON rental.rooms_payment ( landlord );

CREATE INDEX fk_rooms_payment ON rental.rooms_payment ( year );

CREATE INDEX fk_rooms_payment_months ON rental.rooms_payment ( month );

CREATE INDEX fk_rooms_payment_rooms ON rental.rooms_payment ( room );

CREATE INDEX fk_rooms_payment_job_batches ON rental.rooms_payment ( tenant );

ALTER TABLE rental.accs_summary ADD CONSTRAINT fk_accs_summary_financial_year FOREIGN KEY ( f_year ) REFERENCES rental.financial_year( year ) ON DELETE NO ACTION ON UPDATE NO ACTION;

ALTER TABLE rental.accs_summary ADD CONSTRAINT fk_accs_summary_months FOREIGN KEY ( f_month ) REFERENCES rental.months( name ) ON DELETE NO ACTION ON UPDATE NO ACTION;

ALTER TABLE rental.balances ADD CONSTRAINT fk_balances_financial_year FOREIGN KEY ( year ) REFERENCES rental.financial_year( year ) ON DELETE NO ACTION ON UPDATE NO ACTION;

ALTER TABLE rental.balances ADD CONSTRAINT fk_balances_landlord FOREIGN KEY ( landlord ) REFERENCES rental.landlord( id ) ON DELETE CASCADE ON UPDATE NO ACTION;

ALTER TABLE rental.balances ADD CONSTRAINT fk_balances_months FOREIGN KEY ( month ) REFERENCES rental.months( name ) ON DELETE NO ACTION ON UPDATE NO ACTION;

ALTER TABLE rental.balances ADD CONSTRAINT fk_balances_tenants FOREIGN KEY ( tenant ) REFERENCES rental.tenants( id ) ON DELETE CASCADE ON UPDATE NO ACTION;

ALTER TABLE rental.disburse_landlord ADD CONSTRAINT fk_disburse_money_landlord FOREIGN KEY ( landlord ) REFERENCES rental.landlord( id ) ON DELETE CASCADE ON UPDATE NO ACTION;

ALTER TABLE rental.period_money ADD CONSTRAINT fk_period_money_comission FOREIGN KEY ( month ) REFERENCES rental.months( id ) ON DELETE NO ACTION ON UPDATE NO ACTION;

ALTER TABLE rental.period_money ADD CONSTRAINT fk_period_money_financial_year FOREIGN KEY ( year ) REFERENCES rental.financial_year( id ) ON DELETE NO ACTION ON UPDATE NO ACTION;

ALTER TABLE rental.rooms ADD CONSTRAINT fk_rooms_landlord FOREIGN KEY ( landlord ) REFERENCES rental.landlord( id ) ON DELETE CASCADE ON UPDATE NO ACTION;

ALTER TABLE rental.rooms_payment ADD CONSTRAINT fk_rooms_payment FOREIGN KEY ( year ) REFERENCES rental.financial_year( year ) ON DELETE NO ACTION ON UPDATE NO ACTION;

ALTER TABLE rental.rooms_payment ADD CONSTRAINT fk_rooms_payment_cache_locks FOREIGN KEY ( landlord ) REFERENCES rental.landlord( id ) ON DELETE NO ACTION ON UPDATE NO ACTION;

ALTER TABLE rental.rooms_payment ADD CONSTRAINT fk_rooms_payment_job_batches FOREIGN KEY ( tenant ) REFERENCES rental.tenants( id ) ON DELETE SET NULL ON UPDATE NO ACTION;

ALTER TABLE rental.rooms_payment ADD CONSTRAINT fk_rooms_payment_months FOREIGN KEY ( month ) REFERENCES rental.months( name ) ON DELETE NO ACTION ON UPDATE NO ACTION;

ALTER TABLE rental.rooms_payment ADD CONSTRAINT fk_rooms_payment_rooms FOREIGN KEY ( room ) REFERENCES rental.rooms( id ) ON DELETE CASCADE ON UPDATE NO ACTION;

ALTER TABLE rental.tenants ADD CONSTRAINT fk_tenants_landlord FOREIGN KEY ( room_id ) REFERENCES rental.rooms( id ) ON DELETE CASCADE ON UPDATE NO ACTION;
