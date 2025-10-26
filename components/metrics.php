<style>
  :root {
    --primary-color: #007bff;
    --secondary-color: #6c757d;
    --success-color: #28a745;
    --danger-color: #dc3545;
    --warning-color: #ffc107;
    --info-color: #17a2b8;
    --light-color: #f8f9fa;
    --dark-color: #343a40;
    --border-radius: 8px;
    --card-shadow: 0 2px 6px rgba(0, 0, 0, 0.06);
    --transition: all 0.2s ease;
  }

  .metrics-container {
    width: 93%;
    margin-bottom: 20px;
  }

  .date-filter-section {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 10px 15px;
    margin-bottom: 15px;
    background: var(--light-color);
    border-radius: var(--border-radius);
    box-shadow: var(--card-shadow);
    font-size: 14px;
  }

  .date-filter-section p {
    margin: 0;
    font-weight: 600;
    color: var(--dark-color);
    font-size: 14px;
  }

  .filter-group {
    display: flex;
    gap: 8px;
    align-items: center;
  }

  .sort-component {
    position: relative;
  }
  .sort-component select{
    pointer-events: none;
    opacity: 0.6;
    background-color: #f5f5f5;
  }

  .sort-select {
    padding: 6px 8px;
    border-radius: 6px;
    border: 1px solid #dee2e6;
    background-color: white;
    font-weight: 500;
    cursor: pointer;
    transition: var(--transition);
    min-width: 90px;
    font-size: 13px;
  }

  .sort-select:focus {
    outline: none;
    border-color: var(--primary-color);
    box-shadow: 0 0 0 2px rgba(0, 123, 255, 0.1);
  }

  .metrics-grid {
    display: grid;
    grid-template-columns: auto auto auto auto;
    gap: 12px;
    width: 100%;
  }

  .metric-card {
    background: white;
    border-radius: var(--border-radius);
    padding: 15px;
    box-shadow: var(--card-shadow);
    transition: var(--transition);
    border-left: 6px solid var(--primary-color);
    border-bottom: 4px solid #63a0ddff;
    position: relative;
    overflow: hidden;
  }

  .metric-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
  }

  .metric-value {
    font-size: 18px;
    font-weight: 700;
    margin: 5px 0 3px;
    color: var(--dark-color);
    line-height: 1.2;
  }

  .metric-title {
    font-size: 13px;
    font-weight: 600;
    margin: 0 0 4px;
    line-height: 1.3;
  }

  .metric-description {
    font-size: 11px;
    color: var(--secondary-color);
    margin: 0;
    line-height: 1.2;
  }

  .metric-trend {
    display: flex;
    align-items: center;
    font-size: 11px;
    margin-top: 5px;
  }

  .trend-up {
    color: var(--success-color);
  }

  .trend-down {
    color: var(--danger-color);
  }

  .trend-icon {
    margin-right: 3px;
    font-weight: bold;
    font-size: 10px;
  }

  /* Color coding for different metric types */
  .card-balance {
    border-left-color: var(--danger-color);
  }

  .card-revenue {
    border-left-color: var(--primary-color);
  }

  .card-payment {
    border-left-color: var(--success-color);
  }

  .card-profit {
    border-left-color: var(--success-color);
  }

  /* Compact layout for very small screens */
  @media (max-width: 1200px) {
    .metrics-grid {
      grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
    }
  }

  @media (max-width: 768px) {
    .date-filter-section {
      flex-direction: column;
      align-items: flex-start;
      gap: 8px;
      padding: 8px 12px;
    }
    
    .filter-group {
      width: 100%;
      justify-content: space-between;
    }
    
    .sort-component {
      flex: 1;
    }
    
    .sort-select {
      width: 100%;
    }
    
    .metrics-grid {
      grid-template-columns: repeat(auto-fill, minmax(140px, 1fr));
      gap: 10px;
    }
    
    .metric-card {
      padding: 12px;
    }
    
    .metric-value {
      font-size: 16px;
    }
    
    .metric-title {
      font-size: 12px;
    }
  }

  @media (max-width: 480px) {
    .filter-group {
      flex-direction: column;
      gap: 6px;
    }
    
    .sort-component {
      width: 100%;
    }
    
    .metrics-grid {
      grid-template-columns: repeat(2, 1fr);
      gap: 8px;
    }
    
    .metric-card {
      padding: 10px;
    }
    
    .metric-value {
      font-size: 15px;
    }
  }

  /* Extra compact mode for dense layouts */
  .metrics-grid.compact {
    grid-template-columns: auto auto auto auto;
    gap: 8px;
  }
  
  .metrics-grid.compact .metric-card {
    padding: 10px 8px;
  }
  
  .metrics-grid.compact .metric-value {
    font-size: 16px;
  }
  
  .metrics-grid.compact .metric-title {
    font-size: 12px;
  }
  
  .metrics-grid.compact .metric-description {
    font-size: 10px;
  }
