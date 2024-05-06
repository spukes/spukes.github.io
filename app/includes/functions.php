<?php

include_once 'Parsedown.php';
include_once 'Gemtext.php';

//Set your current timezone
date_default_timezone_set('Europe/Berlin');

/* General Blog Functions */

function get_post_names(){

	static $_cache = array();

	if(empty($_cache)){

		$_cache = array_reverse(glob('posts/*.{md,gmi}', GLOB_BRACE));
	}

	return $_cache;
}

function get_page_names(){

	static $_cachepages = array();

	if(empty($_cachepages)) {
		$_cachepages = array_reverse(glob('static/*.{md,gmi}', GLOB_BRACE));

	}
	return $_cachepages;

}

function get_posts($page = 1, $perpage = 0){

	if($perpage == 0){
		$perpage = config('posts.perpage');
	}

	$posts = get_post_names();

	// Extract a specific page with results
	$posts = array_slice($posts, ($page-1) * $perpage, $perpage);

	$tmp = array();
	

	// Create a new instance of the markdown parser
	$md = new Parsedown();
	$md->setBreaksEnabled(true);
	
	foreach($posts as $k=>$v){
		
		$post = new stdClass;
		
		// Extract the date
		$arr = explode('_', $v);
		//var_dump($arr);
		$ext = end(explode('.',$arr[1]));
		//var_dump($ext);
		$post->date = strtotime(str_replace('posts/','',$arr[0]));

		// The post URL
		if ($ext == 'md'){
			$post->url = site_url().date('Y/m', $post->date).'/'.str_replace('.md','',$arr[1]);
			// Get the contents and convert it to HTML
			$content = $md->text(file_get_contents($v));
			// Extract the title and body
			$arr = explode('</h1>', $content);
			$post->title = str_replace('<h1>','',$arr[0]);
			$post->body = $arr[1];

			$tagblock = explode('<!--tags:',$content);
			$tagtags = '/<!--tags: (.*?)-->/s';
			preg_match($tagtags, $content, $matches);
			$tags = $matches[1];
			$post->tags = explode(", ", $tags);

			//Excerpt: finds the <!--more--> tag
			$post->excerpt = substr($post->body, 0, strpos($post->body, '<!--more-->'));
		
		} else if ($ext == 'gmi'){
			//In case content is Gemini:
			$post->url = site_url().date('Y/m', $post->date).'/'.str_replace('.gmi','',$arr[1]);
			
			$content = gemtext_to_html(file_get_contents($v));
			
			$post->title = $content['title'];
			$post->body = end(explode('</h1>', $content['body'])); 

			//Excerpt : 420 first characters
			$post->excerpt = mb_strimwidth($post->body, 0, 420, '...') .'<br>';

			// Mostrar tags y reemplazar
			$regex = '/#(?!#|\s)(\w+)/'; // Expresión regular para buscar strings que comiencen por "#" seguido de caracteres alfanuméricos, pero no seguido de "#" ni espacio en blanco.	
			preg_match_all($regex, $post->excerpt, $matches); // Buscar todas las coincidencias del principio (420 caracteres) y guardarlas en el array $matches.
			$post->tags = $matches[1]; // Obtener el array con los strings encontrados.
			
			//Limpiando el contenido de tags
	
			if($post->tags) {
				foreach ($post->tags as $tag) {
					$post->body = str_replace('#'.$tag, '', $post->body); //Limpiamos el body
					$post->excerpt = mb_strimwidth($post->body, 0, 420, '...') .'<br>'; //limpiamos el excerpt
				}
			} else {

				//do nothing
				//$post->body = end(explode('</h1>', $content['body'])); 
			}

			
			

			
		
		} else {
			// do nothing
		}

		$tmp[] = $post;
	}

	return $tmp;
}

// Get pages
function get_pages(){
	$pages = get_page_names();

	$tmp = array();

	// Create a new instance of the markdown parser
	$md = new Parsedown();
	$md->setBreaksEnabled(true);

	foreach($pages as $k=>$v){

		$page = new stdClass;

		// The post URL
		$page->url = site_url().str_replace('.md','',$v);

		// Get the contents and convert it to HTML
		$content = $md->text(file_get_contents($v));

		// Extract the title and body
		$arr = explode('</h1>', $content);
		$page->title = str_replace('<h1>','',$arr[0]);
		$page->body = $arr[1];

		$tmp[] = $page;
	}

	return $tmp;
}

// Find post by year, month and name
function find_post($year, $month, $name){

	foreach(get_post_names() as $index => $v){
		if( strpos($v, "$year-$month") !== false && (strpos($v, $name.'.md')||strpos($v, $name.'.gmi')) !== false){

			// Use the get_posts method to return
			// a properly parsed object

			$arr = get_posts($index+1,1);
			return $arr[0];
		}
	}

	return false;
}

