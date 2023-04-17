<?php

namespace App\Services;

use App\Models\Date;
use App\Traits\ApiResponser;
use Exception;

class DateService
{
    use ApiResponser;

    private $dateModel;

    public function __construct(
        Date $dateModel
    ){
        $this->dateModel = $dateModel;
    }

    public function getDateList($filter = [], $limit = 10, $isTrash = false, $with = [], $orderBy = [])
    {
        $date = $this->dateModel->query();

        if($isTrash == true)
            $date->onlyTrashed();

        if(isset($filter['nama']))
            $date->where('nama', $filter['nama']);
        
        if(isset($filter['q']))
            $date->when($filter['q'], function ($date, $q){
               $date->where('nama', 'like' . $q. '%');
            });
        
        if(isset($filter['limit']))
            $limit = $filter['limit'];

        if(!empty($with))
            $date->with($with);

        if(!empty($orderBy))
            foreach ($orderBy as $key => $value) {
                $date->orderBy($key, $value);
            }

        $result = $date->paginate($limit);

        return $result;
    }

    public function getPage($where, $with=[])
    {
        $date = $this->dateModel->query();

        if(!empty($with))
            $date->with($with);

        $result = $date->firstWhere($where);

        return $result;
    }
}