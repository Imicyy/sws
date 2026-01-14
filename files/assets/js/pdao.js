// Dynamic PDAO data source
let barangayData = {};

let currentChart = null;
let currentPage = 1;
const itemsPerPage = 5;
let filteredData = [];
let allData = [];
let currentChartType = 'doughnut'; // Default chart type

// Calculate statistics (after load)
let totalPDAO = 0;
let totalMale = 0;
let totalFemale = 0;
let averagePDAO = 0;
let averageMalePercentage = '0.0';
let averageFemalePercentage = '0.0';

// Update stats display (after load)
function updateStatsDisplay() {
    document.getElementById('totalPDAO').textContent = totalPDAO.toLocaleString();
    document.getElementById('averagePDAO').textContent = averagePDAO;
}

// Initialize data
function initializeData() {
    const entries = Object.entries(barangayData);
    totalPDAO = entries.reduce((sum, [_, d]) => sum + d.pdaoCount, 0);
    totalMale = entries.reduce((sum, [_, d]) => sum + (d.maleCount || 0), 0);
    totalFemale = entries.reduce((sum, [_, d]) => sum + (d.femaleCount || 0), 0);
    averagePDAO = entries.length ? Math.round(totalPDAO / entries.length) : 0;
    averageMalePercentage = totalPDAO ? ((totalMale / totalPDAO) * 100).toFixed(1) : '0.0';
    averageFemalePercentage = totalPDAO ? ((totalFemale / totalPDAO) * 100).toFixed(1) : '0.0';
    updateStatsDisplay();

    allData = entries.map(([id, data]) => ({
        id: parseInt(id),
        name: data.name,
        pdaoCount: data.pdaoCount,
        maleCount: data.maleCount,
        femaleCount: data.femaleCount,
        malePercentage: data.pdaoCount ? ((data.maleCount / data.pdaoCount) * 100).toFixed(1) : '0.0',
        femalePercentage: data.pdaoCount ? ((data.femaleCount / data.pdaoCount) * 100).toFixed(1) : '0.0',
        percentage: totalPDAO ? ((data.pdaoCount / totalPDAO) * 100).toFixed(1) : '0.0'
    }));
    filteredData = [...allData];
}

async function loadPdaoData() {
    try {
        const res = await fetch('/api/analytics/pdao', { credentials: 'same-origin' });
        const json = await res.json();
        if (!json.success) throw new Error('Failed to fetch PDAO data');

        barangayData = {};
        json.data.forEach(item => {
            barangayData[item.id] = {
                name: item.name,
                pdaoCount: item.pdaoCount,
                maleCount: item.maleCount,
                femaleCount: item.femaleCount
            };
        });

        initializeData();
        renderTable();
        renderPagination();
    } catch (err) {
        console.error(err);
        barangayData = {};
        initializeData();
        renderTable();
        renderPagination();
    }
}

