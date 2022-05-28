var editor;
$(document).ready(function () {

  editor = new $.fn.dataTable.Editor({
    ajax: "../index.php",
    table: "#example",
    fields: [ //parametri form
    {
      label: "First name:",
      name: "firstName"
    }, {
      label: "Last name:",
      name: "lastName"
    }, {
      label: "Gender:",
      name: "gender",
      type:"select",
      options: [
        "M",
        "F"
    ]
    }, {
      label: "Hire date:",
      name: "hireDate",
      type: 'datetime',
      displayFormat: 'YYYY-MM-DD',
      wireFormat: 'YYYY-MM-DD',
      keyInput: false
    }, {
      label: "Birth date:",
      name: "birthDate",
      type: "datetime",
      displayFormat: 'YYYY-MM-DD',
      wireFormat: 'YYYY-MM-DD',
      keyInput: false
    }, {
      name: "users.removed_date",
      type: "hidden"
    }
    ]
  });

  var table = $('#example').DataTable({
    dom: "Bfrtip",
    "processing": true,
    "serverSide": true,
    ajax: {
      url: "../index.php",
      type: 'POST'
    },
    columns: [
      { data: "DT_RowId" },
      { data: "firstName" },
      { data: "lastName" },
      { data: "gender" },
      { data: "hireDate" },
      { data: "birthDate" }
    ],
    select: true,
    buttons: [
      { extend: "create", editor: editor },
      { extend: "edit", editor: editor},
      {
        extend: "selected",
        text: 'Delete',
        action: function (e, dt, node, config) {
          var rows = table.rows({ selected: true }).indexes();

          editor
            .hide(editor.fields())
            .one('close', function () {
              setTimeout(function () { // Wait for animation
                editor.show(editor.fields());
              }, 500);
            })
            .edit(rows, {
              title: 'Delete',
              message: rows.length === 1 ?
                'Are you sure you wish to delete this row?' :
                'Are you sure you wish to delete these ' + rows.length + ' rows',
              buttons: 'Delete'
            })
            .val('users.removed_date', (new Date()).toISOString().split('T')[0]);
        }
      }
    ]
  });
});
