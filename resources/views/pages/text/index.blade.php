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
                        <h5>Add Data</h5>
                        <br>
                        <form id="addText" role="form" method="POST" action="">
                            <div class="form-group mx-sm-3 mb-2">
                                <label for="text1" class="sr-only">Text 1</label>
                                <input id="text1" type="text" class="form-control" name="text1" placeholder="Text 1"
                                       autofocus>
                            </div>
                            <div class="form-group mx-sm-3 mb-2">
                                <label for="text2" class="sr-only">Text 2</label>
                                <input id="text2" type="text" class="form-control" name="text2" placeholder="Text 2"
                                       autofocus>
                            </div>
                            <div class="form-group mx-sm-3 mb-2">
                                <label for="text3" class="sr-only">Text 3</label>
                                <input id="text3" type="text" class="form-control" name="text3" placeholder="Text 3"
                                       autofocus>
                            </div>
                            <button id="submitText" type="button" class="btn btn-primary mb-2 mx-sm-3">Submit</button>
                        </form>
                    </div>
                </div>
                <br>
                <div class="card card-default" style="margin:20px;">
                  <div class="card-body">
                    <h5>List Data</h5>
                    <br>
                    <div class="table-responsive">
                      <table class="table table-hover table-bordered" style="overflow:auto;">
                          <tr>
                              <th>#</th>
                              <th>Text 1</th>
                              <th>Text 2</th>
                              <th>Text 3</th>
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
                              <button type="button" class="btn btn-success updateText">Update
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
        //set index
        firebase.database().ref('TextApps/').on('value', function (snapshot) {
            var value = snapshot.val();
            $.each(value, function (index, value) {
                lastIndex = index;
            });
        });

        // Get Data
        firebase.database().ref('TextApps/').orderByChild('desaId').equalTo("{{ Auth::id() }}").on('value', function (snapshot) {
            var value = snapshot.val();
            var htmls = [];
            if (value != null) {
                form.style.display = "none";
              $.each(value, function (index, value) {
                  if (value) {
                      htmls.push('<tr>\
                    		<td>' + index + '</td>\
                    		<td>' + value.text1 + '</td>\
                    		<td>' + value.text2 + '</td>\
                    		<td>' + value.text3 + '</td>\
                    		<td><button data-toggle="modal" data-target="#update-modal" class="btn btn-info updateData" data-id="' + index + '">Update</button>\
                    		<button data-toggle="modal" data-target="#remove-modal" style="display:none;" class="btn btn-danger removeData" data-id="' + index + '">Delete</button></td>\
                    	</tr>');
                    }
                  // lastIndex = index;
              });
            }else{
                form.style.display = "block";
            }
            $('#tbody').html(htmls);
            $("#submitUser").removeClass('desabled');
        });

        // Add Data
        $('#submitText').on('click', function () {
            var values = $("#addText").serializeArray();
            var text1 = values[0].value;
            var text2 = values[1].value;
            var text3 = values[2].value;
            var textID = lastIndex + 1;

            firebase.database().ref('TextApps/' + textID).set({
                text1: text1,
                text2: text2,
                text3: text3,
                desaId: "{{Auth::id()}}",
            });

            // Reassign lastID value
            lastIndex = textID;
            $("#addText input").val("");
        });

        // Update Data
        var updateID = 0;
        $('body').on('click', '.updateData', function () {
            updateID = $(this).attr('data-id');
            firebase.database().ref('TextApps/' + updateID).on('value', function (snapshot) {
                var values = snapshot.val();
                var updateData =
            '<div class="form-group">\
    		        <label for="text1_update" class="col-md-12 col-form-label">Text 1</label>\
    		        <div class="col-md-12">\
    		            <input id="text1_update" type="text" class="form-control" name="update_text1" value="' + values.text1 + '" required autofocus>\
    		        </div>\
    		    </div>\
            <div class="form-group">\
                <label for="text2_update" class="col-md-12 col-form-label">Text 2</label>\
                <div class="col-md-12">\
                    <input id="text2_update" type="text" class="form-control" name="update_text2" value="' + values.text2 + '" required autofocus>\
                </div>\
            </div>\
            <div class="form-group">\
                <label for="text3_update" class="col-md-12 col-form-label">Text 3</label>\
                <div class="col-md-12">\
                    <input id="text3_update" type="text" class="form-control" name="update_text3" value="' + values.text3 + '" required autofocus>\
                </div>\
            </div>';
                $('#updateBody').html(updateData);
            });
        });
        $('.updateText').on('click', function () {
            var values = $(".users-update-record-model").serializeArray();
            var postData = {
                text1: values[0].value,
                text2: values[1].value,
                text3: values[2].value,
                desaId: "{{ Auth::id() }}",
            };

            var updates = {};
            updates['/TextApps/' + updateID] = postData;

            firebase.database().ref().update(updates);
            $("#update-modal").modal("hide");
        });

        // Remove Data
        $("body").on('click', '.removeData', function () {
            var id = $(this).attr('data-id');
            $('body').find('.users-remove-record-model').append('<input name="id" type="hidden" value="' + id + '">');
        });

        $('.deleteRecord').on('click', function () {
            var values = $(".users-remove-record-model").serializeArray();
            var id = values[0].value;
            firebase.database().ref('TextApps/' + id).remove();
            $('body').find('.users-remove-record-model').find("input").remove();
            $("#remove-modal").modal('hide');
        });
        $('.remove-data-from-delete-form').click(function () {
            $('body').find('.users-remove-record-model').find("input").remove();
        });
    </script>
  </body>
</html>
