create table balance_transactions
(
	id INT(255) PRIMARY KEY COMMENT 'The transaction ID' AUTO_INCREMENT,
	public_id VARCHAR(255) NULL COMMENT 'The Public ID of the transaction',
	source VARCHAR(255) NULL COMMENT 'The source of the transaction (eg; Supplier, PayPal, etc..)',
	type INT(255) NULL COMMENT 'The type of transaction, if it''s adding to the balance or making purchases',
	ref_code VARCHAR(255) null COMMENT 'The ref code used in this transaction if any',
	payment_processor INT(255) NULL COMMENT 'The Payment Processor that was used if any',
	processor_transaction_id VARCHAR(255) NULL COMMENT 'The transaction ID given from the payment processor',
	account_id INT(255) NULL COMMENT 'The ID of the account that''s associated with this transaction',
	amount FLOAT NULL COMMENT 'The amount that gets transferred',
	balance_effect INT(255) null COMMENT 'Indicates if the amount is added to the balance or taken away',
	old_balance FLOAT NULL COMMENT 'The account balance before the transaction',
	new_balance FLOAT NULL COMMENT 'The account balance after the transaction',
	timestamp INT(255) NULL COMMENT 'The Unix Timestamp for this transaction'
);

CREATE UNIQUE INDEX balance_transactions_id_uindex ON balance_transactions (id);
ALTER TABLE balance_transactions COMMENT = 'Records of balance transfers from user accounts';