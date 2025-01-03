<template>
    <div class="bg-white p-4 rounded-lg shadow">
        <h3 class="text-lg font-semibold mb-4">Mood Trends</h3>
        <Line v-if="chartData" :data="chartData" :options="chartOptions" />
    </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import { Line } from 'vue-chartjs';
import { Chart as ChartJS, CategoryScale, LinearScale, PointElement, LineElement, Title, Tooltip, Legend } from 'chart.js';

ChartJS.register(CategoryScale, LinearScale, PointElement, LineElement, Title, Tooltip, Legend);

const props = defineProps({
    entries: {
        type: Array,
        required: true
    }
});

const chartData = computed(() => ({
    labels: props.entries.map(entry => new Date(entry.created_at).toLocaleDateString()),
    datasets: [{
        label: 'Mood Intensity',
        data: props.entries.map(entry => entry.mood_intensity),
        borderColor: '#6366f1',
        tension: 0.4
    }]
}));

const chartOptions = {
    responsive: true,
    maintainAspectRatio: false,
    scales: {
        y: {
            beginAtZero: true,
            max: 10
        }
    }
};
</script> 