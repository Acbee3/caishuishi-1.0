<?php

use Illuminate\Database\Migrations\Migration;

class FixColumnDefault extends Migration
{
    /**
     * Run the migrations.
     * 修复字段 非空值的默认值问题
     *
     * @return void
     */
    public function up()
    {
        $tables = [
            'asset', 'asset_alter', 'cost',
            'cost_item', 'employee', 'fund',
            'fund_item', 'invoice', 'invoice_item',
            'salary', 'salary_employee', 'voucher',
            'voucher_item',
        ];
        foreach ($tables as $table) {
            self::fixColumn($table);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }

    private static function fixColumn(string $table, array $columns = [])
    {

        $columns == [] && $columns = Schema::getColumnListing($table);

        foreach ($columns as $column) {

            if (in_array($column, ['id', 'company_id', 'created_at', 'updated_at']))
                continue;

            if (Schema::hasColumn($table, $column) && Schema::getColumnType($table, $column) == 'integer') {
                Schema::table($table, function (\Illuminate\Database\Schema\Blueprint $table) use ($column) {
                    $table->integer($column)->default(0)->change();
                });
            };

            if (Schema::hasColumn($table, $column) && Schema::getColumnType($table, $column) == 'boolean') {
                Schema::table($table, function (\Illuminate\Database\Schema\Blueprint $table) use ($column) {
                    $table->smallInteger($column)->default(0)->change();
                });
            };

            if (Schema::hasColumn($table, $column) && Schema::getColumnType($table, $column) == 'decimal') {
                Schema::table($table, function (\Illuminate\Database\Schema\Blueprint $table) use ($column) {
                    $table->decimal($column, 11, 2)->default(0)->change();
                });
            };

            if (Schema::hasColumn($table, $column) && Schema::getColumnType($table, $column) == 'bigint') {
                Schema::table($table, function (\Illuminate\Database\Schema\Blueprint $table) use ($column) {
                    $table->bigInteger($column)->default(0)->change();
                });
            };

            if (Schema::hasColumn($table, $column) && Schema::getColumnType($table, $column) == 'string') {
                Schema::table($table, function (\Illuminate\Database\Schema\Blueprint $table) use ($column) {
                    $table->string($column, '255')->default('')->change();
                });
            };

            if (Schema::hasColumn($table, $column) && Schema::getColumnType($table, $column) == 'text') {
                Schema::table($table, function (\Illuminate\Database\Schema\Blueprint $table) use ($column) {
                    $table->text($column)->default('')->change();
                });
            };

        }
    }
}
