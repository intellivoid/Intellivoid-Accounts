<?php


    namespace IntellivoidAccounts\Objects\Subscription;


    /**
     * Class Properties
     * @package IntellivoidAccounts\Objects\Subscription
     */
    class Properties
    {
        /**
         * The initial price to start the subscription, 0 = Free
         *
         * @var float
         */
        public $InitialPrice;

        /**
         * The amount to charge the user per billing cycle
         *
         * @var float
         */
        public $CyclePrice;

        /**
         * The features that this subscription provides
         *
         * @var array(Feature)
         */
        public $Features;

        /**
         * The promotional code used for this subscription
         *
         * @var string
         */
        public $PromotionCode;

        /**
         * Adds a feature to the subscription property
         *
         * @param Feature $feature
         */
        public function addFeature(Feature $feature)
        {
            $id = hash('crc32', $feature->Name);
            $this->Features[$id] = $feature;
        }

        /**
         * Returns an array which represents this object
         *
         * @return array
         */
        public function toArray(): array
        {
            $features = array();

            /** @var Feature $feature */
            foreach($this->Features as $feature)
            {
                $features[] = $feature->toArray();
            }

            return array(
                'initial_price' => $this->InitialPrice,
                'cycle_price' => $this->CyclePrice,
                'promotion_code' => $this->PromotionCode,
                'features' => $features
            );
        }

        /**
         * Creates object from array
         *
         * @param array $data
         * @return Properties
         */
        public static function fromArray(array $data): Properties
        {
            $PropertiesObject = new Properties();

            if(isset($data['initial_price']))
            {
                $PropertiesObject->InitialPrice = (float)$data['initial_price'];
            }

            if(isset($data['cycle_price']))
            {
                $PropertiesObject->CyclePrice = (float)$data['cycle_price'];
            }

            if(isset($data['promotion_code']))
            {
                $PropertiesObject->PromotionCode = $data['promotion_code'];
            }

            foreach($data['features'] as $feature)
            {
                $PropertiesObject->addFeature(Feature::fromArray($feature));
            }

            return $PropertiesObject;
        }
    }