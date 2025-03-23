<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('first_name', 100)->nullable();
            $table->string('last_name', 100)->nullable();
            $table->string('mid_name', 50)->nullable();
            $table->string('father_name', 50)->nullable();
            $table->string('mother_name', 50)->nullable();
            $table->date('birth_date')->nullable();
            $table->string('birth_place', 100)->nullable();
            $table->string('nationality', 100)->nullable();
            $table->string('gender', 50)->nullable();
            $table->string('phone', 100)->nullable();
            $table->string('mobile', 100)->nullable();
            $table->string('email', 100)->nullable();
            $table->string('familty_status', 50)->nullable();
            $table->integer('child_number')->nullable();
            $table->string('address_current', 500)->nullable();
            $table->string('address_permanent', 500)->nullable();
            $table->string('image', 1000)->nullable();
            $table->float('salary')->nullable();
            $table->float('allowances')->nullable();
            $table->float('total_salary')->nullable();
            $table->date('hire_date')->nullable();
            $table->date('end_date')->nullable();
            $table->boolean('status')->nullable()->default(true);
            $table->integer('address_incompany_id')->nullable();
            $table->date('visa_expiry')->nullable();
            $table->integer('visa_validity')->nullable();
            $table->string('finger')->nullable()->unique();
            $table->date('cancelation_date')->nullable();

            
            // add these columns in 30-10-2024 after word employee document 
            $table->string('self_number',100)->nullable();
            $table->string('passport_number',100)->nullable();
            $table->string('insurance_number',100)->nullable();
            $table->string('uaeid_number',100)->nullable();
            $table->string('language',100)->nullable();
            $table->string('education',1000)->nullable();
            $table->string('courses',1000)->nullable();
            $table->string('contract_type',255)->nullable();
            $table->string('workcard_number',100)->nullable();
            $table->string('file_number',100)->nullable();
         
         
            $table->integer('department_hire');
            $table->integer('department_current');
            $table->integer('job_hire');
            $table->integer('job_current');
            $table->integer('company_id');
          
            // end add these columns
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
