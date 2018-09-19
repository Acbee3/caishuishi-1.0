<?php

namespace App\Entity\BusinessDataConfig;


class BusinessConfig
{
    //
    public $type;
    public $FactoryName;


    public function __construct($type)
    {
        $this->type = $type;

        switch ($type){
            case 1:
                $this->FactoryName = "JXFP";
                break;
            case 2:
                $this->FactoryName = "XXFP";
                break;
            case 3:
                $this->FactoryName = "FYFP";
                break;
            case 4:
                $this->FactoryName = "YH";
                break;
            case 5:
                $this->FactoryName = "XJ";
                break;
            case 6:
                $this->FactoryName = "PJ";
                break;

            default:
                $this->FactoryName = "JXFP";
                break;
        }
    }


    public function BCFactory(){
        $obj_name = 'App\Entity\BusinessDataConfig\\'.$this->FactoryName;
        return $class = new $obj_name();

    }


    public function getData(){
        $model = $this->BCFactory();
        return $model->getBusinessData();
    }
}
