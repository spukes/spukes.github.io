<div class="post single">

	<a href="<?php echo site_url(); ?>">← Home</a>
	<h2><?php echo $p->title ?></h2>

	<div class="date"><?php echo date('d F Y', $p->date)?></div>
	<br>

	<?php echo $p->body?>

</div>
<div class="sidebar"> 
	<?php $relatedtags = $p->tags; ?>
	<?php include('related.html.php'); ?>

</div>

<!--Code highlighter -->
<?php if (config('snippet.highlight') == true): ?>
<link rel="stylesheet" href="<?php echo site_url() ?>assets/monokai.min.css">
<script src="<?php echo site_url() ?>assets/highlight.min.js"></script>
<script>hljs.initHighlightingOnLoad();</script>
<?php endif; ?>
