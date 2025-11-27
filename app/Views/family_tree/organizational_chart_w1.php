<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>
    <div class="container-fluid py-4">
        <div class="row mb-3">
            <div class="col-12">
                <div class="card shadow">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Chart</h5>
                        <div>
                            <button id="refreshPage" class="btn btn-light btn-sm">
                                <i class="bi bi-arrow-clockwise"></i>
                                <span class="d-none d-md-inline">Refresh</span>
                            </button>
                            <div class="d-inline-flex align-items-center gap-2" style="width: 200px;">
                                <i class="bi bi-zoom-out zoom-icon"></i>
                                <input type="range" id="zoomSlider" class="form-range" min="0.1" max="2" step="0.05" value="1">
                                <i class="bi bi-zoom-in zoom-icon"></i>
                            </div>
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
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
        // Global variables
        let chart;
        let currentZoom = 1;
        const ZOOM_STEP = 0.05;
        const MIN_ZOOM = 0.1;
        const MAX_ZOOM = 2;

        // Load Google Charts
        google.charts.load('current', {packages:["orgchart"]});
        google.charts.setOnLoadCallback(drawChart);

        // Main chart drawing function
        function drawChart() {
            var data = new google.visualization.DataTable();
            data.addColumn('string', 'Member ID');
            data.addColumn('string', 'Parent ID');
            data.addColumn('string', 'Tooltip');
            data.addColumn('string', 'Gender');
            data.addColumn('string', 'Style');

            // Parse the PHP chart data
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
                renderDistance: 1.5
            };

            // Click handling variables
            let clickTimeout = null;
            let preventSingleClick = false;

            // Single click handler
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

            // Double click handler
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
        }

        // Zoom control functions
        function updateZoom(newZoom) {
            currentZoom = Math.max(MIN_ZOOM, Math.min(MAX_ZOOM, newZoom));
            $('#zoomSlider').val(currentZoom);
            applyZoom();
        }

        function applyZoom() {
            const chartElement = $('.google-visualization-orgchart-table');
            if (chartElement.length) {
                chartElement.css({
                    'transform': `scale(${currentZoom})`,
                    'transform-origin': 'top center'
                });
            }
        }

        function fitChartToContainer() {
            const container = document.querySelector('.chart-container');
            const chartTable = document.querySelector('.google-visualization-orgchart-table');

            if (!container || !chartTable) return;

            // First reset any existing transform to get true dimensions
            chartTable.style.transform = 'none';

            // Get container and chart dimensions
            const containerWidth = container.offsetWidth;
            const containerHeight = container.offsetHeight;
            const chartWidth = chartTable.offsetWidth;
            const chartHeight = chartTable.offsetHeight;

            // Calculate zoom ratios with some padding
            const widthRatio = (containerWidth * 0.95) / chartWidth;  // 95% of container width
            const heightRatio = (containerHeight * 0.95) / chartHeight; // 95% of container height

            // Use the smaller ratio to ensure chart fits both dimensions
            let newZoom = Math.min(widthRatio, heightRatio);

            // Ensure zoom stays within bounds
            newZoom = Math.max(MIN_ZOOM, Math.min(MAX_ZOOM, newZoom));

            // Update zoom and slider
            updateZoom(newZoom);

            // Apply zoom and center immediately after dimension changes
            setTimeout(() => {
                // Get dimensions after zoom
                const newChartWidth = chartTable.offsetWidth;
                const newChartHeight = chartTable.offsetHeight;

                // Calculate center positions
                const scrollLeft = Math.max(0, (newChartWidth - containerWidth) / 2);
                const scrollTop = Math.max(0, (newChartHeight - containerHeight) / 2);

                // Apply scroll positions
                container.scrollLeft = scrollLeft;
                container.scrollTop = scrollTop;
            }, 100);
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

        // Event handlers
        $(document).ready(function() {
            // Zoom controls
            $('#zoomSlider').on('input', function() {
                updateZoom(parseFloat($(this).val()));
            });

            $('.bi-zoom-out').on('click', function() {
                updateZoom(currentZoom - ZOOM_STEP);
            });

            $('.bi-zoom-in').on('click', function() {
                updateZoom(currentZoom + ZOOM_STEP);
            });

            // Button handlers
            $('#refreshChart').on('click', function() {
                currentZoom = 1;
                updateZoom(1);
                drawChart();
            });

            $('#fitChart').on('click', function() {
                fitChartToContainer();
            });

            $('#refreshPage').on('click', function() {
                location.reload();
            });

            $('#exportPDF').on('click', function() {
                exportChartToPDF();
            });

            // Window resize handler with debounce
            let resizeTimeout;
            $(window).on('resize', function() {
                clearTimeout(resizeTimeout);
                resizeTimeout = setTimeout(function() {
                    drawChart();
                    applyZoom();
                }, 250);
            });
        });
    </script>

    <style>
        .chart-container {
            /*position: relative;*/
            height: 100%; /* Ensure it scales properly */
            /*overflow: visible;*/
            width: 100%;
            /*height: calc(100vh - 200px);*/
            min-height: 1000px;
            overflow: auto;
        }

        .google-visualization-orgchart-table {
            margin: 0 !important;
            transition: transform 0.2s ease-out;
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

        .form-range::-webkit-slider-thumb {
            -webkit-appearance: none;
            width: 16px;
            height: 16px;
            border-radius: 50%;
            background: #198754;
            cursor: pointer;
            border: none;
            box-shadow: 0 2px 4px rgba(0,0,0,0.2);
        }

        .form-range::-moz-range-thumb {
            width: 16px;
            height: 16px;
            border-radius: 50%;
            background: #198754;
            cursor: pointer;
            border: none;
            box-shadow: 0 2px 4px rgba(0,0,0,0.2);
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

        #fitChart {
            margin-left: 5px;
        }
    </style>
<?= $this->endSection() ?>
<?= $this->endSection() ?>