<?php // this part is probably common to all pages ?>
<?=doctype();?>
<html lang="en">
<head>
<meta charset="UTF-8">
<title><?=$page_title;?></title>
<?=meta('description', $meta_description) .
meta('viewport', 'width=device-width, initial-scale=1.0') .
link_tag('/favicon.ico', 'shortcut icon', 'image/png') .
link_tag('css/bootstrap.min.css');?>
</head>
<body>

<!-- this menu will be rendered from DB menu / pages entries in controller ( or library ) and passed into view data -->
<nav class="navbar navbar-expand-lg bg-body-tertiary">
  <div class="container-fluid">
    <a class="navbar-brand" href="#">Navbar</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="#">Home</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#">Features</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#">Pricing</a>
        </li>
        <li class="nav-item">
          <a class="nav-link disabled" aria-disabled="true">Disabled</a>
        </li>
      </ul>
    </div>
  </div>
</nav>



<!-- content that changes per page - from DB -->

<div class="container">
    <div class="row">
        <div class="col">
            <header>
                <div>
                    <h1>Welcome to CodeIgniter <?= CodeIgniter\CodeIgniter::CI_VERSION ?></h1>
                    <h2>The small framework with powerful features</h2>
                </div>
            </header>
            <section>
                <h1>About this page</h1>
                <p>The page you are looking at is being generated dynamically by CodeIgniter.</p>
                <p>If you would like to edit this page you will find it located at:</p>
                <pre><code>app/Views/welcome_message.php</code></pre>
                <p>The corresponding controller for this page can be found at:</p>
                <pre><code>app/Controllers/Home.php</code></pre>
            </section>
        </div>
    </div>
</div>

<!-- mostly common to all pages -->
<div class="container">
    <div class="row">
        <div class="col">
            <footer>
                <ul class="nav nav-pills"> <!-- this menu rendered from DB menus / pages entries -->
                  <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="#">Active</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" href="#">Link</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" href="#">Link</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link disabled" aria-disabled="true">Disabled</a>
                  </li>
                </ul>
                <div> <!-- these bits are just for debugging / reporting -->
                    <p>Page rendered in {elapsed_time} seconds using {memory_usage} MB of memory.</p>
                    <p>Environment: <?= ENVIRONMENT ?></p>
                </div>
            </footer>
        </div>
    </div>
</div>

<!-- common to all pages -->
<?=script_tag('js/jquery-3.7.1.min.js') .
script_tag('js/bootstrap.bundle.min.js');?>
</body>
</html>
