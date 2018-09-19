<?php
namespace App\Entity\MakeVoucher;



class MakeVoucher
{
    public $model;
    public $FactoryName;

    const JXFP = 1;
    const XXFP = 2;
    const FYFP = 3;
    const YH = 4;
    const XJ = 5;
    const PJ = 6;

    public function __construct($type)
    {
        switch ($type){
            case 2:
                $this->FactoryName = "JXFP";
                break;
            case 3:
                $this->FactoryName = "XXFP";
                break;
            case 4:
                $this->FactoryName = "FYFP";
                break;
            case 5:
                $this->FactoryName = "PJ";
                break;
            case 6:
                $this->FactoryName = "XJ";
                break;
            case 7:
                $this->FactoryName = "YH";
                break;
            case 8:
                $this->FactoryName = "ZC";
                break;
            /*case 9:
                $this->FactoryName = "ZBJZ";
                break;*/

            default:
                $this->FactoryName = "JXFP";
                break;
        }
        $this->model = $this->BCFactory();
    }


    public function BCFactory(){
        $obj_name = 'App\Entity\MakeVoucher\\'.$this->FactoryName;
        return $class = new $obj_name();

    }


    public function getData($obj){
        return $this->model->makeVoucher($obj);
    }
}