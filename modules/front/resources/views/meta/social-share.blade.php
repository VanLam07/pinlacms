<?php
$postLink = $post->getLink();
?>

<div class="social-share-links width-42">
    <a href="https://www.facebook.com/sharer/sharer.php?u={{ $postLink }}" target="_blank"><img src="/images/icon/facebook-icon.png"></a>
    <a href="https://plus.google.com/share?url={{ $postLink }}" target="_blank"><img src="/images/icon/google-plus-icon.png"></a>
    <a href="https://twitter.com/home?status={{ $postLink }}" target="_blank"><img src="/images/icon/twitter-icon.png"></a>
</div>
