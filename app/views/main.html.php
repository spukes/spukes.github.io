<ul class="posts-list <?php if (config('show.excerpt') == true){ echo 'with-excerpt' ;} ?>">
    <?php foreach($posts as $p):?>
    <li class="post main">
        <?php if (config('show.excerpt') == true) { //estilo entradilla y leer más ?>
        <div class="post-header">
			<div class="date"><?php echo date('d F Y', $p->date)?></div>
            <a href="<?php echo $p->url?>">
                <h2><?php echo $p->title ?></h2>
            </a>
			<?php if(config('show.tags') == true) { ?>
                <span class="tags-line">
                    <?php if ($p->tags): foreach($p->tags as $tag){ if (!empty($tag)): echo '<a href="'.site_url().'tag/'.$tag.'" class="tag">'.$tag.'</a>'; endif; } endif; ?>
                </span>
			<?php } ?>
        </div>

        <?php echo $p->excerpt; ?>
        <div><a href="<?php echo $p->url?>" ><button class="lightgrey">Read more -></button></a></div>
        </div>
        
        <?php } else { //estilo listado de entradas ?>
        <div>
            <div class="date"><?php echo date('d.m', $p->date)?></div>
            <div>
                <a href="<?php echo $p->url?>"><?php echo $p->title ?></a>
                <?php if(config('show.tags') == true) { ?>
                <span class="tags-line">
                    <?php foreach($p->tags as $tag){ if (!empty($tag)): echo '<a href="'.site_url().'tag/'.$tag.'" class="tag">'.$tag.'</a>'; endif; } ?>
                </span>
				<?php } ?>
            </div>
            
    </li>

    <?php } ?>

    <?php endforeach;?>
</ul>
<div>
    <?php if ($has_pagination['prev']):?>
    <a href="?page=<?php echo $page-1?>" class="pagination-arrow newer" style="float:right">Más recientes &rarr;</a>
    <?php endif;?>

    <?php if ($has_pagination['next']):?>
    <a href="?page=<?php echo $page+1?>" class="pagination-arrow older" style="float:left">&larr; Más antiguas</a>
    <?php endif;?>
</div>
<div style="clear:both"></div>


<?php generate_rss(get_posts(1,30));?>