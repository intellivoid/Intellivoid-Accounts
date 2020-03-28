create table if not exists application_access
(
    id                           int auto_increment comment 'Unique Internal Database ID for this record',
    public_id                    varchar(255) null comment 'Unique public ID for this Application Access Record',
    application_id               int          null comment 'The Application ID that''s associated associated with this account',
    account_id                   int          null comment 'The Account ID that this record is associated with',
    permissions                  blob         null comment 'The permissions that this Application currently requests from the Account.',
    status                       int          null comment 'The current status of this Application access to this Account',
    creation_timestamp           int          null comment 'The timestamp for when this record was created',
    last_authenticated_timestamp int          null comment 'The timestamp for when this Account last authenticated to ths Application',
    constraint application_access_application_id_account_id_uindex
        unique (application_id, account_id),
    constraint application_access_id_uindex
        unique (id),
    constraint application_access_public_id_uindex
        unique (public_id)
)
    comment 'Records for what Applications currently has access to Accounts' collate = utf8mb4_general_ci;

alter table application_access
    add primary key (id);

create table if not exists applications
(
    id                     int auto_increment comment 'Internal Database ID',
    public_app_id          varchar(255) null comment 'The public unique Application ID',
    secret_key             varchar(255) null comment 'The secret key for authentication operations',
    name                   varchar(255) null comment 'The name of the application',
    name_safe              varchar(255) null comment 'Safe version of the application name',
    permissions            blob         null comment 'The permissions that this appication requires when requesting authentication access',
    status                 int          null comment 'The status of this application, this determines the operation of the authentication',
    authentication_mode    int          null comment 'The mode of authentication that this application uses',
    account_id             int          null comment 'The account ID that this application is owned by',
    flags                  blob         null comment 'Flags associated with this Application',
    creation_timestamp     int          null comment 'The Unix Timestamp of when this application was registered',
    last_updated_timestamp int          null comment 'The Unix Timestamp of when this application was last updated',
    constraint applications_id_uindex
        unique (id),
    constraint applications_name_name_safe_uindex
        unique (name, name_safe),
    constraint applications_name_safe_uindex
        unique (name_safe),
    constraint applications_name_uindex
        unique (name),
    constraint applications_public_app_id_uindex
        unique (public_app_id)
)
    comment 'Table for Applications that are registered in Intellivoid' collate = utf8mb4_general_ci;

create index applications_public_app_id_secret_key_index
    on applications (public_app_id, secret_key);

alter table applications
    add primary key (id);

create table if not exists authentication_access
(
    id                  int auto_increment comment 'The internal unique database ID for this authentication access',
    access_token        varchar(255) null comment 'The private access token for fetching information to the account',
    application_id      int          null comment 'The ID of the application that issued this authentication access',
    account_id          int          null comment 'The ID of the account that''s authenticated',
    request_id          int          null comment 'The ID of the authentication request that created this access',
    permissions         blob         null comment 'The permission that the application has access to using this access token',
    status              int          null comment 'The status of this current access',
    expires_timestamp   int          null comment 'The Unix Timestamp of when this authentication access expires',
    last_used_timestamp int          null comment 'The Unix Timestamp of when this authentication access was last used',
    created_timestamp   int          null comment 'The Unix Timestamp of when this authentication access was created',
    constraint authentication_access_access_token_uindex
        unique (access_token),
    constraint authentication_access_id_uindex
        unique (id),
    constraint authentication_access_request_id_uindex
        unique (request_id)
)
    comment 'Table of authentication access tokens granted by the system for applications' collate = utf8mb4_general_ci;

alter table authentication_access
    add primary key (id);

create table if not exists authentication_requests
(
    id                    int auto_increment comment 'Internal unique database ID for this authentication request',
    request_token         varchar(255) null comment 'The public request authentication token, which is later used to request an authentication token when authenticated',
    application_id        int          null comment 'The Application ID that issued this request',
    status                int          null comment 'The current status of the authentication request',
    account_id            int          null comment 'The account ID associated with this request once authenticatied, 0 means not authenticated',
    host_id               int          null comment 'The ID of the host that issued this request',
    requested_permissions blob         null comment 'The permissions that the Application requestes from the user',
    created_timestamp     int          null comment 'The Unix Timestamp of when this requested was issued',
    expires_timestamp     int          null comment 'The Unix Timestamp of when this request expires, once expired it won''t be used anymore.',
    constraint authentication_requests_id_uindex
        unique (id),
    constraint authentication_requests_request_token_uindex
        unique (request_token)
)
    comment 'Temporary authentication requests issued by applications' collate = utf8mb4_general_ci;

create index authentication_requests_application_id_account_id_index
    on authentication_requests (application_id, account_id);

alter table authentication_requests
    add primary key (id);

