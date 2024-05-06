
<?php 
    if (!empty($relatedtags[0])) {
       echo '<hr>';
       echo '<ul class="related">'; 
        foreach ($relatedtags as $relatedtag) {
            search_tag($relatedtag);
        }
        echo '</ul>';       
    }
?>