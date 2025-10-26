             <div class="summary">

                <!-- ...................................summary.................................. -->
                <div class="sum">
                    <div class="circle">
                        <img src="../assets/profile-2user.svg" alt="">
                    </div>
                    <div class="inf">
                        <h3>Total Tenants</h3>
                        <h4><?php echo $ttenants?></h4>
                        <p>this month</p>
                    </div>
                </div>

                <div class="sum">
                    <div class="circle">
                        <img src="../assets/profile-tick.svg" alt="">
                    </div>
                    <div class="inf">
                        <h3>Total landlords</h3>
                        <h4><?php echo $tlandlords?></h4>
                        <p>this month</p>
                    </div>
                </div>

                <div class="sum">
                    <div class="circle">
                        <img src="../assets/monitor.svg" alt="">
                    </div>
                    <div class="inf">
                        <h3>Vacant Rooms</h3>
                        <h4><?php echo count($roomsWithoutTenant)?></h4>
                        <p>this month</p>
                    </div>
                </div>

            </div>