<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Exceptions\InvalidOrderException;
use App\Http\Requests\DateRequest;
use App\Traits\ApiResponser;
use App\Services\DateService;

class DateController extends Controller
{
    use ApiResponser;

    private $dateService;

    public function __construct(
        DateService $dateService
    ){
        $this->dateService = $dateService;
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

      if($request->input('nama', '') != ''){
        $filter['nama'] = $request->input('nama');
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

      $data = $this->dateService->getDateList($filter, false, 0, 10, false, [], [$orderBy => $orderType]);

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
    public function store(DateRequest $request)
    {
        return $this->dateService->storeDate($request);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Date  $date
     * @return \Illuminate\Http\Response
     */
    public function show(Date $date)
    {
        $data = $this->dateService->getDate(['id' => $date->id],[]);
        return $this->success($data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Date  $date
     * @return \Illuminate\Http\Response
     */
    public function edit(Date $date)
    {

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Date  $date
     * @return \Illuminate\Http\Response
     */
    public function update(DateRequest $request, Date $date)
    {
        return $this->dateService->updateDate(['id' => $date->id], $request);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Date  $date
     * @return \Illuminate\Http\Response
     */
    public function destroy(Date $date)
    {
        return $this->dateService->deleteDate(['id' => $date->id]);
    }
}