create table if not exists cookies
(
    id            int auto_increment comment 'Cookie ID',
    date_creation int          null comment 'The unix timestamp of when the cookie was created',
    disposed      tinyint(1)   null comment 'Flag for if the cookie was disposed',
    name          varchar(255) null comment 'The name of the Cookie (Public)',
    token         varchar(255) null comment 'The public token of the cookie which uniquely identifies it',
    expires       int          null comment 'The Unix Timestamp of when the cookie should expire',
    ip_tied       tinyint(1)   null comment 'If the cookie should be strictly tied to the client''s IP Address',
    client_ip     varchar(255) null comment 'The client''s IP Address of the cookie is tied to the IP',
    data          blob         null comment 'ZiProto Encoded Data associated with the cookie',
    constraint cookies_token_uindex
        unique (token),
    constraint sws_id_uindex
        unique (id)
)
    comment 'The main database for Secured Web Sessions library' charset = latin1;

alter table cookies
    add primary key (id);

create table if not exists otl_codes
(
    id         int auto_increment comment 'The internal unique database ID for this login code',
    code       varchar(255) null comment 'Unique authentication code used to login and authenticate',
    vendor     varchar(255) null comment 'The name of the Application/Service that authenticated the account using this code (Default: None)',
    account_id int          null comment 'The account ID that generated this code',
    status     int          null comment 'The current status of this authentication code',
    expires    int          null comment 'The Unix Timestamp for when this code expires',
    created    int          null comment 'The Unix Timestamp of when this record was created',
    constraint otl_codes_code_account_id_uindex
        unique (code, account_id),
    constraint otl_codes_code_uindex
        unique (code),
    constraint otl_codes_id_uindex
        unique (id)
)
    comment 'One time login codes used for authentication via internal services' collate = utf8mb4_general_ci;

alter table otl_codes
    add primary key (id);

create table if not exists subscription_plans
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

alter table subscription_plans
    add primary key (id);

create table if not exists subscription_promotions
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

alter table subscription_promotions
    add primary key (id);

create table if not exists subscriptions
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

alter table subscriptions
    add primary key (id);

create table if not exists support_tickets
(
    id                   int auto_increment,
    ticket_number        varchar(255) null comment 'A Unique Ticket Number used for a reference',
    source               varchar(255) null comment 'The source of the support ticket',
    subject              varchar(255) null comment 'The subject of the ticket',
    message              text         null comment 'The message regarding the ticket',
    response_email       varchar(255) null comment 'The response email for the sender of this ticket',
    ticket_status        int          null comment 'The status of the support ticket',
    ticket_notes         text         null comment 'Optional Administrator Notes that are attached to this ticket',
    submission_timestamp int          null comment 'The Unix Timestamp of when this support ticket was submitted',
    constraint support_tickets_id_uindex
        unique (id),
    constraint support_tickets_ticket_number_uindex
        unique (ticket_number)
)
    comment 'Table of support tickets that can be reported from various sources' charset = latin1;

alter table support_tickets
    add primary key (id);

create table if not exists telegram_clients
(
    id            int auto_increment comment 'The unique internal database ID for this Telegram Client',
    public_id     varchar(255) null comment 'The unique Public ID for this Telegram Client',
    available     tinyint(1)   null comment 'Indicates if this Telegram Client is available',
    account_id    int          null comment '0 If a account is not associated with this Telegram Client',
    user          blob         null comment 'ZiProto encoded data for Telegram User Data',
    chat          blob         null comment 'ZiProto encoded data for Telegram Chat Data',
    session_data  blob         null comment 'ZiProto encoded data for Telegram Session Data',
    chat_id       varchar(255) null comment 'The chat ID associated with this Telegram Client',
    user_id       varchar(255) null comment 'The user ID associated with this Telegram Client',
    last_activity int          null comment 'The Unix Timestamp of when this client was last active',
    created       int          null comment 'The Unix Timestamp of when this client was created and registered into the database',
    constraint telegram_clients_chat_id_user_id_uindex
        unique (chat_id, user_id),
    constraint telegram_clients_id_uindex
        unique (id),
    constraint telegram_clients_public_id_account_id_uindex
        unique (public_id, account_id),
    constraint telegram_clients_public_id_uindex
        unique (public_id)
)
    comment 'Table of Telegram Clients that were assocaited with a Telegram Bot' collate = utf8mb4_general_ci;

alter table telegram_clients
    add primary key (id);

create table if not exists telegram_verification_codes
(
    id                 int auto_increment comment 'Internal unique database ID for this record',
    verification_code  varchar(255) null comment 'The generated verification code unique to this request',
    telegram_client_id int          null comment 'The ID of the Telegram Client that generated this code',
    status             int          null comment 'The current status of this verification code',
    expires            int          null comment 'The Unix Timestamp of when this code expires',
    created            int          null comment 'The Unix Timestamp for when this code was created',
    constraint telegram_verification_codes_id_uindex
        unique (id),
    constraint telegram_verification_codes_verification_code_uindex
        unique (verification_code)
)
    comment 'Verification codes used to verify Telegram Accounts' collate = utf8mb4_general_ci;

