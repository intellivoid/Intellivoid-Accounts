create table if not exists intellivoid.subscription_promotions
(
    id                        int auto_increment,
    public_id                 varchar(255) null comment 'Unique public ID for this subscription promotion',
    promotion_code            varchar(255) null comment 'User friendly promotion code',
    subscription_plan_id      int          null comment 'The subscription plan ID that this promotion is applicable to',
    initial_price             float        null comment 'The initial price that this promotion is offering for the subscription plan',
    cycle_price               float        null comment 'The billing cycle price that this promotion is offering to apply',
    affiliation_account_id    int          null comment 'THe Account ID that receives affiliations, 0 = None',
    affiliation_initial_share float        null comment 'The amount of the initial price to share with the affiliation, 0 = None',
    affiliation_cycle_share   float        null comment 'The amount to share per cycle with the affiliation, 0 = None',
    features                  blob         null comment 'Features to add / override',
    status                    int          null comment 'The current status of the promotion',
    flags                     blob         null comment 'Flags associated with this promotion code',
    last_updated_timestamp    int          null comment 'The Unix Timestamp of when this record was last updated',
    created_timestamp         int          null comment 'The Unix Timestamp of when this record was created',
    constraint subscription_promotions_id_uindex
        unique (id),
    constraint subscription_promotions_promotion_code_uindex
        unique (promotion_code),
    constraint subscription_promotions_public_id_uindex
        unique (public_id)
)
    comment 'Promotion codes applicable to subscriptions' collate = utf8mb4_general_ci;

alter table intellivoid.subscription_promotions
    add primary key (id);

