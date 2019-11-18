create table transaction_records
(
    id         int(255) auto_increment comment 'Unique internal database ID for this transaction record' primary key,
    public_id  varchar(255) null comment 'Unique public ID for this transaction record',
    account_id int(255)     null comment 'The account ID that this transaction is associated with',
    vendor     varchar(255) null comment 'The name of the vendor or account username that this transaction is to or from',
    amount     float        null comment 'The amount that the associated account has withdrawn or received

< 0 	= Currency taken from associated account
0 	= No currency taken
> 0 	= Currency added to the associated account ',
    timestamp  int(255)     null comment 'The Unix Timestamp of when this transaction took place',
    constraint transaction_records_id_uindex unique (id)
) comment 'Records of Balance Transactions regarding subscription payments, payments, withdrawals, etc.';