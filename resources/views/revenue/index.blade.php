@extends('layouts.app')
@section('content')
    <div class="content">

        <!-- Start Content-->
        <div class="container-fluid">

            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box">
                        <h4 class="page-title">{{ __('messages.projects') }}</h4>
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">{{ __('messages.dcq') }}</a></li>
                                <li class="breadcrumb-item active">{{ __('messages.projects') }}</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end page title -->

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="mb-2">
                                <div class="row row-cols-lg-auto g-2 align-items-center">
                                    <div class="col-12">
                                        <div>
                                            <select id="demo-foo-filter-status" class="form-select">
                                                <option value="">Show all</option>
                                                <option value="active">Active</option>
                                                <option value="disabled">Disabled</option>
                                                <option value="suspended">Suspended</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <input id="demo-foo-search" type="text" placeholder="Search" class="form-control"
                                            autocomplete="on">
                                    </div>
                                    <a id="demo-btn-addrow" class="btn btn-primary" href="{{ route('project.add') }}"><i
                                            class="mdi mdi-plus-circle me-2"></i> Add New Projects</a>
                                </div>
                            </div>

                            <div class="table-responsive">
                                <table id="demo-foo-filtering" class="table table-bordered toggle-circle mb-0"
                                    data-page-size="7">
                                    <thead>
                                        <tr>
                                            <th data-toggle="true">ID</th>
                                            <th>Name</th>
                                            <th>Total</th>
                                            <th>Type</th>
                                            <th>Note</th>
                                            <th>Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @isset($arRevenue)
                                            @forelse($arRevenue as $key => $revenue)
                                                <tr>
                                                    <td>
                                                        #{{ $revenue['id'] }}
                                                    </td>
                                                    <td>
                                                        {{ $revenue['name'] }}
                                                    </td>
                                                    <td>
                                                        {{ $revenue['total'] }}
                                                    </td>
                                                    <td>{{ $revenue['type'] }}</td>
                                                    <td>{{ $revenue['note'] }}</td>
                                                    <td>{{ $revenue['entry_date'] }}</td>
                                                </tr>
                                            @empty
                                                <!-- Code to be executed if $arRevenue is empty -->
                                            @endforelse
                                        @endisset

                                    </tbody>
                                    <tfoot>
                                        <tr class="active">
                                            <td colspan="6">
                                                <div>
                                                    <ul
                                                        class="pagination pagination-rounded justify-content-end footable-pagination mb-0">
                                                        <li class="footable-page-arrow disabled"><a data-page="first"
                                                                href="#first">«</a></li>
                                                        <li class="footable-page-arrow disabled"><a data-page="prev"
                                                                href="#prev">‹</a></li>
                                                        <li class="footable-page active"><a data-page="0"
                                                                href="#">1</a></li>
                                                        <li class="footable-page"><a data-page="1" href="#">2</a>
                                                        </li>
                                                        <li class="footable-page"><a data-page="2" href="#">3</a>
                                                        </li>
                                                        <li class="footable-page"><a data-page="3" href="#">4</a>
                                                        </li>
                                                        <li class="footable-page"><a data-page="4" href="#">5</a>
                                                        </li>
                                                        <li class="footable-page-arrow"><a data-page="next"
                                                                href="#next">›</a></li>
                                                        <li class="footable-page-arrow"><a data-page="last"
                                                                href="#last">»</a></li>
                                                    </ul>
                                                </div>
                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div> <!-- end .table-responsive-->
                        </div>
                    </div> <!-- end card -->
                </div> <!-- end col -->
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="mb-2">
                                <h3 style="font-size: 1.rem">Projects</h3>
                            </div>
                            @isset($projects)
                                @forelse($projects as   $project)
                                    <div>
                                        <div class="d-flex flex-row justify-content-between">
                                            <p>{{ $project->name }}</p>
                                            <p> {{ $project->progress == null ? '0%' : $project->progress . '%' }}</p>
                                        </div>

                                        <div class="progress">
                                            <div class="progress-bar" role="progressbar"
                                                style="width: {{ $project->progress == 0 ? '0%' : ($project->progress == null ? '0' : $project->progress . '%') }};"
                                                aria-valuenow="{{ $project->progress }}" aria-valuemin="0" aria-valuemax="100">

                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <!-- Code to be executed if $projects is empty -->
                                @endforelse
                            @endisset


                        </div>
                    </div> <!-- end card -->
                </div> <!-- end col -->
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="mb-2">
                                <p>Revenue Report</p>
                            </div>
                            @isset($projects)
                                @forelse($projects as   $project)
                                @empty
                                    <!-- Code to be executed if $projects is empty -->
                                @endforelse
                            @endisset
                            <canvas id="myChart"></canvas>
                            <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
                            <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/chart.js/dist/chart.umd.min.js"></script>
                            <script>
                                 var revenues = JSON.parse('<?php echo $revenuesJson; ?>')
  var expenses = JSON.parse('<?php echo $expensesJson; ?>')

