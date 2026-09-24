import {
    Chart,
    BarController,
    LineController,
    DoughnutController,
    BarElement,
    LineElement,
    PointElement,
    ArcElement,
    CategoryScale,
    LinearScale,
    Tooltip,
    Legend,
    Filler,
} from 'chart.js';

Chart.register(
    BarController,
    LineController,
    DoughnutController,
    BarElement,
    LineElement,
    PointElement,
    ArcElement,
    CategoryScale,
    LinearScale,
    Tooltip,
    Legend,
    Filler
);

const instances = new Map();
let lastDark = null;

function isDark() {
    return document.documentElement.classList.contains('dark');
}

function fmtMoney(value) {
    return new Intl.NumberFormat('fr-FR', { maximumFractionDigits: 0 }).format(value) + ' FCFA';
}

function themeColors() {
    const dark = isDark();
    return {
        dark,
        grid: dark ? 'rgba(255, 255, 255, 0.08)' : 'rgba(107, 114, 128, 0.15)',
        tick: dark ? 'rgba(226, 232, 240, 0.7)' : 'rgba(107, 114, 128, 0.8)',
        tooltipBg: dark ? 'rgba(30, 41, 59, 0.95)' : 'rgba(17, 24, 39, 0.9)',
        tooltipText: '#f1f5f9',
        zero: dark ? 'rgba(148, 163, 184, 0.25)' : 'rgba(107, 114, 128, 0.35)',
    };
}

function parseJson(value, fallback = []) {
    if (!value) {
        return fallback;
    }
    try {
        return JSON.parse(value);
    } catch (e) {
        return fallback;
    }
}

function destroyChart(canvas) {
    const instance = instances.get(canvas);
    if (instance) {
        instance.destroy();
        instances.delete(canvas);
    }
}

function resetChartInstances() {
    instances.forEach((chart) => chart.destroy());
    instances.clear();
}

// Récupère une instance existante compatible (même type) ou en crée une.
function getChart(canvas, type) {
    const existing = instances.get(canvas);
    if (existing && existing.config.type === type) {
        return existing;
    }
    if (existing) {
        destroyChart(canvas);
    }
    return null;
}

function buildRevenueChart(canvas) {
    const labels = parseJson(canvas.dataset.labels);
    const values = parseJson(canvas.dataset.values);
    const { dark, grid, tick, tooltipBg, tooltipText, zero } = themeColors();

    const max = Math.max(...values, 0);
    const barColor = dark ? '#34d399' : '#10b981';
    const manyLabels = labels.length > 20;

    const existing = getChart(canvas, 'bar');
    if (existing) {
        existing.data.labels = labels;
        existing.data.datasets[0].data = values;
        existing.update('none');
        return;
    }

    const chart = new Chart(canvas, {
        type: 'bar',
        data: {
            labels,
            datasets: [{
                label: 'Chiffre d\'affaires',
                data: values,
                backgroundColor: (context) => {
                    const value = context.parsed.y;
                    return value > 0 ? barColor : zero;
                },
                hoverBackgroundColor: barColor,
                borderRadius: 6,
                borderSkipped: false,
                maxBarThickness: 48,
            }],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            animation: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: tooltipBg,
                    titleColor: tooltipText,
                    bodyColor: tooltipText,
                    padding: 12,
                    cornerRadius: 8,
                    displayColors: false,
                    callbacks: {
                        label: (context) => `${context.parsed.y} FCFA`,
                    },
                },
            },
            scales: {
                x: {
                    grid: { display: false },
                    ticks: {
                        color: tick,
                        font: { size: 10 },
                        maxRotation: manyLabels ? 45 : 0,
                        autoSkip: manyLabels,
                        maxTicksLimit: manyLabels ? 14 : undefined,
                    },
                },
                y: {
                    beginAtZero: true,
                    grid: { color: grid },
                    border: { display: false },
                    ticks: {
                        color: tick,
                        font: { size: 11 },
                        maxTicksLimit: 6,
                        callback: (value) => (max >= 1_000_000
                            ? (value / 1_000_000).toLocaleString('fr-FR') + ' M'
                            : value >= 1_000
                                ? (value / 1_000).toLocaleString('fr-FR') + ' k'
                                : value),
                    },
                },
            },
        },
    });

    instances.set(canvas, chart);
}

