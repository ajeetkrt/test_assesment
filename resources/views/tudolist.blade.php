<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Todo List</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <style>
    body {
      padding: 20px;
    }
    /* h2 {
      margin-bottom: 10px;
    } */
    .form-container {
      margin-bottom: 30px;
    }

    .header-container {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 15px;
    }

    .messages {
      font-size: 20px;
      padding: 10px;
      margin-left: 10px;
      border-radius: 4px;
      position: absolute;
      right: 20px;
      top: 15px;
      z-index: 20;
      max-width: calc(100% - 40px);
    }

    #success-message {
      background: #DEF1D8;
      color: green;
      display: none;
       /* Hidden by default */
    }
    #error-message {
      background: #EFDCDD;
      color: red;
      display: none;
       /* Hidden by default */
    }
  </style>
</head>
<body>
  <div class="container">
    <div class="header-container">
      <div class="left">
        <h2>PHP - Simple To Do List App</h2>
      </div>
      <div class="right">
        <span id="error-message" class="messages"></span>
        <span id="success-message" class="messages"></span>
      </div>
    </div>  
    <hr>
    <div class="row justify-content-center form-container">
  <div class="col-md-6 col-lg-5">
    <!-- Use d-flex to align input and buttons in the same row -->
    <div class="d-flex mb-2">
      <!-- Input field with flex-grow to take up available space -->
      <input type="text" class="form-control me-2 flex-grow-1" id="addtask" placeholder="Enter new task name">
      
      <!-- Submit button -->
      <button type="button" class="btn btn-primary me-2" id="submitbtn">Add Task</button>
      
      <!-- All task button -->
      <!-- <button type="button" class="btn btn-secondary" id="show_all_task">All task</button> -->
      <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#ShowAll">All task</button>
    </div>

    <!-- Error message placeholder -->
    <span id="addtask_error" class="text-danger msg"></span>
  </div>
</div>


    <!-- Table -->
    <table class="table table-bordered table-striped">
      <thead class="table-light">
        <tr>
          <th>#</th>
          <th>Task</th>
          <th>Status</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody id="load-table"></tbody>
    </table>
  </div>

  <!-- Edit Modal -->
  <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="editModalLabel">Update Task</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form>
            <div class="mb-3">
              <label for="editName" class="form-label">Task Name</label>
              <input type="text" class="form-control" id="editName" placeholder="Enter task name">
              <span id="updatetask_error" class="text-danger msg1"></span>
            </div>
            <input type="hidden" id="editTaskId">
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary" id="upsubmitbtn">Update</button>
        </div>
      </div>
    </div>
  </div>

<!-- Show all data model popup -->
<!-- Modal -->
<div class="modal fade" id="ShowAll" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Show All Task</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
      <table class="table table-bordered table-striped">
      <thead class="table-light">
        <tr>
          <th>#</th>
          <th>Task</th>
          <th>Status</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody id="load-all"></tbody>
    </table>
      </div>
    </div>
  </div>
