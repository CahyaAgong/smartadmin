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
                <div class="card card-default" style="margin:20px;">
                    <div class="card-body">
                        <h5>Add Data</h5>
                        <br>
                        <form id="addPengumuman" method="POST" action="">
                            <div class="form-group mx-sm-3 mb-2">
                                <label for="pengumuman" class="sr-only">Name</label>
                                <input id="pengumuman" type="text" class="form-control" name="pengumuman" placeholder="Isi Pengumuman"
                                       autofocus>
                            </div>
                            <button id="submitPengumuman" type="button" class="btn btn-primary mb-2 mx-sm-3">Submit</button>
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
                              <th>Pengumuman</th>
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
                              <button type="button" class="btn btn-success updateAgenda">Update
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

        //set index
        firebase.database().ref('Pengumuman/').on('value', function (snapshot) {
            var value = snapshot.val();
            $.each(value, function (index, value) {
                lastIndex = index;
            });
        });

        // Get Data
        firebase.database().ref('Pengumuman/').orderByChild('desaId').equalTo("{{ Auth::id() }}").on('value', function (snapshot) {
            var value = snapshot.val();
            var htmls = [];
            $.each(value, function (index, value) {
                if (value) {
                    htmls.push('<tr>\
                  		<td>' + index + '</td>\
                  		<td>' + value.pengumuman + '</td>\
                  		<td><button data-toggle="modal" data-target="#update-modal" class="btn btn-info updateData" data-id="' + index + '">Update</button>\
                  		<button data-toggle="modal" data-target="#remove-modal" class="btn btn-danger removeData" data-id="' + index + '">Delete</button></td>\
                  	</tr>');
                  }
                // lastIndex = index;
            });
            $('#tbody').html(htmls);
            $("#submitUser").removeClass('desabled');
        });

        // Add Data
        $('#submitPengumuman').on('click', function () {
            var values = $("#addPengumuman").serializeArray();
            var pengumuman = values[0].value;
            var pengumumanID = lastIndex + 1;

            firebase.database().ref('Pengumuman/' + pengumumanID).set({
                pengumuman: pengumuman,
                desaId: "{{Auth::id()}}",
            });

            // Reassign lastID value
            lastIndex = pengumumanID;
            $("#addPengumuman input").val("");
        });

        // Update Data
        var updateID = 0;
        $('body').on('click', '.updateData', function () {
            updateID = $(this).attr('data-id');
            firebase.database().ref('Pengumuman/' + updateID).on('value', function (snapshot) {
                var values = snapshot.val();
                var updateData = '<div class="form-group">\
    		        <label for="pengumuman_update" class="col-md-12 col-form-label">Isi Pengumuman</label>\
    		        <div class="col-md-12">\
    		            <input id="pengumuman_update" type="text" class="form-control" name="update_pengumuman" value="' + values.pengumuman + '" required autofocus>\
    		        </div>\
    		    </div>';
                $('#updateBody').html(updateData);
            });
        });
        $('.updateAgenda').on('click', function () {
            var values = $(".users-update-record-model").serializeArray();
            var postData = {
                pengumuman: values[0].value,
                desaId: "{{ Auth::id() }}",
            };

            var updates = {};
            updates['/Pengumuman/' + updateID] = postData;

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
            firebase.database().ref('Pengumuman/' + id).remove();
            $('body').find('.users-remove-record-model').find("input").remove();
            $("#remove-modal").modal('hide');
        });
        $('.remove-data-from-delete-form').click(function () {
            $('body').find('.users-remove-record-model').find("input").remove();
        });
    </script>
  </body>
</html>
