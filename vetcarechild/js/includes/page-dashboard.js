
      //query selectors
      var calendarEl = document.getElementById('calendar');
      var containerEl = document.getElementById('external-events');
      var checkbox = document.getElementById('drop-remove');

//array for get data from rest
let globalData = [];

//  array for sending data to rest
let passData=[];

// array for appointment list from database
let appointmentList = [];

// manually input 24 hour
let timePm=0;
let arr = [];
for(let i=1;i<25;i++){
  if(i>12){
    timePm=i % 12;

    if(timePm==0){
      timePm = 12;
      arr.push(timePm+":00 AM");
    }else{
      arr.push(timePm+":00 PM");
    }

  }else{
    // i=i % 12;
    if(i==12){
      arr.push(i+":00 PM");
    }else{
      arr.push(i+":00 AM");

    }
  }
}

//banner
/* Find all slideshow containers */
var slideshowContainers = document.getElementsByClassName("slideshow-container");
/* For each container get starting variables */
for(let s = 0; s < slideshowContainers.length; s++) {
  /* Read the new data attribute */        
  var cycle = slideshowContainers[s].dataset.cycle;
  /* Find all the child nodes with class mySlides */
  var slides = slideshowContainers[s].querySelectorAll('.mySlides');
  var slideIndex = 0;
  /* Now we can cycle slides, but this recursive function must have parameters */
  /* slides and cycle never change, those are unique for each slide container */
  /* slideIndex will increase during each iteration */
  showSlides(slides, slideIndex, cycle);
};

/* Function is alsmost same, but now it uses 3 new parameters */
function showSlides(slides, slideIndex, cycle) {
  for (i = 0; i < slides.length; i++) {
    slides[i].style.display = "none";
  };
  slideIndex++;
  if (slideIndex > slides.length) {
    slideIndex = 1
  };
  slides[slideIndex - 1].style.display = "block";
  /* Calling same function, but with new parameters and cycle time */
  setTimeout(function() {
    showSlides(slides, slideIndex, cycle)
  }, 60000);
};




// display current date and time
let todays = new Date();
let monthName = todays.getMonth()+1;
let dayName = todays.getDate();
let yearName = todays.getFullYear();
let hourName = todays.getHours();
let hourNameGMT = todays.getHours()-8;
let minsName = todays.getMinutes();
let secondssName = todays.getSeconds();
if(hourName <10){
  hourName = '0'+hourName;
}
if(hourNameGMT < 10){
  hourNameGMT = '0'+hourNameGMT;
}
if(minsName < 10){
  minsName = '0'+minsName;
}

let currentDate = yearName+"-"+monthName+"-"+dayName+" "+hourName+":"+minsName+":"+secondssName ;
let currentDateGMT = yearName+"-"+monthName+"-"+dayName+" "+hourNameGMT+":"+minsName+":"+secondssName ;


function removeEvent(id){

  swal({
    title: "Are you sure?",
    text: "Once deleted, you will not be able to recover this appointment!",
    icon: "warning",
    buttons: true,
    dangerMode: true,
  })
  .then((willDelete) => {
    if (willDelete) {
      fetch(url+'trash/'+id,{
        method:"DELETE"
      }).then(res=>{
        if(res.status==200){
          calendarEl.innerHTML = '';
          LoadCalendar();
          summaryAppointment();
        }
      }).catch(e=>{
      });
      swal("Poof! Your Appointment has been deleted!", {
        icon: "success",
      });
    }
  });
}



function loadOption(){
  $.ajax({
    url: url+"appointment/",
    type: 'get',
    dataType: 'json',
    success: function(response) {
      let unique = [...new Set(response.map(item => item.title))];
      let ls ='';
      unique.forEach(e=>{
        ls+=`<option value="${e}">${e}</option>`;
      });
      dropdownListing.innerHTML = ls;
      $('#dropdownListing').chosen({
        no_results_text: "Oops, nothing found!"
      })
    }
  });
}