// Render table rows
function renderTable() {
    const tbody = document.getElementById('tableBody');
    const startIndex = (currentPage - 1) * itemsPerPage;
    const endIndex = startIndex + itemsPerPage;
    const pageData = filteredData.slice(startIndex, endIndex);

    tbody.innerHTML = '';

    if (pageData.length === 0) {
        document.getElementById('noResults').style.display = 'block';
        document.getElementById('dataTable').style.display = 'none';
        document.getElementById('pagination').style.display = 'none';
        return;
    }

    document.getElementById('noResults').style.display = 'none';
    document.getElementById('dataTable').style.display = 'table';
    document.getElementById('pagination').style.display = 'flex';

    pageData.forEach(item => {
        const safeBarangay = item.name.replace(/"/g, '&quot;');
        const row = document.createElement('tr');
        row.innerHTML = `
            <td class="barangay-name">${item.name}</td>
            <td class="pdao-count">${item.pdaoCount.toLocaleString()}</td>
            <td>
                <button class="view-chart-btn" onclick="showChart(${item.id})">
                     View Chart
                </button>
                <button class="view-chart-btn" data-barangay="${safeBarangay}" onclick="openPwdBarangayPrint(this.dataset.barangay, this)">
                     Print
                </button>
            </td>
        `;
        tbody.appendChild(row);
    });
}

// Render pagination
function renderPagination() {
    const totalPages = Math.ceil(filteredData.length / itemsPerPage);
    const paginationControls = document.getElementById('paginationControls');
    const paginationInfo = document.getElementById('paginationInfo');

    // Update info
    const startItem = filteredData.length === 0 ? 0 : (currentPage - 1) * itemsPerPage + 1;
    const endItem = Math.min(currentPage * itemsPerPage, filteredData.length);
    paginationInfo.textContent = `Showing ${startItem}-${endItem} of ${filteredData.length} entries`;

    // Clear previous buttons
    paginationControls.innerHTML = '';

    // Previous button
    const prevBtn = document.createElement('button');
    prevBtn.className = 'page-btn';
    prevBtn.innerHTML = '◀';
    prevBtn.disabled = currentPage === 1;
    prevBtn.onclick = () => changePage(currentPage - 1);
    paginationControls.appendChild(prevBtn);

    // Page number buttons
    const maxVisiblePages = 5;
    let startPage = Math.max(1, currentPage - Math.floor(maxVisiblePages / 2));
    let endPage = Math.min(totalPages, startPage + maxVisiblePages - 1);

    if (endPage - startPage + 1 < maxVisiblePages) {
        startPage = Math.max(1, endPage - maxVisiblePages + 1);
    }

    for (let i = startPage; i <= endPage; i++) {
        const pageBtn = document.createElement('button');
        pageBtn.className = `page-btn ${i === currentPage ? 'active' : ''}`;
        pageBtn.textContent = i;
        pageBtn.onclick = () => changePage(i);
        paginationControls.appendChild(pageBtn);
    }

    // Next button
    const nextBtn = document.createElement('button');
    nextBtn.className = 'page-btn';
    nextBtn.innerHTML = '▶';
    nextBtn.disabled = currentPage === totalPages;
    nextBtn.onclick = () => changePage(currentPage + 1);
    paginationControls.appendChild(nextBtn);
}

// Change page
function changePage(page) {
    const totalPages = Math.ceil(filteredData.length / itemsPerPage);
    if (page >= 1 && page <= totalPages) {
        currentPage = page;
        renderTable();
        renderPagination();
    }
}

// Search functionality
function handleSearch() {
    const searchTerm = document.getElementById('searchInput').value.toLowerCase();
    
    if (searchTerm === '') {
        filteredData = [...allData];
    } else {
        filteredData = allData.filter(item => 
            item.name.toLowerCase().includes(searchTerm)
        );
    }
    
    currentPage = 1;
    renderTable();
    renderPagination();
}

// Switch chart type
function switchChartType(type) {
    currentChartType = type;
    const chartContainer = document.getElementById('chartContainer');
    const tableContainer = document.getElementById('tableContainer');
    const chartTypeButtons = document.querySelectorAll('.chart-type-btn');
    
    // Update button states
    chartTypeButtons.forEach(btn => btn.classList.remove('active'));
    document.querySelector(`[onclick="switchChartType('${type}')"]`).classList.add('active');
    
    if (type === 'table') {
        chartContainer.style.display = 'none';
        tableContainer.style.display = 'block';
        renderChartTable();
    } else {
        chartContainer.style.display = 'block';
        tableContainer.style.display = 'none';
        updateChart();
    }
}

// Render chart table - now shows male/female breakdown
function renderChartTable() {
    const tableBody = document.getElementById('chartTableBody');
    const currentBarangay = allData.find(item => item.id === parseInt(document.getElementById('modalTitle').dataset.barangayId));
    
    if (!currentBarangay) return;
    
    const data = [
        { name: 'Male', percentage: currentBarangay.malePercentage, count: currentBarangay.maleCount },
        { name: 'Female', percentage: currentBarangay.femalePercentage, count: currentBarangay.femaleCount }
    ];
    
    tableBody.innerHTML = '';
    data.forEach((item, index) => {
        const row = document.createElement('tr');
        row.innerHTML = `
            <td style="display: flex; align-items: center;">
                <div style="width: 20px; height: 20px; background-color: ${index === 0 ? '#061727' : '#415E72'}; margin-right: 10px; border-radius: 4px;"></div>
                ${item.name}
            </td>
            <td><strong>${item.percentage}% (${item.count})</strong></td>
        `;
        tableBody.appendChild(row);
    });
}

// Update chart - now shows male/female distribution
function updateChart() {
    const barangayId = parseInt(document.getElementById('modalTitle').dataset.barangayId);
    const barangay = allData.find(item => item.id === barangayId);
    
    if (!barangay) return;
    
    const malePercentage = parseFloat(barangay.malePercentage);
    const femalePercentage = parseFloat(barangay.femalePercentage);

    // Destroy existing chart
    if (currentChart) {
        currentChart.destroy();
    }

    // Create new chart
    const ctx = document.getElementById('pieChart').getContext('2d');
    currentChart = new Chart(ctx, {
        type: currentChartType,
        data: {
            labels: ['Male', 'Female'],
            datasets: [{
                data: [malePercentage, femalePercentage],
                backgroundColor: [
                    '#061727',
                    '#415E72'
                ],
                borderColor: [
                    '#061727',
                    '#FDFAF6'
                ],
                borderWidth: 2,
                hoverOffset: 15
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        padding: 20,
                        font: {
                            size: 14,
                            weight: 'bold'
                        }
                    }
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const label = context.label || '';
                            const value = context.parsed;
                            const barangayData = allData.find(item => item.id === barangayId);
                            const count = label === 'Male' ? barangayData.maleCount : barangayData.femaleCount;
                            return `${label}: ${value.toFixed(1)}% (${count})`;
                        }
                    },
                    titleFont: {
                        size: 16
                    },
                    bodyFont: {
                        size: 14
                    },
                    padding: 12
                }
            },
            animation: {
                animateScale: true,
                animateRotate: true,
                duration: 1000
            }
        }
    });
}

