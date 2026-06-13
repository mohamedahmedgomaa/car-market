<?php

namespace App\Http\Modules\Cities\Controllers;

use Gomaa\Base\Base\Controllers\BaseApiController;
use App\Http\Modules\Cities\Requests\CreateCityRequest;
use App\Http\Modules\Cities\Requests\DeleteCityRequest;
use App\Http\Modules\Cities\Requests\ListCityRequest;
use App\Http\Modules\Cities\Requests\ShowCityRequest;
use App\Http\Modules\Cities\Requests\UpdateCityRequest;
use App\Http\Modules\Cities\Services\CityService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Http\Modules\Cities\Models\City;
use Illuminate\Http\JsonResponse;

class CityController extends BaseApiController
{

    /**
     * @param CityService $service
     */
    public function __construct(CityService $service)
    {
        parent::__construct($service,[
            'index' => ListCityRequest::class,
            'show' => ShowCityRequest::class,
            'store' => CreateCityRequest::class,
            'update' => UpdateCityRequest::class,
            'destroy' => DeleteCityRequest::class,
        ]);
    }

    public function bulkStore(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'country_id' => 'required|exists:countries,id',
            'governorate_id' => 'nullable|exists:governorates,id',
            'cities' => 'required|array|min:1',
            'cities.*.name_ar' => 'required|string',
            'cities.*.name_en' => 'required|string',
            'cities.*.governorate_id' => 'required_without:governorate_id|exists:governorates,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        $countryId = $request->input('country_id');
        $topGovernorateId = $request->input('governorate_id');
        $citiesData = $request->input('cities');

        $createdCities = [];

        DB::transaction(function () use ($countryId, $topGovernorateId, $citiesData, &$createdCities) {
            foreach ($citiesData as $cityData) {
                $govId = $cityData['governorate_id'] ?? $topGovernorateId;
                $city = City::create([
                    'country_id' => $countryId,
                    'governorate_id' => $govId,
                    'name' => [
                        'en' => trim($cityData['name_en']),
                        'ar' => trim($cityData['name_ar']),
                    ]
                ]);
                $createdCities[] = $city;
            }
        });

        return response()->json([
            'message' => 'Cities created successfully!',
            'data' => $createdCities,
        ], 201);
    }

}

