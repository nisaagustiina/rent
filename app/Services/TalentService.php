<?php

namespace App\Services;

use App\Models\Talent;
use App\Models\User;
use App\Traits\ApiResponser;
use Exception;
use Spatie\Permission\Models\Role;

class TalentService
{
    use ApiResponser;

    private $talentModel;

    public function __construct(
        Talent $talentModel
    ){
        $this->talentModel = $talentModel;
    }

    public function getTalentList($filter = [], $withPaginate = true, $offset = 0, $limit = 10, $isTrash = false, $with = [], $orderBy = [])
    {
        $talent = $this->talentModel->query();

        if($isTrash == true)
            $talent->onlyTrashed();

        if(isset($filter['nama']))
            $talent->where('nama', $filter['nama']);

        if(isset($filter['nama_alias']))
            $talent->where('nama_alias', $filter['nama_alias']);
        
        if(isset($filter['q']))
            $talent->where('nama', 'like' . $filter['q']. '%')->orWhere('nama_alias', 'like' .$filter['q'].'%');

        if(isset($filter['offset']))
            $offset = $filter['offset'];

        if(isset($filter['limit']))
            $limit = $filter['limit'];

        if(!empty($with))
            $talent->with($with);

        if(!empty($orderBy))
            foreach ($orderBy as $key => $value) {
                $talent->orderBy($key, $value);
            }

        $count = $talent->count();

        if ($withPaginate == true) {
            $result['count'] = $count;
            $result['offset'] = $offset;
            $result['limit'] = $limit;
            $result['data'] = $talent->paginate($limit);
            $result['params'] = [
                'filter' => $filter,
                'orderBy' => $orderBy
            ];
        } else {
            
            if ($limit > 0)
            $talent->limit($limit);

            $result['count'] = $count;
            $result['offset'] = $offset;
            $result['limit'] = $limit;
            $result['data'] = $talent->get();
            $result['params'] = [
                'filter' => $filter,
                'orderBy' => $orderBy
            ];
        }

        return $result;
    }

    public function getTalent($where, $with = [])
    {
        $talent = $this->talentModel->query();

        if(!empty($with))
            $talent->with($with);

        $result = $talent->firstWhere($where);

        return $result;
    }

    public function storeTalent($data)
    {
        $talent = new Talent();
        try{
            $talent->nama = $data->nama;
            $talent->nama_alias = $data->nama_alias;
            $talent->tempat_lahir = $data->tempat_lahir;
            $talent->tanggal_lahir = $data->tanggal_lahir;
            $talent->tipe = json_encode(explode(',', $data->tipe));
            $talent->alamat = $data->alamat;
            $talent->social_media = json_encode(explode(',', $data->social_media));
            $talent->message = $data->message;
            $talent->service = json_encode(explode(',', $data->service));
            $talent->save();

            $user = new User();
            $user->username = $data->nama_alias;
            $user->password = Hash::make('default-'.$data->nama_alias);
            $user->status = 0;
            $user->created_at = $now;
            $user->updated_at = $now;
            $user->last_login = $now;
            $user->save();

            $user->assignRole('talent');

            return $this->success($talent, 'Data has been saved!');
        } catch (Exception $e) {
            return $this->error(null, $e->getMessage());
        }
    }

    public function upTalentTalent($where, $data)
    {
        $talent = $this->getTalent($where);

        try {
            $talent->nama = $data->nama;
            $talent->nama_alias = $data->nama_alias;
            $talent->tempat_lahir = $data->tempat_lahir;
            $talent->tanggal_lahir = $data->tanggal_lahir;
            $talent->tipe = json_encode(explode(',', $data->tipe));
            $talent->alamat = $data->alamat;
            $talent->social_media = json_encode(explode(',', $data->social_media));
            $talent->message = $data->message;
            $talent->service = json_encode(explode(',', $data->service));
            $talent->save();

            return $this->success($talent, 'Data has been updated!');
        } catch (Exception $e) {
           return $this->error(null, $e->getMessage());
        }
    }

    public function trashTalent($where)
    {
        $talent = $this->getTalent($where);

        try{
            $talent->delete();
            return $this->success($talent, 'Data has been store to trash!');
        } catch (Exception $e) {
            return $this->error(null, $e->getMessage());
         }
    }

    public function restoreTalent($where)
    {
        $talent = $talent->onlyTrashed()->firstWhere($where);

        try{
            $talent->restore(); 
            return $this->success($talent, 'Data has been restored!');
        } catch (Exception $e) {
            return $this->error(null, $e->getMessage());
         }
    }

    public function deleteTalent($where)
    {
        $talent = $this->getTalent($where);

        try{
            $talent->forceDelete();
            return $this->success($talent, 'Data has been deleted!');
        } catch (Exception $e) {
            return $this->error(null, $e->getMessage());
         }
    }
}