// Get page
function find_page($name) {

	foreach(get_page_names() as $index => $v){

		if( strpos($v, 'static/'.$name.'.md') !== false){
			$arr = get_pages();
			return $arr[$index];
		}
	}

	return false;
}

function get_current_url() {
	return 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; 
}

/* Búsqueda por tag - OK */
function search_tag($tagtosearch) {
	/* Búsqueda por tag - OK */
	$posts = get_posts();
	$currentpost = get_current_url();
	//var_dump($currentpost);
	$i = 0;
	$post = $posts[$i];

	foreach($posts as $p):
		if ($p->url !== $currentpost && $p->tags):
			$tags = $p->tags;
				foreach ($tags as $tag):
					if ($tag === $tagtosearch) {
						echo '<li><a href="'. $p->url . '">'.$p->title.'</a></li>';
					}
					
				endforeach;
		endif;
		$i = $i ++;
	endforeach;

//return $archive;
}


// Helper function to determine whether
// to show the pagination buttons
function has_pagination($page = 1){
	$total = count(get_post_names());

	return array(
		'prev'=> $page > 1,
		'next'=> $total > $page*config('posts.perpage')
	);
}


// The not found error
function not_found(){
	error(404, render('404', null, false));
}

function get_links() {
    $links = array();
    $i = 1;

    while (true) {
        $key = 'link.header.' . $i;

        // Check if the key exists in the config
        if (!config($key)) {
            break;
        }

        // Get the value from the config
        $value = config($key);

        // Explode the value by comma to create an array
        $linkData = explode(',', $value);

        // Trim whitespace from each element in the array
        $linkData = array_map('trim', $linkData);

        // Extract the link title and URL from the array
        $title = $linkData[0];
        $url = $linkData[1];

	    // Añadimos site.url si no son rutas absolutas
        if (!preg_match('/^https?:\/\//', $url)) {
            $url =  site_url() . $url;
        }


        // Add the link to the array
        $links[] = array('title' => $title, 'url' => $url);

        $i++;
    }

    return $links;
}

// Turn an array of posts into an RSS feed
/*function generate_rss($posts){

	$feed = new Feed();
	$channel = new Channel();

	$channel
		->title(config('blog.title'))
		->description(config('blog.description'))
		->url(site_url())
		->appendTo($feed);

	foreach($posts as $p){

		$item = new Item();
		$item
			->title($p->title)
			->description($p->body)
			->url($p->url)
			->appendTo($channel);
	}

	echo $feed;
}*/

// Turn an array of posts into a JSON
/*function generate_json($posts){
	return json_encode($posts);
}*/


// Turn an array of posts into an RSS feed
// Turn an array of posts into an RSS feed
function generate_rss($posts){

    // Crear un nuevo XMLWriter
    $xml = new XMLWriter();
    $xml->openURI('./feed.xml');
    $xml->startDocument('1.0', 'UTF-8');
    $xml->setIndent(true);

    // Iniciar el elemento rss
    $xml->startElement('rss');
    $xml->writeAttribute('version', '2.0');

    // Iniciar el elemento channel
    $xml->startElement('channel');

    // Añadir los elementos de la cabecera del canal
    $xml->writeElement('title', config('blog.title'));
    $xml->writeElement('link', site_url());
    $xml->writeElement('description', config('blog.description'));
    $xml->writeElement('language', 'es');
    $xml->writeElement('lastBuildDate', date('D, d M Y H:i:s O'));

    // Añadir la imagen del blog
    $xml->startElement('image');
    $xml->writeElement('url', 'https://pabs.xyz/media/sloth.jpg');
	$xml->writeElement('width', '16');
	$xml->writeElement('height', '16');
    $xml->writeElement('title', config('blog.title'));
    $xml->writeElement('link', site_url());
    $xml->endElement(); // Cerrar el elemento image

    // Añadir los elementos de los posts
    foreach($posts as $post){
        $xml->startElement('item');
        $xml->writeElement('title', $post->title);
        $xml->writeElement('link', $post->url);
        $xml->writeElement('pubDate', date('D, d M Y H:i:s O', $post->date));
        $xml->writeElement('description', $post->body);
        $xml->endElement(); // Cerrar el elemento item
    }

    // Cerrar los elementos channel y rss
    $xml->endElement(); // Cerrar el elemento channel
    $xml->endElement(); // Cerrar el elemento rss

    // Finalizar el documento XML
    $xml->endDocument();

    // Salida del XML
    $xml->flush();
}

// Turn an array of posts into JSON
function generate_json($posts){
    return json_encode($posts);
}
