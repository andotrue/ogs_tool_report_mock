<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="">
  <meta name="author" content="">
  <link rel="icon" href="/favicon.ico">

  <title>{{ $functionName or "OGS Reporting" }} {{ (isset($functionSubName) && $functionSubName)? "-".$functionSubName."-" : "" }}</title>

  <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

  <!-- Bootstrap core CSS -->
  <!-- <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css" rel="stylesheet"> -->
  <!-- <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet"> -->
  <link href="/css/bootstrap-3.3.7/bootstrap.min.css" rel="stylesheet">

  <link href="/css/tool.css" rel="stylesheet">

  <!-- Jvascript -->
  <script src="/js/jquery-3.1.0.min.js"></script>

  @yield('css')

  <style>
  </style>
</head>

<body>
<nav class="navbar navbar-default">
  <div class="container">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      @if (isset($all_store_logo))
        <div class="col-md-4 col-xs-4">
        <!-- <img class='img-thumbnail' src='/img/store/{{ $all_store_logo }}'> -->
        </div>
        <div class="col-md-8 col-xs-8">
          <a class="navbar-brand" href="/tool">
            <span class="site-logo">{{ config('app.name', 'Laravel') }} {{ $page_title or "管理画面" }}</span>
          </a>
        </div>
      @else
        <a class="navbar-brand" href="/tool">{{ config('app.name', 'Laravel') }}</a>
      @endif
    </div>

    <div class="collapse navbar-collapse" id="app-navbar-collapse">
      <!-- Left Side Of Navbar -->
      <ul class="nav navbar-nav">
        &nbsp;
      </ul>

      <!-- Right Side Of Navbar -->
      <ul class="nav navbar-nav navbar-right">
        <!-- Authentication Links -->
        @if (Auth::guest())
            <li><a href="{{ url('/login') }}">Login</a></li>
        @else
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
            {{ Auth::user()->name }} <span class="caret"></span>
          </a>

          <ul class="dropdown-menu" role="menu">
            <li>
              <a href="{{ url('/logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                Logout
              </a>

              <form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">
                {{ csrf_field() }}
              </form>
            </li>
          </ul>
        </li>
        @endif
      </ul>
    </div>

    <div id="navbar" class="collapse navbar-collapse">
      <ul class="nav navbar-nav">
        <li id="informationlink" class="{{ preg_match("/^\/csvinport*/", $_SERVER['REQUEST_URI'])? " active " : ""}}">
          <a href="/csvimport">CSVインポート</a>
        </li>
        <li id="informationlink" class="{{ preg_match("/^\/reporttesttable*/", $_SERVER['REQUEST_URI'])? " active " : ""}}">
          <a href="/reporttesttable">テストテーブル管理</a>
        </li>
        <li id="storelink" class="{{ preg_match("/^\/shop*/", $_SERVER['REQUEST_URI'])? " active " : ""}}">
          <a href="/shop">店舗管理</a>
        </li>
        <li id="imagelink" class="{{ preg_match("/^\/image*/", $_SERVER['REQUEST_URI'])? " active " : ""}}">
          <a href="/image">画像管理</a>
        </li>
        <li id="userlink" class="{{ preg_match("/^\/user*/", $_SERVER['REQUEST_URI'])? " active " : ""}}">
          <a href="/user">ユーザ管理</a>
        </li>
        <li id="accessloglink" class="dropdown {{ preg_match("/^\/accesslog*/", $_SERVER['REQUEST_URI'])? " active " : ""}}">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">効果測定<span
              class="caret"></span></a>
          <ul class="dropdown-menu">
            <li><a href="/accesslog/forcontents/1">コンテンツデータ</a></li>
            <li><a href="/accesslog/forstore/">店舗閲覧データ</a></li>
          </ul>
        </li>
      </ul>
    </div><!--/.nav-collapse -->


  </div>
</nav>

<div class="container">
  @yield('header')
  @yield('content')
</div><!-- /.container -->

<!-- Bootstrap core JavaScript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
<!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script> -->
<!-- <script src="/js/jquery-3.1.0.min.js"></script>

<!-- <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script> -->
<!-- <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script> -->
<script src="/js/bootstrap-3.3.7/bootstrap.min.js"></script>

<!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
<!-- <script src="../../assets/js/ie10-viewport-bug-workaround.js"></script> -->

@yield('scripts')

<!-- GAタグ -->
<script>
  (function (i, s, o, g, r, a, m) {
    i['GoogleAnalyticsObject'] = r;
    i[r] = i[r] || function () {
      (i[r].q = i[r].q || []).push(arguments)
    }, i[r].l = 1 * new Date();
    a = s.createElement(o), m = s.getElementsByTagName(o)[0];
    a.async = 1;
    a.src = g;
    m.parentNode.insertBefore(a, m)
  })(window, document, 'script', 'https://www.google-analytics.com/analytics.js', 'ga');

  ga('create', '{{ env('GA_TRACKINGID', '') }}', 'auto');
  ga('send', 'pageview');
</script>

<!-- roleコントロール -->
{{--
<script>
  //alert("{{ $role = isset(\Auth::user()->role)? \Auth::user()->role : ""}}");
  if("{{ $role }}" == "store"){
    $("#storelink").attr('class', 'disabled');
    $("#storelink a").attr('href', 'javascript:void(0)');

    $("#imagelink").attr('class', 'disabled');
    $("#imagelink a").attr('href', 'javascript:void(0)');
  }
</script>
--}}
</body>
</html>
