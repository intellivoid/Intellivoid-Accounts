create table transaction_records
(
	id int(255) auto_increment comment 'The internal transaction ID',
	public_id varchar(255) null comment 'Public Transaction ID',
	account_id int(255) null comment 'The account ID that this transaction is associated with',
	amount double null comment 'The amount that was transferred',
	operator_type int(255) null comment 'The operator type for the amount effecting the balance (Deposit or Withdrawl)',
	type int(255) null comment 'The type of transaction (Recieve, Payment, Subscription Payment, Fee, etc.)',
	vendor varchar(255) null comment 'The vendor that is involved in this transaction (Intellivoid, PayPal, Stripe, etc...)',
	timestamp int(255) null comment 'The Timestamp that this transaction has been placed',
	constraint transaction_records_id_uindex unique (id)
) comment 'Table of Transaction Records';
alter table transaction_records add primary key (id);