alter table telegram_verification_codes
    add primary key (id);

create table if not exists tracking_user_agents
(
    id                int auto_increment comment 'The ID for this record',
    tracking_id       varchar(255) null comment 'The unique tracking ID calculated for this useragent tracking record',
    user_agent_string varchar(255) null comment 'The full string of the user agent',
    platform          varchar(255) null comment 'The platform that was detected from this useragent',
    browser           varchar(255) null comment 'The browser detected from this user agent',
    version           varchar(255) null comment 'The version detected from this user agent',
    host_id           int          null comment 'The ID of the host that this record is associated with',
    created           int          null comment 'The Unix Timestamp of when this record was created',
    last_seen         int          null comment 'The Unix Timestamp of when this record was last seen',
    constraint tracking_user_agents_id_uindex
        unique (id),
    constraint tracking_user_agents_tracking_id_uindex
        unique (tracking_id),
    constraint tracking_user_agents_user_agent_string_host_id_uindex
        unique (user_agent_string, host_id)
)
    comment 'Table for tracking User Agents' collate = utf8mb4_general_ci;

alter table tracking_user_agents
    add primary key (id);

create table if not exists transaction_records
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

alter table transaction_records
    add primary key (id);

create table if not exists users
(
    id                   int auto_increment comment 'The ID of the account',
    public_id            varchar(255) null comment 'The Public ID of the account',
    username             varchar(255) null comment 'The Alias/Username used for identifying this account',
    email                varchar(255) null comment 'The Email Address that''s associated with this Account',
    password             text         null comment 'The password for authentication (hashed)',
    status               int          null comment 'The status of the account',
    personal_information blob         null comment 'The personal information associated with this account (JSON Encoded)',
    configuration        blob         null comment 'The configuration associated with this account (JSON Encoded)',
    last_login_id        int          null comment 'The ID of the last login record',
    creation_date        int          null comment 'The Unix Timestamp of when this Account was created',
    constraint users_email_uindex
        unique (email),
    constraint users_id_uindex
        unique (id),
    constraint users_public_id_uindex
        unique (public_id),
    constraint users_username_uindex
        unique (username)
)
    comment 'Table of all user accounts' charset = latin1;

alter table users
    add primary key (id);

create table if not exists users_audit
(
    id         int auto_increment,
    account_id int null comment 'The ID of the Account associated with this event',
    event_type int null comment 'The type of event',
    timestamp  int null comment 'The timestamp of when this record was created',
    constraint users_audit_id_uindex
        unique (id)
)
    comment 'Security log for account changes' collate = utf8mb4_general_ci;

alter table users_audit
    add primary key (id);

create table if not exists users_known_hosts
(
    id            int auto_increment comment 'The unique ID for this entry',
    public_id     varchar(255) null comment 'The Public ID for this entry',
    ip_address    varchar(255) null comment 'The IP Address associated with the account',
    blocked       tinyint(1)   null comment 'Indicates if this host is blocked from accessing this account',
    location_data blob         null comment 'ZiProto encoded data which contains the location data for this host',
    last_used     int          null comment 'The Unix Timestamp of when this host was last used to login successfully',
    created       int          null comment 'The Unix Timestamp of when this entry was first created',
    constraint users_known_hosts_id_uindex
        unique (id),
    constraint users_known_hosts_ip_address_uindex
        unique (ip_address),
    constraint users_known_hosts_public_id_uindex
        unique (public_id)
)
    comment 'Table of known hosts associated with user accounts' collate = utf8mb4_general_ci;

alter table users_known_hosts
    add primary key (id);

create table if not exists users_logins
(
    id         int auto_increment comment 'The internal database ID for this login record',
    public_id  varchar(255) null comment 'The unique public ID for this login record',
    origin     varchar(255) null comment 'The origin of the login',
    host_id    int          null comment 'The ID of the host that was used to login to this account',
    user_agent blob         null comment 'ZiProto encoded data for the detected user agent',
    account_id int          null comment 'The account ID associated with this login record',
    status     int          null comment 'The login status
	0 = Success
	1 = Incorrect Credentials
	2 = Verification Failed
	3 = Blocked due to untrusted IP
	4 = Blocked due to suspicious activties ',
    timestamp  int          null comment 'The Unix Timestamp of when this record was created',
    constraint users_logins_id_uindex
        unique (id),
    constraint users_logins_public_id_uindex
        unique (public_id)
)
    comment 'Login history for Intellivoid Accounts (new)' collate = utf8mb4_general_ci;

alter table users_logins
    add primary key (id);

