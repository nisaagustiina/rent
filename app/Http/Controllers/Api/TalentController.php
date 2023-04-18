<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Exceptions\InvalidOrderException;
use App\Http\Requests\TalentRequest;
use App\Traits\ApiResponser;
use App\Services\TalentService;

class TalentController extends Controller
{
    use ApiResponser;

    private $talentService;

    public function __construct(
        TalentService $talentService
    ){
        $this->talentService = $talentService;
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

      if($request->input('nama_alias', '') != ''){
        $filter['nama_alias'] = $request->input('nama_alias');
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

      $data = $this->talentService->getTalentList($filter, false, 0, 10, false, [], [$orderBy => $orderType]);

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
    public function store(TalentRequest $request)
    {
        return $this->talentService->storeTalent($request);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Talent  $talent
     * @return \Illuminate\Http\Response
     */
    public function show(Talent $talent)
    {
        $data = $this->talentService->getTalent(['id' => $talent->id],[]);
        return $this->success($data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Talent  $talent
     * @return \Illuminate\Http\Response
     */
    public function edit(Talent $talent)
    {

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Talent  $talent
     * @return \Illuminate\Http\Response
     */
    public function update(TalentRequest $request, Talent $talent)
    {
        return $this->talentService->updateTalent(['id' => $talent->id], $request);
    }

    public function trash(Talent $talent)
    {
        return $this->talentService->trashTalent(['id' => $talent->id]);
    }

    public function restore(Talent $talent)
    {
        return $this->talentService->restoreTalent(['id' => $talent->id]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Date  $talent
     * @return \Illuminate\Http\Response
     */
    public function destroy(Talent $talent)
    {
        return $this->talentService->deleteTalent(['id' => $talent->id]);
    }
}
