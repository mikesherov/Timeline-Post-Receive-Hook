<?php

/**
 * Contains utilities for open graph objects
 * @author Mike Sherov @mikesherov
 * @name OpenGraphObject
 */

class OpenGraphObject {

	/**
	 * returns html metadata describing an open graph object
	 * @param array $data
	 * @return string
	 */
	public static function markup(array $data) {
		$answer = '<head prefix="og: http://ogp.me/ns# fb: http://ogp.me/ns/fb# post_receive_hook: http://ogp.me/ns/fb/post_receive_hook#"><meta property="fb:app_id" content="' . AppInfo::FACEBOOK_APP_ID . '"> ';
		foreach ( $data as $key => $val ) {
			$answer .= '<meta property="' . $key . '" content="' . htmlentities ( $val ) . '">' . PHP_EOL;
		}
		return $answer;
	}
}