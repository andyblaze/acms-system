<?=view('partials/page-head');?>

<div class="container">
    <div class="row">
        <div class="col">
            <header>
                <h1>Welcome to CodeIgniter <?= CodeIgniter\CodeIgniter::CI_VERSION ?></h1>
                <h2>The small framework with powerful features</h2>
            </header>
            <section class="cms-editable">
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

<?=view('partials/page-foot');?>