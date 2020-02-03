<?php
    
    namespace App\Http\Controllers;
    
    use Illuminate\Filesystem\Filesystem;

    class Uploaded {
    
        /**
         * Name of the uploaded file.
         *
         * @var string
         */
        protected $name;
    
        /**
         * Type of the uploaded file.
         *
         * @var string
         */
        protected $type;
    
        /**
         * Temporary path of the uploaded file.
         *
         * @var string
         */
        protected $tmp_name;
    
        /**
         * Real path of the uploaded file after storing.
         *
         * @var string
         */
        protected $real_path;
    
        /**
         * Real name of the uploaded file after storing.
         *
         * @var string
         */
        protected $real_name;
    
        /**
         * Size of the uploaded file.
         *
         * @var int
         */
        protected $size;
    
        /**
         * Uploaded file extension.
         *
         * @var string
         */
        protected $extension;
    
        /**
         * Uploaded?
         *
         * @var string
         */
        protected $uploaded = false;
    
        /**
         * Upload path.
         *
         * @var string
         */
        protected $path;

    
        public function __construct($file_params)
        {
            foreach ($file_params as $k => $v) {
                $this->$k = $v;
            }
        
            $this->initialize();
        }
        
        private function initialize()
        {
            $this->path = app()->basePath() . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . "upload";
            $this->extension = explode('.', $this->name)[count(explode('.', $this->name)) - 1];
        }
    
        public function store()
        {
            $file_name = uniqid() . '.' . $this->extension;
            $file_path = $this->path . DIRECTORY_SEPARATOR . $file_name;
            $file_system = new Filesystem();
        
            if (file_put_contents($file_path, $file_system->get($this->tmp_name)) != false) {
                $this->real_name = $file_name;
                $this->real_path = $file_path;
                $this->uploaded = true;
            
                return $this->real_name;
            } else {
                return false;
            }
        }
    
    }
