$(document).ready(function () {
    $('.clickable-row').on('click', function () {
        const $row = $(this);
        const landlordId = $row.data('id'); // Get landlord ID from the row's data attribute

        // Remove any existing sub-rows before making a new request
        if ($('.sub-row').length) {
  $('.sub-row').remove();
}else{
        $.ajax({
            url: '../helpers/Vhelper.php', // Replace with actual API endpoint
            method: 'GET',
            data: { blandlord: landlordId }, // Send landlord ID as parameter
            dataType: 'json',
            success: function (response) {
                if (response.success) {
                    // Parse the JSON properly (as some values are double-encoded)
                    //const tenants = response.data.map(tenant => JSON.parse(tenant));

                    response.data.forEach((data) => {
                        const $subRow = $(`
                            <tr class="sub-row">
                                <td>${data.name}</td>
                                <td>${data.balance_bf.toLocaleString()}</td>
                                <td>${data.balance_due.toLocaleString()}</td>
                                <td>${(data.balance_due + data.balance_bf - data.balance).toLocaleString()}</td>
                                <td>${data.balance.toLocaleString()}</td>
                                <td class="${data.balance > 0 ? 'status-inactive' : 'status-active'}">
                                    <div>${data.balance > 0 ? 'Pending' : 'Cleared'}</div>
                                </td>
                            </tr>
                        `);

                        $row.after($subRow);
                    });
                } else {
                    console.log('No tenants found.');
                }
            },
            error: function (error) {
                console.log('Error fetching data:', error);
            }
        });
      }
    });
});