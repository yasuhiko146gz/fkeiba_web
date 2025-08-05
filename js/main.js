var video;
var timer_id = null;

var is_iphone = function(){
  return navigator.userAgent.indexOf('iPhone') > 0;
}

var is_android = function(){
  return navigator.userAgent.indexOf('Android') > 0;
}

var is_landscape = function(){
  return $(window).width() > $(window).height()
}

var iphone_version = function(){

}


$(window).on('resize', function(){
  var coverFontBase = 20;
  var coverFontSize = coverFontBase * ($(video).width() / 900);
  var coverHeight = $(video).width() * 9 / 16;
  $("#cover").css("font-size",coverFontSize + "px");
  $("#cover").css("height", coverHeight+"px");
  //console.log("document.onResize set cover.height="+coverHeight);
});

$(document).ready(function(){
  video = $('#container .video');
  var coverFontBase = 20;
  var coverFontSize = coverFontBase * ($(video).width() / 900);
  var coverHeight = $(video).width() * 9 / 16;
  $("#cover").css("font-size",coverFontSize + "px");
  $("#cover").css("height", coverHeight+"px");
  //console.log("document.onResize set cover.height="+coverHeight);
});


/**
 *config
 */
var FvPollingLib = function(callbacks) {
  var interval = null;
  var before_ticker = "";
  var before_video = "";
  var before_cover = -1;
  var before_camera_name = "";
  var before_interval = "";
  var before_race_idx = 1;

  var getServerInfo = function() {
    //console.log("getServerInfo");
    $.ajaxSetup({cache: false}); //キャッシュさせない
    $.getJSON("./config.json?" + (new Date), function(data){
      if(data){

        if(data.cover_flg != before_cover){
            // 初回ロード時、閉じている場合は反映 ページロード直後開放の場合、最初の1回目は反映しない
            if (before_cover == -1 && data.cover_flg == 0) {
               // DO NOTHING
            } else {
               callbacks.updateCover(data.cover_flg);
            }
            before_cover = data.cover_flg;
            //console.log("data.cover="+data.cover_flg+" before="+before_cover);
        }

        if (data.race_idx != before_race_idx) {
            callbacks.updateRaceInfo(data.race_idx);
            before_race_idx = data.race_idx;
        }

        if(data.ticker.toString() != before_ticker.toString()){
          callbacks.updateTicker(data.ticker);

          if(timer_id != null){
            clearInterval(timer_id);
          }

          if(data.ticker.length > 1){
            setTicker();
          }
          else{
            $(".ticker ul li").show();
          }

          before_ticker = data.ticker;
        }

        // プレーヤー側でonReady受信直後は、文字列変更有無に関わらず設定する
        if(callbacks.needInitCaption()) {
            //console.log("updateCam initCaptionDone");
            callbacks.updateCameraName(data.camera_name);
            callbacks.initCaptionDone();
        }

        if(data.camera_name.toString() != before_camera_name.toString()){
          callbacks.updateCameraName(data.camera_name);
          before_camera_name = data.camera_name;
        }

        if(before_interval != data.polling_interval){
          resetInterval(data.polling_interval);
          before_interval = data.polling_interval;
        }
      }
    });
  }

  var resetInterval = function(sec) {
    if ( interval != null ) {
      clearInterval(interval);
    }

    if ( interval == null ) {
      getServerInfo();
    }

    if(sec != 0){
      interval = setInterval(function(){
        getServerInfo();
      },sec*1000);
    }
  }

  resetInterval(5);
}

/**
 * ticker（レスポンシブ対応版）
 * モバイル縦向けでの1行表示（文字サイズ調整済）
 */
 var setTicker = function(){

  var $setElm = $('.ticker');
  var effectSpeed = 1000;
  var switchDelay = 6000;
  var easing = 'swing';

  $setElm.each(function(){
    var effectFilter = $(this).attr('rel');

    var $targetObj = $(this);
    var $targetUl = $targetObj.children('ul');
    var $targetLi = $targetObj.find('li');
    var $setList = $targetObj.find('li:first');

    var ulWidth = $targetUl.width();
    var listHeight = $targetLi.height();
    
    // モバイル縦向けでの高さ調整（1行表示用）
    var isMobilePortrait = window.matchMedia("(max-width: 768px) and (orientation: portrait)").matches;
    var adjustedHeight = isMobilePortrait ? Math.max(listHeight, 24) : listHeight;
    
    $targetObj.css({height: adjustedHeight});
    $targetLi.css({top:'0',left:'0',position:'absolute'});

    if(effectFilter == 'fade') {
      $setList.css({display:'block',opacity:'0',zIndex:'98'}).stop().animate({opacity:'1'},effectSpeed,easing).addClass('showlist');
      timer_id = setInterval(function(){
        var $activeShow = $targetObj.find('.showlist');
        $activeShow.animate({opacity:'0'},effectSpeed,easing,function(){
          var $next = $(this).next();
          if ($next.length === 0) $next = $targetObj.find('li:first');
          
          // 1行表示で統一
          $next.css({display:'block',opacity:'0',zIndex:'99'});
          
          $next.animate({opacity:'1'},effectSpeed,easing).addClass('showlist');
          $(this).appendTo($targetUl).css({display:'none',zIndex:'98'}).removeClass('showlist');
        });
      },switchDelay);
      
    } else if(effectFilter == 'roll') {
      $setList.css({top:'3em',display:'block',opacity:'0',zIndex:'98'}).stop().animate({top:'0',opacity:'1'},effectSpeed,easing).addClass('showlist');
      timer_id = setInterval(function(){
        var $activeShow = $targetObj.find('.showlist');
        var $next = $activeShow.next();
        if ($next.length === 0) $next = $targetObj.find('li:first');
        
        $activeShow.animate({top:'-3em',opacity:'0'},effectSpeed,easing);
        
        // 1行表示で統一
        $next.css({top:'3em',display:'block',opacity:'0',zIndex:'99'});
        
        $next.animate({top:'0',opacity:'1'},effectSpeed,easing).addClass('showlist');
        $activeShow.appendTo($targetUl).css({zIndex:'98'}).removeClass('showlist');
      },switchDelay);
      
    } else if(effectFilter == 'slide') {
      $setList.css({left:(ulWidth),display:'block',opacity:'0',zIndex:'98'}).stop().animate({left:'0',opacity:'1'},effectSpeed,easing).addClass('showlist');
      timer_id = setInterval(function(){
        var $activeShow = $targetObj.find('.showlist');
        var $next = $activeShow.next();
        if ($next.length === 0) $next = $targetObj.find('li:first');
        
        $activeShow.animate({left:(-(ulWidth)),opacity:'0'},effectSpeed,easing);
        
        // 1行表示で統一
        $next.css({left:(ulWidth),display:'block',opacity:'0',zIndex:'99'});
        
        $next.animate({left:'0',opacity:'1'},effectSpeed,easing).addClass('showlist');
        $activeShow.appendTo($targetUl).css({zIndex:'98'}).removeClass('showlist');
      },switchDelay);
    }
  });
}
