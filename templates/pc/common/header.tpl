<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8" />
<meta name="description" content="TwitterMobileクライアント「Pantter!!」" />
<meta name="author" content="HisatoS." />

<meta property="og:site_name" content="A JACK IN THE BOX" />
<meta property="fb:app_id" content="132755693507641" />
<meta property="fb:admins" content="1260139042" />
<meta property="og:locale" content="ja_JP" />
<meta property="og:image" content="http://pantter.nono150.com/img/icon/PAf.png" />
<meta property="og:type" content="website" />
<meta property="og:title" content="Pantter!!" />
<meta property="og:description" content="ぱんだ特製オレオレクライアントぱんつ！" />
<meta property="og:url" content="http://pantter.nono150.com/" />

<link rel="stylesheet" href="/js/facebox/facebox.css" />
<link rel="stylesheet" href="/css/style.css" />
<link rel="stylesheet" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.16/themes/smoothness/jquery-ui.css" />
<link rel="apple-touch-icon" href="/img/icon/{$icon_type}.png" />
{literal}<!--[if lt IE 9]>
<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->{/literal}
<script src="https://www.google.com/jsapi?key=ABQIAAAAlP_KVQTQ51B0eWFEi4IOzhRSIX4ZXrPM8vbjL1t0qZFcDnMoqxSJ2ECGVAkdMHjPrasb_FHrwWkkZg"></script>
<script>
google.load("jquery", "1.7.1");
google.load("jqueryui", "1.8.16");
</script>
<script src="/js/plugins/jquery.disable.submit.js"></script>
<script src="/js/ui/ui.form.js"></script>
<script src="/js/facebox/facebox.js"></script>
<script src="/cgi/clap/clap_v2.js"></script>
<script src="/js/jack.js"></script>
<script src="./js/mopo.js"></script>

{if !$localhost}
<script type="text/javascript" src="http://www.nono150.com/cgi/ana/lapis/tracker.js" charset="UTF-8"></script>
{literal}	<script type="text/javascript">//<![CDATA[
	var _gaq = _gaq || [];
	_gaq.push(['_setAccount','UA-21055479-1']);
	_gaq.push(['_trackPageview','/404.html?page=' + document.location.pathname + document.location.search + '&from=' + document.referrer],['_trackPageLoadTime']);
	(function() {
		var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
		ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
		var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
	})();
	//]]></script>{/literal}
{/if}

<title>{$page_title}</title>

</head>

<body>
{literal}<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  /*js.src = "//connect.facebook.net/en_US/all.js#xfbml=1&appId=132755693507641";*/
  js.src = "//connect.facebook.net/ja_JP/all.js#xfbml=1&appId=132755693507641";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>
<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
<script type="text/javascript" src="http://b.st-hatena.com/js/bookmark_button.js" charset="utf-8" async="async"></script>
<script type="text/javascript">
  window.___gcfg = {lang: 'ja'};

  (function() {
    var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
    po.src = 'https://apis.google.com/js/plusone.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
  })();
</script>{/literal}
{if !$localhost}
<div id="tracker" style="position:absolute;visibility:hidden;">
<script type="text/javascript">sendData();</script>
<noscript>
<div>
<img src="http://www.nono150.com/cgi/ana/lapis/write.php/img/" width="0" height="0" alt="tracker" />
</div>
</noscript>

<script type="text/javascript">
<!--
document.write('<img src="http://www.nono150.com/cgi/ana/w3a_v2/writelog.php?ref='+document.referrer+'" width="1" height="1" alt="解析タグ" />');
// -->
</script>
</div>
{/if}

<div id="wrapper">

<header id="header">
	<h1 id="tops"><a href="{$site_url}" title="{$site_title}">{$page_title|escape}</a></h1>
</header>
{*
{$smarty.server.PHP_SELF|basename}
*}
