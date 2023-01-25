<?php
 
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\NumberTranslationService;
 
class SiteController extends Controller
{
    public $numberTranslationService;

    public function __construct(NumberTranslationService $numberTranslationService)
    {
        $this->numberTranslationService = $numberTranslationService;
    }

    public function numberTranslation(Request $request)
    {
        return $this->numberTranslationService->numberTranslation($request);
    }

    public function wordTranslation(Request $request)
    {
        return $this->numberTranslationService->wordTranslation($request);
    }

    public function converToUSD(Request $request)
    {
        return $this->numberTranslationService->converToUSD($request);
    }

}