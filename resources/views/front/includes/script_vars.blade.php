<div id="_player_box" ng-controller="playerController">
    <div class="container">
        <div class="_player_bar">
            <div class="_progress_box">
                <div class="_progress_bar" ng-click="seedAudio($event)">
                    <div class="_progress" style="width: <% AUDIO.percentTime %>%;">
                        <span class="_progress_node"></span>
                    </div>
                </div>
            </div>
            <div class="_pl_layer">
                <div class="_btn_controls">
                    <span class="_prev"><i class="fa fa-step-backward"></i></span>
                    <span ng-if="!AUDIO.paused" ng-click="play()" class="_stop"><i class="fa fa-stop"></i></span>
                    <span ng-if="AUDIO.paused" ng-click="play()" class="_play"><i class="fa fa-play"></i></span>
                    <span class="_next"><i class="fa fa-step-forward"></i></span>
                </div>
                <div class="_time">
                    <span class="_runing"><% AUDIO.roudCurrentTime || '00:00' %></span> / <span class="_total"><% AUDIO.roudDuration || '00:00' %></span>
                </div>
                <div class="_pl_title">
                    <marquee><span></span></marquee>
                </div>
                <div class="_volume">
                    <span class="_vl_icon">
                        <i ng-if="AUDIO.volumePercent > 0" class="_vl_up fa fa-volume-up"></i>
                        <i ng-if="AUDIO.volumePercent == 0" class="_vl_mute fa fa-volume-off"></i>
                    </span>
                    <span class="_volume_bar">
                        <span class="_volume_stt" style="width: <% AUDIO.volumePercent || 100 %>%;">
                            <span class="_volume_node"></span>
                        </span>
                    </span>
                    <input type="range" min="0" max="100" class="_volume_val hidden">
                </div>
            </div>
            <video id="_player" class="hidden" src=""></video>
        </div>
    </div>
    <button class="close" ng-click="closePlayer()">
        <i ng-if="AUDIO.show" class="fa fa-angle-down"></i>
        <i ng-if="!AUDIO.show" class="fa fa-angle-up"></i>
    </button>
</div>

<script>
    var SHARE_ID = 's!Aipl9SsCBTTtiMYmwsgC_dTZaCo-fw';
    var _URL = '/{{request()->path()}}';
    var _player = $('#_player');
    var _player_box = $('#_player_box');
</script>