</div>
 <!-- End -->

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

  <script>
    $(document).ready(function() {
      // Fatch All Records
      fetchTasks();
    function fetchTasks() {
        $.ajax({
            url: "{{ route('tudolist.fetchtask') }}", 
            method: "GET",
            success: function(response) {
            //  console.log(response);
                let taskTableBody = '';
                if(response){
                $.each(response, function(index, task) {
                  let statusText = (task.status == '1') ? 'Pending' : 'Done';
                  if(task.status=='1'){
                    taskTableBody += `<tr>
                         <td><label><input type='checkbox' id='ustatus' name='task_checkbox' value='${task.id}' > ${index + 1}</label></td>
                        <td>${task.task_name}</td>
                        <td>${statusText}</td>
                        <td><a href='#' class='text-primary me-2 edit-task' data-bs-toggle='modal' data-bs-target='#editModal' data-task-id='${task.id}'><i class='fas fa-edit'></i></a><a href='#' class='text-danger delete-task' data-task-id='${task.id}'><i class='fas fa-trash'></i></a></td>
                    </tr>`;
                  }else{
                    taskTableBody += `<tr><td colspan='4' class='text-center'>Record Not Found.</td></tr>`
                  }
                });
              }else{
                taskTableBody += `<tr><td colspan='4' class='text-center'>Record Not Found.</td></tr>`
              }
                $('#load-table').html(taskTableBody);
            },
            error: function(xhr, status, error) {
                console.error('Error fetching tasks:', error);
            }
        });
    }
    //  get all task details
    getalltask();
    function getalltask() {
        $.ajax({
            url: "{{ route('tudolist.getall') }}", 
            method: "GET",
            success: function(response) {
            //  console.log(response);
                let taskTableBody = '';
                if(response){
                $.each(response, function(index, task) {
                  let statusText = (task.status == 1) ? 'Pending' : 'Done';
                  let  actionbtn = ``;
                  if(task.status == 1){
                    actionbtn = `<a href='#' class='text-primary me-2 edit-task' data-bs-toggle='modal' data-bs-target='#editModal' data-task-id='${task.id}'><i class='fas fa-edit'></i></a><a href='#' class='text-danger delete-task' data-task-id='${task.id}'><i class='fas fa-trash'></i></a>`;
                  }else{
                     actionbtn = `<a href='#' class='text-danger delete-task' data-task-id='${task.id}'><i class='fas fa-trash'></i></a>`;
                  }

                    taskTableBody += `<tr>
                         <td>${index + 1}</td>
                        <td>${task.task_name}</td>
                        <td>${statusText}</td>
                        <td>${actionbtn}</td>
                    </tr>`;
                });
              }else{
                taskTableBody += `<tr><td colspan='4' class='text-center'>Record Not Found.</td></tr>`
              }
                $('#load-all').html(taskTableBody);
            },
            error: function(xhr, status, error) {
                console.error('Error fetching tasks:', error);
            }
        });
    }
//   Update task status
$(document).on('change', '#ustatus', function() {
    let taskId = $(this).val();
  //  console.log("Task ID:", taskId); 
    $.ajax({
        url: "{{ route('tudolist.updatetask') }}",
        method: "POST",
        data: {
             id: taskId,
            _token: "{{ csrf_token() }}"
        },
        success: function(response) {
          if(response){
                   $('#success-message').html(response.success).slideDown();
                     setTimeout(function(){
                   $("#success-message").slideUp();
                   },2200);
                   fetchTasks();
                   getalltask();
                  }else{
                  $('#error-message').html(response.error).slideDown();
                  setTimeout(function(){
                  $("#error-message").slideUp();
                  },2200);
                  }
        },
   });
});
//  Add Task
      $('#submitbtn').click(function() {
      $('.msg').html('');
        var taskname = $('#addtask').val().trim();
          $.ajax({ 
                url : '{{route("tudolist.addtask")}}',
                type : "POST",
                data : {
                       taskname: taskname, 
                      _token: '{{ csrf_token() }}'
                       },
                success : function(data){
                  if(data){
                   $('#success-message').html(data.success).slideDown();
                     setTimeout(function(){
                   $("#success-message").slideUp();
                   },2200);
                   $('#addtask').val('');
                   fetchTasks();
                   getalltask();
                  }else{
                  $('#error-message').text('Please Try Again..!!').slideDown();
                  setTimeout(function(){
                  $("#error-message").slideUp();
                  },2200);
                  }
                },
                error: function(error) {
                   // console.log(error.responseJSON.errors.taskname[0]);
                    $('#addtask_error').html(error.responseJSON.errors.taskname);
                }
    });
         // console.log(taskname);
      });

// Fetch single record.....
$(document).on('click', '.edit-task', function() {
    var taskId = $(this).data('task-id');
    $.ajax({
        url: "{{ route('tudolist.fetchsinglerecords') }}", 
        type: 'POST', 
        data: {
           task_id: taskId,
            _token: '{{ csrf_token() }}'
        },
        success: function(response) {
            $('#editName').val(response.task_name);
            $('#editTaskId').val(taskId);
        },
    });
});
//  Update Record.....
$('#upsubmitbtn').click(function() {
       $('.msg1').html('');
         var edittaskname = $('#editName').val().trim();
         var taskId = $('#editTaskId').val().trim();
          $.ajax({ 
                url : '{{route("tudolist.updatetudotasks")}}',
                type : "POST",
                data : {
                       taskname: edittaskname, 
                       updatetaskId: taskId,
                      _token: '{{ csrf_token() }}'
                       },
                success : function(data){
                 // console.log(data);
                 $('#editModal').modal('hide');
                  if(data){
                   $('#success-message').html(data.success).slideDown();
                     setTimeout(function(){
                   $("#success-message").slideUp();
                   },2200);
                   $('#addtask').val('');
                  //  $('#editModal').hide();
                   fetchTasks();
                   getalltask();
                  }else{
                  $('#error-message').html(data.error).slideDown();
                  setTimeout(function(){
                  $("#error-message").slideUp();
                  },2200);
                  }
                },
                error: function(error) {
                   // console.log(error.responseJSON.errors.taskname[0]);
                   $('#updatetask_error').html(error.responseJSON.errors.taskname);
                }
    });

          // console.log(edittaskname);
          // console.log(taskId);
      });

// Delete Task
$(document).on('click', '.delete-task', function() {
    var taskId = $(this).data('task-id');
    if (confirm('Are you sure you want to delete this task?')) {
      // console.log(taskId);
      $.ajax({
        url: "{{ route('tudolist.deletetasks') }}",
        method: "POST",
        data: {
             id: taskId,
            _token: "{{ csrf_token() }}"
        },
        success: function(response) {
                console.log(response);
          if(response){
                   $('#success-message').html(response.success).slideDown();
                     setTimeout(function(){
                   $("#success-message").slideUp();
                   },2200);
                   fetchTasks();
                   getalltask();
                  }else{
                  $('#error-message').html(response.error).slideDown();
                  setTimeout(function(){
                  $("#error-message").slideUp();
                  },2200);
                  }
         },
   });
    }
});
    });
  </script>
</body>
</html>
