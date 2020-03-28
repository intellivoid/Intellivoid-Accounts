create table if not exists intellivoid.subscription_plans
(
    id                int auto_increment comment 'Internal unique database ID for this subscription plan',
    public_id         varchar(255) null comment 'Public unique ID for this subscription plan',
    application_id    int          null comment 'The Application ID that this subscription plan is associated with',
    plan_name         varchar(255) null comment 'The name of the plan',
    features          blob         null comment 'ZiProto encoded data of a array of feature objects',
    initial_price     float        null comment 'The initial price for starting the subscription',
    cycle_price       float        null comment 'The price to charge the user per billing cycle',
    billing_cycle     int          null comment 'The amount of seconds required for each billing cycle',
    status            int          null comment 'The status of the subscription plan',
    flags             blob         null comment 'ZiProto encoded data of the flags associated with this subscription plan',
    last_updated      int          null comment 'The Unix Timestamp of when this subscription plan was last updated',
    created_timestamp int          null comment 'The Unix Timestamp of when this record was created',
    constraint subscription_plans_application_id_plan_name_uindex
        unique (application_id, plan_name),
    constraint subscription_plans_id_uindex
        unique (id),
    constraint subscription_plans_public_id_uindex
        unique (public_id)
)
    comment 'Applicable Subscription Plans for starting a new subscription' collate = utf8mb4_general_ci;

alter table intellivoid.subscription_plans
    add primary key (id);

