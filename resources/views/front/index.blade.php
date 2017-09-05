@extends('layouts.frontend')

@section('keyword', 'keyword')
@section('description', 'description')

@section('title', Option::get('_site_title'))

@section('content_col3')

<div class="_items _wrap_items">
    <?php for ($i = 0; $i < 10; $i++) { ?>
        <div class="_item">
            <div class="_inner">
                <h3 class="_title"><a href="#">My Favorite Outdoor Looks</a></h3>
                <div class="_date"><i class="fa fa-clock-o"></i> 5 giờ trước</div>
                <div class="_thumb">
                    <a href="#"><img class="img-fluid" src="/public/images/thumb.jpeg" alt=""></a>
                </div>
                <div class="_item_body">
                    <p class="_item_excerpt">
                        Suspendisse enim justo, tempus a porta at, euismod in tellus. Maecenas congue ante et ante placerat hendrerit. Suspendisse potenti. Etiam tempor, lacus et laoreet viverra, nibh ligula fringilla diam, ut imperdiet eros magna quis lorem. Quisque nec nisl pharetra, convallis ligula maximus, consectetur est. Nullam viverra ante at augue mattis pulvinar
                    </p>
                </div>
                <div class="_item_footer">
                    <div class="row">
                        <div class="col-xs-6">
                            <div class="_social_icon">
                                <a href="#"><img src="/public/images/icon/facebook-icon.png" alt="Facebook"></a>
                                <a href="#"><img src="/public/images/icon/google-plus-icon.png" alt="Google plus"></a>
                                <a href="#"><img src="/public/images/icon/twitter-icon.png" alt="Twitter"></a>
                            </div>
                        </div>
                        <div class="col-xs-6 text-xs-right">
                            <div class="_item_actions">
                                <a href="#" class="_btn_like"><i class="fa fa-thumbs-o-up"></i> Like</a>
                                <a href="#" class="_btn_comment"><i class="fa fa-comments"></i> Comment</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php } ?>
</div>

@stop




