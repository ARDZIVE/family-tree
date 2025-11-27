<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>
    <div class="container-fluid py-4">
        <div class="row mb-3">
            <div class="col-12">
                <div class="card shadow">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Chart</h5>
                        <div>
                            <!-- Zoom controls -->
                            <div class="d-inline-flex align-items-center gap-2 position-relative" style="width: 200px;">
                                <i class="bi bi-zoom-out zoom-icon"></i>
                                <input type="range" id="zoomSlider" class="form-range" min="0.1" max="2" step="0.05" value="1">
                                <i class="bi bi-zoom-in zoom-icon"></i>
                            </div>
                            <!-- Horizontal scroll controls -->
                            <div class="d-inline-flex align-items-center gap-2 position-relative" style="width: 200px;">
                                <i class="bi bi-arrow-left scroll-icon"></i>
                                <input type="range" id="scrollSlider" class="form-range" min="0" max="100" step="1" value="50">
                                <i class="bi bi-arrow-right scroll-icon"></i>
                            </div>
                            <button id="refreshPage" class="btn btn-light btn-sm">
                                <i class="bi bi-arrow-clockwise"></i>
                                <span class="d-none d-md-inline">Refresh</span>
                            </button>
                            <button id="fitChart" class="btn btn-light btn-sm">
                                <i class="bi bi-arrows-angle-expand"></i>
                                <span class="d-none d-md-inline">Fit</span>
                            </button>
                            <button id="refreshChart" class="btn btn-light btn-sm">
                                <i class="bi bi-aspect-ratio"></i>
                                <span class="d-none d-md-inline">Ratio</span>
                            </button>
                            <button id="exportPDF" class="btn btn-light btn-sm">
                                <i class="bi bi-file-pdf"></i>
                                <span class="d-none d-md-inline">PDF</span>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div id="chart_div" class="chart-container"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?= $this->section('scripts') ?>
   <script type="text/javascript">
        // Global variables
        let chart;
        let currentZoom = 1;
        let currentScroll = 50;
        const ZOOM_STEP = 0.05;
        const SCROLL_STEP = 1;
        const MIN_ZOOM = 0.1;
        const MAX_ZOOM = 2;
        const SNAP_THRESHOLD = 0.1;
        const ZOOM_MIDDLE = 1.0;
        const SCROLL_MIDDLE = 50;

        // Load Google Charts
        google.charts.load('current', {packages:["orgchart"]});
        google.charts.setOnLoadCallback(initializeChart);

        function initializeChart() {
            drawChart();
            // Set initial scroll position after a short delay to ensure chart is rendered
            setTimeout(() => {
                const container = document.querySelector('.chart-container');
                if (container) {
                    const maxScroll = container.scrollWidth - container.clientWidth;
                    container.scrollLeft = maxScroll / 2;  // Set to middle position
                    updateSliderFromScroll();
                }
            }, 500);
        }

        // Main chart drawing function
        function drawChart() {
            var data = new google.visualization.DataTable();
            data.addColumn('string', 'Member ID');
            data.addColumn('string', 'Parent ID');
            data.addColumn('string', 'Tooltip');
            data.addColumn('string', 'Gender');
            data.addColumn('string', 'Style');

            const rawChartData = <?= $chartData ?>;
            const formattedData = rawChartData.map(row => {
                const [memberId, parentId, Id, name, gender] = row;
                const style = gender === '1' ? 'background-color: #ADD8E6;' :
                    gender === '2' ? 'background-color: #FFB6C1;' : '';
                return [memberId, parentId, Id, name, style];
            });

            data.addRows(formattedData);

            chart = new google.visualization.OrgChart(document.getElementById('chart_div'));
            var options = {
                allowHtml: true,
                allowCollapse: true,
                size: 'medium',
                compactRows: true,
                nodeClass: 'google-visualization-orgchart-node',
                selectedNodeClass: 'google-visualization-orgchart-nodesel'
            };

            let clickTimeout = null;
            let preventSingleClick = false;

            google.visualization.events.addListener(chart, 'select', function() {
                var selection = chart.getSelection();
                if (selection.length > 0) {
                    var selectedRow = selection[0].row;
                    var memberData = rawChartData[selectedRow];

                    if (preventSingleClick) {
                        preventSingleClick = false;
                        return;
                    }

                    clickTimeout = setTimeout(() => {
                        if (!preventSingleClick) {
                            window.location.href = `<?= base_url('family-tree/view') ?>/${memberData[2]}`;
                        }
                    }, 250);
                }
            });

            $('#chart_div').on('dblclick', '.google-visualization-orgchart-node', function(e) {
                e.preventDefault();
                clearTimeout(clickTimeout);
                preventSingleClick = true;

                const selection = chart.getSelection();
                if (selection.length > 0) {
                    const selectedRow = selection[0].row;
                    const isCollapsed = chart.getCollapsed(selectedRow);
                    chart.collapse(selectedRow, !isCollapsed);
                    chart.draw(data, options);
                }
            });

            chart.draw(data, options);

            // Initial positioning
            setTimeout(() => {
                centerChart();
            }, 100);
        }

        function updateZoom(newZoom, fromButton = false) {
            if (!fromButton && Math.abs(newZoom - ZOOM_MIDDLE) < SNAP_THRESHOLD) {
                newZoom = ZOOM_MIDDLE;
                $('#zoomSlider').addClass('snapped');
                setTimeout(() => $('#zoomSlider').removeClass('snapped'), 500);
            }

            currentZoom = Math.max(MIN_ZOOM, Math.min(MAX_ZOOM, newZoom));
            $('#zoomSlider').val(currentZoom);

            const container = document.querySelector('.chart-container');
            const scrollFraction = container ? container.scrollLeft / (container.scrollWidth - container.clientWidth) : 0.5;

            applyZoom();

            // Restore scroll position after zoom
            if (container) {
                const maxScroll = container.scrollWidth - container.clientWidth;
                container.scrollLeft = maxScroll * scrollFraction;
            }
        }

        function applyZoom() {
            const container = document.querySelector('.chart-container');
            const chartElement = $('.google-visualization-orgchart-table');

            if (!chartElement.length || !container) return;

            // First, reset any existing transforms
            chartElement.css({
                'transform': 'none',
                'left': '0',
                'right': '0',
                'margin': '0 auto'
            });

            // Get the natural width of the chart
            const naturalWidth = chartElement.width();

            // Calculate the scaled width
            const scaledWidth = naturalWidth * currentZoom;

            // Calculate the extra space needed on each side
            const extraSpace = Math.max(0, (scaledWidth - naturalWidth) / 2);

            // Apply the new styles
            chartElement.css({
                'transform': `scale(${currentZoom})`,
                'transform-origin': '50% 0',
                'position': 'relative',
                'margin': `0 ${extraSpace}px`,
                'transition': 'transform 0.2s ease-out'
            });

            if (currentZoom > 1) {
                enableScrollControls();
                container.scrollLeft = (container.scrollWidth - container.clientWidth) / 2;
            } else {
                centerChart();
            }
        }

        function updateSliderFromScroll() {
            const container = document.querySelector('.chart-container');
            if (!container) return;

            const maxScroll = container.scrollWidth - container.clientWidth;
            if (maxScroll <= 0) {
                $('#scrollSlider').val(SCROLL_MIDDLE);
                return;
            }

            const scrollPercentage = (container.scrollLeft / maxScroll) * 100;
            $('#scrollSlider').val(scrollPercentage);
            currentScroll = scrollPercentage;
        }

        function updateScroll(newScroll, fromButton = false) {
            const container = document.querySelector('.chart-container');
            if (!container) return;

            if (!fromButton && Math.abs(newScroll - SCROLL_MIDDLE) < (SNAP_THRESHOLD * 100)) {
                newScroll = SCROLL_MIDDLE;
                $('#scrollSlider').addClass('snapped');
                setTimeout(() => $('#scrollSlider').removeClass('snapped'), 500);
            }

            const chartElement = $('.google-visualization-orgchart-table');
            if (!chartElement.length) return;

            const chartRect = chartElement[0].getBoundingClientRect();
            const totalWidth = chartRect.width;
            const containerWidth = container.clientWidth;
            const maxScroll = totalWidth - containerWidth;

            // Calculate scroll position accounting for padding
            const scrollPosition = (newScroll / 100) * maxScroll;

            container.scrollTo({
                left: scrollPosition,
                behavior: fromButton ? 'auto' : 'smooth'
            });

            currentScroll = newScroll;
            $('#scrollSlider').val(newScroll);
        }

        function centerChart() {
            const container = document.querySelector('.chart-container');
            if (!container) return;

            const chartElement = $('.google-visualization-orgchart-table');
            if (!chartElement.length) return;

            const chartRect = chartElement[0].getBoundingClientRect();
            const totalWidth = chartRect.width;
            const containerWidth = container.clientWidth;

            // Calculate the center position including padding
            const maxScroll = totalWidth - containerWidth;
            const centerPosition = maxScroll / 2;

            container.scrollTo({
                left: centerPosition,
                behavior: 'smooth'
            });

            currentScroll = SCROLL_MIDDLE;
            $('#scrollSlider').val(SCROLL_MIDDLE);
        }
        function enableScrollControls() {
            $('#scrollSlider').prop('disabled', false);
            $('.bi-arrow-left, .bi-arrow-right').removeClass('disabled');
        }

        function fitChartToContainer() {
            const container = document.querySelector('.chart-container');
            const chartTable = document.querySelector('.google-visualization-orgchart-table');

            if (!container || !chartTable) return;

            const containerWidth = container.offsetWidth;
            const containerHeight = container.offsetHeight;
            const chartWidth = chartTable.offsetWidth;
            const chartHeight = chartTable.offsetHeight;

            const widthRatio = (containerWidth * 0.95) / chartWidth;
            const heightRatio = (containerHeight * 0.95) / chartHeight;

            let newZoom = Math.min(widthRatio, heightRatio);
            newZoom = Math.max(MIN_ZOOM, Math.min(MAX_ZOOM, newZoom));

            updateZoom(newZoom);
            centerChart();
        }

        // Export to PDF function
        function exportChartToPDF() {
            $('#loadingIndicator').remove();

            $('body').append(`
        <div id="loadingIndicator" class="position-fixed top-50 start-50 translate-middle bg-white p-3 rounded shadow">
            <div class="d-flex align-items-center">
                <div class="spinner-border text-primary me-2" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <span>Generating PDF...</span>
            </div>
        </div>
    `);

            const chartContainer = document.querySelector('.chart-container');

            html2canvas(chartContainer, {
                scale: 4,
                backgroundColor: '#ffffff',
                width: chartContainer.clientWidth,
                height: chartContainer.clientHeight,
                scrollX: -chartContainer.scrollLeft,
                scrollY: -chartContainer.scrollTop,
                useCORS: true,
                logging: false
            }).then(canvas => {
                const { jsPDF } = window.jspdf;

                const doc = new jsPDF({
                    orientation: 'landscape',
                    format: 'a4',
                    compress: true,
                    unit: 'mm'
                });

                const pageWidth = doc.internal.pageSize.getWidth();
                const pageHeight = doc.internal.pageSize.getHeight();
                const margin = 5;

                const availableWidth = pageWidth - (2 * margin);
                const availableHeight = pageHeight - (2 * margin);

                const imgRatio = canvas.width / canvas.height;
                const pageRatio = availableWidth / availableHeight;

                let imgWidth, imgHeight;
                if (imgRatio > pageRatio) {
                    imgWidth = availableWidth;
                    imgHeight = availableWidth / imgRatio;
                } else {
                    imgHeight = availableHeight;
                    imgWidth = availableHeight * imgRatio;
                }

                const x = margin + (availableWidth - imgWidth) / 2;
                const y = margin + (availableHeight - imgHeight) / 2;

                doc.addImage(
                    canvas.toDataURL('image/jpeg', 1),
                    'JPEG',
                    x,
                    y,
                    imgWidth,
                    imgHeight,
                    undefined,
                    'FAST'
                );

                // Add watermark text
                doc.setFont('helvetica');
                doc.setFontSize(5);
                doc.setTextColor(128, 128, 128); // Gray color
                const now = new Date();
                const formattedDate = now.toLocaleDateString('en-US', {
                    day: '2-digit',
                    month: 'long',
                    year: 'numeric'
                }).replace(',', '-');
                const formattedTime = now.toLocaleTimeString('en-US', {
                    hour: '2-digit',
                    minute: '2-digit',
                    hour12: false
                });
                const watermarkText = `TALATINIAN Family Tree as of: ${formattedDate} at ${formattedTime}`;
                const textWidth = doc.getTextWidth(watermarkText);

                // Position watermark at bottom right with small padding
                doc.text(
                    watermarkText,
                    // pageWidth - margin - textWidth,  // X position (from right)
                    (pageWidth - textWidth) / 2,    // Centered horizontally
                    pageHeight - margin,             // Y position (from bottom)
                    { baseline: 'bottom' }
                );

                doc.save('family-tree.pdf');
                $('#loadingIndicator').remove();
            }).catch(error => {
                console.error('Error generating PDF:', error);
                alert('Error generating PDF. Please try again.');
                $('#loadingIndicator').remove();
            });
        }

        $(document).ready(function() {
            $('#zoomSlider').on('input', function() {
                updateZoom(parseFloat($(this).val()), false);
            });

            $('.bi-zoom-in, .bi-zoom-out').on('click', function() {
                const isZoomIn = $(this).hasClass('bi-zoom-in');
                const newZoom = currentZoom + (isZoomIn ? ZOOM_STEP : -ZOOM_STEP);
                updateZoom(newZoom, true);
            });

            $('#scrollSlider').on('input', function() {
                updateScroll(parseFloat($(this).val()), false);
            });

            $('.bi-arrow-left').on('click', function() {
                if (!$(this).hasClass('disabled')) {
                    const step = SCROLL_STEP * 5;
                    const newScroll = Math.max(0, currentScroll - step);
                    updateScroll(newScroll, true);
                }
            });

            $('.bi-arrow-right').on('click', function() {
                if (!$(this).hasClass('disabled')) {
                    const step = SCROLL_STEP * 5;
                    const newScroll = Math.min(100, currentScroll + step);
                    updateScroll(newScroll, true);
                }
            });

            let scrollTimeout;
            $('.chart-container').on('scroll', function() {
                clearTimeout(scrollTimeout);
                scrollTimeout = setTimeout(function() {
                    if (!$('#scrollSlider').prop('disabled')) {
                        updateSliderFromScroll();
                    }
                }, 50);
            });

            $('#refreshChart').on('click', function() {
                currentZoom = 1;
                updateZoom(1);
                drawChart();
            });

            $('#fitChart').on('click', function() {
                fitChartToContainer();
            });

            $('#exportPDF').on('click', function() {
                exportChartToPDF();
            });

            $('#refreshPage').on('click', function() {
                location.reload();
            });

            let resizeTimeout;
            $(window).on('resize', function() {
                clearTimeout(resizeTimeout);
                resizeTimeout = setTimeout(function() {
                    drawChart();
                    requestAnimationFrame(() => {
                        applyZoom();
                    });
                }, 250);
            });
        });
    </script>

    <style>
        .chart-container {
            height: 100%;
            width: 100%;
            min-height: 1000px;
            overflow: auto;
            position: relative;
        }

        .google-visualization-orgchart {
            display: inline-block;
            position: relative;
            width: 100%;
        }

        .google-visualization-orgchart-table {
            position: relative;
            display: inline-block;
            transform-origin: 50% 0;
            transition: transform 0.2s ease-out;
            will-change: transform;
        }

        .google-visualization-orgchart-node {
            border: none !important;
            box-shadow: none !important;
            border-radius: 4px;
            padding: 8px !important;
            cursor: pointer;
            user-select: none;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
        }

        .google-visualization-orgchart-node:hover {
            background-color: #f8f9fa;
        }

        .card-body {
            padding-top: 10px !important;
        }

        #loadingIndicator {
            z-index: 9999;
            border: 1px solid #dee2e6;
        }

        .zoom-icon {
            font-size: 0.875rem;
            color: #6c757d;
            cursor: pointer;
            padding: 5px;
            transition: color 0.2s ease;
        }

        .zoom-icon:hover {
            color: #0d6efd;
        }

        .scroll-icon {
            font-size: 0.875rem;
            color: #6c757d;
            cursor: pointer;
            padding: 5px;
            transition: color 0.2s ease;
        }

        .scroll-icon:hover {
            color: #0d6efd;
        }

        .scroll-icon.disabled {
            opacity: 0.6;
            cursor: not-allowed;
            pointer-events: none;
        }

        .form-range {
            position: relative;
        }

        @keyframes snapPulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.2); }
            100% { transform: scale(1); }
        }

        .form-range::-webkit-slider-thumb {
            -webkit-appearance: none;
            width: 16px;
            height: 16px;
            border-radius: 50%;
            background: #198754;
            cursor: pointer;
            border: none;
            box-shadow: 0 2px 4px rgba(0,0,0,0.2);
            transition: transform 0.2s ease;
        }

        .form-range::-moz-range-thumb {
            width: 16px;
            height: 16px;
            border-radius: 50%;
            background: #198754;
            cursor: pointer;
            border: none;
            box-shadow: 0 2px 4px rgba(0,0,0,0.2);
            transition: transform 0.2s ease;
        }

        .form-range::-webkit-slider-runnable-track {
            background: #dee2e6;
            height: 4px;
            border-radius: 2px;
        }

        .form-range::-moz-range-track {
            background: #dee2e6;
            height: 4px;
            border-radius: 2px;
        }

        .form-range::-webkit-slider-thumb:hover {
            background: #146c43;
        }

        .form-range::-moz-range-thumb:hover {
            background: #146c43;
        }

        .form-range:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }

        .form-range.snapped::-webkit-slider-thumb {
            animation: snapPulse 0.5s ease;
        }

        .form-range.snapped::-moz-range-thumb {
            animation: snapPulse 0.5s ease;
        }

        .d-inline-flex.position-relative::after {
            content: '';
            position: absolute;
            left: 50%;
            top: 50%;
            width: 2px;
            height: 12px;
            transform: translate(-50%, -50%);
            pointer-events: none;
            z-index: 1;
        }

        .d-inline-flex:has(#zoomSlider)::after {
            background-color: #198754;
        }

        .d-inline-flex:has(#scrollSlider)::after {
            background-color: #ffcc66;
        }

        #scrollSlider::-webkit-slider-thumb {
            background: #ffcc66;
        }

        #scrollSlider::-moz-range-thumb {
            background: #ffcc66;
        }

        #scrollSlider::-webkit-slider-thumb:hover {
            background: #ffbb33;
        }

        #scrollSlider::-moz-range-thumb:hover {
            background: #ffbb33;
        }

        #fitChart {
            margin-left: 5px;
            margin-right: 0;
        }

        .google-visualization-orgchart-nodesel {
            background-color: #e9ecef !important;
        }
    </style>
<?= $this->endSection() ?>
<?= $this->endSection() ?>