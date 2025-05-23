/**
 * Dashboard Management System
 * Refactored for better maintainability and performance
 */

class DashboardManager {
    constructor() {
        this.charts = {};
        this.chartOptions = {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'top',
                    labels: {
                        font: { family: "'Inter', sans-serif", size: 11 }
                    }
                }
            }
        };
        this.colors = {
            blue: { bg: 'rgba(56, 189, 248, 0.2)', border: 'rgb(56, 189, 248)' },
            red: { bg: 'rgba(239, 68, 68, 0.2)', border: 'rgb(239, 68, 68)' },
            green: { bg: 'rgba(5, 150, 105, 0.2)', border: 'rgb(5, 150, 105)' },
            purple: { bg: 'rgba(124, 58, 237, 0.2)', border: 'rgb(124, 58, 237)' },
            orange: { bg: 'rgba(245, 158, 11, 0.2)', border: 'rgb(245, 158, 11)' },
            darkPurple: { bg: 'rgba(76, 29, 149, 0.2)', border: 'rgb(76, 29, 149)' }
        };

        this.init();
    }

    async init() {
        this.initializeCharts();
        this.setupEventListeners();
        await this.loadInitialData();

        // Add debug button if needed
        if (window.DEBUG_MODE) {
            this.addDebugButton();
        }
    }

    // Data fetching
    async fetchData(params = {}) {
        try {
            const queryParams = new URLSearchParams();
            Object.entries(params).forEach(([key, value]) => {
                if (value) queryParams.append(key, value);
            });
            queryParams.append('_t', Date.now());

            const url = `backend/data.php?${queryParams.toString()}`;
            console.log('Fetching from URL:', url);

            const response = await fetch(url, {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'Cache-Control': 'no-cache'
                }
            });

            if (!response.ok) {
                const text = await response.text();
                throw new Error(`Server error ${response.status}: ${text}`);
            }

            const result = await response.json();

            if (result.status === 'error') {
                throw new Error(result.message);
            }

            return result.data || result;
        } catch (error) {
            console.error('Fetch error:', error);
            this.showMessage('Erreur de chargement des données: ' + error.message, 'error');
            throw error;
        }
    }

    // Data processing
    processDataForCharts(rawData) {
        const campaignData = {};
        const etageData = {};

        rawData.forEach(row => {
            const campaignId = row.campaign_id;
            const etageNumber = row.etage_number;

            // Initialize campaign data
            if (!campaignData[campaignId]) {
                campaignData[campaignId] = {
                    variety: row.variety_name,
                    startTime: new Date(row.start_time),
                    endTime: row.end_time ? new Date(row.end_time) : null,
                    etages: {}
                };
            }

            // Initialize etage data
            if (etageNumber && !campaignData[campaignId].etages[etageNumber]) {
                campaignData[campaignId].etages[etageNumber] = {
                    startTime: row.etage_start ? new Date(row.etage_start) : null,
                    endTime: row.etage_end ? new Date(row.etage_end) : null,
                    burnerStatus: []
                };
            }

            // Add burner status data
            if (row.burner_status_numeric !== null && etageNumber) {
                campaignData[campaignId].etages[etageNumber].burnerStatus.push({
                    status: row.burner_status_numeric,
                    changedAt: new Date(row.changed_at)
                });
            }
        });

        // Process etage summary data
        Object.values(campaignData).forEach(campaign => {
            Object.entries(campaign.etages).forEach(([etageNumber, etage]) => {
                const etageKey = `${campaign.variety}-${etageNumber}`;

                if (!etageData[etageKey]) {
                    etageData[etageKey] = {
                        variety: campaign.variety,
                        etageNumber: parseInt(etageNumber),
                        dryingTime: 0,
                        burnerTime: 0,
                        count: 0
                    };
                }

                // Calculate times
                if (etage.endTime && etage.startTime) {
                    const dryingTime = (etage.endTime - etage.startTime) / (1000 * 60 * 60);
                    etageData[etageKey].dryingTime += dryingTime;
                    etageData[etageKey].count += 1;

                    // Calculate burner active time
                    const burnerTime = this.calculateBurnerActiveTime(etage.burnerStatus);
                    etageData[etageKey].burnerTime += burnerTime;
                }
            });
        });

        return { campaignData, etageData };
    }

    calculateBurnerActiveTime(burnerStatusArray) {
        if (!burnerStatusArray || burnerStatusArray.length === 0) return 0;

        let totalActiveTime = 0;
        let lastStatusChange = null;
        let lastStatus = 0;

        const sortedStatus = [...burnerStatusArray].sort(
            (a, b) => new Date(a.changedAt) - new Date(b.changedAt)
        );

        sortedStatus.forEach(status => {
            if (lastStatusChange && lastStatus === 1) {
                totalActiveTime += (new Date(status.changedAt) - lastStatusChange) / (1000 * 60 * 60);
            }
            lastStatusChange = new Date(status.changedAt);
            lastStatus = status.status;
        });

        return totalActiveTime;
    }

    // Chart initialization
    initializeCharts() {
        this.charts.chart1 = this.createChart1();
        this.charts.chart2 = this.createChart2();
        this.charts.chart3 = this.createChart3();
        this.charts.chart4 = this.createChart4();
    }

    createChart1() {
        const ctx = document.getElementById('chart1').getContext('2d');
        return new Chart(ctx, {
            type: 'bar',
            data: {
                labels: [],
                datasets: [
                    {
                        label: 'Durée séchage',
                        data: [],
                        backgroundColor: this.colors.blue.bg,
                        borderColor: this.colors.blue.border,
                        borderWidth: 1
                    },
                    {
                        label: 'Durée bruleur',
                        data: [],
                        backgroundColor: this.colors.red.bg,
                        borderColor: this.colors.red.border,
                        borderWidth: 1
                    }
                ]
            },
            options: {
                ...this.chartOptions,
                scales: {
                    y: {
                        title: { display: true, text: 'Durée (heures)' },
                        max: 14
                    }
                }
            }
        });
    }

    createChart2() {
        const ctx = document.getElementById('chart2').getContext('2d');
        return new Chart(ctx, {
            type: 'line',
            data: {
                labels: [],
                datasets: [
                    {
                        label: 'Etat bruleur',
                        data: [],
                        backgroundColor: this.colors.darkPurple.bg,
                        borderColor: this.colors.darkPurple.border,
                        borderWidth: 1,
                        yAxisID: 'y1'
                    },
                    {
                        label: 'étage panier chargement N°1',
                        data: [],
                        backgroundColor: this.colors.red.bg,
                        borderColor: this.colors.red.border,
                        borderWidth: 1,
                        yAxisID: 'y'
                    }
                ]
            },
            options: {
                ...this.chartOptions,
                scales: {
                    y: {
                        title: { display: true, text: 'Étage' },
                        min: 0, max: 5
                    },
                    y1: {
                        type: 'linear',
                        display: true,
                        position: 'right',
                        title: { display: true, text: 'État brûleur' },
                        min: 0, max: 1,
                        grid: { drawOnChartArea: false }
                    }
                }
            }
        });
    }

    createChart3() {
        const ctx = document.getElementById('chart3').getContext('2d');
        const colorArray = [this.colors.red, this.colors.blue, this.colors.green, this.colors.purple];

        return new Chart(ctx, {
            type: 'line',
            data: {
                labels: [],
                datasets: [
                    {
                        label: 'Etat bruleur',
                        data: [],
                        backgroundColor: this.colors.darkPurple.bg,
                        borderColor: this.colors.darkPurple.border,
                        borderWidth: 1,
                        yAxisID: 'y1'
                    },
                    ...Array.from({length: 4}, (_, i) => ({
                        label: `étage panier chargement N°${i + 1}`,
                        data: [],
                        backgroundColor: colorArray[i].bg,
                        borderColor: colorArray[i].border,
                        borderWidth: 1
                    }))
                ]
            },
            options: {
                ...this.chartOptions,
                scales: {
                    y: {
                        title: { display: true, text: 'Étage' },
                        min: 0, max: 5
                    },
                    y1: {
                        type: 'linear',
                        display: true,
                        position: 'right',
                        title: { display: true, text: 'État brûleur' },
                        min: 0, max: 1,
                        grid: { drawOnChartArea: false }
                    }
                }
            }
        });
    }

    createChart4() {
        const ctx = document.getElementById('chart4').getContext('2d');
        return new Chart(ctx, {
            type: 'bar',
            data: { labels: [], datasets: [] },
            options: {
                ...this.chartOptions,
                plugins: {
                    ...this.chartOptions.plugins,
                    tooltip: {
                        callbacks: {
                            label: (context) => {
                                const value = context.parsed.y;
                                return `${context.dataset.label}: ${this.formatHoursMinutes(value)}`;
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        title: { display: true, text: 'Durée (heures)' },
                        ticks: {
                            callback: (value) => this.formatHoursMinutes(value)
                        }
                    }
                }
            }
        });
    }

    // Chart update methods
    updateChart1(campaignData) {
        const processedData = this.processChart1Data(campaignData);
        this.charts.chart1.data.labels = processedData.labels;
        this.charts.chart1.data.datasets[0].data = processedData.dryingDuration;
        this.charts.chart1.data.datasets[1].data = processedData.burnerDuration;
        this.charts.chart1.update();
    }

    processChart1Data(campaignData) {
        const varietyData = {};
        const labels = [];

        Object.values(campaignData).forEach(campaign => {
            if (campaign.etages[1]) {
                const date = campaign.startTime.toLocaleDateString('fr-FR');

                if (!varietyData[date]) {
                    varietyData[date] = { dryingTime: 0, burnerTime: 0, count: 0 };
                    labels.push(date);
                }

                const dryingTimeHours = campaign.etages[1].endTime && campaign.etages[1].startTime ?
                    (campaign.etages[1].endTime - campaign.etages[1].startTime) / (1000 * 60 * 60) : 0;

                const burnerActiveTime = this.calculateBurnerActiveTime(campaign.etages[1].burnerStatus);

                varietyData[date].dryingTime += dryingTimeHours;
                varietyData[date].burnerTime += burnerActiveTime;
                varietyData[date].count++;
            }
        });

        return {
            labels,
            dryingDuration: labels.map(date => {
                const data = varietyData[date];
                return parseFloat((data.dryingTime / data.count).toFixed(1));
            }),
            burnerDuration: labels.map(date => {
                const data = varietyData[date];
                return parseFloat((data.burnerTime / data.count).toFixed(1));
            })
        };
    }

    updateChart2(campaignData) {
        const dateData = {};

        Object.values(campaignData).forEach(campaign => {
            const date = campaign.startTime.toLocaleDateString('fr-FR');

            if (!dateData[date]) {
                dateData[date] = { etageCount: 0 };
            }

            const etageCount = Object.keys(campaign.etages).length;
            dateData[date].etageCount = Math.max(dateData[date].etageCount, etageCount);
        });

        const labels = Object.keys(dateData);
        const etageCount = labels.map(date => dateData[date].etageCount);

        this.charts.chart2.data.labels = labels;
        this.charts.chart2.data.datasets[0].data = etageCount;
        this.charts.chart2.update();
    }

    updateChart3(campaignData) {
        const campaigns = Object.values(campaignData).sort((a, b) => a.startTime - b.startTime);

        if (campaigns.length === 0) return;

        const recentCampaign = campaigns[campaigns.length - 1];
        const timeSeriesData = this.generateTimeSeriesData(recentCampaign);

        this.charts.chart3.data.labels = timeSeriesData.times;
        this.charts.chart3.data.datasets = timeSeriesData.datasets;
        this.charts.chart3.update();
    }

    generateTimeSeriesData(campaign) {
        const times = [];
        const burnerData = [];
        const etageDatasets = [];

        // Collect all burner status changes
        const allBurnerStatus = [];
        Object.values(campaign.etages).forEach(etage => {
            etage.burnerStatus.forEach(status => {
                allBurnerStatus.push(status);
            });
        });

        allBurnerStatus.sort((a, b) => a.changedAt - b.changedAt);

        const startTime = campaign.startTime;
        let endTime = campaign.endTime || new Date();

        if (endTime - startTime < 60 * 60 * 1000) {
            endTime = new Date(startTime.getTime() + 60 * 60 * 1000);
        }

        // Generate time points (every 30 minutes)
        for (let time = new Date(startTime); time <= endTime; time = new Date(time.getTime() + 30 * 60 * 1000)) {
            times.push(time.toLocaleTimeString('fr-FR', { hour: '2-digit', minute: '2-digit' }));

            // Find current burner status
            const lastBurnerStatus = allBurnerStatus
                .filter(s => s.changedAt <= time)
                .sort((a, b) => b.changedAt - a.changedAt)[0];

            burnerData.push(lastBurnerStatus ? lastBurnerStatus.status : 0);
        }

        // Create datasets
        etageDatasets.push({
            label: 'Etat bruleur',
            data: burnerData,
            backgroundColor: this.colors.darkPurple.bg,
            borderColor: this.colors.darkPurple.border,
            borderWidth: 1,
            yAxisID: 'y1'
        });

        const colorArray = [this.colors.red, this.colors.blue, this.colors.green, this.colors.purple];
        Object.entries(campaign.etages).forEach(([etageNum, etage], index) => {
            const etageStatusData = times.map((_, timeIndex) => {
                const currentTime = new Date(startTime.getTime() + timeIndex * 30 * 60 * 1000);

                if (etage.startTime <= currentTime && (!etage.endTime || etage.endTime >= currentTime)) {
                    return parseInt(etageNum, 10);
                }
                return 0;
            });

            etageDatasets.push({
                label: `étage panier chargement N°${etageNum}`,
                data: etageStatusData,
                backgroundColor: colorArray[index % colorArray.length].bg,
                borderColor: colorArray[index % colorArray.length].border,
                borderWidth: 1
            });
        });

        return { times, datasets: etageDatasets };
    }

    updateChart4(etageData) {
        const campaignData = Object.values(etageData).filter(data => data.count > 0);

        if (campaignData.length === 0) return;

        const panierData = {};
        campaignData.forEach(data => {
            const etageKey = parseInt(data.etageNumber);
            if (!panierData[etageKey]) {
                panierData[etageKey] = { dryingTime: 0, burnerTime: 0 };
            }
            panierData[etageKey].dryingTime = data.dryingTime / data.count;
            panierData[etageKey].burnerTime = data.burnerTime / data.count;
        });

        const datasets = [];
        const colorArray = [this.colors.blue, this.colors.red, this.colors.green, this.colors.purple];

        for (let i = 1; i <= 4; i++) {
            if (panierData[i]) {
                datasets.push({
                    label: `durée séchage panier${i}`,
                    data: [parseFloat(panierData[i].dryingTime.toFixed(2))],
                    backgroundColor: colorArray[(i-1)*2 % colorArray.length].bg,
                    borderColor: colorArray[(i-1)*2 % colorArray.length].border,
                    borderWidth: 1
                });

                datasets.push({
                    label: `durée brûleur panier${i}`,
                    data: [parseFloat(panierData[i].burnerTime.toFixed(2))],
                    backgroundColor: colorArray[(i-1)*2 + 1 % colorArray.length].bg,
                    borderColor: colorArray[(i-1)*2 + 1 % colorArray.length].border,
                    borderWidth: 1
                });
            }
        }

        this.charts.chart4.data.labels = ["Campagne"];
        this.charts.chart4.data.datasets = datasets;
        this.charts.chart4.update();
    }

    // Update all charts
    async updateAllCharts(data) {
        try {
            const { campaignData, etageData } = this.processDataForCharts(data);

            this.updateChart1(campaignData);
            this.updateChart2(campaignData);
            this.updateChart3(campaignData);
            this.updateChart4(etageData);

            this.showMessage('Graphiques mis à jour avec succès', 'success');
        } catch (error) {
            console.error('Chart update error:', error);
            this.showMessage('Erreur lors de la mise à jour des graphiques', 'error');
        }
    }

    // Event handling
    setupEventListeners() {
        const filterForm = document.getElementById('filter-form');
        if (filterForm) {
            filterForm.addEventListener('submit', async (e) => {
                e.preventDefault();

                const formData = new FormData(filterForm);
                const params = {
                    variety: formData.get('variety'),
                    startDate: formData.get('startDate'),
                    endDate: formData.get('endDate'),
                    etage: formData.get('etage')
                };

                try {
                    const data = await this.fetchData(params);
                    await this.updateAllCharts(data);
                } catch (error) {
                    console.error('Filter error:', error);
                }
            });
        }
    }

    async loadInitialData() {
        try {
            const data = await this.fetchData();
            await this.updateAllCharts(data);
        } catch (error) {
            console.error('Initial load error:', error);
        }
    }

    // Utility methods
    formatHoursMinutes(hours) {
        const h = Math.floor(hours);
        const m = Math.round((hours - h) * 60);
        return `${h.toString().padStart(2, '0')}:${m.toString().padStart(2, '0')}`;
    }

    showMessage(message, type = 'info') {
        const container = document.getElementById('message-container');
        if (container) {
            container.textContent = message;
            container.className = type;
            container.style.display = 'block';

            setTimeout(() => {
                container.style.display = 'none';
            }, 5000);
        }
    }

    addDebugButton() {
        const button = document.createElement('button');
        button.innerText = 'Debug';
        button.style.cssText = `
            position: fixed; bottom: 10px; right: 10px; z-index: 9999;
            padding: 8px 16px; background-color: #ff5722; color: white;
            border: none; border-radius: 4px; cursor: pointer;
        `;
        button.onclick = () => {
            console.log('Dashboard state:', {
                charts: this.charts,
                data: this.lastData
            });
        };
        document.body.appendChild(button);
    }
}

// Initialize dashboard when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    window.dashboardManager = new DashboardManager();
});

// Set debug mode if needed
window.DEBUG_MODE = true;
