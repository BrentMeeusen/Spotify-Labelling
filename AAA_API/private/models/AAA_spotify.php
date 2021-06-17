<?php


class Spotify {

}










interface SpotifyCollection {

	public function getData() : array;

	public static function create(array $data) : SpotifyCollection;

}

?>