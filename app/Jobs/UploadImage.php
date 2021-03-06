<?php

namespace App\Jobs;

use Image;
use File;
use App\Models\Design;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class UploadImage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $design;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Design $design)
    {
        $this->design = $design;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $disk = $this->design->disk;
        $filename = $this->design->image;
        $original_file = storage_path() . '/uploads/original/'. $filename;

        try{
            //  Create the large image and save to tmp disc
            //
            Image::make($original_file)
                ->fit(800, 600, function($constraint){
                    $constraint->aspectRatio();
                })
                ->save($large = storage_path('uploads/large/'. $filename));

            //  Create the thumbnail image and save to tmp disc
            //
            Image::make($original_file)
                ->fit(250, 250, function($constraint){
                    $constraint->aspectRatio();
                })
                ->save($thumbnail = storage_path('uploads/thumbnail/'. $filename));
            
            //  Store original image to permanent disc
            //
            if(Storage::disk($disk)
                ->put('uploads/designs/original/'.$filename, fopen($original_file, 'r+'))){
                    File::delete($original_file);
                }

            //  Store large image to permanent disc
            //
            if(Storage::disk($disk)
                ->put('uploads/designs/large/'.$filename, fopen($large, 'r+'))){
                    File::delete($large);
                }

            //  Store thumbnail image to permanent disc
            //
            if(Storage::disk($disk)
                ->put('uploads/designs/thumbnail/'.$filename, fopen($thumbnail, 'r+'))){
                    File::delete($thumbnail);
                }
            
            //  Update the database record with success flag
            //
            $this->design->update([
                'upload_successful' => true
            ]);

        } catch(\Exception $e){
            \Log::error($e->getMessage());
        }

    }
}
