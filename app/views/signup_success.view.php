<?php $this->view("includes/header", $data); ?>
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-lg">
                <div class="card-header bg-primary text-white text-center">
                    <h2>You've Signed Up!</h2>
                </div>
                <div class="card-body">
                    <p class="lead text-center">
                        An email has been sent to you. Please follow the instructions to complete the process.<br>
                        If you don't see the email, please check your spam folder.<br>
                        The email is sent by HC Toke Committee, so please check your spam folder for that name.<br>
                    </p>
                    <p class="text-center">
                        I know this is annoying, but thank the developer Jimmy for that—he wanted to play it safe to protect the Toke Committee's data and yours
                    </p>
                </div>
                <div class="card-footer text-center">
                    <a href="<?= ROOT ?>" class="btn btn-primary">Back to Home Page</a>
                </div>
            </div>
        </div>
    </div>
</div>
    
<?php $this->view("includes/footer", $data); ?>