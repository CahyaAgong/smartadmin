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
                    <h5>Akun Anda</h5>
                    <br>
                    <form role="form" id="form_akun" method="post" action="">
                        <div class="form-group">
                          <label for="Name"> Name </label>
                          <input type="text" name="name" class="form-control" value="{{Auth::user()->name}}" required id="Name" placeholder="Enter Name">
                        </div>
                        <div class="form-group">
                          <label for="Username"> Username </label>
                          <input type="text" name="username" class="form-control" value="{{Auth::user()->username}}" id="Username" placeholder="Enter Username">
                        </div>
                        <div class="form-group">
                          <label for="Full_Name"> Full Name </label>
                          <input type="text" name="full_name" class="form-control" value="{{Auth::user()->full_name}}" id="Full_Name" placeholder="Enter Full Name">
                        </div>
                        <div class="form-group">
                          <label for="Email"> Email </label>
                          <input type="email" name="email" class="form-control" value="{{Auth::user()->email}}" required id="Email" placeholder="Enter Email">
                        </div>
                        <!-- /.card-body -->
                        <div class="card-footer">
                          <input type="submit" class="btn btn-primary" id="editBio" value="Submit">
                        </div>
                    </form>
                  </div>
                </div>

                <div class="card card-default" style="margin:20px;">
                  <div class="card-body">
                    <h5>Password</h5>
                    <br>
                    <form role="form" id="form_pass">
                        <div class="form-group">
                          <label for="Password">Password</label>
                          <input type="password" name="password" class="form-control" id="Password" placeholder="Password">
                        </div>
                        <div class="form-group">
                          <label for="Old_Password">Old Password</label>
                          <input type="password" name="old_password" class="form-control" id="Old_Password" required placeholder="Old Password">
                        </div>
                        <!-- /.card-body -->
                        <div class="card-footer">
                          <button type="submit" class="btn btn-primary" id="editPass">Submit</button>
                        </div>
                    </form>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
    </div>
    @include('layouts._footer')
    <script type="text/javascript">
      $("#form_akun").on('submit', function(e) {
        e.preventDefault()
          var value = $('#form_akun').serializeArray();
          $.ajax({
            headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
            url: "{{route('member.edit')}}",
            type: "POST",
            data: value,
            dataType: "json",
            success: function (res) {
              swal("Sukses Edit", "kamu berhasil mengedit akunmu!", "success");
            },
            complete: function (e) {
            },
            error: function (error) {
              swal("error", "ada kesalahan!", "error");
              console.log(error)
            }
          });
      });

      $("#form_pass").on('submit', function(e) {
        e.preventDefault()
          var value = $('#form_pass').serializeArray();
          $.ajax({
            headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
            url: "{{route('member.editpass')}}",
            type: "POST",
            data: value,
            dataType: "json",
            success: function (res) {
              console.log(res);
              if (res.status == 1) {
                swal("Sukses Edit", "kamu berhasil mengedit password!", "success");
              } else{
                swal("Error", res.message, "error");
              }
            },
            complete: function (e) {
              $('#old_password').val("");
              $('#password').val("");
            },
            error: function (error) {
              swal("error", "ada kesalahan!", "error");
            }
          });
      });
    </script>
  </body>
</html>
