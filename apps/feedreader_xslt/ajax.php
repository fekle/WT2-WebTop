<?php
	error_reporting(E_ALL);
	ini_set('display_errors', '1');
	session_start();

	$rss_url = $_GET["url"];
	$_SESSION["url"] = $rss_url;

	libxml_use_internal_errors(true);
	$feed = simplexml_load_file($rss_url);

	if(!$feed){
		$rss_url="http://www.theverge.com/rss/frontpage";
	}

	$xslDoc = new DOMDocument();
	$xslDoc->load("atom.xsl");

	$xmlDoc = new DOMDocument();
	$xmlDoc->load("$rss_url");

	$proc = new XSLTProcessor();
	$proc->setParameter("", "rss_url", $rss_url);
	$proc->importStylesheet($xslDoc);
	echo $proc->transformToXML($xmlDoc);

?>
