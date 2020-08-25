<?php
    /** @noinspection PhpUnused */
    namespace IntellivoidAccounts;

    use acm\acm;
    use Exception;
    use IntellivoidAccounts\Managers\AccountManager;
    use IntellivoidAccounts\Managers\ApplicationManager;
    use IntellivoidAccounts\Managers\AuditLogManager;
    use IntellivoidAccounts\Managers\CrossOverAuthenticationManager;
    use IntellivoidAccounts\Managers\KnownHostsManager;
    use IntellivoidAccounts\Managers\LoginRecordManager;
    use IntellivoidAccounts\Managers\OtlManager;
    use IntellivoidAccounts\Managers\SubscriptionManager;
    use IntellivoidAccounts\Managers\SubscriptionPlanManager;
    use IntellivoidAccounts\Managers\SubscriptionPromotionManager;
    use IntellivoidAccounts\Managers\TelegramVerificationCodeManager;
    use IntellivoidAccounts\Managers\TrackingUserAgentManager;
    use IntellivoidAccounts\Managers\TransactionManager;
    use IntellivoidAccounts\Managers\TransactionRecordManager;
    use IntellivoidAccounts\Services\Telegram;
    use mysqli;
    use ppm\ppm;
    use TelegramClientManager\Managers\TelegramClientManager;
    use udp\udp;

    /** @noinspection PhpUnhandledExceptionInspection */
    ppm::import("net.intellivoid.acm");
    /** @noinspection PhpUnhandledExceptionInspection */
    ppm::import("net.intellivoid.bcmath");
    /** @noinspection PhpUnhandledExceptionInspection */
    ppm::import("net.intellivoid.ipstack");
    /** @noinspection PhpUnhandledExceptionInspection */
    ppm::import("net.intellivoid.msqg");
    /** @noinspection PhpUnhandledExceptionInspection */
    ppm::import("net.intellivoid.tsa");
    /** @noinspection PhpUnhandledExceptionInspection */
    ppm::import("net.intellivoid.udp");
    /** @noinspection PhpUnhandledExceptionInspection */
    ppm::import("net.intellivoid.ziproto");
    /** @noinspection PhpUnhandledExceptionInspection */
    ppm::import("net.intellivoid.telegram_client_manager");
    /** @noinspection PhpUnhandledExceptionInspection */
    ppm::import("net.intellivoid.subscriptions");

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
         * @var udp
         */
        private $app_udp;

        /**
         * @var TelegramVerificationCodeManager
         * @deprecated
         */
        private $TelegramVerificationCodeManager;

        /**
         * @var TrackingUserAgentManager
         */
        private $TrackingUserAgentManager;

        /**
         * @var OtlManager
         */
        private $OtlManager;

        /**
         * @var AuditLogManager
         */
        private $AuditLogManager;

        /**
         * @var mixed
         */
        private $TelegramConfiguration;

        /**
         * @var Telegram
         */
        private $TelegramService;

        /**
         * @var SubscriptionPlanManager
         */
        private $SubscriptionPlanManager;

        /**
         * @var SubscriptionPromotionManager
         */
        private $SubscriptionPromotionManager;

        /**
         * @var TransactionRecordManager
         */
        private $TransactionRecordManager;

        /**
         * @var TransactionManager
         */
        private $TransactionManager;

        /**
         * @var SubscriptionManager
         */
        private $SubscriptionManager;

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
            $this->TelegramConfiguration = $this->acm->getConfiguration('TelegramService');

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
            $TelegramClientManager = new \TelegramClientManager\TelegramClientManager();
            $this->TelegramClientManager = $TelegramClientManager->getTelegramClientManager();
            $this->CrossOverAuthenticationManager = new CrossOverAuthenticationManager($this);
            $this->ApplicationManager = new ApplicationManager($this);
            $this->TelegramVerificationCodeManager = new TelegramVerificationCodeManager($this);
            $this->TrackingUserAgentManager = new TrackingUserAgentManager($this);
            $this->OtlManager = new OtlManager($this);
            $this->AuditLogManager = new AuditLogManager($this);
            $this->TelegramService = new Telegram($this);
            $this->SubscriptionPlanManager = new SubscriptionPlanManager($this);
            $this->SubscriptionPromotionManager = new SubscriptionPromotionManager($this);
            $this->TransactionRecordManager = new TransactionRecordManager($this);
            $this->TransactionManager = new TransactionManager($this);
            $this->SubscriptionManager = new SubscriptionManager($this);

            if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN')
            {
                $this->udp = new udp($this->SystemConfiguration['ProfilesLocation_Windows']);
                $this->app_udp = new udp($this->SystemConfiguration['AppIconsLocation_Windows']);
            }
            else
            {
                $this->udp = new udp($this->SystemConfiguration['ProfilesLocation_Unix']);
                $this->app_udp = new udp($this->SystemConfiguration['AppIconsLocation_Unix']);
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

        /**
         * @return udp
         */
        public function getAppUdp(): udp
        {
            return $this->app_udp;
        }

        /**
         * @return TelegramVerificationCodeManager
         * @deprecated
         */
        public function getTelegramVerificationCodeManager(): TelegramVerificationCodeManager
        {
            return $this->TelegramVerificationCodeManager;
        }

        /**
         * @return TrackingUserAgentManager
         */
        public function getTrackingUserAgentManager(): TrackingUserAgentManager
        {
            return $this->TrackingUserAgentManager;
        }

        /**
         * @return OtlManager
         */
        public function getOtlManager(): OtlManager
        {
            return $this->OtlManager;
        }

        /**
         * @return AuditLogManager
         */
        public function getAuditLogManager(): AuditLogManager
        {
            return $this->AuditLogManager;
        }

        /**
         * @return mixed
         */
        public function getTelegramConfiguration()
        {
            return $this->TelegramConfiguration;
        }

        /**
         * @return Telegram
         */
        public function getTelegramService(): Telegram
        {
            return $this->TelegramService;
        }

        /**
         * @return SubscriptionPlanManager
         */
        public function getSubscriptionPlanManager(): SubscriptionPlanManager
        {
            return $this->SubscriptionPlanManager;
        }

        /**
         * @return SubscriptionPromotionManager
         */
        public function getSubscriptionPromotionManager(): SubscriptionPromotionManager
        {
            return $this->SubscriptionPromotionManager;
        }

        /**
         * @return TransactionRecordManager
         */
        public function getTransactionRecordManager(): TransactionRecordManager
        {
            return $this->TransactionRecordManager;
        }

        /**
         * @return TransactionManager
         */
        public function getTransactionManager(): TransactionManager
        {
            return $this->TransactionManager;
        }

        /**
         * @return SubscriptionManager
         */
        public function getSubscriptionManager(): SubscriptionManager
        {
            return $this->SubscriptionManager;
        }

    }