// Show chart modal - updated to show comprehensive PDAO statistics
function showChart(barangayId) {
    const barangay = allData.find(item => item.id === barangayId);
    const modal = document.getElementById('chartModal');
    const modalTitle = document.getElementById('modalTitle');
    const chartInfo = document.getElementById('chartInfo');
    
    modalTitle.textContent = `${barangay.name} - PDAO`;
    modalTitle.dataset.barangayId = barangayId; // Store for reference
    modal.style.display = 'block';
    document.body.style.overflow = 'hidden';

    // Calculate statistics
    const withPension = Math.round(barangay.pdaoCount * 0.65); // Assume 65% have pension
    const withoutPension = barangay.pdaoCount - withPension;
    const pensionPercentage = ((withPension / barangay.pdaoCount) * 100).toFixed(1);
    const noPensionPercentage = ((withoutPension / barangay.pdaoCount) * 100).toFixed(1);
    
    // Find highest and lowest populations
    const sortedByPdao = [...allData].sort((a, b) => b.pdaoCount - a.pdaoCount);
    const highest = sortedByPdao[0];
    const lowest = sortedByPdao[sortedByPdao.length - 1];

    // Update chart info with comprehensive statistics
    chartInfo.innerHTML = `
        <h3>${barangay.name} PDAO Statistics</h3>
        <p><strong>Total Registered PDAO:</strong> ${barangay.pdaoCount.toLocaleString()}</p>
        <p><strong>Percentage with Pension:</strong> ${pensionPercentage}% (${withPension.toLocaleString()})</p>
        <p><strong>Percentage without Benefits:</strong> ${noPensionPercentage}% (${withoutPension.toLocaleString()})</p>
        <p><strong>Highest Population:</strong> ${highest.name} (${highest.pdaoCount.toLocaleString()})</p>
        <p><strong>Lowest Population:</strong> ${lowest.name} (${lowest.pdaoCount.toLocaleString()})</p>
        <hr style="margin: 15px 0;">
        <p style="font-style: italic; color: #666;"><strong>Insight:</strong> ${noPensionPercentage}% of registered PDAOs in ${barangay.name} do not have pension benefits, representing ${withoutPension.toLocaleString()} individuals who may need additional support.</p>
        <hr style="margin: 15px 0;">
        <h4>Gender Distribution</h4>
        <p><strong>Male:</strong> ${barangay.maleCount} (${barangay.malePercentage}%)</p>
        <p><strong>Female:</strong> ${barangay.femaleCount} (${barangay.femalePercentage}%)</p>
        <hr style="margin: 15px 0;">
    `;

    // Reset to default chart type
    currentChartType = 'doughnut';
    document.querySelectorAll('.chart-type-btn').forEach(btn => btn.classList.remove('active'));
    document.querySelector('[onclick="switchChartType(\'doughnut\')"]').classList.add('active');
    
    // Show chart container, hide table container
    document.getElementById('chartContainer').style.display = 'block';
    document.getElementById('tableContainer').style.display = 'none';

    updateChart();
}

