<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Manufacturer;
use App\Models\Product;
use App\Models\ProductClass;
use App\Models\ProductStore;
use App\Utils\Util;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ManufacturerController extends Controller
{
    /**
     * All Utils instance.
     *
     */
    protected $commonUtil;
    public function __construct(Util $commonUtil)
    {
        $this->commonUtil = $commonUtil;
    }

    public function index()
    {

        $manufacturers = Manufacturer::all();
        return view('manufacturers.index')->with(compact(
            'manufacturers'
        ));
    }
    public function create()
    {
        $quick_add = request()->quick_add ?? null;
        $type = request()->type ?? null;
        $product_classes = ProductClass::orderBy('name', 'asc')->pluck('name', 'id');

        return view('manufacturers.create')->with(compact(
            'type',
            'quick_add',
            'product_classes'
        ));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $this->validate(
            $request,
            ['name' => ['required', 'max:255']]
        );
        try {
            $data = $request->only('name', 'translations');
            $data['translations'] = !empty($data['translations']) ? $data['translations'] : [];
            DB::beginTransaction();
            $manufacturer = Manufacturer::create($data);
            DB::commit();
            $output = [
                'success' => true,
                'manufacturer_id' => $manufacturer->id,
                'msg' => __('lang.success')
            ];
        } catch (\Exception $e) {
            Log::emergency('File: ' . $e->getFile() . 'Line: ' . $e->getLine() . 'Message: ' . $e->getMessage());
            $output = [
                'success' => false,
                'msg' => __('lang.something_went_wrong')
            ];
        }
        return redirect()->back()->with('status', $output);
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

    public function edit($id)
    {
        $manufacturer = Manufacturer::findOrFail($id);
        $product_classes = ProductClass::orderBy('name', 'asc')->pluck('name', 'id');
        return view('manufacturers.edit')->with(compact(
            'manufacturer',
            'product_classes'
        ));
    }

    public function update(Request $request, $id)
    {
        $this->validate(
            $request,
            ['name' => ['required', 'max:255']]
        );

        try {
            $data = $request->only('name', 'translations');
            $data['translations'] = !empty($data['translations']) ? $data['translations'] : [];
            DB::beginTransaction();
            $manufacturer = Manufacturer::find($id);
            $manufacturer->update([
                "name" => $data["name"],
                "translations" => $data["translations"]
            ]);
            DB::commit();
            $output = [
                'success' => true,
                'msg' => __('lang.success')
            ];
        } catch (\Exception $e) {
            Log::emergency('File: ' . $e->getFile() . 'Line: ' . $e->getLine() . 'Message: ' . $e->getMessage());
            $output = [
                'success' => false,
                'msg' => __('lang.something_went_wrong')
            ];
        }

        return redirect()->back()->with('status', $output);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            Manufacturer::find($id)->delete();
            $output = [
                'success' => true,
                'msg' => __('lang.success')
            ];
        } catch (\Exception $e) {
            Log::emergency('File: ' . $e->getFile() . 'Line: ' . $e->getLine() . 'Message: ' . $e->getMessage());
            $output = [
                'success' => false,
                'msg' => __('lang.something_went_wrong')
            ];
        }

        return $output;
    }
}
