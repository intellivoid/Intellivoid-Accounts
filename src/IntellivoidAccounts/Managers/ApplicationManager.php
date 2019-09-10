<?php


    namespace IntellivoidAccounts\Managers;


    use IntellivoidAccounts\IntellivoidAccounts;
    use IntellivoidAccounts\Objects\COA\Application;
    use IntellivoidAccounts\Utilities\Hashing;

    /**
     * Class ApplicationManager
     * @package IntellivoidAccounts\Managers
     */
    class ApplicationManager
    {
        /**
         * @var IntellivoidAccounts
         */
        private $intellivoidAccounts;

        /**
         * ApplicationManager constructor.
         * @param IntellivoidAccounts $intellivoidAccounts
         */
        public function __construct(IntellivoidAccounts $intellivoidAccounts)
        {
            $this->intellivoidAccounts = $intellivoidAccounts;
        }

        public function register_application(string $name, int $authentication_mode, array $permissions): Application
        {
            $CreatedTimestamp = (int)time();
            $PublicApplicationId = Hashing::applicationPublicId($name, $CreatedTimestamp);

        }
    }