<?php

namespace App\Http\Controllers\Designs;

use App\Jobs\UploadImage;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\IDesign;

class UploadController extends Controller
{
    protected $designs;


    public function __construct(IDesign $designs)
    {
        $this->designs = $designs;
    }


    public function upload(Request $request)
    {
        //  Validate the request
        //
        $this->validate($request, [
            'image' => ['required', 'mimes:jpeg,gif,bmp,png', 'max:5300']
        ]);

        //  Get the image
        //
        $image = $request->file('image');
        $image_path = $image->getPathName();

        //  Get the original file name and replace any spaces with underscores
        //  Append a timestamp to the end of each file name to avoid duplicate file names
        //
        $filename = time()."_".preg_replace('/\s+/', '_', strtolower($image->getClientOriginalName()));
    
        //  Move the image to the temporary location (tmp) which is the 
        //  extra local disc created in the directory   config/filesystemss.php 
        //
        $tmp = $image->storeAs('uploads/original', $filename, 'tmp');

        //  Create the database record for the design
        //
        //  $design = auth()->user()->designs()->create([
        //      'image' => $filename,
        //      'disk' => config('site.upload_disk')
        //  ]);

        $design = $this->designs->create([
            'user_id' => auth()->id(),
            'image' => $filename,
            'disk' => config('site.upload_disk')
        ]);

        
        //  Dispatch a job to handle the image manipulation
        //
        $this->dispatch(new UploadImage($design));

        return response()->json($design, 200);
    
    }

}
