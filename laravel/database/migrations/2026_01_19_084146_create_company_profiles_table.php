<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompanyProfilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('company_profiles', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('domainIp')->nullable();
            $table->string('database')->nullable();
            $table->string('username')->nullable();
            $table->string('password')->nullable();
            $table->string('logo')->nullable();
            $table->bigInteger('created_by')->default(1);
            $table->dateTime('created_at')->useCurrent();
            $table->bigInteger('updated_by')->nullable();
            $table->dateTime('updated_at')->nullable();
            $table->ipAddress('ipAddress');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('company_profiles');
    }
}
