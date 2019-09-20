<?php


    namespace IntellivoidAccounts\Managers;


    use IntellivoidAccounts\Exceptions\DatabaseException;
    use IntellivoidAccounts\IntellivoidAccounts;
    use IntellivoidAccounts\Objects\UserAgent;
    use IntellivoidAccounts\Utilities\Hashing;
    use IntellivoidAccounts\Utilities\Validate;
    use msqg\QueryBuilder;

    /**
     * Class TrackingUserAgentManager
     * @package IntellivoidAccounts\Managers
     */
    class TrackingUserAgentManager
    {
        /**
         * @var IntellivoidAccounts
         */
        private $intellivoidAccounts;

        /**
         * TrackingUserAgentManager constructor.
         * @param IntellivoidAccounts $intellivoidAccounts
         */
        public function __construct(IntellivoidAccounts $intellivoidAccounts)
        {
            $this->intellivoidAccounts = $intellivoidAccounts;
        }

        /**
         * Registers a new record into the database, returns the tracking id
         *
         * @param string $user_agent_string
         * @param int $host_id
         * @return string
         * @throws DatabaseException
         */
        public function registerRecord(string $user_agent_string, int $host_id): string
        {
            if(Validate::userAgent($user_agent_string) == false)
            {
                return null;
            }

            $created = (int)time();
            $user_agent_parse = UserAgent::fromString($user_agent_string);

            $tracking_id = Hashing::uaTrackingId($user_agent_string, $host_id);
            $tracking_id = $this->intellivoidAccounts->database->real_escape_string($tracking_id);
            $user_agent_string = $this->intellivoidAccounts->database->real_escape_string($user_agent_string);
            $host_id = (int)$host_id;
            $platform = 'Unknown';
            $browser = 'Unknown';
            $version = 'Unknown';

            if($user_agent_parse->Platform !== null)
            {
                $platform = $this->intellivoidAccounts->database->real_escape_string($user_agent_parse->Platform);
            }

            if($user_agent_parse->Browser !== null)
            {
                $browser = $this->intellivoidAccounts->database->real_escape_string($user_agent_parse->Browser);
            }

            if($user_agent_parse->Version !== null)
            {
                $version = $this->intellivoidAccounts->database->real_escape_string($user_agent_parse->Version);
            }

            $Query = QueryBuilder::insert_into('tracking_user_agents', array(
                'tracking_id' => $tracking_id,
                'user_agent_string' => $user_agent_string,
                'platform' => $platform,
                'browser' => $browser,
                'version' => $version,
                'host_id' => $host_id,
                'created' => $created,
                'last_seen' => $created
            ));
            $QueryResults = $this->intellivoidAccounts->database->query($Query);
            if($QueryResults == false)
            {
                throw new DatabaseException($Query, $this->intellivoidAccounts->database->error);
            }
            else
            {
                return $tracking_id;
            }

        }
    }