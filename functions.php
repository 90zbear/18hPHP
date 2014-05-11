<?php
function youtube($key = NULL, $type = NULL){
	$search = str_replace(' ','+',$key);
	$url = "http://gdata.youtube.com/feeds/api/videos?orderby=relevance&format=5&max-results=1&v=2&alt=jsonc&q=".$search;
	$json = file_get_contents($url);
	$output = json_decode($json);
	switch ($type) {
		case 'movie':
			$id = $output->data->items[0]->id;
			print ('<iframe width="560" height="315" src="//www.youtube.com/embed/'.$id.'" frameborder="0" allowfullscreen></iframe>');
			break;

		case 'image':
			$thumb = $output->data->items[0]->thumbnail->sqDefault;
			print ('<img src="'.$thumb.'"">');
			break;

		default:

			break;
	}
}
?>