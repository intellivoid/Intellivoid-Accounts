<?php


    namespace IntellivoidAccounts\Managers;


    use IntellivoidAccounts\Abstracts\AuditEventType;
    use IntellivoidAccounts\Exceptions\DatabaseException;
    use IntellivoidAccounts\Exceptions\InvalidEventTypeException;
    use IntellivoidAccounts\IntellivoidAccounts;
    use msqg\QueryBuilder;

    /**
     * Class AuditLogManager
     * @package IntellivoidAccounts\Managers
     */
    class AuditLogManager
    {
        /**
         * @var IntellivoidAccounts
         */
        private $intellivoidAccounts;

        /**
         * AuditLogManager constructor.
         * @param IntellivoidAccounts $intellivoidAccounts
         */
        public function __construct(IntellivoidAccounts $intellivoidAccounts)
        {
            $this->intellivoidAccounts = $intellivoidAccounts;
        }

        /**
         * Records an audit event for an account
         *
         * @param int $account_id
         * @param int $event_type
         * @return bool
         * @throws DatabaseException
         * @throws InvalidEventTypeException
         */
        public function logEvent(int $account_id, int $event_type): bool
        {
            switch($event_type)
            {
                case AuditEventType::NewLoginDetected:
                case AuditEventType::PasswordUpdated:
                case AuditEventType::PersonalInformationUpdated:
                case AuditEventType::EmailUpdated:
                case AuditEventType::MobileVerificationEnabled:
                case AuditEventType::MobileVerificationDisabled:
                case AuditEventType::RecoveryCodesEnabled:
                case AuditEventType::RecoveryCodesDisabled:
                case AuditEventType::TelegramVerificationEnabled:
                case AuditEventType::TelegramVerificationDisabled:
                case AuditEventType::ApplicationCreated:
                case AuditEventType::NewLoginLocationDetected:
                    break;

                default:
                    throw new InvalidEventTypeException();
            }

            $account_id = (int)$account_id;
            $event_type = (int)$event_type;
            $timestamp = (int)time();

            $Query = QueryBuilder::insert_into('users_audit', array(
                'account_id' => $account_id,
                'event_type' => $event_type,
                'timestamp' => $timestamp
            ));

            $QueryResults = $this->intellivoidAccounts->database->query($Query);
            if($QueryResults == false)
            {
                throw new DatabaseException($Query, $this->intellivoidAccounts->database->error);
            }
            else
            {
                return True;
            }
        }
    }