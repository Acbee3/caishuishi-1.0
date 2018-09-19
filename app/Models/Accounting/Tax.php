<?php

namespace App\models\Accounting;

use Illuminate\Database\Eloquent\Model;

class Tax extends Model
{
    protected $table = 'tax';
    protected $guarded = [];

    /**
     * 初始化数据
     * @return bool
     */
    public static function initData()
    {

        $data = [
            ['id' => 1, 'name' => '应交城市维护建设税'],
            ['id' => 2, 'name' => '应交教育附加'],
            ['id' => 3, 'name' => '应交地方教育费附加'],
            ['id' => 4, 'name' => '计提企业所得税'],
            ['id' => 5, 'name' => '计提印花税'],
            ['id' => 6, 'name' => '小规模增值税减免'],
            ['id' => 7, 'name' => '防伪税控费实际抵减'],
        ];

        foreach ($data as $datum) {
            (new Tax($datum))->save();
        }
    }

}
