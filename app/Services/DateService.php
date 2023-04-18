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

    public function getDateList($filter = [], $withPaginate = true, $offset = 0, $limit = 10, $isTrash = false, $with = [], $orderBy = [])
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

        if(isset($filter['offset']))
            $offset = $filter['offset'];

        if(isset($filter['limit']))
            $limit = $filter['limit'];

        if(!empty($with))
            $date->with($with);

        if(!empty($orderBy))
            foreach ($orderBy as $key => $value) {
                $date->orderBy($key, $value);
            }

        $count = $date->count();

        if ($withPaginate == true) {
            $result['count'] = $count;
            $result['offset'] = $offset;
            $result['limit'] = $limit;
            $result['data'] = $date->paginate($limit);
            $result['params'] = [
                'filter' => $filter,
                'orderBy' => $orderBy
            ];
        } else {
            
            if ($limit > 0)
            $date->limit($limit);

            $result['count'] = $count;
            $result['offset'] = $offset;
            $result['limit'] = $limit;
            $result['data'] = $date->get();
            $result['params'] = [
                'filter' => $filter,
                'orderBy' => $orderBy
            ];
        }

        return $result;
    }

    public function getDate($where, $with = [])
    {
        $date = $this->dateModel->query();

        if(!empty($with))
            $date->with($with);

        $result = $date->firstWhere($where);

        return $result;
    }

    public function storeDate($data)
    {
        $date = new Date();
        try{
            $date->nama = $data->nama;
            $date->service = json_encode(explode(',', $data->service));
            $date->save();
            
            return $this->success($date, 'Data has been saved!');
        } catch (Exception $e) {
            return $this->error(null, $e->getMessage());
        }
    }

    public function updateDate($where, $data)
    {
        $date = $this->getDate($where);

        try {
            $date->nama = $data->nama;
            $date->service = json_encode(explode(',', $data->service));
            $date->save();

            return $this->success($date, 'Data has been updated!');
        } catch (Exception $e) {
           return $this->error(null, $e->getMessage());
        }
    }

    public function trashDate($where)
    {
        $date = $this->getDate($where);

        try{
            $date->delete();
            return $this->success($date, 'Data has been store to trash!');
        } catch (Exception $e) {
            return $this->error(null, $e->getMessage());
         }
    }

    public function restoreDate($where)
    {
        $date = $date->onlyTrashed()->firstWhere($where);

        try{
            $date->restore(); 
            return $this->success($date, 'Data has been restored!');
        } catch (Exception $e) {
            return $this->error(null, $e->getMessage());
         }
    }

    public function deleteDate($where)
    {
        $date = $this->getDate($where);

        try{
            $date->forceDelete();
            return $this->success($date, 'Data has been deleted!');
        } catch (Exception $e) {
            return $this->error(null, $e->getMessage());
         }
    }
}