<?php

function siy_build_uri($atts) {
	$app       = $atts['app'];
	$params    = $atts;
	$append    = $atts['append'];
	$page      = $atts['page'];
	$keywords  = $atts['keywords'];
	$size      = $atts['size'];

	$uri = build_uri($app, $params, $append, $page, $keywords, $size);

	return $uri;
}
