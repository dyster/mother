<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
                {{ get_title() }}
                {{ stylesheet_link('bootstrap/css/bootstrap.min.css') }}
                {{ stylesheet_link('bootstrap/css/bootstrap-theme.min.css') }}

                <meta name="viewport" content="width=device-width, initial-scale=1">
                <meta name="description" content="mother">
                <meta name="author" content="Coresys">
                <meta http-equiv="X-UA-Compatible" content="IE=edge">

                <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
                <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
                <!--[if lt IE 9]>
                  <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
                  <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
                <![endif]-->



	</head>
	<body role="document" style="padding-top: 50px;">

	<div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
        <div class="container">
          <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
              <span class="sr-only">Toggle navigation</span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="#">Project name</a>
          </div>
          <div class="navbar-collapse collapse">
          {{ elements.getMenu() }}

          </div><!--/.nav-collapse -->
        </div>
    </div>

		{{ content() }}
    
		{{ javascript_include('js/jquery/jquery.min.js') }}
        {{ javascript_include('bootstrap/js/bootstrap.min.js') }}
	</body>
</html>