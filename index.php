<?php

// Explicitly including the dispatch framework,
// and our functions.php file
require 'app/includes/dispatch.php';
require 'app/includes/functions.php';

// Load the configuration file
config('source', 'app/config.ini');

// The front page of the blog.
// This will match the root url
get('/index', function () {

	$page = from($_GET, 'page');
	$page = $page ? (int)$page : 1;

	$posts = get_posts($page);

	if(empty($posts) || $page < 1){
		// a non-existing page
		not_found();
	}

    render('main',array(
    	'page' => $page,
		'posts' => $posts,
		'has_pagination' => has_pagination($page)
	));
});

// The blog apart
get('/blog', function () {

	$page = from($_GET, 'page');
	$page = $page ? (int)$page : 1;

	$posts = get_posts($page);

	if(empty($posts) || $page < 1){
		// a non-existing page
		not_found();
	}

    render('main',array(
    	'page' => $page,
		'posts' => $posts,
		'has_pagination' => has_pagination($page)
	));
});


// Show the RSS feed 
get('/rss',function(){
   $posts = get_posts(1,5);
   generate_rss($posts);
   header('Location:feed.xml');
   exit();
});

get('/feed', function(){
	$posts = get_posts(1, 5);
	generate_rss($posts);
	header('Location:feed.xml');
	exit();

});

// The static page
get('/:static',function($static){

	$page = find_page($static);

	if(!$page){
		not_found();
	}
	render('page',array(
			'title' => $page->title .' ⋅ ' . config('blog.title'),
			'p' => $page
	));


});

// The post page
get('/:year/:month/:name',function($year, $month, $name){

	$post = find_post($year, $month, $name);

	if(!$post){
		not_found();
	}

	render('post',array(
		'title' => $post->title .' ⋅ ' . config('blog.title'),
		'p' => $post
	));
});

//Rendering a tag (appliable to posts)
get('/tag/:tag',function($tagtosearch){

        //$archive = search_tag($tagtosearch);
        //var_dump($archive);
        //die;

        //if(count($archive) === 0){
        //        not_found();
        //}
        render('archive', array(
                'tag' => $tagtosearch,
                'title' => 'Etiqueta: ' . $tagtosearch .' · '. config('blog.title'),
                'archive' => $archive

        ));
        //echo search_tag($tagtosearch);
});

// The JSON API
get('/api/json',function(){

	header('Content-type: application/json');

	// Print the 10 latest posts as JSON
	echo generate_json(get_posts(1, 10));
});




// If we get here, it means that
// nothing has been matched above

get('.*',function(){
	not_found();
});

// Serve the blog
dispatch();
