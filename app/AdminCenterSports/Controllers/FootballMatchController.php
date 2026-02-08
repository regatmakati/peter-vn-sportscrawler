<?php

namespace App\AdminCenterSports\Controllers;

use App\Admin\Filters\TimestampBetween;
use App\Models\Sports\SportsBasketballMatchModel;
use App\Models\Sports\SportsFootballCompetitionModel;
use App\Models\Sports\SportsFootballMatchModel;
use App\Models\Sports\SportsFootballSeasonModel;
use App\Models\Sports\SportsFootballTeamModel;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;

class FootballMatchController extends AdminController
{
    public function index(Content $content)
    {
        $content->header('足球比赛列表');
        $content->title('足球比赛列表');
        $content->breadcrumb(['text' => '足球比赛列表', 'url' => '/football/matchList']);
        $content->description('足球比赛列表');
        $content->body(self::grid());
        return $content;
    }

    protected function grid()
    {
        $grid = new Grid(new SportsFootballMatchModel());
        $grid->model()->where(['is_deleted' => SportsFootballMatchModel::DELETED_NO])->orderByDesc('match_time');

        $grid->actions(function ($actions) {
            $actions->disableView();
            $actions->disableDelete();
            $actions->disableEdit();
        });

        $grid->filter(function($filter){
            $filter->disableIdFilter();
            $filter->column(1/2, function ($filter) {
                $filter->equal('id', 'ID');
            });
            $filter->column(1/2, function ($filter) {
                $filter->where(function ($query) {
                    $ids = SportsFootballSeasonModel::where('year', 'LIKE', "%{$this->input}%")->pluck('id');
                    $query->whereIn('season_id', $ids);
                }, '赛季');
            });
            $filter->column(1/2, function ($filter) {
                $filter->where(function ($query) {
                    $ids = SportsFootballCompetitionModel
                        ::orWhere('name_zh', 'LIKE', "%{$this->input}%")
                        ->orWhere('name_en', 'LIKE', "%{$this->input}%")
                        ->pluck('id');
                    $query->whereIn('competition_id', $ids);
                }, '赛事');
            });
            $filter->column(1/2, function ($filter) {
                $filter->where(function ($query) {
                    $ids = SportsFootballTeamModel
                        ::orWhere('name_zh', 'LIKE', "%{$this->input}%")
                        ->orWhere('name_en', 'LIKE', "%{$this->input}%")
                        ->pluck('id');
                    $query->whereIn('home_team_id', $ids);
                }, '主队');
            });
            $filter->column(1/2, function ($filter) {
                $filter->where(function ($query) {
                    $ids = SportsFootballTeamModel
                        ::orWhere('name_zh', 'LIKE', "%{$this->input}%")
                        ->orWhere('name_en', 'LIKE', "%{$this->input}%")
                        ->pluck('id');
                    $query->whereIn('away_team_id', $ids);
                }, '客队');
            });
            $filter->column(1/2, function ($filter) {
                $filter->where(function ($query) {
                    if ($this->input == "playing") {
                        $query->whereIn('status_id', SportsFootballMatchModel::$playingStatusMap);
                    } else {
                        $query->where('status_id', $this->input);
                    }
                }, '状态')->select(SportsFootballMatchModel::$statusAdminMap);
            });
            $filter->column(1/2, function ($filter) {
                $filter->use(new TimestampBetween('match_time', '时间'))->date();
            });
            $filter->column(1/2, function ($filter) {
                $filter->where(function ($query) {
                    if (!empty($this->input)) {
                        $query->orWhere('live_url_1', '!=', '')->orWhere('live_url_2', '!=', '')->orWhere('live_url_3', '!=', '');
                    }
                }, '直播')->select([1 => '有直播源']);
            });

        });

        $grid->column('id', __('ID'))->sortable();
        $grid->column('season_id', __('admin.season'))->display(function () {
            $row = SportsFootballSeasonModel::where(['id' => $this->season_id])->first();
            return $row->year ?? '--';
        });
        $grid->column('competition_id', __('admin.competition'))->display(function () {
            $row = SportsFootballCompetitionModel::where(['id' => $this->competition_id])->first();
            return $row->name_zh ?? '--';
        });
        $grid->column('home_team_id', __('admin.home_team'))->display(function () {
            $row = SportsFootballTeamModel::where(['id' => $this->home_team_id])->first();
            if (!empty($row->name_zh)) $text = $row->name_zh;
            if (!empty($row->logo) && !empty($row->name_zh))
                $text = "<img width='15' height='15' src='{$row->logo}'>{$row->name_zh}";
            return $text ?? '--';
        });
        $grid->column('away_team_id', __('admin.away_team'))->display(function () {
            $row = SportsFootballTeamModel::where(['id' => $this->away_team_id])->first();
            if (!empty($row->name_zh)) $text = $row->name_zh;
            if (!empty($row->logo) && !empty($row->name_zh))
                $text = "<img width='15' height='15' src='{$row->logo}'>{$row->name_zh}";
            return $text ?? '--';
        });
        $grid->column('status_id', __('admin.match_status'))->display(function () {
            return SportsFootballMatchModel::$statusMap[$this->status_id] ?? '--';
        });
        $grid->column('match_time', __('admin.match_time'))->display(function () {
            return date("Y-m-d H:i:s", $this->match_time)  ?? '--';
        });
        $grid->column('neutral', __('admin.neutral'))->display(function () {
            return SportsFootballMatchModel::$neutralMap[$this->neutral] ?? '--';
        });
        $grid->column('live_url', __('admin.live_url'))->display(function () {
            $btn = $btn1 = $btn2 = $btn3 = '';
            if (in_array($this->status_id, array_merge([SportsFootballMatchModel::STATUS_NOT_START], SportsFootballMatchModel::$playingStatusMap)))
            {
                $func = function ($url) {
                    $anchorPlayUrl = config("params.anchorplay.url");
                    return "<div><button style='height: 25px;line-height: 25px;' class='layui-btn layui-btn-primary layui-btn-sm' onclick=copyToClip(this.innerText)>{$anchorPlayUrl}/live/{$url}.flv</button></div>";
                };
                if (!empty($this->live_url_1)) $btn1 = $func($this->live_url_1);
                if (!empty($this->live_url_2)) $btn2 = $func($this->live_url_2);
                if (!empty($this->live_url_3)) $btn3 = $func($this->live_url_3);
                if (!empty($btn1)) $btn = $btn1;
                if (!empty($btn2)) $btn .= $btn2;
                if (!empty($btn3)) $btn .= $btn3;
            }
            return $btn;
        });
//        $grid->column('created_at', __('admin.created_at'));
//        $grid->column('updated_at', __('admin.updated_at'));

        return $grid;
    }

    protected function detail($id)
    {
        $show = new Show(SportsFootballMatchModel::findOrFail($id));

        $show->field('id', __('ID'));
//        $show->field('created_at', __('admin.created_at'));
//        $show->field('updated_at', __('admin.updated_at'));
        return $show;
    }

    protected function form()
    {
        $form = new Form(new SportsFootballMatchModel);

        $form->display('id', __('ID'));
//        $form->display('created_at', __('admin.created_at'));
//        $form->display('updated_at', __('admin.updated_at'));

        return $form;
    }

}
