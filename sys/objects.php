<?php
	
	//macht ein neues objekt namens app, sollte selbsterklärend sein
	class App{
		public $name;
		public $full_name;
		public $url;
		public $user;
		public $icon;
		public $left;
		public $top;
		public $window_left;
		public $window_top;
		public $window_on;
		public $window_width;
		public $window_height;
		
		function __construct($name, $full_name, $url, $user, $icon){
			$this->name = $name;
			$this->full_name = $full_name;
			$this->url = $url;
			$this->user = $user;
			$this->icon = $icon;
			
			$ui = new UI();
			$db = new DB();
			
			$apps = unserialize($db->get_saveapp()["app_save"]);
			
			$this->left = $apps->$name->left;
			$this->top = $apps->$name->top;
			
			$this->window_left = $apps->$name->window_left;
			$this->window_top = $apps->$name->window_top;
			$this->window_on = $apps->$name->window_on;
			$this->window_width = $apps->$name->window_width;
			$this->window_height = $apps->$name->window_height;
			$this->window_active = $apps->$name->window_active;
		}
		
	}
	
	class User{
		public $name;
		public $full_name;
		public $pwd;
		public $app_save;
		
		function __construct($name, $full_name, $pwd, $app_save){
			$this->name = $name;
			$this->full_name = $full_name;
			$this->pwd = $pwd;
			$this->app_save = $app_save;
		}
	}

?>