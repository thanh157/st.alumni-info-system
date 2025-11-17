<!doctype html>
<html lang="en">

@include('admin.includes.head')
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<body>
    <!--Main navbar-->
    @include('admin.includes.header')
    <!--/Main navbar-->

    <!-- Loading overlay -->
    <div id="loading-overlay"
        style="display:none;position:fixed;top:0;left:0;right:0;bottom:0;z-index:1050;background:rgba(255,255,255,0.8);display:flex;align-items:center;justify-content:center;">
        <div class="wave-loader">
            <span></span><span></span><span></span><span></span><span></span>
        </div>
    </div>
    <!-- CSS hiệu ứng -->
    <style>
        .wave-loader {
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .wave-loader span {
            display: block;
            width: 5px;
            height: 20px;
            margin: 0 3px;
            background: #0d6efd;
            animation: wave 1.2s infinite ease-in-out;
        }

        table thead tr td {
            font-weight: 600;
        }

        .wave-loader span:nth-child(2) {
            animation-delay: -1.1s;
        }

        .wave-loader span:nth-child(3) {
            animation-delay: -1.0s;
        }

        .wave-loader span:nth-child(4) {
            animation-delay: -0.9s;
        }

        .wave-loader span:nth-child(5) {
            animation-delay: -0.8s;
        }

        @keyframes wave {

            0%,
            40%,
            100% {
                transform: scaleY(0.4);
            }

            20% {
                transform: scaleY(1);
            }
        }
    </style>

    <!-- Page content -->
    <div class="page-content">

        @include('admin.includes.sidebars')

        <!-- Main content -->
        <div class="content-wrapper">
            <!-- Inner content -->
            <div class="content-inner">

                {{-- @include('admin.includes.toast') --}}

                @yield('content')

                <!-- Footer -->
                @include('admin.includes.footer')
                <!-- /footer -->
            </div>
            <!-- /inner content -->
        </div>
        <!-- /main content -->

    </div>
    <!-- /page content -->

    <!-- NProgress -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/nprogress/0.2.0/nprogress.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/nprogress/0.2.0/nprogress.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @include('admin.includes.script')



    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const links = document.querySelectorAll("a[href]:not([target='_blank']):not([href^='#']):not([href^='javascript']):not([download])"); links.forEach(link => {
                link.addEventListener("click", function (e) {
                    const href = link.getAttribute("href");
                    if (href && !href.startsWith("#") && !href.startsWith("javascript")) {
                        NProgress.start();
                        document.getElementById("loading-overlay").style.display = "flex";
                    }
                });
            });

            window.addEventListener("pageshow", function () {
                NProgress.done();
                document.getElementById("loading-overlay").style.display = "none";
            });

        });
        toastr.options = {
            "closeButton": true,
            "progressBar": true,
            "positionClass": "toast-top-right",
            "timeOut": "5000"
        };

        @if(Session::has('success'))
            toastr.success("{{ Session::get('success') }}");
        @endif

        @if(Session::has('error'))
            toastr.error("{{ Session::get('error') }}");
        @endif

        @if(Session::has('info'))
            toastr.info("{{ Session::get('info') }}");
        @endif

        @if(Session::has('warning'))
            toastr.warning("{{ Session::get('warning') }}");
        @endif
        function handleDownloadClick(event, timeoutMs) {
            event.preventDefault();

            const button = $(event.currentTarget);
            const downloadUrl = button.attr('href');

            if (button.data('loading') === true) {
                return;
            }


            button.data('loading', true);
            const originalHtml = button.html();

            if (button.hasClass('btn')) {
                button.prop('disabled', true).html('<i class="spinner-border spinner-border-sm"></i> Đang xử lý...');
            } else {
                button.find('span').text('Đang xử lý...');
            }


            if (typeof toastr !== 'undefined') {
                toastr.info('Server đang xử lý. Vui lòng chờ...', 'Đang xử lý', {
                    timeOut: timeoutMs,
                    progressBar: true
                });
            }


            const link = document.createElement('a');
            link.href = downloadUrl;
            link.setAttribute('download', '');
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);


            setTimeout(function () {
                button.data('loading', false);
                button.prop('disabled', false);
                button.html(originalHtml);
            }, timeoutMs);
        }

        $(document).ready(function () {


            $('.download-link').on('click', function (e) {
                handleDownloadClick(e, 20000); // 20 giây
            });
            $('.download-link2').on('click', function (e) {
                handleDownloadClick(e, 5000); // 8 giây
            });

        });
    </script>

    @stack('script')
</body>

</html>