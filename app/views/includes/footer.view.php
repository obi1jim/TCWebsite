<div class="container">
  <footer class="py-3 my-4">
    <?php
    $ses = new \Core\Session();
     if ($ses->is_logged_in()): ?>
    
    <ul class="nav justify-content-center border-bottom pb-3 mb-3">
      <li class="nav-item"><a href="<?=ROOT?>" class="nav-link px-2 text-body-secondary">Home</a></li>
      <li class="nav-item"><a href="<?=ROOT.'/logout'?>" class="nav-link px-2 text-body-secondary">Logout</a></li>
      <li class="nav-item"><a href="<?=ROOT.'/about'?>" class="nav-link px-2 text-body-secondary">About</a></li>
    </ul>
    <?php else: ?>
    <ul class="nav justify-content-center border-bottom pb-3 mb-3">
      <li class="nav-item"><a href="<?=ROOT?>" class="nav-link px-2 text-body-secondary">Home</a></li>
      <li class="nav-item"><a href="<?=ROOT?>/login" class="nav-link px-2 text-body-secondary">Login</a></li>
      <li class="nav-item"><a href="<?=ROOT?>/signup" class="nav-link px-2 text-body-secondary">Signup</a></li>
      
      <li class="nav-item"><a href="<?=ROOT?>/about" class="nav-link px-2 text-body-secondary">About</a></li>
    </ul>
    <?php endif; ?>
    <p class="text-center text-body-secondary">&copy; 2025 Toke Committee</p>
  </footer>
</div>
<script src="<?=ROOT?>/assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>