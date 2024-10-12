
function suggetion() {

     $('#sug_input').keyup(function(e) {

         var formData = {
             'product_name' : $('input[name=title]').val()
         };

         if(formData['product_name'].length >= 1){

           // process the form
           $.ajax({
               type        : 'POST',
               url         : 'ajax.php',
               data        : formData,
               dataType    : 'json',
               encode      : true
           })
               .done(function(data) {
                   //console.log(data);
                   $('#result').html(data).fadeIn();
                   $('#result li').click(function() {

                     $('#sug_input').val($(this).text());
                     $('#result').fadeOut(500);

                   });

                   $("#sug_input").blur(function(){
                     $("#result").fadeOut(500);
                   });

               });

         } else {

           $("#result").hide();

         };

         e.preventDefault();
     });

 }
  $('#sug-form').submit(function(e) {
      var formData = {
          'p_name' : $('input[name=title]').val()
      };
        // process the form
        $.ajax({
            type        : 'POST',
            url         : 'ajax.php',
            data        : formData,
            dataType    : 'json',
            encode      : true
        })
            .done(function(data) {
                //console.log(data);
                $('#product_info').html(data).show();
                total();
                $('.datePicker').datepicker('update', new Date());

            }).fail(function() {
                $('#product_info').html(data).show();
            });
      e.preventDefault();
  });
  function total(){
    $('#product_info input').change(function(e)  {
            var price = +$('input[name=price]').val() || 0;
            var qty   = +$('input[name=quantity]').val() || 0;
            var total = qty * price ;
                $('input[name=total]').val(total.toFixed(2));
    });
  }

  $(document).ready(function() {

    //tooltip
    $('[data-toggle="tooltip"]').tooltip();

    $('.submenu-toggle').click(function () {
       $(this).parent().children('ul.submenu').toggle(200);
    });
    //suggetion for finding product names
    suggetion();
    // Callculate total ammont
    total();

    $('.datepicker')
        .datepicker({
            format: 'yyyy-mm-dd',
            todayHighlight: true,
            autoclose: true
        });
  });

  function submitUserForm() {
    var formData = new FormData(document.getElementById('addUserForm'));
  
    fetch('add_user_ajax.php', {
      method: 'POST',
      body: formData
    })
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        // Update the table with the new user data
        var newUserRow = `
          <tr>
            <td class="text-center border px-4 py-2">${data.user.id}</td>
            <td class="border px-4 py-2">${data.user.name}</td>
            <td class="border px-4 py-2">${data.user.username}</td>
            <td class="text-center border px-4 py-2">${data.user.role}</td>
            <td class="text-center border px-4 py-2">
              <span class="${data.user.status === '1' ? 'bg-green-500' : 'bg-red-500'} text-white px-2 py-1 rounded">${data.user.status === '1' ? 'Active' : 'Deactive'}</span>
            </td>
            <td class="border px-4 py-2">${data.user.last_login}</td>
            <td class="text-center border px-4 py-2">
              <div class="flex justify-center space-x-2">
                <a href="edit_user.php?id=${data.user.id}" class="bg-yellow-500 text-white px-2 py-1 rounded hover:bg-yellow-600">
                  <i class="glyphicon glyphicon-pencil"></i>
                </a>
                <a href="delete_user.php?id=${data.user.id}" class="bg-red-500 text-white px-2 py-1 rounded hover:bg-red-600">
                  <i class="glyphicon glyphicon-trash"></i>
                </a>
              </div>
            </td>
          </tr>
        `;
        document.getElementById('userTableBody').insertAdjacentHTML('beforeend', newUserRow);
        closeModal();
      } else {
        alert(data.message || 'Failed to add user');
      }
    })
    .catch(error => {
      console.error('Error:', error);
      alert('An error occurred while adding the user');
    });
  }
  
