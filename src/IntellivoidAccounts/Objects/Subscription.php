<?php


    namespace IntellivoidAccounts\Objects;


    use IntellivoidAccounts\Objects\Subscription\Properties;

    class Subscription
    {
        /**
         * Private internal unique database ID for this subscription record
         *
         * @var int
         */
        public $ID;

        /**
         * The unique Public ID for this subscription record
         *
         * @var string
         */
        public $PublicID;

        /**
         * User-friendly name for this subscription
         *
         * @var string
         */
        public $SubscriptionName;

        /**
         * The ID of the Application that this subscription is associated with
         *
         * @var int
         */
        public $ApplicationID;

        /**
         * The ID of the account that this subscription is tied to
         *
         * @var int
         */
        public $AccountID;

        /**
         * Indicates if this subscription is active or not
         *
         * @var bool
         */
        public $Active;

        /**
         * The interval for the billing cycle
         *
         * @var int
         */
        public $BillingCycle;

        /**
         * The Unix Timestamp which indicates the next billing cycle
         *
         * @var int
         */
        public $NextBillingCycle;

        /**
         * Properties for this subscription
         *
         * @var Properties
         */
        public $Properties;

        /**
         * The Unix Timestamp for when this subscription has started
         *
         * @var int
         */
        public $StartedTimestamp;

        /**
         * The Unix Timestamp for when this subscription record has been created
         *
         * @var int
         */
        public $CreatedTimestamp;

        /**
         * Returns an array which represents this object
         *
         * @return array
         */
        public function toArray(): array
        {
            return array(
                'id' => (int)$this->ID,
                'public_id' => (int)$this->PublicID,
                'application_id' => (int)$this->ApplicationID,
                'account_id' => (int)$this->AccountID,
                'active' => (bool)$this->Active,
                'billing_cycle' => (int)$this->BillingCycle,
                'next_billing_cycle' => (int)$this->NextBillingCycle,
                'properties' => $this->Properties->toArray(),
                'started_timestamp' => (int)$this->StartedTimestamp,
                'created_timestamp' => (int)$this->CreatedTimestamp
            );
        }

        /**
         * Constructs object from array
         *
         * @param array $data
         * @return Subscription
         */
        public static function fromArray(array $data): Subscription
        {
            $SubscriptionObject = new Subscription();

            if(isset($data['id']))
            {
                $SubscriptionObject->ID = (int)$data['id'];
            }

            if(isset($data['public_id']))
            {
                $SubscriptionObject->PublicID = (int)$data['public_id'];
            }

            if(isset($data['application_id']))
            {
                $SubscriptionObject->ApplicationID = (int)$data['application_id'];
            }

            if(isset($data['account_id']))
            {
                $SubscriptionObject->AccountID = (int)$data['account_id'];
            }

            if(isset($data['active']))
            {
                $SubscriptionObject->Active = (bool)$data['active'];
            }

            if(isset($data['billing_cycle']))
            {
                $SubscriptionObject->BillingCycle = (int)$data['billing_cycle'];
            }

            if(isset($data['next_billing_cycle']))
            {
                $SubscriptionObject->NextBillingCycle = (int)$data['next_billing_cycle'];
            }

            if(isset($data['properties']))
            {
                $SubscriptionObject->Properties = Properties::fromArray($data['properties']);
            }

            if(isset($data['started_timestamp']))
            {
                $SubscriptionObject->StartedTimestamp = (int)$data['started_timestamp'];
            }

            if(isset($data['created_timestamp']))
            {
                $SubscriptionObject->CreatedTimestamp = (int)$data['created_timestamp'];
            }

            return $SubscriptionObject;
        }
    }