<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>{{ env('NAMA_APP') }} </title>
    @include('layouts._app')
  </head>
  <body class="sidebar-mini">
    <div class="wrapper">
      @section('navbar')
        @include('layouts._navbar')
      @section('sidebar')
        @include('layouts._sidebar')
      @section('content')
        <div class="content-wrapper">
          <div class="container-fluid">
            <div class="row">
              <div class="col-lg-12">
                <div id="formAdd" class="card card-default" style="margin:20px;">
                  <div class="card-body">
                    <h5># Add File</h5>
                      <form id="addFile" class="" method="POST" action="" enctype="multipart/form-data">
                          <div class="form-group mb-2">
                              <label for="file" class="sr-only">file upload : </label>
                              <input id="file" type="file" class="form-control" name="file"  placeholder="Name"
                                     required autofocus multiple>
                          </div>
                          <button id="submitFile" type="button" class="btn btn-primary mb-2">Submit</button>
                      </form>
                  </div>
                </div>
                <br>
                <div class="card card-default" style="margin:20px;">
                  <div class="card-body">
                    <h5># List Logo</h5>
                    <br>
                    <div class="table-responsive">
                      <table class="table table-hover table-bordered" style="overflow:auto;">
                          <tr>
                              <th>Name</th>
                              <th>Image</th>
                              <th width="180" class="text-center">Action</th>
                          </tr>
                          <tbody id="tbody">

                          </tbody>
                      </table>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Update Model -->
          <form action="" method="POST" class="users-update-record-model form-horizontal">
              <div id="update-modal" data-backdrop="static" data-keyboard="false" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel"
                   aria-hidden="true">
                  <div class="modal-dialog modal-dialog-centered" style="width:55%;">
                      <div class="modal-content" style="overflow: hidden;">
                          <div class="modal-header">
                              <h4 class="modal-title" id="custom-width-modalLabel">Update</h4>
                              <button type="button" class="close" data-dismiss="modal"
                                      aria-hidden="true">×
                              </button>
                          </div>
                          <div class="modal-body" id="updateBody">

                          </div>
                          <div class="modal-footer">
                              <button type="button" class="btn btn-light"
                                      data-dismiss="modal">Close
                              </button>
                              <button type="button" class="btn btn-success updateFoto">Update
                              </button>
                          </div>
                      </div>
                  </div>
              </div>
          </form>
          <!-- Delete Model -->
          <form action="" method="POST" class="users-remove-record-model">
              <div id="remove-modal" data-backdrop="static" data-keyboard="false" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel"
                   aria-hidden="true" style="display: none;">
                  <div class="modal-dialog modal-dialog-centered" style="width:55%;">
                      <div class="modal-content">
                          <div class="modal-header">
                              <h4 class="modal-title" id="custom-width-modalLabel">Delete</h4>
                              <button type="button" class="close remove-data-from-delete-form" data-dismiss="modal"
                                      aria-hidden="true">×
                              </button>
                          </div>
                          <div class="modal-body">
                              <p>Do you want to delete this record?</p>
                          </div>
                          <div class="modal-footer">
                              <button type="button" class="btn btn-default waves-effect remove-data-from-delete-form"
                                      data-dismiss="modal">Close
                              </button>
                              <button type="button" class="btn btn-danger waves-effect waves-light deleteRecord">Delete
                              </button>
                          </div>
                      </div>
                  </div>
              </div>
          </form>
        </div>
    </div>
    @include('layouts._footer')

    <!-- JS -->
    {{--Firebase Tasks--}}
    <!-- <script src="https://code.jquery.com/jquery-3.4.0.min.js"></script> -->
    <script src="https://www.gstatic.com/firebasejs/5.10.1/firebase.js"></script>
    <script>
        // Initialize Firebase
        var config = {
            apiKey: "{{ config('services.firebase.api_key') }}",
            authDomain: "{{ config('services.firebase.auth_domain') }}",
            databaseURL: "{{ config('services.firebase.database_url') }}",
            storageBucket: "{{ config('services.firebase.storage_bucket') }}",
        };
        firebase.initializeApp(config);

        var database = firebase.database();

        var lastIndex = 0;

        var form = document.getElementById("formAdd");

        var path = " {{ Auth::user()->name }}/Logo/";

        var old_image = "";

        //set index
        firebase.database().ref('Logo/').on('value', function (snapshot) {
            var value = snapshot.val();
            $.each(value, function (index, value) {
                lastIndex = index;
            });
        });

        // Get Image
        firebase.database().ref('Logo/').orderByChild('desaId').equalTo("{{ Auth::id() }}").on('value', function (snapshot) {
            var value = snapshot.val();
            var htmls = [];
            if (value != null) {
                form.style.display = "none";
                $.each(value, function (index, value) {
                    if (value) {
                        htmls.push('<tr>\
                    <td>' + value.logo + '</td>\
                    <td> <img src="' + value.url + '" width="400" height="400" /></td>\
                    <td><button data-toggle="modal" data-target="#update-modal" class="btn btn-info updateData" data-id="' + index + '">Update</button>\
                    <button data-toggle="modal" data-target="#remove-modal" class="btn btn-danger removeData" data-id="' + index + '">Delete</button></td>\
                  </tr>');
                  old_image = value.logo;
                    }
                });
            }else{
                form.style.display = "block";
            }
            $('#tbody').html(htmls);
            $("#submitFile").removeClass('desabled');
        });

        // Submit File
        $('#submitFile').on('click', function () {
        // Create a root reference
        var storageRef = firebase.storage().ref();
        // File or Blob named mountains.jpg
        var file = $('#file')[0].files[0];
        // Create the file metadata
        var metadata = {
          contentType: 'image/jpeg'
        };
        // Upload file and metadata to the object 'images/mountains.jpg'
        var uploadTask = storageRef.child(path + file.name).put(file, metadata);

        // Listen for state changes, errors, and completion of the upload.
          uploadTask.on(firebase.storage.TaskEvent.STATE_CHANGED, // or 'state_changed'
              function(snapshot) {
                // Get task progress, including the number of bytes uploaded and the total number of bytes to be uploaded
                var progress = (snapshot.bytesTransferred / snapshot.totalBytes) * 100;
                // console.log('Upload is ' + progress + '% done');
                swal({
                  title: "Uploading File.." + progress + "% done",
                  icon: "info",
                  buttons: {
                    catch: {
                      text: "Batalkan",
                      value: "catch",
                      className:'btn btn-danger'
                    },
                  },
                })
                .then((value) => {
                    switch (value) {

                    case "catch":
                      uploadTask.cancel();
                      break;

                    }
                });

                switch (snapshot.state) {
                  case firebase.storage.TaskState.PAUSED: // or 'paused'
                    console.log('Upload is paused');
                    break;
                  case firebase.storage.TaskState.RUNNING: // or 'running'
                    // console.log('Upload is running');
                    break;
                }
              }, function(error) {
              // A full list of error codes is available at
              // https://firebase.google.com/docs/storage/web/handle-errors
              switch (error.code) {
                case 'storage/unauthorized':
                  // User doesn't have permission to access the object
                  break;

                case 'storage/canceled':
                  // User canceled the upload
                  break;

                case 'storage/unknown':
                  // Unknown error occurred, inspect error.serverResponse
                  break;
              }
            }, function() {
              // Upload completed successfully, now we can get the download URL
              uploadTask.snapshot.ref.getDownloadURL().then(function(downloadURL) {
                // console.log('File available at', downloadURL);
                var userID = lastIndex + 1;
                firebase.database().ref('Logo/' + userID).set({
                    desaId: "{{Auth::id()}}",
                    logo: file.name,
                    url: downloadURL,
                });
                lastIndex = userID;
                swal({
                  title: "Succed Uploading..",
                  // text: "You clicked the button!",
                  icon: "success",
                  button: "OK",
                });
                document.getElementById("file").value = "";
              });
            });
        });

        // Update Data
        var updateID = 0;
        $('body').on('click', '.updateData', function () {
            updateID = $(this).attr('data-id');
            firebase.database().ref('Logo/' + updateID).on('value', function (snapshot) {
                var values = snapshot.val();
                var updateData =
                '<div class="form-group">\
                    <label for="image_update" class="col-md-12 col-form-label">Image</label>\
                    <div class="col-md-12">\
                        <input id="image_update" type="text" class="form-control" name="updateimage" value="' + values.logo + '" readonly disabled required autofocus>\
                    </div>\
                </div>\
                <div class="form-group">\
                    <label for="foto_update" class="col-md-12 col-form-label">Upload</label>\
                    <div class="col-md-12">\
                        <input id="foto_update" type="file" class="form-control" name="updatefoto" required autofocus>\
                    </div>\
                </div>';
                $('#updateBody').html(updateData);
            });
        });

        $('.updateFoto').on('click', function () {
          // Create a root reference
          var storageRef = firebase.storage().ref();
          // File or Blob named mountains.jpg
          var file_update = $('#foto_update')[0].files[0];
          // Create the file metadata
          var metadata = {
            contentType: 'image/jpeg'
          };

          var uploadTask = storageRef.child(path + file_update.name).put(file_update, metadata);

          // Listen for state changes, errors, and completion of the upload.
          uploadTask.on(firebase.storage.TaskEvent.STATE_CHANGED, // or 'state_changed'
              function(snapshot) {
                // Get task progress, including the number of bytes uploaded and the total number of bytes to be uploaded
                var progress = (snapshot.bytesTransferred / snapshot.totalBytes) * 100;
                // console.log('Upload is ' + progress + '% done');
                swal({
                  title: "Uploading " + progress + "% done",
                  icon: "info",
                  buttons: {
                    catch: {
                      text: "Batalkan",
                      value: "catch",
                      className:'btn btn-danger'
                    },
                  },
                })
                .then((value) => {
                    switch (value) {

                    case "catch":
                      uploadTask.cancel();
                      break;

                    }
                });

                switch (snapshot.state) {
                  case firebase.storage.TaskState.PAUSED: // or 'paused'
                    console.log('Upload is paused');
                    break;
                  case firebase.storage.TaskState.RUNNING: // or 'running'
                    // console.log('Upload is running');
                    break;
                }
              }, function(error) {
              // A full list of error codes is available at
              // https://firebase.google.com/docs/storage/web/handle-errors
              switch (error.code) {
                case 'storage/unauthorized':
                  // User doesn't have permission to access the object
                  break;

                case 'storage/canceled':
                  // User canceled the upload
                  break;

                case 'storage/unknown':
                  // Unknown error occurred, inspect error.serverResponse
                  break;
              }
            }, function() {
              // Upload completed successfully, now we can get the download URL
              uploadTask.snapshot.ref.getDownloadURL().then(function(downloadURL) {
                // console.log('File available at', downloadURL);
                storageRef.child(path + old_image).delete().then(function() {
                  // File deleted successfully
                }).catch(function(error) {
                  console.log(error);
                });

                var userID = lastIndex + 1;
                var values = $(".users-update-record-model").serializeArray();
                var postData = {
                    logo: file_update.name,
                    url: downloadURL,
                    desaId: "{{ Auth::id() }}",
                };

                var updates = {};
                updates['/Logo/' + updateID] = postData;
                firebase.database().ref().update(updates);

                swal({
                  title: "Succed Uploading..",
                  // text: "You clicked the button!",
                  icon: "success",
                  button: "OK",
                });
                document.getElementById("foto_update").value = "";
                $("#update-modal").modal("hide");
              });
            });
        });

        // Remove Data
        $("body").on('click', '.removeData', function () {
            var id = $(this).attr('data-id');
            $('body').find('.users-remove-record-model').append('<input name="id" type="hidden" value="' + id + '">');
        });

        $('.deleteRecord').on('click', function () {
            // Create a root reference
            var storageRef = firebase.storage().ref();
            var values = $(".users-remove-record-model").serializeArray();
            var id = values[0].value;
            var image = "";

            firebase.database().ref('Logo/' + id + '/').on('value', function (snapshot) {
              var value = snapshot.val();
                if (value) {
                    image = value.logo;
                  }
            });
            firebase.database().ref('Logo/' + id).remove();
            storageRef.child(path + image).delete().then(function() {
              // File deleted successfully
            }).catch(function(error) {
              console.log(error);
            });
            $('body').find('.users-remove-record-model').find("input").remove();
            $("#remove-modal").modal('hide');
        });
        $('.remove-data-from-delete-form').click(function () {
            $('body').find('.users-remove-record-model').find("input").remove();
        });
    </script>
  </body>
</html>
