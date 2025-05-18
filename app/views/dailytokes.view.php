<?php $this->view("includes/header", $data); ?>
<!-- I think this is where the main tag goes -->
<main class="container">
   
    <h1>Daily Tokes</h1>
    <p>These values are meant to be updated weekly. Daily Updates are not guaranteed</p>
    <p>Daily Drops may not be 100% accurate since some data isn't available at the same time, such as digital Craps or Rapid daily drops </p>

    <h3>Current Pay Period</h3>

    <table class="table table-sm table-bordered table-striped">
  <thead>
    <tr>
      <th scope="col">Day Of Week</th>
      <th scope="col">Drop Date</th>
      <th scope="col">Amount</th>
      
    </tr>
  </thead>
  <tbody>
    <?php 
        for($i = 14; $i < 28; $i++){
            echo "<tr>\n";
            echo "<th scope=\"row\">".$data['daily_tokes'][$i]->day_of_week."</th>\n";
            // Format date as mm/dd/yyyy
            $date = date('m/d/Y', strtotime($data['daily_tokes'][$i]->date_drop));
            echo "<td>".$date."</td>\n";
            // Format amount as currency
            $amount = number_format($data['daily_tokes'][$i]->daily_drop, 2);
            echo "<td>$".$amount."</td>\n";
            echo "</tr>\n";
        }
        //I just discovered that variable made inside the for loops can still be accessed outside the loop's scope.
        //this will ensure that the variables are unset and not used outside the loop.
        unset($date);
        unset($amount);
        unset($i);
    ?>
  </tbody>
</table>

<h3>Previous Pay Period</h3>

    <table class="table table-bordered table-striped">
  <thead>
    <tr>
      <th scope="col">Day Of Week</th>
      <th scope="col">Drop Date</th>
      <th scope="col">Amount</th>
      
    </tr>
  </thead>
  <tbody>
    <?php 
        for($i = 0; $i < 14; $i++){
            echo "<tr>\n";
            echo "<th scope=\"row\">".$data['daily_tokes'][$i]->day_of_week."</th>\n";
            // Format date as mm/dd/yyyy
            $date = date('m/d/Y', strtotime($data['daily_tokes'][$i]->date_drop));
            echo "<td>".$date."</td>\n";
            // Format amount as currency
            $amount = number_format($data['daily_tokes'][$i]->daily_drop, 2);
            echo "<td>$".$amount."</td>\n";
            echo "</tr>\n";
        }
        unset($date);
        unset($amount);
        unset($i);
    ?>
  </tbody>
</table>
</main>

<?php $this->view("includes/footer", $data); ?>