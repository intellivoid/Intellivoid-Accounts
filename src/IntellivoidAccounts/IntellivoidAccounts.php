<?php
    /** @noinspection PhpUnused */
    namespace IntellivoidAccounts;

    use acm\acm;
    use Exception;
    use IntellivoidAccounts\Managers\AccountManager;
    use IntellivoidAccounts\Managers\ApplicationManager;
    use IntellivoidAccounts\Managers\CrossOverAuthenticationManager;
    use IntellivoidAccounts\Managers\KnownHostsManager;
    use IntellivoidAccounts\Managers\LoginRecordManager;
    use IntellivoidAccounts\Managers\TelegramClientManager;
    use IntellivoidAccounts\Managers\TransactionRecordManager;
    use mysqli;
    use udp\udp;

    $LocalDirectory = __DIR__ . DIRECTORY_SEPARATOR;

    include_once($LocalDirectory . 'Abstracts' . DIRECTORY_SEPARATOR . 'SearchMethods' . DIRECTORY_SEPARATOR . 'AccountSearchMethod.php');
    include_once($LocalDirectory . 'Abstracts' . DIRECTORY_SEPARATOR . 'SearchMethods' . DIRECTORY_SEPARATOR . 'ApplicationSearchMethod.php');
    include_once($LocalDirectory . 'Abstracts' . DIRECTORY_SEPARATOR . 'SearchMethods' . DIRECTORY_SEPARATOR . 'AuthenticationAccessSearchMethod.php');
    include_once($LocalDirectory . 'Abstracts' . DIRECTORY_SEPARATOR . 'SearchMethods' . DIRECTORY_SEPARATOR . 'AuthenticationRequestSearchMethod.php');
    include_once($LocalDirectory . 'Abstracts' . DIRECTORY_SEPARATOR . 'SearchMethods' . DIRECTORY_SEPARATOR . 'KnownHostsSearchMethod.php');
    include_once($LocalDirectory . 'Abstracts' . DIRECTORY_SEPARATOR . 'SearchMethods' . DIRECTORY_SEPARATOR . 'LoginRecordMultiSearchMethod.php');
    include_once($LocalDirectory . 'Abstracts' . DIRECTORY_SEPARATOR . 'SearchMethods' . DIRECTORY_SEPARATOR . 'LoginRecordSearchMethod.php');
    include_once($LocalDirectory . 'Abstracts' . DIRECTORY_SEPARATOR . 'SearchMethods' . DIRECTORY_SEPARATOR . 'TelegramClientSearchMethod.php');
    include_once($LocalDirectory . 'Abstracts' . DIRECTORY_SEPARATOR . 'SearchMethods' . DIRECTORY_SEPARATOR . 'TransactionRecordSearchMethod.php');
    include_once($LocalDirectory . 'Abstracts' . DIRECTORY_SEPARATOR . 'AccountRequestPermissions.php');
    include_once($LocalDirectory . 'Abstracts' . DIRECTORY_SEPARATOR . 'AccountStatus.php');
    include_once($LocalDirectory . 'Abstracts' . DIRECTORY_SEPARATOR . 'ApplicationStatus.php');
    include_once($LocalDirectory . 'Abstracts' . DIRECTORY_SEPARATOR . 'AuthenticationAccessStatus.php');
    include_once($LocalDirectory . 'Abstracts' . DIRECTORY_SEPARATOR . 'AuthenticationMode.php');
    include_once($LocalDirectory . 'Abstracts' . DIRECTORY_SEPARATOR . 'AuthenticationRequestStatus.php');
    include_once($LocalDirectory . 'Abstracts' . DIRECTORY_SEPARATOR . 'ExceptionCodes.php');
    include_once($LocalDirectory . 'Abstracts' . DIRECTORY_SEPARATOR . 'LoginStatus.php');
    include_once($LocalDirectory . 'Abstracts' . DIRECTORY_SEPARATOR . 'OperatorType.php');
    include_once($LocalDirectory . 'Abstracts' . DIRECTORY_SEPARATOR . 'TelegramChatType.php');
    include_once($LocalDirectory . 'Abstracts' . DIRECTORY_SEPARATOR . 'TransactionType.php');

    include_once($LocalDirectory . 'Exceptions' . DIRECTORY_SEPARATOR . 'AccountLimitedException.php');
    include_once($LocalDirectory . 'Exceptions' . DIRECTORY_SEPARATOR . 'AccountNotFoundException.php');
    include_once($LocalDirectory . 'Exceptions' . DIRECTORY_SEPARATOR . 'AccountSuspendedException.php');
    include_once($LocalDirectory . 'Exceptions' . DIRECTORY_SEPARATOR . 'ApplicationAlreadyExistsException.php');
    include_once($LocalDirectory . 'Exceptions' . DIRECTORY_SEPARATOR . 'ApplicationNotFoundException.php');
    include_once($LocalDirectory . 'Exceptions' . DIRECTORY_SEPARATOR . 'AuthenticationAccessNotFoundException.php');
    include_once($LocalDirectory . 'Exceptions' . DIRECTORY_SEPARATOR . 'AuthenticationRequestAlreadyUsedException.php');
    include_once($LocalDirectory . 'Exceptions' . DIRECTORY_SEPARATOR . 'AuthenticationRequestNotFoundException.php');
    include_once($LocalDirectory . 'Exceptions' . DIRECTORY_SEPARATOR . 'ConfigurationNotFoundException.php');
    include_once($LocalDirectory . 'Exceptions' . DIRECTORY_SEPARATOR . 'DatabaseException.php');
    include_once($LocalDirectory . 'Exceptions' . DIRECTORY_SEPARATOR . 'EmailAlreadyExistsException.php');
    include_once($LocalDirectory . 'Exceptions' . DIRECTORY_SEPARATOR . 'HostBlockedFromAccountException.php');
    include_once($LocalDirectory . 'Exceptions' . DIRECTORY_SEPARATOR . 'HostNotKnownException.php');
    include_once($LocalDirectory . 'Exceptions' . DIRECTORY_SEPARATOR . 'IncorrectLoginDetailsException.php');
    include_once($LocalDirectory . 'Exceptions' . DIRECTORY_SEPARATOR . 'InsufficientFundsException.php');
    include_once($LocalDirectory . 'Exceptions' . DIRECTORY_SEPARATOR . 'InvalidAccountStatusException.php');
    include_once($LocalDirectory . 'Exceptions' . DIRECTORY_SEPARATOR . 'InvalidArgumentException.php');
    include_once($LocalDirectory . 'Exceptions' . DIRECTORY_SEPARATOR . 'InvalidEmailException.php');
    include_once($LocalDirectory . 'Exceptions' . DIRECTORY_SEPARATOR . 'InvalidIpException.php');
    include_once($LocalDirectory . 'Exceptions' . DIRECTORY_SEPARATOR . 'InvalidLoginStatusException.php');
    include_once($LocalDirectory . 'Exceptions' . DIRECTORY_SEPARATOR . 'InvalidMessageContentException.php');
    include_once($LocalDirectory . 'Exceptions' . DIRECTORY_SEPARATOR . 'InvalidMessageSubjectException.php');
    include_once($LocalDirectory . 'Exceptions' . DIRECTORY_SEPARATOR . 'InvalidPasswordException.php');
    include_once($LocalDirectory . 'Exceptions' . DIRECTORY_SEPARATOR . 'InvalidRequestPermissionException.php');
    include_once($LocalDirectory . 'Exceptions' . DIRECTORY_SEPARATOR . 'InvalidSearchMethodException.php');
    include_once($LocalDirectory . 'Exceptions' . DIRECTORY_SEPARATOR . 'InvalidTransactionTypeException.php');
    include_once($LocalDirectory . 'Exceptions' . DIRECTORY_SEPARATOR . 'InvalidUsernameException.php');
    include_once($LocalDirectory . 'Exceptions' . DIRECTORY_SEPARATOR . 'InvalidVendorException.php');
    include_once($LocalDirectory . 'Exceptions' . DIRECTORY_SEPARATOR . 'LoginRecordNotFoundException.php');
    include_once($LocalDirectory . 'Exceptions' . DIRECTORY_SEPARATOR . 'TelegramClientNotFoundException.php');
    include_once($LocalDirectory . 'Exceptions' . DIRECTORY_SEPARATOR . 'TransactionRecordNotFoundException.php');
    include_once($LocalDirectory . 'Exceptions' . DIRECTORY_SEPARATOR . 'UsernameAlreadyExistsException.php');

    include_once($LocalDirectory . 'Managers' . DIRECTORY_SEPARATOR . 'AccountManager.php');
    include_once($LocalDirectory . 'Managers' . DIRECTORY_SEPARATOR . 'ApplicationManager.php');
    include_once($LocalDirectory . 'Managers' . DIRECTORY_SEPARATOR . 'AuthenticationAccessManager.php');
    include_once($LocalDirectory . 'Managers' . DIRECTORY_SEPARATOR . 'AuthenticationRequestManager.php');
    include_once($LocalDirectory . 'Managers' . DIRECTORY_SEPARATOR . 'CrossOverAuthenticationManager.php');
    include_once($LocalDirectory . 'Managers' . DIRECTORY_SEPARATOR . 'KnownHostsManager.php');
    include_once($LocalDirectory . 'Managers' . DIRECTORY_SEPARATOR . 'LoginRecordManager.php');
    include_once($LocalDirectory . 'Managers' . DIRECTORY_SEPARATOR . 'MessagesManager.php');
    include_once($LocalDirectory . 'Managers' . DIRECTORY_SEPARATOR . 'TelegramClientManager.php');
    include_once($LocalDirectory . 'Managers' . DIRECTORY_SEPARATOR . 'TransactionRecordManager.php');

    if(class_exists('ZiProto\ZiProto') == false)
    {
        include_once(__DIR__ . DIRECTORY_SEPARATOR . 'ZiProto' . DIRECTORY_SEPARATOR . 'ZiProto.php');
    }

    if(class_exists('BasicCalculator\BC') == false)
    {
        include_once(__DIR__ . DIRECTORY_SEPARATOR . 'BasicCalculator' . DIRECTORY_SEPARATOR . 'BC.php');
    }

    if(class_exists('IPStack\IPStack') == false)
    {
        include_once(__DIR__ . DIRECTORY_SEPARATOR . 'IPStack' . DIRECTORY_SEPARATOR . 'IPStack.php');
    }

    if(class_exists('msqg\msqg') == false)
    {
        include_once(__DIR__ . DIRECTORY_SEPARATOR . 'msqg' . DIRECTORY_SEPARATOR . 'msqg.php');
    }

    if(class_exists('tsa\tsa') == false)
    {
        include_once(__DIR__ . DIRECTORY_SEPARATOR . 'tsa' . DIRECTORY_SEPARATOR . 'tsa.php');
    }

    if(class_exists('acm\acm') == false)
    {
        include_once(__DIR__ . DIRECTORY_SEPARATOR . 'acm' . DIRECTORY_SEPARATOR . 'acm.php');
    }

    if(class_exists('udp\udp') == false)
    {
        include_once(__DIR__ . DIRECTORY_SEPARATOR . 'udp' . DIRECTORY_SEPARATOR . 'udp.php');
    }

    include(__DIR__ . DIRECTORY_SEPARATOR . 'AutoConfig.php');

    /**
     * Class IntellivoidAccounts
     * @package IntellivoidAccounts
     */
    class IntellivoidAccounts
    {

        /**
         * @var mysqli
         */
        public $database;

        /**
         * @var LoginRecordManager
         */
        private $LoginRecordManager;

        /**
         * @var AccountManager
         */
        private $AccountManager;

        /**
         * @var TransactionRecordManager
         */
        private $TransactionRecordManager;

        /**
         * @var acm
         */
        private $acm;

        /**
         * @var mixed
         */
        private $DatabaseConfiguration;

        /**
         * @var KnownHostsManager
         */
        private $KnownHostsManager;

        /**
         * @var TelegramClientManager
         */
        private $TelegramClientManager;

        /**
         * @var mixed
         */
        private $IpStackConfiguration;

        /**
         * @var mixed
         */
        private $SystemConfiguration;

        /**
         * @var udp
         */
        private $udp;

        /**
         * @var CrossOverAuthenticationManager
         */
        private $CrossOverAuthenticationManager;

        /**
         * @var ApplicationManager
         */
        private $ApplicationManager;

        /**
         * IntellivoidAccounts constructor.
         * @throws Exception
         */
        public function __construct()
        {
            $this->acm = new acm(__DIR__, 'Intellivoid Accounts');
            $this->DatabaseConfiguration = $this->acm->getConfiguration('Database');
            $this->IpStackConfiguration = $this->acm->getConfiguration('IpStack');
            $this->SystemConfiguration = $this->acm->getConfiguration('System');

            $this->database = new mysqli(
                $this->DatabaseConfiguration['Host'],
                $this->DatabaseConfiguration['Username'],
                $this->DatabaseConfiguration['Password'],
                $this->DatabaseConfiguration['Name'],
                $this->DatabaseConfiguration['Port']
            );

            $this->AccountManager = new AccountManager($this);
            $this->KnownHostsManager = new KnownHostsManager($this);
            $this->LoginRecordManager = new LoginRecordManager($this);
            $this->TransactionRecordManager = new TransactionRecordManager($this);
            $this->TelegramClientManager = new TelegramClientManager($this);
            $this->CrossOverAuthenticationManager = new CrossOverAuthenticationManager($this);
            $this->ApplicationManager = new ApplicationManager($this);

            if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN')
            {
                $this->udp = new udp($this->SystemConfiguration['ProfilesLocation_Windows']);
            }
            else
            {
                $this->udp = new udp($this->SystemConfiguration['ProfilesLocation_Unix']);
            }
        }

        /**
         * @return LoginRecordManager
         */
        public function getLoginRecordManager(): LoginRecordManager
        {
            return $this->LoginRecordManager;
        }

        /**
         * @return AccountManager
         */
        public function getAccountManager(): AccountManager
        {
            return $this->AccountManager;
        }

        /**
         * @return TransactionRecordManager
         */
        public function getTransactionRecordManager(): TransactionRecordManager
        {
            return $this->TransactionRecordManager;
        }

        /**
         * @return KnownHostsManager
         */
        public function getKnownHostsManager(): KnownHostsManager
        {
            return $this->KnownHostsManager;
        }

        /**
         * @return TelegramClientManager
         */
        public function getTelegramClientManager(): TelegramClientManager
        {
            return $this->TelegramClientManager;
        }

        /**
         * @return acm
         */
        public function getAcm(): acm
        {
            return $this->acm;
        }

        /**
         * @return mixed
         */
        public function getIpStackConfiguration()
        {
            return $this->IpStackConfiguration;
        }

        /**
         * @return mixed
         */
        public function getSystemConfiguration()
        {
            return $this->SystemConfiguration;
        }

        /**
         * @return udp
         */
        public function getUdp(): udp
        {
            return $this->udp;
        }

        /**
         * @return CrossOverAuthenticationManager
         */
        public function getCrossOverAuthenticationManager(): CrossOverAuthenticationManager
        {
            return $this->CrossOverAuthenticationManager;
        }

        /**
         * @return ApplicationManager
         */
        public function getApplicationManager(): ApplicationManager
        {
            return $this->ApplicationManager;
        }

    }