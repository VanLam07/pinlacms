<?php

namespace Front\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Media;
use App\Models\Tax;
use Admin\Facades\AdConst;
use App\Exceptions\PlException;

class AlbumController extends Controller
{
    public function view($slug, $id)
    {
        $album = Tax::findByLang($id);
        if (!$album) {
            abort(404);
        }
        $collectMedias = $album->medias()->paginate(AdConst::PER_PAGE);
        return view('front::album.view', compact('album', 'collectMedias'));
    }
}
