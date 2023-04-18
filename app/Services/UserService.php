<?php

namespace App\Services;

use App\Models\User;
use App\Traits\ApiResponser;
use Exception;
use Spatie\Permission\Models\Role;

class UserService
{
    use ApiResponser;

    private $userModel;

    public function __construct(
        User $userModel
    ){
        $this->userModel = $userModel;
    }

    public function getUserList($filter = [], $withPaginate = true, $offset = 0, $limit = 10, $isTrash = false, $with = [], $orderBy = [])
    {
        $user = $this->userModel->query();

        if($isTrash == true)
            $user->onlyTrashed();

        if(isset($filter['username']))
            $user->where('username', $filter['username']);

        if(isset($filter['status']))
            $user->where('status', $filter['status']);

        if(isset($filter['q']))
            $user->where('username', 'like' . $filter['q']. '%');

        if(isset($filter['offset']))
            $offset = $filter['offset'];

        if(isset($filter['limit']))
            $limit = $filter['limit'];

        if(!empty($with))
            $user->with($with);

        if(!empty($orderBy))
            foreach ($orderBy as $key => $value) {
                $user->orderBy($key, $value);
            }

        $count = $user->count();

        if ($withPaginate == true) {
            $result['count'] = $count;
            $result['offset'] = $offset;
            $result['limit'] = $limit;
            $result['data'] = $user->paginate($limit);
            $result['params'] = [
                'filter' => $filter,
                'orderBy' => $orderBy
            ];
        } else {
            
            if ($limit > 0)
            $user->limit($limit);

            $result['count'] = $count;
            $result['offset'] = $offset;
            $result['limit'] = $limit;
            $result['data'] = $user->get();
            $result['params'] = [
                'filter' => $filter,
                'orderBy' => $orderBy
            ];
        }

        return $result;
    }

    public function getUser($where, $with = [])
    {
        $user = $this->userModel->query();

        if(!empty($with))
            $user->with($with);

        $result = $user->firstWhere($where);

        return $result;
    }

    public function storeUser($data)
    {
        $user = new User();
        try{
            $user->username = $data->username;
            $user->email = $data->email;
            $user->password = Hash::make($data->password);
            $user->status = $data->status ?? 0;
            $user->created_at = $now;
            $user->updated_at = $now;
            $user->last_login = $now;
            $user->assignRole($data->role);
            $user->save();

            return $this->success($user, 'Data has been saved!');
        } catch (Exception $e) {
            return $this->error(null, $e->getMessage());
        }
    }

    public function upUserUser($where, $data)
    {
        $user = $this->getUser($where);

        try {
            $user->username = $data->username;
            $user->email = $data->email;
            $user->password = Hash::make($data->password);
            $user->status = $data->status ?? 0;
            $user->created_at = $now;
            $user->updated_at = $now;
            $user->last_login = $now;
            $user->syncRoles($data->role);
            $user->save();

            return $this->success($user, 'Data has been updated!');
        } catch (Exception $e) {
           return $this->error(null, $e->getMessage());
        }
    }

    public function trashUser($where)
    {
        $user = $this->getUser($where);

        try{
            $user->delete();
            return $this->success($user, 'Data has been store to trash!');
        } catch (Exception $e) {
            return $this->error(null, $e->getMessage());
         }
    }

    public function restoreUser($where)
    {
        $user = $user->onlyTrashed()->firstWhere($where);

        try{
            $user->restore(); 
            return $this->success($user, 'Data has been restored!');
        } catch (Exception $e) {
            return $this->error(null, $e->getMessage());
         }
    }

    public function deleteUser($where)
    {
        $user = $this->getUser($where);

        try{
            $user->forceDelete();
            $user->permissions()->delete();
            return $this->success($user, 'Data has been deleted!');
        } catch (Exception $e) {
            return $this->error(null, $e->getMessage());
         }
    }
}