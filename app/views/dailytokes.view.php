<?php $this->view("includes/header", $data); ?>
<!-- I think this is where the main tag goes -->
<main class="container">
    <?php 
        //show($data['daily_tokes']);
        show($data['daily_tokes'][27]->date_drop);
        show($data['daily_tokes'][0]->daily_drop);
        show($data['daily_tokes'][0]->expiry);
        show($data['daily_tokes'][0]->id);
        show($data['daily_tokes'][0]->date_drop);
     ?>
    <h1>Welcome to the Daily Tokes Page</h1>
    <p>This is the Daily Tokes page of the application.</p>
</main>

<?php $this->view("includes/footer", $data); ?>