console.log(parseInt(revenues[1]))

                            // setup
                            const data = {
                              labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
                              datasets: [{
                                label: 'Revenues',
                                data: [
  parseInt(revenues[1]),
  parseInt(revenues[2]),
  parseInt(revenues[3]),
  parseInt(revenues[4]),
  parseInt(revenues[5]),
  parseInt(revenues[6]),
  parseInt(revenues[7]),
  parseInt(revenues[8]),
  parseInt(revenues[9]),
  parseInt(revenues[10]),
  parseInt(revenues[11]),
  parseInt(revenues[12])
],
                                backgroundColor: [
                                  ' rgba(0, 255, 145, 0.8)',

                                ],

                                borderWidth: 1
                              },
                              {
                                label: 'Expenses',
                                data: [
                                    parseInt(-1*expenses[1]),
                                    parseInt(-1*expenses[2]),
                                    parseInt(-1*expenses[3]),
                                    parseInt(-1*expenses[4]),
                                    parseInt(-1*expenses[5]),
                                    parseInt(-1*expenses[6]),
                                    parseInt(-1*expenses[7]),
                                    parseInt(-1*expenses[8]),
                                    parseInt(-1*expenses[9]),
                                    parseInt(-1*expenses[10]),
                                    parseInt(-1*expenses[11]),
                                    parseInt(-1*expenses[12])

],
                                borderColor: [
                                  'rgba(255, 61, 72, 1)',

                                ],
                                backgroundColor: [
                                'rgba(255, 61, 72, 1)',

                                ],
                                borderWidth: 1
                              }]
                            };

                            // config
                            const config = {
                              type: 'bar',
                              data,
                              options: {
                                scales: {
                                    x:{
                                        stacked: true
                                    },
                                  y: {
                                    beginAtZero: true,
                                    stacked: true
                                  }
                                }
                              }
                            };

                            // render init block
                            const myChart = new Chart(
                              document.getElementById('myChart'),
                              config
                            );

                            // Instantly assign Chart.js version
                            const chartVersion = document.getElementById('chartVersion');
                            chartVersion.innerText = Chart.version;
                            </script>



                        </div>
                    </div> <!-- end card -->
                </div> <!-- end col -->
            </div>
            <!-- end row -->
        </div> <!-- container -->

    </div> <!-- content -->

    <!-- Footer Start -->
    <footer class="footer">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-6">
                    <script>
                        document.write(new Date().getFullYear())
                    </script> &copy; Minton theme by <a href="">Coderthemes</a>
                </div>
                <div class="col-md-6">
                    <div class="text-md-end footer-links d-none d-sm-block">
                        <a href="javascript:void(0);">About Us</a>
                        <a href="javascript:void(0);">Help</a>
                        <a href="javascript:void(0);">Contact Us</a>
                    </div>
                </div>
            </div>
        </div>
    </footer>
    <!-- end Footer -->
@endsection
