<?php

namespace App\Http\Controllers;
use Illuminate\Support\Str;

use Illuminate\Http\Request;
use App\Http\Controllers\BaseController as BaseController;
use App\Models\ShortLink;
use Validator;
use App\Http\Resources\ShortLink as ShortLinkResource;

class ShortLinkController extends BaseController
{
    public function index()
    {
        $shortlinks = ShortLink::all();
        return $this->sendResponse(ShortLinkResource::collection($shortlinks), 'Short links retrieved successfully.')->setStatusCode(200);
    }

    public function store(Request $request)
    {

        $request->validate([
            'url' => 'required|url'
        ]);

        $find = ShortLink::where('link', $request->input('url'))->first();
        if ($find) {
            return $this->sendResponse(new ShortLinkResource($find), 'Short links generted successfully.')->setStatusCode(200);
        } else {
            $input['link'] = $request->input('url');
            $input['code'] = Str::random(6);
        
            $shortlink = ShortLink::create($input);
            $shortlink = $shortlink->fresh();
            return $this->sendResponse(new ShortLinkResource($shortlink), 'Short links generted successfully.')->setStatusCode(201);
        }
        
    }

    public function shortenLink($code)
    {
        $find = ShortLink::where('code', $code)->first();
   
        return redirect($find->link);
    }

    //$deletedRows = Flight::where('active', 0)->delete();

    public function destroy(Request $request)
    {
        $request->validate([
            'url' => 'required|url'
        ]);

        $code = explode('/', $request->input('url'));
        $code = end($code);
        $deletedRows = ShortLink::where('code', $code)->delete();

        if($deletedRows) {
            return $this->sendResponse('', 'URL deleted')->setStatusCode(200);
        } else {
            return $this->sendError('URL not found')->setStatusCode(404);
        }
    }
}
