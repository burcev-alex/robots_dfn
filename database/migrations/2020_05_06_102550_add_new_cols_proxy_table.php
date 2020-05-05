<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNewColsProxyTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('proxies', function (Blueprint $table) {
            $table->text('type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        $connection = config('database.default');
        $driver = config("database.connections.{$connection}.driver");

        // Fallback for sqlite
        if ($driver === 'sqlite') {
            Schema::dropIfExists('proxies');

            return;
        }

        Schema::table('proxies', function (Blueprint $table) {
            $table->dropColumn('type');
        });
    }
}