loadOption();
let today = new Date();
let month = today.getMonth()+1;
let day= today.getDate();
let year = today.getFullYear();
let clickDates = [];
var calendar = new FullCalendar.Calendar(calendarEl, {
  plugins: [ 'interaction', 'dayGrid', 'timeGrid', 'list','bootstrap'],
  height: 'child',
  header: {
    right: 'prev,next today',
    left: 'title',
    center: 'dayGridMonth,timeGridDay,listWeek'
  },
  defaultView: 'listWeek',
  themeSystem:'standard',
  validRange: {
    start:  moment().format('YYYY-MM-DD'),
    end: ''
  },
    navLinks: true, // can click day/week names to navigate views
    editable: true,
    eventLimit: true, // allow "more" link when too many events
    selectable:true,
    longPressDelay:0,
    dateClick:function(info){

    },
    select: function(info) {
      $('#appointmentForm').modal("show");
      $('#scheduledate').val(info.startStr);
      if(info.startStr !== moment().format('YYYY-MM-DD')){
        $('#daysnotif').val(info.startStr);
        $('#nav-sms-tab').prop('hidden',false);
      }else{
        $('#nav-sms-tab').prop('hidden',true);
      }
      $('#complaints').get(0).reset();
    },
    eventRender: function(info) {
      // console.log(info);
      // console.log(view);
      
      if (info.view.type == "listWeek"){
       info.el.children[2].innerHTML = `<b>${info.event.title}</b> : <i>${info.event.extendedProps.description}</i>`;
       // info.el.find('.fc-list-item-title').html(newdisplay);
       // console.log(info);
     }
     $(info.el).tooltip({
      title:    '<div class="popoverTitleCalendar" style="background-color:'+ info.event.backgroundColor +'; color:'+ info.event.textColor +'">'+ info.event.title +'</div>',
      content:  '<div class="popoverInfoCalendar">' +
      '<p><strong>ID:</strong> ' + info.event.id + '</p>' +
      '<p><strong>Time:</strong> ' + moment(info.event.start).format('HH:mm A') +'-'+  moment(info.event.end).format('HH:mm A') +'</p>' +
      '</div>',
      delay: {
       show: "50",
       hide: "50"
     },
     trigger: 'hover',
     placement: 'right',
     html: true,
     container: 'body'
   });
     let show_type = true;
     const selected = document.querySelectorAll('#dropdownListing option:checked');
     const types = Array.from(selected).map(el => el.value);
     if (types && types.length > 0) {
       if (types[0] == "all") {
         show_type = true;
       } else {
         show_type = types.indexOf(info.event.title) >= 0;
       }
     }
     return show_type;

   },
   eventSources:[{url:url+"appointment"}],
   eventTimeFormat:{
    hour: 'numeric',
    minute: '2-digit',
    meridiem: 'short'
  },
  eventClick: function(info) {
    $('#each_appointment').modal("show");
    appointment_all(info.event.id);
  },

});

calendar.render();
  //rerender events
  $('#dropdownListing').on('change',function(x){
    x.preventDefault();
    calendar.rerenderEvents();
    // console.log('ok');
  });

  let fetchAllAppointments;

  document.addEventListener('DOMContentLoaded', function(e) {
    $('#customSwitch2').click(function(){
      if($(this).prop('checked')){
        $('#scheduledate').val(moment().format('YYYY-MM-DD'));
        $('#nav-sms-tab').prop('hidden',true);
      }else{
        $('#scheduledate').val("");
        $('#nav-sms-tab').prop('hidden',false);
      }
    });
    
    $('#hidehistory').click(function(){
      $('#hidehistory').prop('hidden',true);
      $('#showhistory').prop('hidden',false);
      $('#history').prop('hidden',true);

    });
    $('#showhistory').click(function(){
      $('#showhistory').prop('hidden',true);
      $('#hidehistory').prop('hidden',false);
      $('#history').prop('hidden',false);
    });

    $('#from_time').timepicker();
    $('#history').DataTable();
    $('#pet-table').DataTable();
    displayStaff();
    displaypets();
    get_appointments_all();
    smsbalance();

    var ifConnected = window.navigator.onLine;
    if(ifConnected){
     checkNewBanner();
   }

 });


  $("#saveAppointment").click(function( event ) {
    event.preventDefault();
    let data = {};
    $('#form_appointments').serializeArray().forEach(x=>{
      data[x.name] = x.value;
    });

    $('#sms_form').serializeArray().forEach(x=>{
      data[x.name] = x.value;
    });

    $.ajax({
      url: url+"appointment/",
      data:data,
      type: 'post',
      dataType: 'json',
      success: function(response) {
        get_appointments_all();
        calendar.refetchEvents();
        calendar.rerenderEvents();
        $('#appointmentForm').modal('hide');
        Swal.fire({
          position: 'top-end',
          icon: 'success',
          title: 'New Appointment Added!',
          showConfirmButton: false,
          timer: 1500
        })
      }
    });
    return false;
  });

  function displayStaff(){
    let ls="";
    fetch(url+"employees").then(res=>res.json()).then(res=>{
      res.forEach(x=>{
        ls+=`<option value="${x.ID}">${x.name} (${x.role}) </option>`
      });

      $('#staffs').html(ls);
      $('#up_staffs').html(ls);
    });

  }

  function displaypets(){
    let pets = <?php displayPetsName();?>;
    let ls ='';
    pets.forEach(e=>{
      ls+=`<option value="${e.pet_id}">${e.pet_name} (${e.pet_owner}) </option>`;
    });
    $('#pet_list').html(ls);
    $('#pet_list').chosen({ width:'100%' });
  }

  function smsbalance(){
    $.ajax({
      url: url+"sms/balance",
      data:{name:"Raffielim",password:"123456"},
      type: 'POST',
      dataType: 'json',
      success: function(response) {
        $('#smsbalance').html(response.balance);
      }
    });
  }

  function checkNewBanner(){
    let newurl = 'https://vaxilifecorp.com/wp-json/vet/v1/';
    fetch(newurl+'banner').then(res=>res.json()).then(res=>{
     updateLocalTableBanner(res);
   });
  }

  async function updateLocalTableBanner(data){
   await  fetch(url+'updateLocalbanner',{
    method:'POST',
    body:JSON.stringify(data)
  }).then(res=>{
    return res.json();
  })
}
