<?php

namespace App\Application\PrivateClasses;

use App\Application\Application;
use Carbon\Carbon;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class UploadFiles
{
    private array $disks;
    private string $pathFileName;

    public function __construct()
    {
        $this->disks = [
            "user" => "Uploads/Users",
            "article" => "Uploads/Articles",
            "category" => "Uploads/Categories",
            "any" => "Uploads/Any"
        ];
    }

    public function DefaultPhotoPath():string{
        return "Uploads/Users/Default-Photo.png";
    }

    /**
     * @param $file
     * @param string $diskType
     * @param string $dir
     * @return string
     */
    public function Upload($file, string $diskType, string $dir = ""):string
    {
        $TempName = time().$file->getClientOriginalName();
        if(array_key_exists($diskType,$this->disks)){
           $TempName =  $file->storeAs($dir,$TempName,[
                "disk" => $diskType
            ]);
            $TempName = $this->disks[$diskType] ."/".$TempName;
        }else{
            $TempName = $file->storeAs($dir,$TempName,[
                "disk" => "any"
            ]);
            $TempName = $this->disks['any'] ."/".$TempName;
        }
        $this->pathFileName = $TempName;
        return $TempName;
    }

    public function DeleteFile(string $path){
        if ($this->DefaultPhotoPath()!==$path){
            if (file_exists(public_path($path))==true){
                unlink(public_path($path));
            }
        }
    }

    public function rollBackUpload(){
        if (!is_null($this->pathFileName)){
            $this->DeleteFile($this->pathFileName);
        }
        $this->pathFileName = null;
    }
}
