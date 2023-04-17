<?php

namespace App\Http\Controllers\Api;

use App\Models\Date;
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
      if($request->input('limit', '') != ''){
        $filter['limit'] = $request->input('limit');
      }

      $data['data'] = $this->dateService->getDateList($filter, 10, false, [], ['id'=>'ASC']);

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
    public function store(Request $request)
    {
     
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Date  $date
     * @return \Illuminate\Http\Response
     */
    public function show(Date $date)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Date  $date
     * @return \Illuminate\Http\Response
     */
    public function edit(Date $date)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Date  $date
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Date $date)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Date  $date
     * @return \Illuminate\Http\Response
     */
    public function destroy(Date $date)
    {
        //
    }
}
