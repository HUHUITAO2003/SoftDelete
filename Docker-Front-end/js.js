var editor; // use a global for the submit and return data rendering in the examples

$.fn.dataTable.render.moment = function (from, to, locale) {
  // Argument shifting
  if (arguments.length === 1) {
    locale = 'en';
    to = from;
    from = 'YYYY-MM-DD';
  }
  else if (arguments.length === 2) {
    locale = 'en';
  }

  return function (d, type, row) {
    if (!d) {
      return type === 'sort' || type === 'type' ? 0 : d;
    }

    var m = window.moment(d, from, locale, true);

    // Order and type get a number value from Moment, everything else
    // sees the rendered value
    return m.format(type === 'sort' || type === 'type' ? 'x' : to);
  };
};

$(document).ready(function () {
  editor = new $.fn.dataTable.Editor({
    ajax: "../index.php",
    table: "#example",
    fields: [
    {
      label: "First name:",
      name: "users.firstName"
    }, {
      label: "Last name:",
      name: "users.lastName"
    }, {
      label: "Gender:",
      name: "users.gender",
      type:"select",
      options: [
        "M",
        "F"
    ]
    }, {
      label: "Hire date:",
      name: "users.hireDate",
      type: 'datetime',
      displayFormat: 'YYYY-MM-DD',
      wireFormat: 'YYYY-MM-DD',
      keyInput: false
    }, {
      label: "Birth date:",
      name: "users.birthDate",
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
      { extend: "edit", editor: editor,
      action: function () {
        editor.edit(table.rows( { selected: true } ).indexes(),{ buttons: 'Update'});

    } }, 
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
