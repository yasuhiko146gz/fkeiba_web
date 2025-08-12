<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <title>船橋マルチスクリーン</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="apple-mobile-web-app-capable" content="yes">

  <!-- OGP -->
  <meta property="og:type" content="video.other" />
  <meta property="og:url" content="https://funabashi-keiba.com/" />
  <meta property="og:title" content="船橋マルチスクリーン" />
  <meta property="og:description" content="船橋ケイバをマルチスクリーンで楽しもう！" />
  <meta property="og:image" content="https://funabashi-keiba.gnzo.com/img/fkeiba3.jpg" />
  <meta property="og:site_name" content="船橋ケイバ" />

  <!-- Twitter -->
  <meta name="twitter:card" content="summary_large_image" />
  <meta name="twitter:title" content="船橋マルチスクリーン" />
  <meta name="twitter:description" content="船橋ケイバをマルチスクリーンで楽しもう！" />
  <meta name="twitter:image" content="https://funabashi-keiba.gnzo.com/img/fkeiba3.jpg" />

  <!-- Favicon -->
  <link rel="icon" href="/favicon.ico" />

  <!-- jQuery -->
  <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>

  <!-- jQuery UI -->
  <link rel="stylesheet" href="//ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/themes/smoothness/jquery-ui.css" />
  <script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>
  <script src="./js/jquery.ui.touch-punch.min.js"></script>

  <!-- Latest compiled and minified CSS -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">

  <!-- Optional theme -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css" integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r" crossorigin="anonymous">

  <!-- Latest compiled and minified JavaScript -->
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>

  <!-- ICON -->
  <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">

  <!-- resetcss -->
  <!--
  <link rel="stylesheet" type="text/css" href="https://yui.yahooapis.com/3.18.1/build/cssreset/cssreset-min.css">
  -->

  <!-- Google tag (gtag.js) -->
  <script async src="https://www.googletagmanager.com/gtag/js?id=G-HRW2S9NW67"></script>
  <script>
    window.dataLayer = window.dataLayer || [];

    function gtag() {
      dataLayer.push(arguments);
    }
    gtag('js', new Date());

    gtag('config', 'G-HRW2S9NW67');
</script>

  <!-- fvLivePlayer -->
  <script src="./js/fvLivePlayer/lib/platform.js"></script>
  <script src="./js/fvLivePlayer/embed.min.js"></script>
<!--
  <script src="https://tk3.s3.ap-northeast-1.amazonaws.com/fvLivePlayer/lib/platform.js"></script>
  <script src="https://tk3.s3.ap-northeast-1.amazonaws.com/fvLivePlayer/embed.min.js"></script>
  -->

  <!-- YouTube Liveコメント表示CSS -->
  <link href="./css/youtube-comments.css" rel="stylesheet" type="text/css">
  <!-- main style -->
  <link href="./css/style.css" rel="stylesheet" type="text/css">
  <!-- main js -->
  <script type="text/javascript" src="./js/main.js"> </script>
  <!-- YouTube Liveコメント表示JS -->
  <!-- <script type="text/javascript" src="./js/youtube-comments.js"> </script> -->

</head>

