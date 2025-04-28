<?php $this->view("includes/header", $data); ?>
<!-- I think this is where the main tag goes -->
<main class="container">
    <?php 
    
        show($data['start_pp']);
        show($data['previous_pp']);
        
     ?>
    <h1>Welcome to the Daily Tokes Page</h1>
    <p>This is the Daily Tokes page of the application.</p>
</main>

<?php $this->view("includes/footer", $data); ?>