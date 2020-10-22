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
                    <h5>Akun Terdaftar</h5>
                    <div id="grid"></div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
    </div>
    @include('layouts._footer')
        <script type="text/x-kendo-template" id="addAccount">
          <div class="k-edit-label"><label for="name">Nama : </label></div>
          <div data-container-for="name" class="k-edit-field">
              <input type="text" class="k-textbox" name="name" data-bind="value:name" required data-required-msg="Field tidak boleh kosong" style="padding:4px; width:215px"/>
          </div>
          <div class="k-edit-label"><label for="username">username : </label></div>
          <div data-container-for="username" class="k-edit-field">
              <input type="text" class="k-textbox" name="username" data-bind="value:username" required data-required-msg="Field tidak boleh kosong" style="padding:4px; width:215px"/>
          </div>
          <div class="k-edit-label"><label for="email">Email : </label></div>
          <div data-container-for="email" class="k-edit-field">
              <input type="email" class="k-textbox" name="email" data-bind="value:email" required data-required-msg="Field tidak boleh kosong" style="padding:4px; width:215px"/>
          </div>
          # if(isNew()) {#
          <div class="k-edit-label"><label for="password">Password : </label></div>
          <div data-container-for="password" class="k-edit-field">
              <input type="password" class="k-textbox" name="password" required data-required-msg="Field tidak boleh kosong" minlength="6" style="padding:4px; width:215px"/>
          </div>
          #}#
        </script>
        <script>
            $(document).ready(function() {
                var element = $("#grid").kendoGrid({
                  dataSource: {
                      transport: {
                          read: function (options) {
                              $.ajax({
                                  url: "{{route('account.get')}}",
                                  type: "GET",
                                  data: options.data,
                                  dataType: "json",
                                  success: function (res) {
                                      options.success(res);
                                  }
                              });
                          },
                          create: function (options) {
                            var accesses = [];

                            $.each($(".access-checkbox:checked"), function () {
                              accesses.push($(this).val());
                            });
                            options.data.accesses = accesses;
                            // options.data.Clasification_Id = $('#Id_clas``s').val();
                            $.ajax({
                                  headers: {
                                          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                      },
                                  url: "{{route('account.add')}}",
                                  type: "POST",
                                  data: options.data,
                                  dataType: "json",
                                  success: function (res) {
                                      options.success(res);
                                          $("#grid").data("kendoGrid").dataSource.read();
                                  },
                                  complete: function (e) {
                                      $("#grid").data("kendoGrid").dataSource.read();

                                  }
                              });
                          },
                          update: function (options){
                              var accesses = [];
                              $.each($(".access-checkbox:checked"), function () {
                                  accesses.push($(this).val());
                              });
                              options.data.accesses = accesses;
                              $.ajax({
                              headers: {
                                      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                  },
                              type: "POST",
                              url: "{{route('account.update')}}",
                              dataType: "json",
                              data: options.data,
                              success: function (res) {
                                  options.success(res);
                                      },
                              complete: function (e) {
                                  $("#grid").data("kendoGrid").dataSource.read();
                                  }
                              });
                          },
                      },
                      schema: {
                          data: "data",
                          total: "total",
                          model: {
                              id: "id",
                              fields: {
                                  id: { defaultValue: null }
                              }
                          }
                      },
                    pageSize: 10,
                      // serverPaging: true,
                  },
                  dataBinding: function() {
                      numberEvent = (this.dataSource.page() -1) * this.dataSource.pageSize();
                  },
                  columns: [
                      {
                        field: "No",
                        title: "&nbsp;",
                       // template: "#= ++numberEvent #",
                        headerAttributes: { style: "text-align: center" },
                        attributes : {style: "text-align: center"},
                        width: 50
                      },
                      {
                          field: "id",
                          title: "Id",
                          hidden: true,
                          headerAttributes: { style: "text-align: center" } ,
                          width: "50px"
                      },
                      {
                          field: "name",
                          title: "Nama",
                          headerAttributes: { style: "text-align: center" } ,
                          // width: "120px"
                      },
                      {
                          field: "username",
                          title: "Username",
                          headerAttributes: { style: "text-align: center" } ,
                          // width: "120px"
                      },
                      {
                          field: "email",
                          title: "E-mail",
                          headerAttributes: { style: "text-align: center" } ,
                          // width: "120px"
                      },
                      {
                          field: "member_end",
                          title: "Langganan berakhir",
                          headerAttributes: { style: "text-align: center" } ,
                          // width: "120px"
                      },
                      {
                          // hidden: true,
                          title: "Action",
                          headerAttributes: { class: "table-header-cell", style: "text-align: center" },
                          attributes: { class: "table-cell", style: "text-align: center" },
                          width: "200px",
                          command: [
                              {
                                  name: "edit",
                                  text: {
                                      edit: "Edit",
                                      update: "Simpan",
                                      cancel: "Batal"
                                  }
                              },
                              {
                                  name: "deleteAccount",
                                  iconClass: "k-icon k-i-trash",
                                  text: "Hapus",
                                  click: function (f) {
                                      f.preventDefault();
                                      var tr = $(f.target).closest("tr"),
                                      data = this.dataItem(tr);
                                      swal({
                                          title: "Apa Anda Yakin Ingin Menghapus "+ data.name + " ?",
                                          icon: "warning",
                                          buttons: true,
                                          dangerMode: true,
                                          }).then((willDelete) => {
                                              if (willDelete) {
                                                  $.ajax({
                                                  headers: {
                                                          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                                      },
                                                  type: "POST",
                                                  url: "{{route('account.delete')}}",
                                                  dataType: "json",

                                                  data: {id:data.id},

                                                  success: function (data) {
                                                  swal("Poof! Berhasil Dihapus!", {
                                                  icon: "success",
                                                      });
                                                  },
                                                  error: function (xhr, ajaxOptions, thrownError) {
                                                    console.log(thrownError);
                                                      swal("Gagal Menghapus! Data Telah Digunakan", {
                                                      icon: "error",
                                                      });
                                                  },
                                                  complete: function (e) {
                                                      $("#grid").data("kendoGrid").dataSource.read();
                                                  }
                                                  });

                                              }
                                          });
                                  }
                              },
                          ]
                      },
                  ],
                  noRecords: true,
                  sortable: true,
                  pageable: {
                      pageSizes: true,
                      // numeric: false,
                      // input: true,
                      refresh: true
                  },
                  toolbar: [
                      { name: "create", text: "Tambah Data" }
                  ],
                  editable: {
                      mode: "popup",
                      template: $("#addAccount").html()
                  },
                  edit: function (e) {
                    e.container.parent().find('.k-window-title').text(e.model.id == "" || e.model.id == null ? "Tambah Data" : "Edit Data")
                  }
                });
            });
        </script>
  </body>
</html>
