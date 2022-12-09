<?php

namespace App\Http\Controllers;

use App\Http\Requests\Video\IndexRequest;
use App\Http\Requests\Video\PlayRequest;
use App\Http\Resources\Video\VideoCollection;
use Illuminate\Http\Request;
use Iman\Streamer\VideoStreamer;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\StreamedResponse;

class VideoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(IndexRequest $request): VideoCollection
    {
        $path = $request->validated()['path'];

        $items = $this->getItems($path);

        return new VideoCollection($items);
    }

    public function getItems(string $path): array
    {
        $dirElements = scandir($path);

        $items = [];

        foreach ($dirElements as $dir) {
            if ($dir != '..' && $dir != '.') {
                if (!is_dir($path . '\\' . $dir)) {
                    $info = pathinfo($path . '\\' . $dir);

                    $items[] = $path . '\\' . $info['basename'];
                }
            }
        }

        return $items;
    }

    public function play(PlayRequest $request)
    {
        $path = $request->validated()['path'];

//        return new BinaryFileResponse($path);

//        return response()->download($path, basename($path));

        VideoStreamer::streamFile($path);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
