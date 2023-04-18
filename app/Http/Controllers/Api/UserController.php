<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Exceptions\InvalidOrderException;
use App\Http\Requests\UserRequest;
use App\Traits\ApiResponser;
use App\Services\userService;

class UserController extends Controller
{
    use ApiResponser;

    private $userService;

    public function __construct(
        userService $userService
    ){
        $this->userService = $userService;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        
      $filter = [];

      if($request->input('offset', '') != ''){
        $filter['offset'] = $request->input('offset');
      }

      if($request->input('limit', '') != ''){
        $filter['limit'] = $request->input('limit');
      }

      $q = $request->input('q', '');
      if ($q != '') {
          $filter['q'] = $q;
      }

      if($request->input('username', '') != ''){
        $filter['username'] = $request->input('username');
      }

      if($request->input('order_by', '') != ''){
        $orderBy = $request->input('order_by');
      }else{
        $orderBy = 'id';
      }

      //orderType inc ASC|DESC
      if($request->input('order_type', '') != ''){
        $orderType = $request->input('order_type');
      }else{
        $orderType = 'asc';
      }

      $data = $this->userService->getUserList($filter, false, 0, 10, false, [], [$orderBy => $orderType]);

      return $this->success($data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserRequest $request)
    {
        return $this->userService->storeUser($request);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        $data = $this->userService->getUser(['id' => $user->id],[]);
        return $this->success($data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(UserRequest $request, User $user)
    {
        return $this->userService->updateUser(['id' => $user->id], $request);
    }

    public function trash(User $user)
    {
        return $this->userService->trashUser(['id' => $user->id]);
    }

    public function restore(User $user)
    {
        return $this->userService->restoreUser(['id' => $user->id]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Date  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        return $this->userService->deleteUser(['id' => $user->id]);
    }
}
