/**
 * ticker（レスポンシブ対応版）
 * モバイル縦向けでの2行表示に対応
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
    
    // モバイル縦向けでの高さ調整
    var isMobilePortrait = window.matchMedia("(max-width: 768px) and (orientation: portrait)").matches;
    var adjustedHeight = isMobilePortrait ? Math.max(listHeight, 40) : listHeight;
    
    $targetObj.css({height: adjustedHeight});
    $targetLi.css({top:'0',left:'0',position:'absolute'});

    if(effectFilter == 'fade') {
      $setList.css({display:'block',opacity:'0',zIndex:'98'}).stop().animate({opacity:'1'},effectSpeed,easing).addClass('showlist');
      timer_id = setInterval(function(){
        var $activeShow = $targetObj.find('.showlist');
        $activeShow.animate({opacity:'0'},effectSpeed,easing,function(){
          var $next = $(this).next();
          if ($next.length === 0) $next = $targetObj.find('li:first');
          
          // モバイル縦向けでの表示調整
          if (isMobilePortrait) {
            $next.css({
              display:'-webkit-box',
              '-webkit-line-clamp': '2',
              '-webkit-box-orient': 'vertical',
              opacity:'0',
              zIndex:'99'
            });
          } else {
            $next.css({display:'block',opacity:'0',zIndex:'99'});
          }
          
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
        
        // モバイル縦向けでの表示調整
        if (isMobilePortrait) {
          $next.css({
            top:'3em',
            display:'-webkit-box',
            '-webkit-line-clamp': '2',
            '-webkit-box-orient': 'vertical',
            opacity:'0',
            zIndex:'99'
          });
        } else {
          $next.css({top:'3em',display:'block',opacity:'0',zIndex:'99'});
        }
        
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
        
        // モバイル縦向けでの表示調整
        if (isMobilePortrait) {
          $next.css({
            left:(ulWidth),
            display:'-webkit-box',
            '-webkit-line-clamp': '2',
            '-webkit-box-orient': 'vertical',
            opacity:'0',
            zIndex:'99'
          });
        } else {
          $next.css({left:(ulWidth),display:'block',opacity:'0',zIndex:'99'});
        }
        
        $next.animate({left:'0',opacity:'1'},effectSpeed,easing).addClass('showlist');
        $activeShow.appendTo($targetUl).css({zIndex:'98'}).removeClass('showlist');
      },switchDelay);
    }
  });
}
