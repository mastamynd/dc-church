<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentScheduleTables extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('billing_cycles')) {
            Schema::create('billing_cycles', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->integer('company_id');
                $table->string('name');
                $table->date('scheduled_date');
                $table->string('status')->default('open');
                $table->text('notes')->nullable();
                $table->string('created_from')->nullable();
                $table->integer('created_by')->nullable();
                $table->timestamps();
                $table->softDeletes();

                $table->index(['company_id', 'status', 'scheduled_date'], 'ps_cycles_company_status_date');
            });
        }

        if (!Schema::hasTable('payment_schedules')) {
            Schema::create('payment_schedules', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->integer('company_id');
                $table->unsignedBigInteger('billing_cycle_id');
                $table->string('type');
                $table->date('scheduled_date');
                $table->string('status')->default('draft');
                $table->double('total_amount')->default(0);
                $table->timestamp('submitted_at')->nullable();
                $table->integer('triggered_by')->nullable();
                $table->timestamp('triggered_at')->nullable();
                $table->string('created_from')->nullable();
                $table->integer('created_by')->nullable();
                $table->timestamps();
                $table->softDeletes();

                $table->index(['company_id', 'billing_cycle_id', 'status'], 'ps_schedules_cycle_status');
                $table->index(['company_id', 'type', 'status'], 'ps_schedules_type_status');
            });
        }

        if (!Schema::hasTable('payment_schedule_items')) {
            Schema::create('payment_schedule_items', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->integer('company_id');
                $table->unsignedBigInteger('payment_schedule_id');
                $table->integer('demarcation_id');
                $table->string('payment_type');
                $table->double('amount')->default(0);
                $table->integer('bank_id')->nullable();
                $table->string('bank_name')->nullable();
                $table->string('paybill')->nullable();
                $table->string('account_number')->nullable();
                $table->string('status')->default('pending');
                $table->timestamps();
                $table->softDeletes();

                $table->index(['payment_schedule_id', 'status'], 'ps_items_schedule_status');
                $table->index(['demarcation_id', 'payment_type'], 'ps_items_demarcation_type');
            });
        }

        if (!Schema::hasTable('payment_schedule_item_division')) {
            Schema::create('payment_schedule_item_division', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->unsignedBigInteger('schedule_item_id');
                $table->unsignedBigInteger('payment_division_id');
                $table->timestamps();

                $table->unique('payment_division_id', 'psid_division_unique');
                $table->index('schedule_item_id', 'psid_item_id');
            });
        }

        if (!Schema::hasTable('payment_schedule_approval_configs')) {
            Schema::create('payment_schedule_approval_configs', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->integer('company_id');
                $table->string('name')->default('M-Pesa payouts');
                $table->string('applies_to')->default('both');
                $table->boolean('enabled')->default(1);
                $table->string('created_from')->nullable();
                $table->integer('created_by')->nullable();
                $table->timestamps();
                $table->softDeletes();

                $table->index(['company_id', 'enabled'], 'ps_approval_configs_enabled');
            });
        }

        if (!Schema::hasTable('payment_schedule_approval_steps')) {
            Schema::create('payment_schedule_approval_steps', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->integer('company_id');
                $table->unsignedBigInteger('config_id');
                $table->unsignedInteger('level')->default(1);
                $table->string('name');
                $table->string('approver_type')->default('user');
                $table->integer('approver_id');
                $table->boolean('is_final')->default(0);
                $table->timestamps();
                $table->softDeletes();

                $table->index(['config_id', 'level'], 'ps_approval_steps_level');
            });
        }

        if (!Schema::hasTable('payment_schedule_approvals')) {
            Schema::create('payment_schedule_approvals', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->integer('company_id');
                $table->unsignedBigInteger('payment_schedule_id');
                $table->unsignedBigInteger('step_id')->nullable();
                $table->unsignedInteger('level')->default(1);
                $table->string('name')->nullable();
                $table->string('approver_type')->default('user');
                $table->integer('approver_id');
                $table->boolean('is_final')->default(0);
                $table->string('status')->default('pending');
                $table->integer('approved_by')->nullable();
                $table->timestamp('approved_at')->nullable();
                $table->text('comment')->nullable();
                $table->timestamps();
                $table->softDeletes();

                $table->index(['payment_schedule_id', 'level', 'status'], 'ps_approvals_level_status');
            });
        }

        if (!Schema::hasTable('payment_schedule_otps')) {
            Schema::create('payment_schedule_otps', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->integer('company_id');
                $table->unsignedBigInteger('payment_schedule_id');
                $table->integer('user_id');
                $table->string('code_hash');
                $table->timestamp('expires_at');
                $table->timestamp('used_at')->nullable();
                $table->unsignedInteger('attempts')->default(0);
                $table->timestamps();
                $table->softDeletes();

                $table->index(['payment_schedule_id', 'user_id'], 'ps_otps_schedule_user');
            });
        }

        if (!Schema::hasTable('mpesa_transactions')) {
            Schema::create('mpesa_transactions', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->integer('company_id');
                $table->unsignedBigInteger('payment_schedule_id');
                $table->unsignedBigInteger('schedule_item_id');
                $table->string('originator_conversation_id')->nullable();
                $table->string('conversation_id')->nullable();
                $table->string('transaction_id')->nullable();
                $table->string('command_id')->default('BusinessPayBill');
                $table->string('party_a')->nullable();
                $table->string('party_b')->nullable();
                $table->string('account_reference')->nullable();
                $table->double('amount')->default(0);
                $table->string('result_code')->nullable();
                $table->text('result_desc')->nullable();
                $table->longText('request_payload')->nullable();
                $table->longText('callback_payload')->nullable();
                $table->string('status')->default('pending');
                $table->timestamps();
                $table->softDeletes();

                $table->index(['payment_schedule_id', 'status'], 'ps_mpesa_schedule_status');
                $table->index(['schedule_item_id'], 'ps_mpesa_item');
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('mpesa_transactions');
        Schema::dropIfExists('payment_schedule_otps');
        Schema::dropIfExists('payment_schedule_approvals');
        Schema::dropIfExists('payment_schedule_approval_steps');
        Schema::dropIfExists('payment_schedule_approval_configs');
        Schema::dropIfExists('payment_schedule_item_division');
        Schema::dropIfExists('payment_schedule_items');
        Schema::dropIfExists('payment_schedules');
        Schema::dropIfExists('billing_cycles');
    }
}