</style>

<div class="metrics-container">
  <div class="date-filter-section" >
    <p>Financial Year</p>
    <div class="filter-group">
      <div class="sort-component">
        <select id="year-select" class="sort-select">
          <option value="<?php echo date('Y')?>"><?php echo date('Y')?></option>
          <option value="2022">2022</option>
          <option value="2023">2023</option>
          <option value="2024">2024</option>
        </select>
      </div>
      <div class="sort-component">
        <select id="month-select" class="sort-select">
          <option value="<?php echo date('n')?>"><?php echo date('M')?></option>
          <option value="2">Feb</option>
          <option value="3">Mar</option>
          <option value="4">Apr</option>
          <option value="5">May</option>
          <option value="6">Jun</option>
          <option value="7">Jul</option>
          <option value="8">Aug</option>
          <option value="9">Sep</option>
          <option value="10">Oct</option>
          <option value="11">Nov</option>
          <option value="12">Dec</option>
        </select>
      </div>
    </div>
  </div>

  <div class="metrics-grid compact">
    <div class="metric-card card-balance">
      <div class="metric-value">UGX <?php echo number_format($total_balance_bfw, 0, '.', ',')?></div>
      <h3 class="metric-title">Balance B/F</h3>
      <p class="metric-description">Previous month</p>
    </div>

    <div class="metric-card card-revenue">
      <div class="metric-value">UGX <?php echo number_format($total_balance_duew, 0, '.', ',') ?></div>
      <h3 class="metric-title">Expected Gross</h3>
      <p class="metric-description">This month</p>
    </div>

    <div class="metric-card card-payment">
      <div class="metric-value">UGX <?php echo number_format($total_balance_duew+$total_balance_bfw-$total_balance, 0, '.', ',') ?></div>
      <h3 class="metric-title">Total Payment</h3>
      <p class="metric-description">Collected</p>
    </div>

    <div class="metric-card card-revenue">
      <div class="metric-value">UGX <?php echo number_format($total_balance-$total_balance2, 0, '.', ',') ?></div>
      <h3 class="metric-title">Upfront Payment</h3>
      <p class="metric-description">Advance payments</p>
    </div>

    <div class="metric-card card-balance">
      <div class="metric-value">UGX <?php echo number_format($total_balance, 0, '.', ',')  ?></div>
      <h3 class="metric-title">Total Balance</h3>
      <p class="metric-description">Outstanding</p>
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
        $commission = getcomission($landlord,$final_balance,count($tenantst));
        $commission_main = getcomission($landlord, $total_balance_due, count($tenantst));
        $exp_profit += $commission_main;
        $got_profit += $commission;
      }
    ?>

    <div class="metric-card card-profit">
      <div class="metric-value">UGX <?php echo number_format($exp_profit, 0, '.', ',') ?></div>
      <h3 class="metric-title">Expected Profit</h3>
      <p class="metric-description">Projected</p>
    </div>

    <div class="metric-card card-profit">
      <div class="metric-value">UGX <?php echo number_format($got_profit, 0, '.', ',')?> </div>
      <h3 class="metric-title">Accumulated Profit</h3>
      <p class="metric-description">Actual</p>
    </div>
  </div>
</div>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    const yearSelect = document.getElementById('year-select');
    const monthSelect = document.getElementById('month-select');
    
    function updateMetrics() {
      const metricCards = document.querySelectorAll('.metric-card');
      metricCards.forEach(card => {
        card.style.opacity = '0.7';
      });
      
      setTimeout(() => {
        metricCards.forEach(card => {
          card.style.opacity = '1';
        });
      }, 300);
    }
    
    yearSelect.addEventListener('change', updateMetrics);
    monthSelect.addEventListener('change', updateMetrics);
  });
</script>