<?php class MainTemplater {
    private $template;
    function __construct($template = null){if(isset($template)){$this->load($template);}}
	public function load($template){if(!is_file($template)){echo "file not found";}elseif(!is_readable($template)){throw new IOException("Could not access file: $template");}else{$this->template=$template;}}
	public function set($var, $content){$this->$var=$content;}
	public function get($var, $key){$var=$this->$key;}
	public function publish($output = true){ob_start();require $this->template;$content=ob_get_clean();print $content;}
	public function parse(){ob_start();require $this->template;$content=ob_get_clean();return $content;}	
} ?>