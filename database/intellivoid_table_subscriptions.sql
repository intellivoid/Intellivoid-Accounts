create table if not exists intellivoid.subscriptions
(
    id                   int auto_increment comment 'The internal unique database ID for this record',
    public_id            varchar(255) null comment 'Unique public ID for this subscription',
    account_id           int          null comment 'The ID of the Account that this subscription is associated with',
    subscription_plan_id int          null comment 'The ID of the subscription plan that this subscription is associated with',
    active               tinyint(1)   null comment 'Indicates if this subscription is currently active or not',
    billing_cycle        int          null comment 'The cycle for billing this subscription (Every x seconds, bill the user) x = this value',
    next_billing_cycle   int          null comment 'The next Unix Timestamp for when this billing cycle should be processed',
    properties           blob         null comment 'ZiProto Encoded data which represents the properties for this subscription',
    created_timestamp    int          null comment 'The Unix Timestamp of this record was created',
    flags                blob         null comment 'Admin-placed flags for this subscription record (Special perms, etc)',
    constraint subscriptions_account_id_subscription_plan_id_uindex
        unique (account_id, subscription_plan_id),
    constraint subscriptions_id_uindex
        unique (id),
    constraint subscriptions_public_id_uindex
        unique (public_id)
)
    comment 'Subscriptions associated with users and services' collate = utf8mb4_general_ci;

alter table intellivoid.subscriptions
    add primary key (id);

