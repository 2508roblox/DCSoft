@extends('layouts.app')
@section('content')
    <div class="content">

        <!-- Start Content-->
        <div class="container-fluid">

            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box">
                        <h4 class="page-title">{{ __('messages.tasks') }}</h4>
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">{{ __('messages.dcq') }}</a></li>
                                <li class="breadcrumb-item active">{{ __('messages.tasks') }}</li>
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


                            <div class="row">
                                <div class="col-12">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="mb-2">
                                                <h3 style="font-size: 1.rem">Tasks</h3>
                                            </div>
                                            <div id="chartContainer" style="width: 700px; height: 600px;">
                                                <canvas id="myChart"></canvas>
                                            </div>



                                        </div>
                                    </div> <!-- end card -->
                                </div> <!-- end col -->
                            </div>
                        </div>
                    </div> <!-- end card -->
                </div> <!-- end col -->
            </div>

            <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
            <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/chart.js/dist/chart.umd.min.js" defer></script>
            <script>
                var tasks_statistic = JSON.parse('<?php echo $tasksJson; ?>')


                // config
                const config = {

                    type: 'doughnut',
                    data: {
                        datasets: [{
                            data: [tasks_statistic['to do'], tasks_statistic['complete'], tasks_statistic[
                                'awaiting confirmation'], tasks_statistic['overdue']],
                            backgroundColor: [
                                'rgba(0, 102, 255, 0.8)',
                                'rgba(0, 255, 0, 1)',
                                'rgb(255, 165, 0)',
                                'rgb(255, 0, 0)',

                            ],
                        }, ],
                        labels: ['Todo', 'Complete', 'Awaiting Confirmation', 'Overdue'],
                    },
                    options: {

                    },

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
