<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CmfSportsBasketballLineUpModel extends Model
{
    protected $table = 'cmf_sports_basketball_line_up';
    protected $primaryKey = 'id';
    public $timestamps = true;

    public static function insertOrUpdate($lineUp)
    {
        $columns['matchId'] = $lineUp->matchId ?? $lineUp['matchId'];
        $columns['homeTeamLineUp'] = json_encode($lineUp->homeTeamLineUp ?? $lineUp['homeTeamLineUp']);
        $columns['awayTeamLineUp'] = json_encode($lineUp->awayTeamLineUp ?? $lineUp['awayTeamLineUp']);
        $columns['homeTeamSubLineUp'] = json_encode($lineUp->homeTeamSubLineUp ?? $lineUp['homeTeamSubLineUp']);
        $columns['awayTeamSubLineUp'] = json_encode($lineUp->awayTeamSubLineUp ?? $lineUp['awayTeamSubLineUp']);
        $columns['homeTeamInjuryList'] = json_encode($lineUp->homeTeamInjuryList ?? $lineUp['homeTeamInjuryList']);
        $columns['awayTeamInjuryList'] = json_encode($lineUp->awayTeamInjuryList ?? $lineUp['awayTeamInjuryList']);
        $columns['homeTeamFormation'] = $lineUp->homeTeamFormation ?? $lineUp['homeTeamFormation'];
        $columns['awayTeamFormation'] = $lineUp->awayTeamFormation ?? $lineUp['awayTeamFormation'];
        if (self::where(['matchId' => $columns['matchId']])->exists()) {    //更新
            self::where(['matchId' => $columns['matchId']])->update($columns);
        } else {    //创建
            self::insert($columns);
        }
    }

}
