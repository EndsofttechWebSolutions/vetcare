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

//JSON obj for getting all details in adding appointments
let dataObj ={
  date :[],
  timeStart :[],
  timeEnd :[],
  session :[],
  price :[]
} 

let forSort = [];

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
  let unique = [...new Set(appointmentList.map(item => item.title))];
  let ls ='';
  unique.forEach(e=>{
    ls+=`<option value="${e}">${e}</option>`;
  });
  dropdownListing.innerHTML = ls;
  $('#dropdownListing').chosen({
    no_results_text: "Oops, nothing found!"
  })
}


function LoadCalendar(){
    appointmentList = [];
    let dd = <?php get_appointments();?>;
    appointmentList = <?php get_appointments();?>;;
    loadOption();

  //calendar
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
    visibleRange: {
      start: <?php echo date("Y-m-d"); ?>,
      end: ''
    },
    navLinks: true, // can click day/week names to navigate views
    editable: true,
    eventLimit: true, // allow "more" link when too many events
    selectable:true,
    dateClick:function(info){ 

    },
    select: function(info) {
      $('#appointmentForm').modal("show");
      $('#scheduledate').val(info.startStr);
   
//   let listDates = getDates(info.startStr,info.endStr);
//   tableForm.hidden=false;
//   tableForm2.hidden=true;
//   sampleSubmit.hidden=false;
//   saveTemplateButton.hidden=false;
//   chooseTemplate.hidden=false;

//   let minusDay = info.endStr.split("-");
//   let decDay=minusDay[2]-1;
//   if(decDay <10){
//         decDay = "0"+decDay;
// }
// let newEndDateStr = minusDay[0]+"-"+minusDay[1]+"-"+decDay;
//       // console.log(newEndDateStr);

//       // dateToday.innerHTML=info.startStr+" / "+minusDay;
//       dataObj.date.sort();
//       dateToday.innerHTML = calendar.formatRange(dataObj.date[0], dataObj.date[dataObj.date.length-1], {
//         month: 'long',
//         year: 'numeric',
//         day: 'numeric',
//         separator: ' to '
// })


},
eventRender: function(info) {
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

      // if($('#dropdownListing').val()!= null){
      //   return ['all', info.event.title].indexOf($('#dropdownListing').val()) >= 0
      // }else{

      // }
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
   eventSources:[{events:dd}],
   eventTimeFormat:{
    hour: 'numeric',
    minute: '2-digit',
    meridiem: 'short'
  },
  eventClick: function(info) {
   summaryAppointment(info.event.title);
 },

});

  calendar.render();
  //rerender events
  $('#dropdownListing').on('change',function(x){
    x.preventDefault();
    calendar.rerenderEvents();
    // console.log('ok');
  });
}
let fetchAllAppointments;
document.addEventListener('DOMContentLoaded', function(e) {
  LoadCalendar();
     $('#from_time').timepicker();
});