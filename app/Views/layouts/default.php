<?=view('partials/page-head');?>

<div class="container">
    <div class="row">
        <div class="col">
            <header>
                <?=$hero_image->render();?>
                <h1>Welcome to CodeIgniter <?= CodeIgniter\CodeIgniter::CI_VERSION ?></h1>
                <h2>The small framework with powerful features</h2>
                <h3><?=$telNo;?></h3>
            </header>
            <section class="cms-editable">
                <?=$main_content->render();?>
            </section>
        </div>
    </div>
</div>

<?=view('partials/page-foot');?>