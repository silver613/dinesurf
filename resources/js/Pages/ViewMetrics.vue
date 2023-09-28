<template>
    <div class="container">
        <div class="my-10">
            <h2>View Metrics Per Button Click</h2>
            <table id="datatable">
            <thead>
                <tr>
                <th>#</th>
                <th>Click  Count</th>
                <th>User Ip</th>
                <th>Location</th>
                <th>Dinesurf User</th>
                <th>Updated At</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="metric in metrics" :key="metric.id">
                  <td>
                      {{ metric.id }}
                  </td>
                  <td>
                     {{ metric.count }}
                  </td>
                  <td>
                     {{ metric.ip }}
                  </td>
                  <td>
                     {{ metric.location }}
                  </td>
                  <td>
                     {{ metric.user_id != null ? 'Yes' : 'No' }}
                  </td>
                  <td>
                     {{   new Date(metric.updated_at ).toLocaleString(
                  "en-US",
                  {  year:"numeric", month:"2-digit", day:'2-digit' }
                ) }}
                  </td>
                </tr>
            </tbody>
            </table>
        </div>

    </div>
  </template>
  
  <script>
import "jquery/dist/jquery.min.js";
import "bootstrap/dist/css/bootstrap.min.css";
import "datatables.net-dt/js/dataTables.dataTables";
import "datatables.net-dt/css/jquery.dataTables.min.css";
import $ from "jquery";

export default {

  props:[ 'data'],

  data: function () {
    return {
      metrics: this.data,
    };
  },



  mounted() {

    // console.log("data:", this.data);
    setTimeout(() => {
          $("#datatable").DataTable({
            lengthMenu: [
              [5,10, 25, 50, -1],
              [5,10, 25, 50, "All"],
            ],
            pageLength: 5,
          });
        });
  },
};
</script>
  
  <style>
    .container {
      max-width: 1000px;
    }
  </style>