function buildDonutChart(canvas) {
    const labels = parseJson(canvas.dataset.labels);
    const values = parseJson(canvas.dataset.values);
    const background = parseJson(canvas.dataset.colors);
    const { tooltipBg, tooltipText } = themeColors();

    const existing = getChart(canvas, 'doughnut');
    if (existing) {
        existing.data.labels = labels;
        existing.data.datasets[0].data = values;
        existing.data.datasets[0].backgroundColor = background;
        existing.update('none');
        return;
    }

    const chart = new Chart(canvas, {
        type: 'doughnut',
        data: {
            labels,
            datasets: [{
                data: values,
                backgroundColor: background,
                borderWidth: 0,
                hoverOffset: 4,
            }],
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            cutout: '68%',
            animation: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: tooltipBg,
                    titleColor: tooltipText,
                    bodyColor: tooltipText,
                    padding: 10,
                    cornerRadius: 8,
                    callbacks: {
                        label: (context) => {
                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                            const percent = total > 0 ? Math.round((context.parsed / total) * 100) : 0;
                            return `${context.parsed} (${percent}%)`;
                        },
                    },
                },
            },
        },
    });

    instances.set(canvas, chart);
}

function buildSparklineChart(canvas) {
    const labels = parseJson(canvas.dataset.labels);
    const values = parseJson(canvas.dataset.values);
    const color = canvas.dataset.color || '#0ea5e9';
    const { tooltipBg, tooltipText } = themeColors();

    const existing = getChart(canvas, 'line');
    if (existing) {
        existing.data.labels = labels;
        existing.data.datasets[0].data = values;
        existing.data.datasets[0].borderColor = color;
        existing.data.datasets[0].pointHoverBackgroundColor = color;
        existing.update('none');
        return;
    }

    const chart = new Chart(canvas, {
        type: 'line',
        data: {
            labels,
            datasets: [{
                data: values,
                borderColor: color,
                backgroundColor: color + '1f',
                fill: true,
                tension: 0.4,
                borderWidth: 2,
                pointRadius: 0,
                pointHoverRadius: 3,
                pointHoverBackgroundColor: color,
            }],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            animation: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    enabled: true,
                    backgroundColor: tooltipBg,
                    titleColor: tooltipText,
                    bodyColor: tooltipText,
                    displayColors: false,
                    callbacks: {
                        title: (items) => items[0].label ?? '',
                        label: (context) => `${context.parsed.y}`,
                    },
                },
            },
            scales: {
                x: { display: false },
                y: { display: false },
            },
        },
    });

    instances.set(canvas, chart);
}

function initDashboardCharts(force = false) {
    lastDark = isDark();

    if (force) {
        resetChartInstances();
    }

    document.querySelectorAll('canvas[data-chart]').forEach((canvas) => {
        switch (canvas.dataset.chart) {
            case 'revenue':
                buildRevenueChart(canvas);
                break;
            case 'donut':
                buildDonutChart(canvas);
                break;
            case 'sparkline':
                buildSparklineChart(canvas);
                break;
            default:
                break;
        }
    });
}

// Initialisation au chargement
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => initDashboardCharts());
} else {
    initDashboardCharts();
}

// Réinitialisation après chaque re-rendu Livewire (poll, filtres, morph)
document.addEventListener('livewire:init', () => {
    window.Livewire.hook('morph.updated', () => initDashboardCharts());
    window.Livewire.hook('navigated', () => initDashboardCharts());
});

// Synchronisation au changement de thème (class dark sur <html>)
const themeObserver = new MutationObserver(() => {
    const dark = isDark();
    if (dark !== lastDark) {
        initDashboardCharts(true);
    }
});
themeObserver.observe(document.documentElement, { attributes: true, attributeFilter: ['class'] });