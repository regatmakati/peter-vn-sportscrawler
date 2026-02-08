<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSportsDota2MatchOddsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sports_dota2_match_odds', function (Blueprint $table) {
            $table->integer('odds_company_id')->nullable()->comment('指数公司id');
            $table->integer('odds_type_id')->nullable()->index('idx_odds_type_id')->comment('指数类型id');
            $table->integer('range')->nullable()->index('idx_range')->comment('覆盖范围（0表示整场比赛，非0表示对应小局）');
            $table->integer('match_id')->nullable()->index('idx_match_id')->comment('比赛id');
            $table->text('odds')->nullable()->comment('指数：0:"变化时间 - int"，1:"主胜 - float"，2:"和局（0） - int"，3:"客胜 - float"，4:"是否封盘：1-封盘,0-未封盘 - int"');
            $table->timestamps()->comment('更新时间');
            $table->unique(['odds_company_id', 'odds_type_id', 'range', 'match_id'], 'unq_odds_company_id_odds_type_id_range_match_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sports_dota2_match_odds');
    }
}
