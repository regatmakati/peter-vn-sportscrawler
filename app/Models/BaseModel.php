<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model{
    CONST DELETED_NO = 0;
    CONST DELETED_YES = 1;

    public static $deletedMap = [
        self::DELETED_NO => '正常',
        self::DELETED_YES => '以删除',
    ];

    public function updateColumnsByPk(array $columns)
    {
        return self::where([$this->primaryKey => $columns[$this->primaryKey]])->update($columns);
    }

    public function updateColumnsByConditions(array $conditions, array $columns)
    {
        return self::where($conditions)->update($columns);
    }
}
