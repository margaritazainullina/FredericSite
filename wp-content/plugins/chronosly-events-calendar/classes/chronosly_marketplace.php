<?php
/**
 * Base del marketplace
 */
if(!class_exists('Chronosly_MarketPlace')){


    class Chronosly_MarketPlace{

        private $addons, $templates;

        public function __construct(Chronosly_MK_Addons $addons, Chronosly_MK_Templates $templates){
            if(is_admin()){
                $this->addons = $addons;
                $this->templates = $templates;

            }


        }
    }
}
