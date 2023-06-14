<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductClass;
use App\Models\ProductStore;
use App\Utils\Util;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ProductClassController extends Controller
{

    /**
     * All Utils instance.
     *
     */
    protected $commonUtil;

    /**
     * Constructor
     *
     * @param ProductUtils $product
     * @return void
     */
    public function __construct(Util $commonUtil)
    {
        $this->commonUtil = $commonUtil;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $product_classes = ProductClass::withCount('products')->orderBy('sort', 'asc')->get();

        return view('product_class.index')->with(compact(
            'product_classes'
        ));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $quick_add = request()->quick_add ?? null;

        return view('product_class.create')->with(compact(
            'quick_add'
        ));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'max:255', 'unique:product_classes,name'],
            'status' => ['required', 'boolean'],
        ]);
        if ($validator->fails()) {
            if ($request->ajax()) {
                return response()->json(array(
                    'success' => false,
                    'message' => 'There are incorect values in the form!',
                    'msg' => $validator->getMessageBag()->first()
                ));
            }
        }
        try {
            $data = $request->except('_token', 'quick_add');
            $data['translations'] = !empty($data['translations']) ? $data['translations'] : [];
            $data['status'] = !empty($data['status']) ? 1 : 0;

            $class = ProductClass::create($data);


            if ($request->has("cropImages") && count($request->cropImages) > 0) {
                foreach ($this->getCroppedImages($request->cropImages) as $imageData) {
                    $class->clearMediaCollection('product_class');
                    $extention = explode(";", explode("/", $imageData)[1])[0];
                    $image = rand(1, 1500) . "_image." . $extention;
                    $filePath = public_path('uploads/' . $image);
                    $fp = file_put_contents($filePath, base64_decode(explode(",", $imageData)[1]));
                    $class->addMedia($filePath)->toMediaCollection('product_class');
                }
            }

            $output = [
                'success' => true,
                'id' => $class->id,
                'msg' => __('lang.success')
            ];
        } catch (\Exception $e) {
            Log::emergency('File: ' . $e->getFile() . 'Line: ' . $e->getLine() . 'Message: ' . $e->getMessage());
            $output = [
                'success' => false,
                'msg' => __('lang.something_went_wrong')
            ];
        }


        if ($request->quick_add) {
            return $output;
        }

        return redirect()->back()->with('status', $output);
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $product_class = ProductClass::find($id);

        return view('product_class.edit')->with(compact(
            'product_class'
        ));
    }

    public function update(Request $request, $id)
    {
        $this->validate(
            $request,
            ['name' => ['required', 'max:255']],
        );

        try {
            $data = $request->only('name', 'description', 'sort', 'translations', 'status');
            $data['translations'] = !empty($data['translations']) ? $data['translations'] : [];
            $data['status'] = !empty($data['status']) ? 1 : 0;
            $class = ProductClass::where('id', $id)->first();
            $class->update($data);

//            if ($request->has('uploaded_image_name')) {
//                if (!empty($request->input('uploaded_image_name'))) {
//                    $class->clearMediaCollection('product_class');
//                    $class->addMediaFromDisk($request->input('uploaded_image_name'), 'temp')->toMediaCollection('product_class');
//                }
//            }
            if ($request->has("cropImages") && count($request->cropImages) > 0) {
                foreach ($this->getCroppedImages($request->cropImages) as $imageData) {
                    $class->clearMediaCollection('product_class');
                    $extention = explode(";", explode("/", $imageData)[1])[0];
                    $image = rand(1, 1500) . "_image." . $extention;
                    $filePath = public_path('uploads/' . $image);
                    $fp = file_put_contents($filePath, base64_decode(explode(",", $imageData)[1]));
                    $class->addMedia($filePath)->toMediaCollection('product_class');
                }
            }
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
            if (request()->source == 'pct') {
                ProductClass::find($id)->delete();
                $categories =  Category::where('product_class_id', $id)->get();
                foreach ($categories as $category) {
                    Category::where('parent_id', $category->id)->delete();
                    $products = Product::where('category_id', $category->id)->orWhere('sub_category_id', $category->id)->get();
                    foreach ($products as $product) {
                        ProductStore::where('product_id', $product->id)->delete();
                        $product->delete();
                    }
                    $category->delete();
                }
            } else {
                $category = Category::where('product_class_id', $id)->first();

                if (!empty($category)) {
                    $output = [
                        'success' => false,
                        'msg' => __('lang.product_class_has_category')
                    ];

                    return $output;
                } else {
                    ProductClass::find($id)->delete();
                }
            }



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

    public function getDropdown()
    {
        $product_classes = ProductClass::orderBy('name', 'asc')->pluck('name', 'id');
        $product_classes_dp = $this->commonUtil->createDropdownHtml($product_classes, 'Please Select');

        return $product_classes_dp;
    }
    public function getBase64Image($Image)
    {

        $image_path = str_replace(env("APP_URL") . "/", "", $Image);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $image_path);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $image_content = curl_exec($ch);
        curl_close($ch);
//    $image_content = file_get_contents($image_path);
        $base64_image = base64_encode($image_content);
        $b64image = "data:image/jpeg;base64," . $base64_image;
        return $b64image;
    }

    public function getCroppedImages($cropImages)
    {
        $dataNewImages = [];
        foreach ($cropImages as $img) {
            if (strlen($img) < 200) {
                $dataNewImages[] = $this->getBase64Image($img);
            } else {
                $dataNewImages[] = $img;
            }
        }
        return $dataNewImages;
    }
}
