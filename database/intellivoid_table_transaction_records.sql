create table if not exists intellivoid.transaction_records
(
    id         int auto_increment comment 'Unique internal database ID for this transaction record',
    public_id  varchar(255) null comment 'Unique public ID for this transaction record',
    account_id int          null comment 'The account ID that this transaction is associated with',
    vendor     varchar(255) null comment 'The name of the vendor or account username that this transaction is to or from',
    amount     float        null comment 'The amount that the associated account has withdrawn or received

< 0 	= Currency taken from associated account
0 	= No currency taken
> 0 	= Currency added to the associated account ',
    timestamp  int          null comment 'The Unix Timestamp of when this transaction took place',
    constraint transaction_records_id_uindex
        unique (id),
    constraint transaction_records_public_id_uindex
        unique (public_id)
)
    comment 'Records of Balance Transactions regarding subscription payments, payments, withdrawals, etc.'
    collate = utf8mb4_general_ci;

alter table intellivoid.transaction_records
    add primary key (id);

