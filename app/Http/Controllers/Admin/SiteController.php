<?php

namespace App\Http\Controllers\Admin;

use App\Models\Site;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Cache;

class SiteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $site = Site::orderBy('sort')->get();
        $assign = compact('site');
        return view('admin.site.index', $assign);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.site.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Site $siteModel)
    {
        $data = $request->except('_token');
        $result = $siteModel->storeData($data);
        if ($result) {
            // 更新缓存
            Cache::forget('common:site');
        }
        return redirect('admin/site/index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $site = Site::find($id);
        $assign = compact('site');
        return view('admin.site.edit', $assign);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id, Site $siteModel)
    {
        $map = [
            'id' => $id
        ];
        $data = $request->except('_token');
        $result = $siteModel->updateData($map, $data);
        if ($result) {
            // 更新缓存
            Cache::forget('common:site');
        }
        return redirect()->back();
    }

    /**
     * 排序
     *
     * @param Request $request
     * @param Site    $siteModel
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function sort(Request $request, Site $siteModel)
    {
        $data = $request->except('_token');
        $editData = [];
        foreach ($data as $k => $v) {
            $editData[] = [
                'id' => $k,
                'sort' => $v
            ];
        }
        $result = $siteModel->updateBatch($editData);
        if ($result) {
            // 更新缓存
            Cache::forget('common:site');
        }
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, Site $siteModel)
    {
        $map = [
            'id' => $id
        ];
        $result = $siteModel->destroyData($map);
        if ($result) {
            // 更新缓存
            Cache::forget('common:site');
        }
        return redirect()->back();
    }
}
