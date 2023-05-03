<?php class Templater extends MainTemplater {
    private $template;
    function __construct($template = null) {parent::__construct();foreach ($GLOBALS as $key => $values) { $this->$key = $values; }if (isset($template)) { $this->load($template); }}
    public function load($template) {if (!is_file($template)) {$var =  $template; $var .=  "<br />"; $var .=  "file not found"; }elseif (!is_readable($template)) { throw new IOException("Could not access file: $template"); }else { $this->template = $template; }}
    public function set($var, $content) { $this->$var = $content; }
    public function get($var, $key) { $var = $this->$key; }
    public function publish($output = true){ob_start();include $this->template;$content=ob_get_clean();print $content;}
    public function parse() {ob_start();include $this->template;$content = ob_get_clean();return $content;}
} ?>