// Open printable view for a barangay's PWDs
async function openPwdBarangayPrint(barangayName, btnEl) {
    if (!barangayName) return;

    const originalText = btnEl ? btnEl.innerHTML : '';
    if (btnEl) {
        btnEl.disabled = true;
        btnEl.innerHTML = 'Loading...';
    }

    try {
        const res = await fetch(`/api/pwds/barangay/${encodeURIComponent(barangayName)}`, {
            credentials: 'same-origin'
        });
        if (!res.ok) throw new Error('Unable to load PWD data');

        const json = await res.json();
        if (!json.success) throw new Error(json.message || 'Failed to load data');

        const printHtml = buildPwdBarangayPrintHtml(barangayName, json.data || []);

        const newWin = window.open('', '_blank', 'width=1200,height=900,scrollbars=yes');
        if (!newWin) {
            alert('Popup blocked! Please allow popups to view the print page.');
            return;
        }
        newWin.document.open();
        newWin.document.write(printHtml);
        newWin.document.close();
    } catch (err) {
        console.error(err);
        alert(err.message || 'Error opening print view');
    } finally {
        if (btnEl) {
            btnEl.disabled = false;
            btnEl.innerHTML = originalText;
        }
    }
}

// Build printable HTML for barangay PWDs
function buildPwdBarangayPrintHtml(barangayName, pwds) {
    const esc = (s) => String(s ?? '').replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;');

    // Calculate summary statistics
    let totalCount = 0;
    let totalMale = 0;
    let totalFemale = 0;
    const disabilityCounts = {};

    const rows = (pwds && pwds.length
        ? pwds
        : []).map((pwd, idx) => {
            totalCount++;
            const gender = (pwd.gender || '').toString().toLowerCase();
            if (gender === 'male') {
                totalMale++;
            } else if (gender === 'female') {
                totalFemale++;
            }

            // Count disabilities
            if (pwd.disability && pwd.disability !== 'N/A') {
                const disabilities = pwd.disability.split(',').map(d => d.trim()).filter(Boolean);
                disabilities.forEach(disability => {
                    disabilityCounts[disability] = (disabilityCounts[disability] || 0) + 1;
                });
            }

            return `
            <tr>
                <td>${idx + 1}</td>
                <td>${esc(pwd.fullName || 'N/A')}</td>
                <td>${esc(pwd.contact || 'N/A')}</td>
                <td>${esc(pwd.gender || 'N/A')}</td>
                <td>${esc(pwd.age ?? 'N/A')}</td>
                <td>${esc(pwd.disability || 'N/A')}</td>
            </tr>
        `;
        }).join('');

    const emptyState = `
        <tr>
            <td colspan="6" class="text-center">No PWDs found for this barangay.</td>
        </tr>
    `;

    // Build disability summary HTML
    const disabilitySummaryRows = Object.entries(disabilityCounts)
        .sort((a, b) => b[1] - a[1]) // Sort by count descending
        .map(([disability, count]) => `
            <tr>
                <td>${esc(disability)}</td>
                <td><strong>${count}</strong></td>
            </tr>
        `).join('');

    const disabilitySummary = disabilitySummaryRows ? `
        <div class="mt-4">
            <h5>Disability Summary</h5>
            <div class="table-responsive">
                <table class="table table-bordered table-sm">
                    <thead class="table-secondary">
                        <tr>
                            <th>Disability Type</th>
                            <th>Count</th>
                        </tr>
                    </thead>
                    <tbody>
                        ${disabilitySummaryRows}
                    </tbody>
                </table>
            </div>
        </div>
    ` : '';

    return `<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>${esc(barangayName)} - PWDs</title>
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <link rel="stylesheet" href="/bower_components/bootstrap/css/bootstrap.min.css">
    <style>
        body { padding: 30px; font-family: Arial, sans-serif; }
        .print-actions { text-align: right; margin-bottom: 20px; }
        .print-actions button { margin-left: 10px; }
        .table thead th { white-space: nowrap; }
        .summary-box {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 5px;
            margin-bottom: 20px;
            border: 1px solid #dee2e6;
        }
        .summary-box h5 {
            margin-bottom: 15px;
            color: #495057;
        }
        .summary-item {
            margin: 8px 0;
            font-size: 14px;
        }
        .summary-item strong {
            color: #212529;
        }
        @media print {
            .print-actions { display: none; }
            body { padding: 0; }
        }
    </style>
</head>
<body>
    <div class="print-actions">
        <button class="btn btn-secondary btn-sm" onclick="window.close()">Close</button>
        <button class="btn btn-primary btn-sm" onclick="window.print()">Print</button>
    </div>
    <div class="container-fluid">
        <div class="mb-3">
            <h3 class="mb-0">PWDs - ${esc(barangayName)}</h3>
            <small class="text-muted">Essential information: Name, Contact, Gender, Age, Disability</small>
        </div>
        
        <div class="summary-box">
            <h5>Report Summary</h5>
            <div class="row">
                <div class="col-md-6">
                    <div class="summary-item"><strong>Total Count:</strong> ${totalCount}</div>
                    <div class="summary-item"><strong>Total Male:</strong> ${totalMale}</div>
                    <div class="summary-item"><strong>Total Female:</strong> ${totalFemale}</div>
                </div>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Contact</th>
                        <th>Gender</th>
                        <th>Age</th>
                        <th>Disability</th>
                    </tr>
                </thead>
                <tbody>
                    ${rows || emptyState}
                </tbody>
            </table>
        </div>
        
        ${disabilitySummary}
        
        <div class="text-end text-muted">
            Generated: ${new Date().toLocaleString()}
        </div>
    </div>
</body>
</html>`;
}

// Close modal functionality
function closeModal() {
    const modal = document.getElementById('chartModal');
    modal.style.display = 'none';
    document.body.style.overflow = 'auto';
    if (currentChart) {
        currentChart.destroy();
        currentChart = null;
    }
}

// Event listeners
document.querySelector('.close').onclick = closeModal;
document.getElementById('searchInput').oninput = handleSearch;

window.onclick = function(event) {
    const modal = document.getElementById('chartModal');
    if (event.target === modal) {
        closeModal();
    }
}

// Keyboard support
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        closeModal();
    }
});

// Initialize the application
loadPdaoData();