<body>
  <div id="container">
    <!-- 改良版ヘッダー部分 -->
    <header id="header-container">
      <div class="header-logo">
        <a href="./"><img class="logoimg" src="./img/wts_logo.jpg" /></a>
      </div>
      <nav class="header-nav">
        <ul>
          <li><a target="_blank" href="https://www.f-keiba.com/" style="text-decoration: none; font-weight: bold; color: #58A76E; text-decoration: underline;">船橋ケイバ</a></li>
          <li><a href="#modal2" data-toggle="modal" data-target="#modal2">ヘルプ</a></li>
          <li><a target="_blank" href="https://forms.gle/LxqsEpgxSaKv6RNh9" class='survey-link'>【特典付】アンケート</a></li>
        </ul>
      </nav>
    </header>
    <div class="archive-notice">
      ※現在は録画配信中です (2025年7月6日分)
    </div>
    <div id="ticker" class="ticker" rel="slide">
      <div id="notice_title">
        <span>#Notice</span>
      </div>
      <ul></ul>
    </div>

    <!-- メインコンテンツラッパー -->
    <div class="main-content-wrapper">
      <!-- プレーヤーとコメントのレスポンシブコンテナ -->
      <div class="player-comments-container">
        <!-- プレーヤーエリア -->
        <div class="video-player-area">
          <div class="video">
            <div id='fvPlayer'></div>
            <div id="cover"><span></span></div>
          </div>
        </div>

        <!-- YouTube Liveコメントエリア -->
        <div class="youtube-comments-wrapper">
          <div id="youtube-comments-container"></div>
        </div>
      </div>

      <!-- モバイル縦向き用透明iframe -->
      <iframe id="mobile-overlay-iframe" style="position: absolute; left: 0; width: 100%; background: red; opacity: 0.3; z-index: 9999; border: none; pointer-events: none;"></iframe>
    </div>
  </div>

  <!-- ヘルプ -->
  <div class="modal fade" id="modal2" tabindex="-1">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">
            <span aria-hidden="true">&times;</span>
          </button>
          <h4 class="modal-title" id="modal-label">推奨視聴環境</h4>
        </div>
        <div class="modal-body">
          <div id="environment">
            <table>
              <tbody>
                <h3>Windows／Mac</h3>
                <tr>
                  <th>
                    <p>OS</p>
                  </th>
                  <td>
                    <p>Windows 10以降</p>
                    <p>OS X 10.12以降</p>
                  </td>
                </tr>
                <tr>
                  <th>
                    <p>ブラウザ</p>
                  </th>
                  <td>
                    <p>Google Chrome最新版</p>
                    <p>Microsoft Edge最新版</p>
                    <p>Firefox最新版</p>
                    <p>Safari 最新版</p>
                  </td>
                </tr>
                <tr>
                  <th>
                    <p>CPU</p>
                  </th>
                  <td>
                    <p>クロック速度 Intel PentiumD 2.8GHz相当以上</p>
                    <p>Inter Core2 Duo 1.6GHz相当以上</p>
                  </td>
                </tr>
                <tr>
                  <th>
                    <p>メモリ</p>
                  </th>
                  <td>
                    <p>4GB以上</p>
                  </td>
                </tr>
                <tr>
                  <th>
                    <p>その他</p>
                  </th>
                  <td>
                    <p>JavaScriptを「オン」Cookieを「受け付ける」に設定</p>
                  </td>
                </tr>
              </tbody>
            </table>

            <table>
              <tbody>
                <h3>iPhone／iPad／Android</h3>
                <tr>
                  <th>
                    <p>OS</p>
                  </th>
                  <td>
                    <p>iOS 10.0以降</p>
                    <p>Android 4.4以降</p>
                  </td>
                </tr>
                <tr>
                  <th>
                    <p>ブラウザ</p>
                  </th>
                  <td>
                    <p>Google Chrome最新版</p>
                    <p>Safari最新版</p>
                  </td>
                </tr>
                <tr>
                  <th>
                    <p>その他</p>
                  </th>
                  <td>
                    <p>JavaScriptを「オン」Cookieを「受け付ける」に設定</p>
                  </td>
                </tr>
              </tbody>
            </table>

            <table>
              <tbody>
                <h3>お問合せ</h3>
                <tr>
                  <th>
                    <p>対応時間</p>
                  </th>
                  <td>
                    <p>7/29（火）〜 7/31（木）：14:05 〜 21:15</p>
                  </td>
                </tr>
                <tr>
                  <th>
                    <p>お電話でのお問合わせ</p>
                  </th>
                  <td>
                    <p>03-6820-9007</p>
                  </td>
                </tr>
              </tbody>
            </table>

            <table>
              <tbody>
                <h3>注意事項</h3>
                <tr>
                  <!-- <th><p>注意事項</p></th> -->
                  <td>
                    <p>wifi環境など高速通信（下り3Mbps以上）が可能な電波の良い所でご視聴ください。</p>
                    <p>回線状況や電波状況の悪い環境下では、読み込みに時間がかかる場合があります。</p>
                    <p>モバイルWi-Fi、スマートフォンをご利用の際は、ご契約をされております通信会社のWebサイトに記載の注意事項をご確認の上ご利用ください。</p>
                    <!-- <a href="https://www.nttdocomo.co.jp/info/safety/packet.html" target="_blank">NTT docomo</a><br>
                    <a href="https://www.softbank.jp/mobile/support/procedure/charge_guide/trouble/confirm/" target="_blank">SoftBank</a><br>
                    <a href="https://www.au.kddi.com/mobile/charge/reference/packet-caution/" target="_blank">au</a><br>
                    <a href="https://www.uqwimax.jp/service/speedlimt_pop02.html" target="_blank">UQ WIMAX</a><br>
                    ※上記以外のキャリアをご利用の方はご自身の契約内容をご確認のうえご視聴ください。 -->
                    <br>
                    <p>本配信は、スマートフォン、パソコン、タブレットのブラウザ上で無料視聴可能ですが、インターネット接続に関わる通信料は利用者様のご負担となります。</p>
                    <br>
                    <p>※ネット配信のため、映像には30秒程度のタイムラグがあります。</p>
                    <br>
                    <p class="danger">※データ通信料は利用者様のご負担になりますので、データ通信量にご注意ください。</p>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">閉じる</button>
        </div>
      </div>
    </div>
  </div>

  <?php
  require_once("./define.php");
  ?>

  <script type='text/javascript'>
    var player_ref = null;
    var initCaption = false;

    cover_content = <?php echo json_encode($COVER_CONTENT); ?>;

    new FvLivePlayer(
      'fvPlayer', {
        originWidth: 1920,
        originHeight: 1080,
        disableSeek: false,
        enableShare: false,
        isTheaterModeEnable: true,
        isLive: false,
        enableMMS: true,
        showLicensee: false,
        autoplay: false,
        row: 4,
        col: 4,
        isDebug: true,
        gaTrackingId : "G-HRW2S9NW67",
        thumb_key: "./js/fvLivePlayer/img/fkeiba_0730.jpg",
        statsInterval: 10,
        contentID: "fk2025",
        cameras: [{
            x: 0,
            y: 0,
            level: 3,
            tag: 'main',
            isMain: true,
            src: 'https://d1ke28lwcarijs.cloudfront.net/vodcf/smil:0730_fv1.smil/playlist.m3u8'
          },
          {
            caption: '出走表',
            x: 3,
            y: 0,
            level: 1,
            tag: 'camera1',
            src: 'https://d1ke28lwcarijs.cloudfront.net/vodcf/smil:0730_fv1.smil/playlist.m3u8'
          },
          {
            caption: '本場映像',
            x: 3,
            y: 1,
            level: 1,
            tag: 'camera2',
            src: 'https://d1ke28lwcarijs.cloudfront.net/vodcf/smil:0730_fv2.smil/playlist.m3u8'
          },
          {
            caption: 'オッズ',
            x: 3,
            y: 2,
            level: 1,
            tag: 'camera3',
            src: 'https://d1ke28lwcarijs.cloudfront.net/vodcf/smil:0730_fv3.smil/playlist.m3u8'
          },
          {
            caption: 'ハートビートライブ',
            x: 3,
            y: 3,
            level: 1,
            tag: 'camera4',
            defaultView: true,
            src: 'https://d1ke28lwcarijs.cloudfront.net/vodcf/smil:0730_fv4.smil/playlist.m3u8'
          },
          {
            caption: 'パドック③',
            x: 2,
            y: 3,
            level: 1,
            tag: 'camera5',
            src: 'https://d1ke28lwcarijs.cloudfront.net/vodcf/smil:0730_fv5.smil/playlist.m3u8'
          },
          {
            caption: 'パドック②',
            x: 1,
            y: 3,
            level: 1,
            tag: 'camera6',
            src: 'https://d1ke28lwcarijs.cloudfront.net/vodcf/smil:0730_fv6.smil/playlist.m3u8'
          },
          {
            caption: 'パドック①',
            x: 0,
            y: 3,
            level: 1,
            tag: 'camera7',
            src: 'https://d1ke28lwcarijs.cloudfront.net/vodcf/smil:0730_fv7.smil/playlist.m3u8'
          }
        ],

        onReady: function(player) {
          //console.log("HTML onReady");
          // Windowsでプレイヤーの表示が崩れるの対応
          setTimeout(function() {
            $(window).trigger("resize");
          }, 100);
          setTimeout(function() {
            $(window).trigger("resize");
          }, 200);
          player_ref = player;
        }
      }
    );

    function debugLog(message, limit = 100) {
      const now = new Date();
      const timeStr = now.toLocaleTimeString('ja-JP', {
        hour12: false
      }) + '.' + String(now.getMilliseconds()).padStart(3, '0');
      const formattedMessage = `[${timeStr}] ${message}`;

      $('#debug').append('<p>' + $('<div>').text(formattedMessage).html() + '</p>');

      const lines = $('#debug p');
      if (lines.length > limit) {
        lines.first().remove();
      }
    }

    function getOrientation() {
      // Screen Orientation API available?
      if (screen.orientation && screen.orientation.type) {
        ret = screen.orientation.type.indexOf('landscape') !== -1 ?
          'landscape' :
          'portrait';
        return ret;
      }

      // Legacy window.orientation (iOS Safari, etc.)
      if (typeof window.orientation === 'number') {
        return (window.orientation === 90 || window.orientation === -90) ?
          'landscape' :
          'portrait';
      }

      // Fallback: infer from viewport dimensions
      return window.innerWidth > window.innerHeight ? 'landscape' : 'portrait';
    }

    /**
     * iPhone / iPad（iPadOS13 以降の Mac 風 UA を含む）だけを true にする
     */
    function isIOSDevice() {
      const ua = navigator.userAgent.toLowerCase();

      // iPhone / iPod
      if (/iphone|ipod/.test(ua)) return true;

      // 旧 iPad
      if (/ipad/.test(ua)) return true;

      /* iPadOS13 以降は Mac と同じ UA を返すので追加判定
         Touch ポイントが 2 本以上あれば iPad とみなす */
      if (ua.indexOf('macintosh') !== -1 &&
        typeof navigator.maxTouchPoints === 'number' &&
        navigator.maxTouchPoints > 1) {
        return true;
      }

      return false; // その他（Android 等）は false
    }

    /**
     * 判定関数
     * @param  {String=} uaString  テスト用に UA を渡すことも可能
     * @return {Boolean}           true = モバイル端末
     */
    function isMobileDevice(uaString) {
      // UA を取得して小文字化
      var ua = (uaString || navigator.userAgent).toLowerCase();

      /* ---------------------------------
       * 1. 一般的なモバイルキーワード
       * --------------------------------- */
      var mobileRegex = /android|iphone|ipod|ipad|windows phone|blackberry|bb10|kindle|silk|opera mini|palm|mobile|mobi|mini/i;

      if (mobileRegex.test(ua)) {
        return true;
      }

      /* ---------------------------------
       * 2. iPadOS 13 以降（Mac 風 UA へ変更）
       *    - iPad だが UA に「macintosh」が入る
       *    - タッチポイントが 2 本以上
       * --------------------------------- */
      if (/macintosh/.test(ua) &&
        typeof navigator.maxTouchPoints === 'number' &&
        navigator.maxTouchPoints > 1) {
        return true;
      }

      /* ---------------------------------
       * 3. それ以外 → PC とみなす
       * --------------------------------- */
      return false;
    }

    function isMobileLandScape() {
      if (isMobileDevice() && (getOrientation() == "landscape")) {
        return true;
      } else {
        return false;
      }
    }

    // 端末向き変更時の処理
    var _lastOrientation = null;

    function handleOrientationChange() {
      var newOrientation = getOrientation();
      // 前回と向きが変わった時のみ実施
      if (newOrientation !== _lastOrientation) {
        _lastOrientation = newOrientation;
        //console.log('orientation changed → ' + newOrientation);
        if (isMobileDevice()) {
          if (getOrientation() == "landscape") {
            $('#header-container').hide();
            $('#ticker').hide();
            $('.archive-notice').hide();

            // iPhone Safari URLバー自動非表示機能
            handleUrlBarAutoHide();
          } else {
            $('#header-container').show();
            $('#ticker').show();
            $('.archive-notice').show();

            // 縦向き時はiOS用CSSクラスを削除
            if (isIOSDevice()) {
              document.body.classList.remove('ios-landscape');
<<<<<<< HEAD
=======
              document.body.classList.remove('ios-landscape-url-hidden');
>>>>>>> 11ae480f2a94038dac00393faffe72ecdbecc83a
            }

            // モバイル縦向き時の透明iframe制御
            handleMobilePortraitIframe();
          }
        }
        adjustCoverImageHeight();
      }
    }

    /**
     * iPhone Safari URLバー自動非表示処理
     */
    function handleUrlBarAutoHide() {
      if (!isIOSDevice() || !isMobileLandScape()) {
        return;
      }

      // iOS用CSSクラスを追加
      document.body.classList.add('ios-landscape');

<<<<<<< HEAD
      // 少し遅延してからURLバー隠し処理を実行
=======
      // 自動的にURLバーを隠す処理を実行
>>>>>>> 11ae480f2a94038dac00393faffe72ecdbecc83a
      setTimeout(function() {
        autoHideUrlBar();
      }, 150);

      // 念のため追加で実行（端末によってタイミングが異なる場合がある）
      setTimeout(function() {
        autoHideUrlBar();
      }, 400);
<<<<<<< HEAD
=======
    }

    /**
     * 自動的にURLバーを隠してスクロール制御を適用
     */
    function autoHideUrlBar() {
      if (!isIOSDevice() || !isMobileLandScape()) {
        return;
      }

      // URLバーを隠すためのスクロール
      window.scrollTo(0, 1);

      // URLバーが隠れたかを監視
      setTimeout(function() {
        checkUrlBarHiddenAndApplyControl();
      }, 200);
    }

    /**
     * URLバー状態を確認してスクロール制御を適用
     */
    function checkUrlBarHiddenAndApplyControl() {
      if (!isIOSDevice() || !isMobileLandScape()) {
        return;
      }

      if (!isUrlBarVisible()) {
        // URLバーが隠れた場合、スクロールを完全禁止
        document.body.classList.add('ios-landscape-url-hidden');
        console.log('URLバー非表示検出：スクロール制御を適用');
      } else {
        // まだ表示されている場合は再試行
        setTimeout(function() {
          autoHideUrlBar();
        }, 300);
      }
>>>>>>> 11ae480f2a94038dac00393faffe72ecdbecc83a
    }

    /**
     * URLバーが表示されている場合に隠す処理
     */
    function hideUrlBarIfNeeded() {
      // URLバー表示状態をチェック
      if (isUrlBarVisible()) {
        // 微小なスクロールでURLバーを隠す
        window.scrollTo(0, 1);

        // デバッグログ
        console.log('URLバー非表示処理を実行しました');
      }
    }

    /**
     * URLバーが表示されているかを判定
     * @return {boolean} true: 表示中, false: 非表示
     */
    function isUrlBarVisible() {
      if (!isIOSDevice() || !isMobileLandScape()) {
        return false;
      }

      // 画面の幅と高さの差でURLバーの表示状態を判定
      var heightDiff = screen.width - window.innerHeight;
      return heightDiff > 20;
    }

    /**
    * モバイル縦向き時の透明iframeサイズ調整
    */
    function handleMobilePortraitIframe() {
    const mobileIframe = document.getElementById('mobile-overlay-iframe');

    if (isMobileDevice() && getOrientation() === 'portrait') {
    // プレーヤーが表示されているかチェック
    const isPlayerVisible = $('#fvPlayer').is(':visible');

    if (isPlayerVisible) {
    // ヘッダー高さを取得
    const headerHeight = $('#header-container').is(':visible') ? $('#header-container').outerHeight() || 0 : 0;

    // ティッカー高さを取得
    const tickerHeight = $('#ticker').is(':visible') ? $('#ticker').outerHeight() || 0 : 0;

    // アーカイブ通知高さを取得
    const archiveNoticeHeight = $('.archive-notice').is(':visible') ? $('.archive-notice').outerHeight() || 0 : 0;

    // プレーヤー高さを取得
    const playerHeight = $('.video-player-area').outerHeight() || 0;

    // 画面全体の高さ
    const windowHeight = window.innerHeight;

    // プレーヤーの下から開始するtop位置を計算
    const iframeTop = headerHeight + tickerHeight + archiveNoticeHeight + playerHeight;

    // 残りの高さを計算
    const remainingHeight = windowHeight - iframeTop;

    // 残りの高さ + 10pxでiframeの高さを設定
    const iframeHeight = remainingHeight + 10;

    mobileIframe.style.top = iframeTop + 'px';
    mobileIframe.style.height = iframeHeight + 'px';

    // ページ全体の高さをiframeの最下部まで含むように設定
    const totalPageHeight = iframeTop + iframeHeight;
    document.body.style.minHeight = totalPageHeight + 'px';
    document.getElementById('container').style.minHeight = totalPageHeight + 'px';

    // デバッグ情報をコンソールに出力
    console.log('iframe調整:', {
    windowHeight: windowHeight,
    headerHeight: headerHeight,
    tickerHeight: tickerHeight,
      archiveNoticeHeight: archiveNoticeHeight,
        playerHeight: playerHeight,
      iframeTop: iframeTop,
      remainingHeight: remainingHeight,
      iframeHeight: iframeHeight,
        totalPageHeight: totalPageHeight
        });
      } else {
          // プレーヤー非表示時はデフォルト位置
          mobileIframe.style.top = '0px';
          mobileIframe.style.height = '100vh';

          // ページ高さをリセット
          document.body.style.minHeight = 'auto';
          document.getElementById('container').style.minHeight = 'auto';
        }
      } else {
        // 横向き時はページ高さをリセット
        document.body.style.minHeight = 'auto';
        document.getElementById('container').style.minHeight = 'auto';
      }
      // 横向き時はサイズ変更しない（そのまま）
    }

    // 端末向き変更時のイベント登録 複数イベントで登録し、ハンドラ側でデバウンス処理を行う
    if (screen.orientation && screen.orientation.addEventListener) {
      screen.orientation.addEventListener('change', handleOrientationChange, false);
    }
    if ('onorientationchange' in window) {
      window.addEventListener('orientationchange', handleOrientationChange, false);
    }
    window.addEventListener('resize', handleOrientationChange, false);
    if (window.visualViewport) {
      visualViewport.addEventListener('resize', handleOrientationChange, false);
    }

    // URLバー状態変化の監視（iOS用）
    if (isIOSDevice()) {
      window.addEventListener('resize', function() {
        // URLバーが隠れた後の処理
        if (isMobileLandScape() && !isUrlBarVisible()) {
          // レイアウト再調整
          setTimeout(adjustCoverImageHeight, 100);
        }
      });
    }

    function adjustCoverImageHeight() {
      // ヘッダ・フッタの固定長と画面高さを取得
      headerContainerHeight = $('#header-container').outerHeight();
      tickerHeight = $('#ticker').outerHeight();
      windowWidth = $(window).width();
      windowHeight = $(window).outerHeight(true);

      contentHeight = windowHeight - (headerContainerHeight + tickerHeight);
      // モバイル横向きは、ヘッダとティッカーサイズを非表示の前提
      if (isMobileLandScape()) {
        contentHeight = windowHeight;
      }

      if ($('#fvPlayer').is(':visible')) {
        // レスポンシブレイアウト対応：プレーヤーエリアの幅を基準にする
        var playerAreaWidth = $('.video-player-area').length > 0 ? $('.video-player-area').width() : windowWidth;

        // 1024px以上の場合は、コメント欄分の幅を考慮
        if (windowWidth >= 1024) {
          // 画面幅に応じてコメント欄幅を計算
          var commentWidth;
          if (windowWidth <= 1440) {
            commentWidth = 300; // 1024px-1440px: 300px
          } else {
            commentWidth = 400; // 1441px以上: 400px
          }

          // コメント欄 + gapを引く
          var availableWidth = windowWidth - (commentWidth + 24);
          // プレーヤーエリアの実際の幅と比較して小さい方を使用
          availableWidth = Math.min(availableWidth, playerAreaWidth);
        } else {
          var availableWidth = playerAreaWidth;
        }

        new_width = (contentHeight - 47) * 16 / 9;
        //console.log("contentHeight:" + contentHeight + " availableWidth:" + availableWidth + " new_width:" + new_width);

        if (new_width < availableWidth) {
          // モバイル端末横向の場合、プレーヤー高さが端末高さと一致するようにする
          if (isMobileLandScape() && isIOSDevice()) {
            new_width = windowHeight * 16 / 9;
            $('#fvPlayer').width(Math.min(new_width, availableWidth) + 'px');
          } else {
            $('#fvPlayer').width(new_width + 'px');
          }
        } else {
          $('#fvPlayer').width(availableWidth + 'px');
        }
      }

      // main.jsの関数も呼び出してレイアウトを同期
      if (typeof updatePlayerLayout === 'function') {
        updatePlayerLayout();
      }
    }

    new FvPollingLib({
      updateTicker: function(tickerArray) {
        $(".ticker ul").empty();
        for (var i = 0; i < tickerArray.length; i++) {
          $(".ticker ul").append("<li>" + tickerArray[i] + "</li>");
        }
      },

      updateRaceInfo: function(race_idx) {
      },
      updateCover: function(flg) {
        if (flg == 0) {
          //console.log("cover hide");
          $("#cover").hide();
          $("#cover span").empty();
          $("#fvPlayer").show();
          if (player_ref != null && player_ref.getReady()) {
            player_ref.play();
          }
          var ua = window.navigator.userAgent.toLowerCase();
          if (
            ((ua.indexOf("win") != -1) && (ua.indexOf("trident") != -1)) ||
            ((ua.indexOf("win") != -1) && (ua.indexOf("edge") != -1))
          ) {
            //console.log("updateCover flg=" + flg + " reload");
            location.reload();
          }
        } else {
          //console.log("cover show content:" + cover_content[flg]["content"]);
          $("#cover span").html(cover_content[flg]["content"]);
          $("#cover").show();
          //if (player_ref != null)
          //  player_ref.pause();
          $("#fvPlayer").hide();
        }
        adjustCoverImageHeight();
        // プレーヤー表示状態変更時にiframe制御を実行
        handleMobilePortraitIframe();
      },
      updateCameraName: function(data) {
        for (var i = 0; i < data.length; i++) {
          if (player_ref != null)
            player_ref.changeCameraCaption('camera' + (i + 1), data[i]);
        }
      },
      needInitCaption: function() {
        if (player_ref != null && initCaption == false) {
          return true;
        } else {
          return false;
        }
      },
      initCaptionDone: function() {
        initCaption = true;
      },
    });

    $(document).ready(function() {
      handleOrientationChange();
      adjustCoverImageHeight();
      handleMobilePortraitIframe();
      let resizeTimer;
      $(window).on("resize", function() {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(function() {
          adjustCoverImageHeight();
          handleMobilePortraitIframe();
        }, 100);
      });
    });

  </script>
</body>

</html>
