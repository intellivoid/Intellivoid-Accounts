create table subscription_promotions
(
    id                        int(255) auto_increment,
    public_id                 varchar(255) null comment 'Unique public ID for this subscription promotion',
    promotion_code            varchar(255) null comment 'User friendly promotion code',
    subscription_plan_id      int(255)     null comment 'The subscription plan ID that this promotion is applicable to',
    affiliation_account_id    int(255)     null comment 'THe Account ID that receives affiliations, 0 = None',
    affiliation_initial_share float        null comment 'The amount of the initial price to share with the affiliation, 0 = None',
    affiliation_cycle_share   float        null comment 'The amount to share per cycle with the affiliation, 0 = None',
    features                  blob         null comment 'Features to add / override',
    status                    int(255)     null comment 'The current status of the promotion',
    flags                     blob         null comment 'Flags associated with this promotion code',
    last_updated_timestamp    int(255)     null comment 'The Unix Timestamp of when this record was last updated',
    created_timestamp         int(255)     null comment 'The Unix Timestamp of when this record was created',
    constraint subscription_promotions_id_uindex unique (id)
) comment 'Promotion codes applicable to subscriptions';
alter table subscription_promotions add primary key (id);