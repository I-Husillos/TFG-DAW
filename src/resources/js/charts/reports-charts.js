// Los datos viajan desde PHP a JS mediante data-attributes
// del elemento canvas. Así no hay ningún PHP mezclado
// en bloques de JavaScript dentro de las vistas Blade.
export function initReportsCharts() {

    const ctxDaily = document.getElementById('chartDaily');
    if (ctxDaily) {
        const labels  = JSON.parse(ctxDaily.dataset.labels);
        const income  = JSON.parse(ctxDaily.dataset.income);
        const expense = JSON.parse(ctxDaily.dataset.expense);

        new window.Chart(ctxDaily, {
            type: 'line',
            data: {
                labels,
                datasets: [
                    {
                        label: 'Ingresos',
                        data: income,
                        borderColor: '#28a745',
                        backgroundColor: 'rgba(40,167,69,0.1)',
                        fill: true,
                        tension: 0.3,
                    },
                    {
                        label: 'Gastos',
                        data: expense,
                        borderColor: '#dc3545',
                        backgroundColor: 'rgba(220,53,69,0.1)',
                        fill: true,
                        tension: 0.3,
                    },
                ],
            },
            options: {
                responsive: true,
                plugins: { legend: { position: 'top' } },
                scales: { y: { beginAtZero: true } },
            },
        });
    }

    const ctxCat = document.getElementById('chartCategories');
    if (ctxCat) {
        const labels  = JSON.parse(ctxCat.dataset.labels);
        const amounts = JSON.parse(ctxCat.dataset.amounts);

        if (labels.length > 0) {
            new window.Chart(ctxCat, {
                type: 'doughnut',
                data: {
                    labels,
                    datasets: [{
                        data: amounts,
                        backgroundColor: [
                            '#007bff', '#28a745', '#ffc107', '#dc3545',
                            '#17a2b8', '#6f42c1', '#fd7e14', '#20c997',
                            '#6c757d', '#e83e8c',
                        ],
                    }],
                },
                options: {
                    responsive: true,
                    plugins: { legend: { position: 'bottom' } },
                },
            });
        }
    }
}