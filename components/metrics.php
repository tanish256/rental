<style>
  tr {
      cursor: pointer;
    }
    .metrics{
      width: 100%;
      justify-content: center;
      gap: 10px;
      flex-wrap: wrap;
      .card{
        width: 23%;
        padding: 10px;
      }
    }
    .root .dashmain{
      width: 72%;
    }
    .date{
      gap: 10px;
      width: 100%;
      align-items: center;
      padding-inline: 20px;
      opacity:0.4;
      user-select: none;
      pointer-events: none;
      display: flex;
      font-weight: bold;
      p{
        color: rgb(32, 85, 32);
        margin: 0;
      }
      .sort-component{
      width: fit-content;
      .sort-select{
        width: 100px;
        font-weight: bold;
      }
    }
    }
    
</style>
<div class="metrics">
    
    <div class="date">
    <p>Financial Year</p>
    <div class="sort-component">
        <select id="sort-options" class="sort-select">
        <option value="name-asc"><?php echo date('Y')?></option>
        <option value="name-desc">2022</option>
        <option value="date-asc">2023</option>
        <option value="date-desc">2024</option>
        </select>
    </div>
    <div class="sort-component">
        <select id="sort-options" class="sort-select">
        <option value="name-asc"><?php echo date('M')?></option>
        <option value="name-desc">Feb</option>
        <option value="date-asc">Mar</option>
        <option value="date-desc">Aprirufgro</option>
        </select>
    </div>
    <div class="month"></div>
    </div>
    <div class="card">
        <p>UGX <?php echo number_format($total_balance_bfw, 0, '.', ',')?></p>
        <h3>Balance b/F</h3>
        <p>this month</p>
    </div>

    <div class="card">
        <p>UGX <?php echo number_format($total_balance_duew, 0, '.', ',') ?></p>
        <h3>Expected Gross</h3>
        <p>this month</p>
    </div>

    <div class="card">
        <p>UGX <?php echo number_format($total_balance_duew+$total_balance_bfw-$total_balance, 0, '.', ',') ?></p>
        <h3>Total Payment</h3>
        <p>this month</p>
    </div>

    <div class="card">
    <p>UGX <?php echo number_format($total_balance-$total_balance2, 0, '.', ',') ?></p>
    <h3>Upfront Payment</h3>
    <p>this month</p>
    </div>

    <div class="card">
        <p>UGX <?php echo number_format($total_balance, 0, '.', ',')  ?></p>
        <h3>Total Balance</h3>
        <p>this month</p>
    </div>
    <?php
        // First calculate all profits
        $exp_profit = 0;
        $got_profit = 0;

        foreach ($landlords as $landlord) {
            $tenantsl = getTenantsByLandlord($landlord['id']);
            $tenantst = json_decode($tenantsl, true);
            
            $total_balance_due = 0;
            $total_balance_bf = 0;
            $total_balance = 0;
            foreach ($tenantst as $tenant) {
                $total_balance_due += isset($tenant['balance_due']) ? $tenant['balance_due'] : 0;
                $total_balance += isset($tenant['balance']) ? $tenant['balance'] : 0;
                $total_balance_bf += isset($tenant['balance_bf']) ? $tenant['balance_bf'] : 0;
            }
            $final_balance = $total_balance_due + $total_balance_bf - $total_balance;
            $commission =getcomission($landlord,$final_balance,count($tenantst));
            $commission_main = getcomission($landlord, $total_balance_due, count($tenantst));
            $exp_profit += $commission_main;
            $got_profit += $commission;
        }
    ?>

    <div class="card">
        <p>UGX <?php echo number_format($exp_profit, 0, '.', ',') ?></p>
        <h3>Expected Profit</h3>
        <p> this month</p>
    </div>

    <div class="card">
        <p>UGX <?php echo number_format($got_profit, 0, '.', ',')?> </p>
        <h3>Accumulated Profit</h3>
        <p> this month</p>
    </div>
</div>