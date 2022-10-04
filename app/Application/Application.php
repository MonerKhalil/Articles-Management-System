<?php

namespace App\Application;

use App\Application\DB\OrderByData;
use App\Application\PrivateClasses\HandleJson;
use App\Application\PrivateClasses\UploadFiles;

class Application
{
    use OrderByData;
    private static $app;
    private $handleJson;
    private $uploadFiles;

    private function __construct(){
        static::$app = $this;
    }

    /**
     * @return Application
     */
    public static function getApp() : Application{
        if (is_null(static::$app)){
            static::$app = new static();
        }
        return static::$app;
    }

    /**
     * @return HandleJson
     */
    public function getHandleJson(): HandleJson
    {
        if (is_null($this->handleJson)){
            $this->handleJson = new HandleJson();
        }
        return $this->handleJson;
    }

    /**
     * @return UploadFiles
     */
    public function getUploadFiles():UploadFiles{
        if (is_null($this->uploadFiles)){
            $this->uploadFiles = new UploadFiles();
        }
        return $this->uploadFiles;
    }

    /**
     * @return string
     */
    public function getLang(): string
    {
        return app()->getLocale();
    }

    public function getErrorMessages(){
        if($this->getLang() === "en"){
            return require __DIR__."/Errors/"."en.php";
        }else{
            return require __DIR__."/Errors/"."ar.php";
        }
    }

    public function isOnlineInternet(): bool
    {
        if(@fopen("https://www.google.com","r")){
            return true;
        }
        return false;
    }
}
