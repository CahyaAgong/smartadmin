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
                        <form id="addAgenda" method="POST" action="">
                            <div class="form-group mx-sm-3 mb-2">
                                <label for="judul" class="sr-only">Name</label>
                                <input id="judul" type="text" class="form-control" name="judul" placeholder="Judul Agenda"
                                       autofocus>
                            </div>
                            <div class="form-group mx-sm-3 mb-2">
                                <label for="email" class="sr-only">Email</label>
                                <input id="rincian" type="text" class="form-control" name="rincian" placeholder="Rincian Agenda"
                                       autofocus>
                            </div>
                            <div class="form-group mx-sm-3 mb-2">
                                <label for="email" class="sr-only">Waktu</label>
                                <input id="waktu" type="text" class="form-control" name="waktu" placeholder="Waktu Agenda"
                                       autofocus>
                            </div>
                            <button id="submitAgenda" type="button" class="btn btn-primary mb-2 mx-sm-3">Submit</button>
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
                              <th>Judul</th>
                              <th>Rincian</th>
                              <th>Waktu</th>
                              <th>Desa Id</th>
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
        firebase.database().ref('Agenda/').on('value', function (snapshot) {
            var value = snapshot.val();
            $.each(value, function (index, value) {
                lastIndex = index;
            });
        });

        // Get Data
        firebase.database().ref('Agenda/').orderByChild('desaId').equalTo("{{ Auth::id() }}").on('value', function (snapshot) {
            var value = snapshot.val();
            var htmls = [];
            $.each(value, function (index, value) {
                if (value) {
                    htmls.push('<tr>\
                  		<td>' + value.judul + '</td>\
                  		<td>' + value.rincian + '</td>\
                  		<td>' + value.waktu + '</td>\
                  		<td>' + value.desaId + '</td>\
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
        $('#submitAgenda').on('click', function () {
            var values = $("#addAgenda").serializeArray();
            var judul = values[0].value;
            var rincian = values[1].value;
            var waktu = values[2].value;
            var agendaID = lastIndex + 1;

            firebase.database().ref('Agenda/' + agendaID).set({
                judul: judul,
                rincian: rincian,
                waktu: waktu,
                desaId: "{{Auth::id()}}",
            });

            // Reassign lastID value
            lastIndex = agendaID;
            $("#addAgenda input").val("");
        });

        // Update Data
        var updateID = 0;
        $('body').on('click', '.updateData', function () {
            updateID = $(this).attr('data-id');
            firebase.database().ref('Agenda/' + updateID).on('value', function (snapshot) {
                var values = snapshot.val();
                var updateData = '<div class="form-group">\
    		        <label for="judul_update" class="col-md-12 col-form-label">Judul Agenda</label>\
    		        <div class="col-md-12">\
    		            <input id="judul_update" type="text" class="form-control" name="update_judul" value="' + values.judul + '" required autofocus>\
    		        </div>\
    		    </div>\
    		    <div class="form-group">\
    		        <label for="rincian_update" class="col-md-12 col-form-label">Rincian Agenda</label>\
    		        <div class="col-md-12">\
    		            <input id="rincian_update" type="text" class="form-control" name="update_rincian" value="' + values.rincian + '" required autofocus>\
    		        </div>\
    		    </div>\
    		    <div class="form-group">\
    		        <label for="waktu_update" class="col-md-12 col-form-label">Waktu Agenda</label>\
    		        <div class="col-md-12">\
    		            <input id="waktu_update" type="text" class="form-control" name="update_waktu" value="' + values.waktu + '" required autofocus>\
    		        </div>\
    		    </div>';
                $('#updateBody').html(updateData);
            });
        });
        $('.updateAgenda').on('click', function () {
            var values = $(".users-update-record-model").serializeArray();
            var postData = {
                judul: values[0].value,
                rincian: values[1].value,
                waktu: values[2].value,
                desaId: "{{Auth::id()}}",
            };

            var updates = {};
            updates['/Agenda/' + updateID] = postData;

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
            firebase.database().ref('Agenda/' + id).remove();
            $('body').find('.users-remove-record-model').find("input").remove();
            $("#remove-modal").modal('hide');
        });
        $('.remove-data-from-delete-form').click(function () {
            $('body').find('.users-remove-record-model').find("input").remove();
        });
    </script>
  </body>
</html>
