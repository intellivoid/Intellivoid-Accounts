<?php

    namespace IntellivoidAccounts\Objects\Account\Configuration;

    use IntellivoidAccounts\Abstracts\OpenBluPlan;
    use IntellivoidAccounts\Abstracts\TransactionStatus;

    /**
     * Class OpenBlu
     * @package IntellivoidAccounts\Objects\Account\Configuration
     */
    class OpenBlu
    {
        /**
         * The current plan of the API
         *
         * @var OpenBluPlan|int
         */
        public $CurrentPlan;

        /**
         * The status of the transaction status right now
         *
         * @var TransactionStatus|int
         */
        public $TransactionStatus;

        /**
         * The access key ID
         *
         * @var int
         */
        public $AccessKeyID;

        /**
         * Indicates if a merchant was used to active this plan, if so
         * then it cannot be cancelled/replaced directly
         *
         * @var bool
         */
        public $MerchantUsed;

        /**
         * The code that was used with this plan, this allows the plan
         * conditions to be altered, including the price.
         *
         * @var string
         */
        public $PlanCode;

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
                'transaction_status' => (int)$this->TransactionStatus,
                'access_key_id' => (int)$this->AccessKeyID,
                'merchant_used' => $this->MerchantUsed,
                'plan_code' => $this->PlanCode,
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

            if(isset($data['transaction_status']))
            {
                $ConfigurationObject->TransactionStatus = (int)$data['transaction_status'];
            }

            if(isset($data['access_key_id']))
            {
                $ConfigurationObject->AccessKeyID = (int)$data['access_key_id'];
            }

            if(isset($data['merchant_used']))
            {
                $ConfigurationObject->MerchantUsed = $data['merchant_used'];
            }

            if(isset($data['plan_code']))
            {
                $ConfigurationObject->PlanCode = $data['plan_code'];
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