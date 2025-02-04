$(document).ready(function () {
    
    $('#xt').click(function () {
        $('.Tparent.tenant').hide();
    });
    $('#xl').click(function () {
        $('.Tparent.landlord').hide();
    });
    

    
});

function TReport(tenantId) {
    $('.Tparent.tenant').css('display', 'flex');
    $.ajax({
        url: 'Vhelper.php',
        type: 'GET',
        data: { tenant: tenantId }, // Send the tenant ID as a GET parameter
        dataType: 'json',
        success: function(data) {
            if (data.success) {
                // Update the DOM with received data
                $('#tname').val(data.data.name);
                $('#troom').val('room id: #'+data.data.room_id);
                $('#tcontact').val(data.data.contact);
                $('#tid').val('tenant id: #'+data.data.id);
                $('#tlandlord').val('landlord: '+data.data.landlord);
                $('#tlocation').val(data.data.location); // Ensure API returns 'location'
                $('#tbalance').val('Balance: UGX '+data.data.balance);
                $('#tdate').val('registered on '+data.data.date_onboarded);
                $("#thistory").attr("href", "transhistry.php?tid="+data.data.id);
               // $('#tenantDetails').show(); // Show the details div
            } else {
                alert('Error fetching tenant data!');
            }
        },
        error: function(xhr, status, error) {
            console.error('Error:', error);
        }
    });
}
function TEdit(tenantId) {
    $('.Tparent.tenant.edit').css('display', 'flex');
    $.ajax({
        url: 'Vhelper.php',
        type: 'GET',
        data: { tenant: tenantId }, // Send the tenant ID as a GET parameter
        dataType: 'json',
        success: function(data) {
            if (data.success) {
                // Update the DOM with received data
                $('#tname').val(data.data.name);
                $('#troom').val('room id: #'+data.data.room_id);
                $('#tcontact').val(data.data.contact);
                $('#tid').val('tenant id: #'+data.data.id);
                $('#tlandlord').val('landlord: '+data.data.landlord);
                $('#tlocation').val(data.data.location); // Ensure API returns 'location'
                $('#tbalance').val('Balance: UGX '+data.data.balance);
                $('#tdate').val('registered on '+data.data.date_onboarded);
               // $('#tenantDetails').show(); // Show the details div
            } else {
                alert('Error fetching tenant data!');
            }
        },
        error: function(xhr, status, error) {
            console.error('Error:', error);
        }
    });
}

