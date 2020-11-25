<?php /** @noinspection PhpUnused */


    namespace IntellivoidAccounts\Objects\KnownHost\Properties;

    /**
     * Class AccountCreationLimitation
     * @package IntellivoidAccounts\Objects\KnownHost\Properties
     */
    class AccountCreationLimitation
    {
        /**
         * The amount of accounts created with this Host
         *
         * @var int
         */
        public $AccountsCreatedCount;

        /**
         * The next Unix Timestamp that will take a number off the AccountsCreatedCount
         *
         * @var int
         */
        public $NextClearTimestamp;

        /**
         * If this property is true, the Mass Account Creation check will always return false
         *
         * @var bool
         */
        public $AutomatedBanTriggered;

        /**
         * The Unix Timestamp for when this automated ban is supposed to be lifted
         *
         * @var int
         */
        public $AutomatedBanLiftTimestamp;

        /**
         * Violation check for mass account creation, passes if the host hasn't violated this policy
         * and fails if the host has violated the policy
         *
         * @return bool
         * @noinspection PhpUnused
         */
        public function violationCheck(): bool
        {
            // Check if the automated ban was triggered
            if($this->AutomatedBanTriggered)
            {
                // If the automated ban should be lifted by the timestamp
                if((int)time() > $this->AutomatedBanLiftTimestamp)
                {
                    $this->AutomatedBanTriggered = false;
                    $this->AccountsCreatedCount = 0;
                    $this->AutomatedBanLiftTimestamp = 0;
                }
                else
                {
                    return false; // The violation check failed
                }
            }

            // Check if the accounts created count should be discounted by the timestamp
            if((int)time() > $this->NextClearTimestamp)
            {
                if($this->AccountsCreatedCount > 0)
                {
                    $this->AccountsCreatedCount -= 1;
                }

                $this->NextClearTimestamp = ((int)time() + 172800); // 48 Hours (2 Days)
            }

            // If more than two accounts were created in the past 48 hours
            if($this->AccountsCreatedCount > 2)
            {
                $this->AutomatedBanTriggered = true;
                $this->AutomatedBanLiftTimestamp = ((int)time() +  432000); // 120 Hours (5 days)

                return false; // The violation check failed
            }

            return true;
        }

        /**
         * Lifts the violation ban
         *
         * @return bool
         * @noinspection PhpUnused
         */
        public function liftViolatingBan()
        {
            $this->AutomatedBanTriggered = false;
            $this->AccountsCreatedCount = 0;
            $this->AutomatedBanLiftTimestamp = 0;

            return true;
        }

        /**
         * Adds an addition to the counter
         *
         * @param int $amount
         */
        public function count(int $amount=1)
        {
            $this->AccountsCreatedCount += $amount;
            $this->violationCheck();
        }

        /**
         * AccountCreationLimitation constructor.
         */
        public function __construct()
        {
            $this->AccountsCreatedCount = 0;
            $this->NextClearTimestamp = 0;
            $this->AutomatedBanTriggered = false;
            $this->AutomatedBanLiftTimestamp = 0;
        }

        /**
         * Returns an array which represents this object's structure
         *
         * @return array
         */
        public function toArray(): array
        {
            return array(
                "accounts_created_count" => $this->AccountsCreatedCount,
                "next_clear_timestamp" => $this->NextClearTimestamp,
                "automated_ban_triggered" => $this->AutomatedBanTriggered,
                "automated_ban_lift_timestamp" => $this->AutomatedBanLiftTimestamp
            );
        }

        /**
         * Constructs the object from an array
         *
         * @param array $data
         * @return AccountCreationLimitation
         */
        public static function fromArray(array $data): AccountCreationLimitation
        {
            $ReturnObject = new AccountCreationLimitation();

            if(isset($data["accounts_created_count"]))
            {
                $ReturnObject->AccountsCreatedCount = $data["accounts_created_count"];
            }

            if(isset($data["next_clear_timestamp"]))
            {
                $ReturnObject->NextClearTimestamp = $data["next_clear_timestamp"];
            }

            if(isset($data["automated_ban_triggered"]))
            {
                $ReturnObject->AutomatedBanTriggered = $data["automated_ban_triggered"];
            }

            if(isset($data["automated_ban_lift_timestamp"]))
            {
                $ReturnObject->AutomatedBanLiftTimestamp = $data["automated_ban_lift_timestamp"];
            }

            return $ReturnObject;
        }
    }