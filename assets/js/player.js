import DataTable from 'datatables.net-dt';

const baseUrl = $('#base-url').val();
const teamId = $('#team-id').val();

let PlayersTable = new DataTable('#players-table', {
  processing: true,
  serverSide: true,
  ajax: {
    url: `${baseUrl}/players/list/${teamId}`,
    type: 'POST',
    data: function (param) {
      param.offset = param.start;
      param.limit = 10;
      param.orderBy = param.columns[param.order[0].column].name;
      param.orderDir = param.order[0].dir;
    },
    dataSrc : function ( json ){
      return json;
    },
    lengthMenu: [
      [10, 25, 50, -1],
      [10, 25, 50, 'All'],
    ],
    columns: [
      { data: "name" },
      { data: "surname" }
    ],
  }
});