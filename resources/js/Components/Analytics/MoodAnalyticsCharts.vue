<template>
    <div class="grid md:grid-cols-2 gap-8 mb-8">
        <!-- Mood Trend Line Graph -->
        <div class="bg-white p-6 rounded-xl shadow-lg">
            <h3 class="text-lg font-semibold mb-4 text-gray-800">Mood Trends</h3>
            <canvas ref="moodTrendChart"></canvas>
        </div>

        <!-- Sleep Quality Correlation -->
        <div class="bg-white p-6 rounded-xl shadow-lg">
            <h3 class="text-lg font-semibold mb-4 text-gray-800">Sleep Quality Impact</h3>
            <canvas ref="sleepCorrelationChart"></canvas>
        </div>

        <!-- Emotional State Distribution -->
        <div class="bg-white p-6 rounded-xl shadow-lg">
            <h3 class="text-lg font-semibold mb-4 text-gray-800">Emotional States</h3>
            <canvas ref="emotionDistributionChart"></canvas>
        </div>

        <!-- Activity Impact Analysis -->
        <div class="bg-white p-6 rounded-xl shadow-lg">
            <h3 class="text-lg font-semibold mb-4 text-gray-800">Activity Impact</h3>
            <canvas ref="activityImpactChart"></canvas>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted, watch } from 'vue';
import Chart from 'chart.js/auto';

const props = defineProps({
    entries: {
        type: Array,
        required: true
    }
});

const moodTrendChart = ref(null);
const sleepCorrelationChart = ref(null);
const emotionDistributionChart = ref(null);
const activityImpactChart = ref(null);

let charts = {
    moodTrend: null,
    sleepCorrelation: null,
    emotionDistribution: null,
    activityImpact: null
};

// Watch for changes in entries
watch(() => props.entries, (newEntries) => {
    updateCharts(newEntries);
}, { deep: true });

onMounted(() => {
    initializeCharts();
    updateCharts(props.entries);
});

const initializeCharts = () => {
    // Initialize Mood Trend Chart
    charts.moodTrend = new Chart(moodTrendChart.value, {
        type: 'line',
        data: {
            labels: [],
            datasets: [{
                label: 'Mood Intensity',
                data: [],
                borderColor: '#6366f1',
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    max: 10
                }
            }
        }
    });

    // Initialize Sleep Correlation Chart
    charts.sleepCorrelation = new Chart(sleepCorrelationChart.value, {
        type: 'scatter',
        data: {
            datasets: [{
                label: 'Sleep vs Mood',
                data: [],
                backgroundColor: '#6366f1'
            }]
        },
        options: {
            responsive: true,
            scales: {
                x: {
                    title: {
                        display: true,
                        text: 'Sleep Quality'
                    }
                },
                y: {
                    title: {
                        display: true,
                        text: 'Mood Intensity'
                    }
                }
            }
        }
    });

    // Initialize Emotion Distribution Chart
    charts.emotionDistribution = new Chart(emotionDistributionChart.value, {
        type: 'doughnut',
        data: {
            labels: [],
            datasets: [{
                data: [],
                backgroundColor: [
                    '#6366f1',
                    '#8b5cf6',
                    '#d946ef',
                    '#ec4899',
                    '#f43f5e',
                    '#f97316',
                    '#eab308'
                ]
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'right'
                }
            }
        }
    });

    // Initialize Activity Impact Chart
    charts.activityImpact = new Chart(activityImpactChart.value, {
        type: 'bar',
        data: {
            labels: [],
            datasets: [{
                label: 'Average Mood',
                data: [],
                backgroundColor: '#6366f1'
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    max: 10
                }
            }
        }
    });
};

const updateCharts = (entries) => {
    if (!entries.length) return;

    // Update Mood Trend Chart
    const moodTrendData = entries.map(entry => ({
        x: new Date(entry.created_at),
        y: entry.mood_intensity
    })).sort((a, b) => a.x - b.x);

    charts.moodTrend.data.labels = moodTrendData.map(d => 
        d.x.toLocaleDateString('en-US', { month: 'short', day: 'numeric' })
    );
    charts.moodTrend.data.datasets[0].data = moodTrendData.map(d => d.y);
    charts.moodTrend.update();

    // Update Sleep Correlation Chart
    charts.sleepCorrelation.data.datasets[0].data = entries.map(entry => ({
        x: entry.sleep_quality,
        y: entry.mood_intensity
    }));
    charts.sleepCorrelation.update();

    // Update Emotion Distribution Chart
    const emotionCounts = entries.reduce((acc, entry) => {
        acc[entry.emotional_state] = (acc[entry.emotional_state] || 0) + 1;
        return acc;
    }, {});

    charts.emotionDistribution.data.labels = Object.keys(emotionCounts);
    charts.emotionDistribution.data.datasets[0].data = Object.values(emotionCounts);
    charts.emotionDistribution.update();

    // Update Activity Impact Chart
    const activityImpact = entries.reduce((acc, entry) => {
        const activities = entry.activities_description.split(',').map(a => a.trim());
        activities.forEach(activity => {
            if (!acc[activity]) {
                acc[activity] = { total: 0, count: 0 };
            }
            acc[activity].total += entry.mood_intensity;
            acc[activity].count++;
        });
        return acc;
    }, {});

    const averageImpacts = Object.entries(activityImpact)
        .map(([activity, data]) => ({
            activity,
            average: data.total / data.count
        }))
        .sort((a, b) => b.average - a.average)
        .slice(0, 5);

    charts.activityImpact.data.labels = averageImpacts.map(d => d.activity);
    charts.activityImpact.data.datasets[0].data = averageImpacts.map(d => d.average);
    charts.activityImpact.update();
};
</script> 