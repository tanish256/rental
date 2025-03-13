$(document).ready(function () {
    
    $('#xt').click(function () {
        $('.Tparent.tenant.edit').hide();
    });
    $('#xtr').click(function () {
        $('.Tparent.tenant.r').hide();
    });
    $('#xl').click(function () {
        $('.Tparent.landlord.r').hide();
    });
    $('#xr').click(function () {
        $('.Tparent.room').hide();
    });
    $('#xle').click(function () {
        $('.Tparent.landlord.e').hide();
    });
    $("#landlordForm").submit(function(event){
        event.preventDefault();
        
        var formData = {
            name: $("input[name='name']").val(),
            contact: $("input[name='contact']").val(),
            email: $("input[name='email']").val(),
            location: $("input[name='location']").val()
        };
        
        $.ajax({
            url: "RegisterLandlord.php",
            type: "POST",
            contentType: "application/json",
            data: JSON.stringify(formData),
            success: function(response){
                console.log(response.message);
                    $('.Tparent.landlord.r').hide();
                    location.reload();
                
            },
            error: function(xhr, status, error){
                alert("Error: " + xhr.responseText);
            }
        });
    });
    $("#landlordForm2").submit(function(event){
        event.preventDefault();
        
        var formData = {
            name: $("input[name='lname']").val(),
            contact: $("input[name='lcontact']").val(),
            email: $("input[name='lemail']").val(),
            location: $("input[name='llocation']").val(),
            rooms: $("input[name='lrooms']").val(),
            id: $("input[name='lid']").val()
        };
        
        $.ajax({
            url: "RegisterLandlord.php",
            type: "POST",
            contentType: "application/json",
            data: JSON.stringify(formData),
            success: function(response){
                console.log(response.message);
                    $('.Tparent.landlord.r').hide();
                    location.reload();
                
            },
            error: function(xhr, status, error){
                alert("Error: " + xhr.responseText);
            }
        });
    });

    
});
$("#Editrrom").submit(function(event){
    event.preventDefault();
    
    var formData = {
        id: $("input[name='rid']").val(),
        condition: $("input[name='rcondition']").val(),
        amount: $("input[name='ramount']").val(),
        location: $("input[name='rlocation']").val()
    };
    
    $.ajax({
        url: "editroom.php",
        type: "POST",
        contentType: "application/json",
        data: JSON.stringify(formData),
        success: function(response){
            console.log(response.message);
                $('.Tparent.room').hide();
                location.reload();
            
        },
        error: function(xhr, status, error){
            alert("Error: " + xhr.responseText);
        }
    });
});
function Rdel(){
    if (confirm('Are you sure you want to delete Room #'+$("input[name='rid']").val())) {
        var formData = {
            id: $("input[name='rid']").val(),
            condition: $("input[name='rcondition']").val(),
            amount: $("input[name='ramount']").val(),
            location: $("input[name='rlocation']").val(),
            del:1
        };
        
        $.ajax({
            url: "editroom.php",
            type: "POST",
            contentType: "application/json",
            data: JSON.stringify(formData),
            success: function(response){
                console.log(response.message);
                    $('.Tparent.room').hide();
                    location.reload();
                
            },
            error: function(xhr, status, error){
                alert("Error: " + xhr.responseText);
            }
        });
    } else {

    }
    
}
function TReport(tenantId) {
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
                $('#tamount').val('Room Value: UGX '+Number(data.data.amount).toLocaleString());
                $('#tlocation').val(data.data.location); // Ensure API returns 'location'
                $('#tbalance').val('Balance: UGX '+ Number(data.data.balance).toLocaleString());
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
                $('#tidd').val(data.data.id);
                $('#tlandlord').val('landlord: '+data.data.landlord);
                $('#tlocation').val(data.data.location); // Ensure API returns 'location'
                $('#tbalance').val('Balance: UGX '+ Number(data.data.balance).toLocaleString());
                $('#tdate').val('registered on '+data.data.date_onboarded);
                $('#Tdel').attr('onclick', 'Tdel(' + data.data.id + ')');
            } else {
                alert('Error fetching tenant data!');
            }
        },
        error: function(xhr, status, error) {
            console.error('Error:', error);
        }
    });
}
function Redit(id) {
    $('.Tparent.room').css('display', 'flex');
    $.ajax({
        url: 'Vhelper.php',
        type: 'GET',
        data: { room: id }, // Send the tenant ID as a GET parameter
        dataType: 'json',
        success: function(data) {
            if (data.success) {
                // Update the DOM with received data
                $('#rid').val("Room id#"+data.data.id);
                $('#ridd').val(data.data.id);
                $('#landlordname').val(data.data.landlord_name);
                $('#rcondition').val(data.data.roomcondition);
                $('#rlocation').val(data.data.location);
                $('#ramount').val(data.data.amount);
                $('#rdel').attr('onclick', 'Rdel(' + data.data.id + ')');
            } else {
                alert('Error fetching tenant data!');
            }
        },
        error: function(xhr, status, error) {
            console.error('Error:', error);
        }
    });
}
function Elandlord(id) {
    $('.Tparent.landlord.e').css('display', 'flex');
$.ajax({
url: 'Vhelper.php',
type: 'GET',
data: { landlord: id }, // Send the tenant ID as a GET parameter
dataType: 'json',
success: function(data) {
if (data.success) {
    // Update the DOM with received data
    let dateOnboarded = data.data["date onboarded"];
    $('#lname').val(data.data.name);
    $('#lcontact').val(data.data.contact);
    $('#lid').val(data.data.id);
    $('#lidd').val("ID#"+data.data.id);
    $('#llocation').val(data.data.location); // Ensure API returns 'location'
    $('#lemail').val(data.data.email);
    $('#ldate').val('registered on '+dateOnboarded);
    $('#Ldel').attr('onclick', 'Ldel(' + data.data.id + ')');
    //$("#thistory").attr("href", "transhistry.php?tid="+data.data.id);
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
function Rlandlord() {
    $('.Tparent.landlord.r').css('display', 'flex');
        
    }

    function RTenant() {
        $('.Tparent.tenant.r').css('display', 'flex');
    
    }
    $("#RegisterT").submit(function(event){
        event.preventDefault();
        
        var formData = {
            id: $("input[name='trid']").val(),
            name: $("input[name='trname']").val(),
            room: $("input[name='trroom']").val(),
            contact: $("input[name='trcontact']").val()
        };
        
        $.ajax({
            url: "RegisterTenant.php",
            type: "POST",
            contentType: "application/json",
            data: JSON.stringify(formData),
            success: function(response){
                console.log(response.message);
                $('.Tparent.tenant.r').hide();
                    location.reload();
                
            },
            error: function(xhr, status, error){
                alert("Error: " + xhr.responseText);
            }
        });
    });
    $("#EditTenant").submit(function(event){
        event.preventDefault();
        
        var formData = {
            id: $("input[name='tid']").val(),
            name: $("input[name='tname']").val(),
            room: $("input[name='troom']").val(),
            contact: $("input[name='tcontact']").val()
        };
        
        $.ajax({
            url: "RegisterTenant.php",
            type: "POST",
            contentType: "application/json",
            data: JSON.stringify(formData),
            success: function(response){
                console.log(response.message);
                $('.Tparent.tenant.edit').hide();
                    location.reload();
                
            },
            error: function(xhr, status, error){
                alert("Error: " + xhr.responseText);
            }
        });
    });
    $('#lrooms').keydown(function(event) {
    event.preventDefault(); // Prevent typing any keys
});