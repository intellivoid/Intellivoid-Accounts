<?php

    namespace IntellivoidAccounts\Objects\Account\Configuration;

    use IntellivoidAccounts\Abstracts\OpenBluPlan;

    /**
     * Class OpenBlu
     * @package IntellivoidAccounts\Objects\Account\Configuration
     */
    class OpenBlu
    {
        /**
         * @var OpenBluPlan|int
         */
        public $CurrentPlan;

        /**
         * The access key ID
         *
         * @var int
         */
        public $AccessKeyID;

        /**
         * The PayPal Transaction ID
         *
         * @var string
         */
        public $TransactionID;

        /**
         * @var int
         */
        public $TransactionProcessedTimestamp;

        /**
         * OpenBlu constructor.
         */
        public function __construct()
        {
            $this->CurrentPlan = OpenBluPlan::None;
        }

        /**
         * Converts object to array
         *
         * @return array
         */
        public function toArray(): array
        {
            return array(
                'current_plan' => (int)$this->CurrentPlan,
                'access_key_id' => (int)$this->AccessKeyID,
                'transaction_id' => $this->TransactionID,
                'transaction_processed_timestamp' => (int)$this->TransactionProcessedTimestamp
            );
        }

        /**
         * Creates object from array
         *
         * @param array $data
         * @return OpenBlu
         */
        public static function fromArray(array $data): OpenBlu
        {
            $ConfigurationObject = new OpenBlu();

            if(isset($data['current_plan']))
            {
                $ConfigurationObject->CurrentPlan = (int)$data['current_plan'];
            }

            if(isset($data['access_key_id']))
            {
                $ConfigurationObject->AccessKeyID = (int)$data['access_key_id'];
            }

            if(isset($data['transaction_id']))
            {
                $ConfigurationObject->TransactionID = $data['transaction_id'];
            }

            if(isset($data['transaction_processed_timestamp']))
            {
                $ConfigurationObject->TransactionProcessedTimestamp = (int)$data['transaction_processed_timestamp'];
            }

            return $ConfigurationObject;
        }
    }