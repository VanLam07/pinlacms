<?php

namespace Front\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Tax;
use Admin\Facades\AdConst;

class AlbumController extends Controller
{
    public function view($slug, $id)
    {
        $album = Tax::findByLang($id);
        if (!$album || $album->status != AdConst::STT_PUBLISH) {
            abort(404);
        }
        $collectMedias = $album->medias()->paginate(AdConst::PER_PAGE);
        return view('front::album.view', compact('album', 'collectMedias'));
    }
}
