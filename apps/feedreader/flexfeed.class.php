<?php

class Post{
	public $title;
	public $content;
	public $link;
	public $published;
	public $id;

	public function __construct($a, $b, $c, $d, $e){
		$this->title = $a;
		$this->content = $b;
		$this->link = $c;
		$this->published = $d;
		$this->id = $e;
	}
}

class flexfeed{

	/**
	 * Variables to be returned
	 */
	public $title;
	public $icon;
	public $updated;
	public $link;
	public $description;
	public $posts;
	public $error;

	public function __construct($url){

		/**
		 * Load SimpleXML & tell him to shut up on Errors
		 * @param feed URL
		 */
		libxml_use_internal_errors(true);
		$feed = simplexml_load_file($url);

		if(!$feed){
			$this->error = "Invalid URL";
			return;
		}

		/**
		 * Check feed Type
		 */
		if (strcasecmp($feed->getName(), "feed") == 0){
			/**
			 * ATOM
			 */
			$this->parseAtom($feed);
		}else if(strcasecmp($feed->getName(), "rss") == 0){
			/**
			 * RSS
			 */
			$this->parseRss($feed);
		}else if(strcasecmp($feed->getName(), "rdf") == 0){
			/**
			 * RDF
			 */
			$this->parseRdf($feed);
		}else if(strcasecmp($feed->getName(), "html") == 0){
			/**
			 * HTML
			 */
			$this->error = "Thats HTML, dummie!";
		}else{
			$this->error = "Invalid or Unsupported feed type.";
		}
	}

	/**
	 * check if post is empty
	 */
	private function checkpost($post){
		if(!$post){
			$post = "This entry has no content.";
		}
		return $post;
	}

	/**
	 * Dirty Function for stripping tags, but keeping line breaks
	 * @param $string string to strip
	 * @return stripped string
	 */
	private function strip($string){
		$ret = htmlspecialchars_decode(trim($string));
		$ret = preg_replace("/<\/? ?(a|br|div|em|h1|h2|h3|h4|h5).*?>/i","", $ret);
		$ret = preg_replace("/<\/? ?(p).*?>/i","!BREAK!", $ret);
		$ret = preg_replace("/(style|height|width|id|class)=\".*?\"/i","", $ret);
		$ret = preg_replace("/<(iframe|video|frame|frameset|script|p|br).*?\/(iframe|video|frame|frameset|script|p|br)>/i","", $ret);
		if(substr($ret, 0, 7) == "!BREAK!"){
			$ret = substr($ret, 7);
		}
		return str_ireplace("!BREAK!", "<br>", $ret);
	}

	/**
	 * Parse Atom feed
	 * @param $feed SimpleXMLObject
	 */
	private function parseAtom($feed){
		$this->title = $feed->title;
		$this->description = $feed->subtitle;
		$this->icon = $feed->icon;
		$this->updated = $feed->updated;
		$this->link = $feed->link->attributes()->href;
		$this->posts = array();

		$i = 0;
		foreach ($feed->entry as $key => $post){
			$this->posts[$i] = new Post($post->title, $this->strip($this->checkpost($post->content)), $post->link->attributes()->href, $post->published, uniqid());
			$i++;
		}
		unset($i);
	}

	/**
	 * Parse RSS feed
	 * @param $feed SimpleXMLObject
	 */

	private function parseRss($feed){
		$this->title = $feed->channel->title;
		$this->description = $feed->channel->description;
		$this->icon = $feed->channel->image->url;
		$this->updated = $feed->channel->pubDate;
		$this->link = $feed->channel->link;
		$this->posts = array();

		$i = 0;
		foreach ($feed->channel->item as $key => $post){
			$this->posts[$i] = new Post($post->title, $this->strip($this->checkpost($post->description)), $post->link, $post->pubDate, uniqid());
			$i++;
		}
		unset($i);
	}

	/**
	 * Parse RDF feed
	 * @param $feed SimpleXMLObject
	 */
	private function parseRdf($feed){
		$this->title = $feed->channel->title;
		$this->description = $feed->channel->description;
		$this->icon = $feed->image->url;
		$this->updated = $feed->channel->pubDate;
		$this->link = $feed->channel->link;
		$this->posts = array();

		$i = 0;
		foreach ($feed->item as $key => $post){
			$this->posts[$i] = new Post($post->title, $this->strip($this->checkpost($post->description)), $post->link, $post->date, uniqid());
			$i++;
		}
		unset($i);
	}

}
