create table if not exists intellivoid.support_tickets
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

alter table intellivoid.support_tickets
    add primary key (id);

