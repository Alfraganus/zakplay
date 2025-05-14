<?php
namespace App\Modules\test\controllers;

use App\Helpers\MultiLanguageModelService;
use App\Http\Controllers\Controller;
use App\Modules\test\models\Ads;
use App\Modules\test\models\RoadmapTest;
use App\Modules\test\repository\RoadmapTestRepository;
use App\Modules\test\service\RoadmapTestCreateService;
use App\Modules\test\service\RoadmapTestSubmitService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdController extends Controller
{
    protected $testService;
    protected $testSubmitService;
    protected $roadmapTestRepo;

    public function __construct(
        RoadmapTestCreateService $testService,
        RoadmapTestRepository $roadmapTestRepository,
        RoadmapTestSubmitService $testSubmitService
    )
    {
        $this->testService = $testService;
        $this->roadmapTestRepo = $roadmapTestRepository;
        $this->testSubmitService = $testSubmitService;
    }

    public function upsertAds(Request $request)
    {
        $model = $request->input('id') ? Ads::find($request->input('id')) : new Ads();

        $model->fill($request->all());

        $model->save();

        $adList = [];

        foreach (['uz', 'ru'] as $lang) {
            $inputName = "ad_$lang";

            if ($request->hasFile($inputName)) {
                $file = $request->file($inputName);
                $filename = uniqid() . '.' . $file->getClientOriginalExtension();
                $file->storeAs('media/ads', $filename, 'public'); // stored in storage/app/public/media/ads
                $adList[$lang] = "media/ads/$filename";
            } elseif (is_string($request->input($inputName))) {
                $adList[$lang] = $request->input($inputName);
            }
        }

        if (!empty($adList)) {
            $model->ad_list = $adList;
            $model->save();
        }

        return response()->json([
            'success' => true,
            'data' => $model
        ]);
    }

    public function findSingleAd($id)
    {
        $ad = Ads::find($id);

        if (!$ad) {
            return response()->json([
                'success' => false,
                'message' => 'Ad not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $ad
        ]);
    }

    public function findAllAds()
    {
        $ads = Ads::all();

        return response()->json([
            'success' => true,
            'data' => $ads
        ]);
    }

    public function deleteAd($id)
    {
        $ad = Ads::find($id);

        if (!$ad) {
            return response()->json([
                'success' => false,
                'message' => 'Ad not found'
            ], 404);
        }

        $ad->delete();

        return response()->json([
            'success' => true,
            'message' => 'Ad deleted successfully'
        ]);
    }

}
