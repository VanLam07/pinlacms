<?php

namespace Front\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Media;
use App\Models\Tax;
use App\Exceptions\PlException;

class AlbumController extends Controller
{
    public function view($id, $slug = null)
    {
        $album = Tax::findByLang($id);
        if (!$album) {
            abort(404);
        }
        $collectMedias = Media::getData([
            'albums' => [$id]
        ]);
        return view('front::album.view', compact('album', 'collectMedias'));
    }
}
