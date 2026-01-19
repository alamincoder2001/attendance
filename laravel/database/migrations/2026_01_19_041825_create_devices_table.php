<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDevicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('devices', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('type', 100)->default('ZK TFT');
            $table->string('serial_number', 150)->default('0000000000');
            $table->string('port', 20)->default('4370');
            $table->ipAddress('ipAddress');
            $table->char('status', 1)->default('p'); // a = active, p = pending, d = deactive;
            $table->bigInteger('created_by')->default(1);
            $table->dateTime('created_at')->useCurrent();
            $table->bigInteger('updated_by')->nullable();
            $table->dateTime('updated_at')->nullable();
            $table->bigInteger('deleted_by')->nullable();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('devices');
    }
}
