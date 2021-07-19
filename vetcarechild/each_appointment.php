<script>
  let appointments = [];
  let staffList = [];
  let cdetails = {
        clinic_id : <?php echo $myclinic_id;?>
    }
  function get_appointments_all(){
    fetch(url+"appointment_all").then(res=>res.json()).then(res=>{
      appointments = res;
    });

	
    fetch(url+"employees",{
        method: 'POST',
        body : JSON.stringify(cdetails)
    }).then(res=>res.json()).then(res=>{
      staffList = res;
    });
	
  }



  document.querySelector('#updateAppointment').addEventListener("click",function(e) {
    e.preventDefault();
    let data = {};
    let form = document.querySelector('#update_form_appointment');
    form = serializeArray(form);
    form.forEach(x=>{
      data[x.name] = x.value;
    });
      $.ajax({
        url: url+"appointment/edit",
        data:JSON.stringify(data),
        type: 'post',
        dataType: 'json',
        success: function(response) {
            if(response.message=="OK"){
                get_appointments_all();
          calendar.refetchEvents();
          calendar.rerenderEvents();
          $('#each_appointment').modal('hide');
          Swal.fire({
            position: 'top-end',
            icon: 'success',
            title: 'Appointment Updated!',
            showConfirmButton: false,
            timer: 1500
          })
            }
          
        }
      });
      return false;
    });

  document.querySelector('#deleteAppointment').addEventListener("click",function(e) {
    e.preventDefault();
    let data = {};
    let form = document.querySelector('#update_form_appointment');
    form = serializeArray(form);
    form.forEach(x=>{
      if(x.name=="up_appointmentID"){
        data[x.name] = x.value;
        data.action = "delete";
      }
    });
    Swal.fire({
      title: 'Are you sure?',
      text: "You won't be able to recover this appointment!",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
      if (result.value) {
        $.ajax({
          url: url+"appointment/remove",
          data:data,
          type: 'post',
          dataType: 'json',
          success: function(response) {
              if(response.message=="OK"){
                get_appointments_all();
            calendar.refetchEvents();
            calendar.rerenderEvents();
            $('#each_appointment').modal('hide');
              Swal.fire(
          'Deleted!',
          'Your appointment has been deleted.',
          'success'
          )
              }
          }
        });
        return false;
      }
    });
  });



</script>
