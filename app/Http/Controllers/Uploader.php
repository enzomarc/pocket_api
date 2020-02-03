<?php
    
    namespace App\Http\Controllers;

    use App\Http\Controllers\Uploaded;
    
    
    class Uploader {
    
        /**
         * Files list.
         *
         * @var array
         */
        protected static $files = [];


        /**
         * Get uploaded files.
         *
         * @return array
         */
        public static function files()
        {
            foreach ($_FILES as $file) {
                self::$files[] = new Uploaded($file);
            }
            
            return self::$files;
        }

        /**
         * Get uploaded file.
         *
         * @param $name
         * @return \App\Http\Controllers\Uploaded|bool
         */
        public static function file($name)
        {
            $files = $_FILES;
    
            if (array_key_exists($name, $files))
                return new Uploaded($files[$name]);
    
            return false;
        }

        /**
         * Get file URL.
         *
         * @param $file
         * @return bool|string
         */
        public static function url($file)
        {
            if ($file == null || $file == "") return false;

            $path = app()->basePath() . DIRECTORY_SEPARATOR . "public" . DIRECTORY_SEPARATOR . "upload" . DIRECTORY_SEPARATOR . $file;
            
            if (file_exists($path))
                return "/upload/" . $file;
            
            return false;
        }
        
    }
