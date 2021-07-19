// Get the template HTML and remove it from the doumenthe template HTML and remove it from the doument
var previewNode = document.querySelector("#template");
previewNode.id = "";
var previewTemplate = previewNode.parentNode.innerHTML;
previewNode.parentNode.removeChild(previewNode);

var myDropzone = new Dropzone(document.body, { // Make the whole body a dropzone
  url: url+"appointment/attach", // Set the url
  thumbnailWidth: 80,
  thumbnailHeight: 80,
  parallelUploads: 20,
  previewTemplate: previewTemplate,
  autoQueue: false, // Make sure the files aren't queued until manually added
  previewsContainer: "#previews", // Define the container to display the previews
  clickable: ".fileinput-button" // Define the element that should be used as click trigger to select files.
});


myDropzone.on("addedfile", function(file) {
  // Hookup the start button
  file.previewElement.querySelector(".start").onclick = function() {
    myDropzone.enqueueFile(file);
    let filelisttable= $('#files_list').DataTable();
     filelisttable.ajax.reload();
  };

});

// Update the total progress bar
myDropzone.on("totaluploadprogress", function(progress) {
  document.querySelector("#total-progress .progress-bar").style.width = progress + "%";
  let filelisttable= $('#files_list').DataTable();
  filelisttable.ajax.reload();

});

myDropzone.on("sending", function(file,xhr,formData) {
  // Show the total progress bar when upload starts
  document.querySelector("#total-progress").style.opacity = "1";
  // And disable the start button
  file.previewElement.querySelector(".start").setAttribute("hidden", true);
  file.previewElement.querySelector(".cancel").setAttribute("hidden", true);
  formData.append('appointment_id',$('#appointment_id').val());
  formData.append('pet_id',$('#pet_id').val());
  formData.append('owner_id',$('#owner_id').val());
  let filelisttable= $('#files_list').DataTable();
   filelisttable.ajax.reload();
});

// Hide the total progress bar when nothing's uploading anymore
myDropzone.on("queuecomplete", function(progress) {
  document.querySelector("#total-progress").style.opacity = "0";
  let filelisttable= $('#files_list').DataTable();
   filelisttable.ajax.reload();
   if(progress == undefined){
    $('#previews').html('');
   
  }
});

// Setup the buttons for all transfers
// The "add files" button doesn't need to be setup because the config
// `clickable` has already been specified.
document.querySelector("#actions .start").onclick = function() {
  myDropzone.enqueueFiles(myDropzone.getFilesWithStatus(Dropzone.ADDED));
  let filelisttable= $('#files_list').DataTable();
   filelisttable.ajax.reload();
};
document.querySelector("#actions .cancel").onclick = function() {
  myDropzone.removeAllFiles(true);
};
