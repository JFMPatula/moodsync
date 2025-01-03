<template>
    <Head title="Mood Analytics" />

    <AuthenticatedLayout>
        <div class="min-h-screen bg-gradient-to-br from-violet-50 to-blue-50 py-8">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Header -->
                <div class="bg-white shadow-xl rounded-xl overflow-hidden border-t-4 border-indigo-500 mb-8">
                    <div class="p-8">
                        <h2 class="text-3xl font-bold text-gray-800 text-center">Mood History & Insights</h2>
                        <p class="text-center text-gray-600 mt-2">Review and analyze your mood tracking journey</p>
                    </div>
                </div>

                <!-- Add this right after the header section and before the Analytics Button -->
                <div class="flex justify-between items-center mb-6">
                    <!-- New Entry Button -->
                    <Link
                        href="/dashboard"
                        class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg transition-colors duration-150"
                    >
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="h-5 w-5 mr-2"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M12 6v6m0 0v6m0-6h6m-6 0H6"
                            />
                        </svg>
                        New Mood Entry
                    </Link>

                    <!-- Todo List Button -->
                    <button
                        @click="showTodoList = !showTodoList"
                        class="inline-flex items-center px-4 py-2 mx-2 bg-yellow-600 hover:bg-yellow-700 text-white rounded-lg transition-colors duration-150"
                    >
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="h-5 w-5 mr-2"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"
                            />
                        </svg>
                        {{ showTodoList ? 'Hide Todo List' : 'Show Todo List' }}
                    </button>

                    <!-- Existing Analytics Button -->
                    <button
                        @click="showAnalytics = !showAnalytics"
                        class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg transition-colors duration-150"
                    >
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="h-5 w-5 mr-2"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"
                            />
                        </svg>
                        {{ showAnalytics ? 'Hide Analytics' : 'View Analytics' }}
                    </button>
                </div>

                <!-- Analytics Dashboard -->
                <Transition
                    enter-active-class="transition-all duration-300 ease-out"
                    enter-from-class="opacity-0 transform -translate-y-4"
                    enter-to-class="opacity-100 transform translate-y-0"
                    leave-active-class="transition-all duration-300 ease-in"
                    leave-from-class="opacity-100 transform translate-y-0"
                    leave-to-class="opacity-0 transform -translate-y-4"
                >
                    <div v-if="showAnalytics" class="mb-8">
                        <MoodAnalyticsCharts :entries="moodEntries" />
                    </div>
                </Transition>

                <!-- Add this after your Analytics section -->
                <Transition
                    enter-active-class="transition-all duration-300 ease-out"
                    enter-from-class="opacity-0 transform -translate-y-4"
                    enter-to-class="opacity-100 transform translate-y-0"
                    leave-active-class="transition-all duration-300 ease-in"
                    leave-from-class="opacity-100 transform translate-y-0"
                    leave-to-class="opacity-0 transform -translate-y-4"
                >
                    <div v-if="showTodoList" class="mb-8">
                        <TodoList />
                    </div>
                </Transition>

                <!-- Filters -->
                <div class="bg-white rounded-xl shadow-lg p-6 mb-8">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Search Entries</label>
                            <input 
                                v-model="searchQuery" 
                                type="text"
                                class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500"
                                placeholder="Search by description or triggers..."
                            />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Filter by Emotion</label>
                            <select 
                                v-model="emotionFilter"
                                class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500"
                            >
                                <option value="">All Emotions</option>
                                <option v-for="emotion in emotions" :key="emotion" :value="emotion">
                                    {{ emotion.charAt(0).toUpperCase() + emotion.slice(1) }}
                                </option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Time Period</label>
                            <select 
                                v-model="dateFilter"
                                class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500"
                            >
                                <option value="all">All Time</option>
                                <option value="today">Today</option>
                                <option value="week">This Week</option>
                                <option value="month">This Month</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Mood Entries List -->
                <div class="space-y-6">
                    <template v-if="!isLoading && filteredEntries.length > 0">
                        <div v-for="entry in filteredEntries" :key="entry.id" 
                             class="bg-white rounded-xl shadow-lg overflow-hidden">
                            <div class="p-6">
                                <!-- Entry Header -->
                                <div class="flex items-center justify-between mb-4">
                                    <div class="flex items-center space-x-3">
                                        <span class="text-2xl">{{ getEmotionEmoji(entry.emotional_state) }}</span>
                                        <div>
                                            <h3 class="text-lg font-semibold text-gray-800">
                                                Mood Intensity: {{ entry.mood_intensity }}/10
                                            </h3>
                                            <p class="text-sm text-gray-500">{{ formatDate(entry.created_at) }}</p>
                                        </div>
                                    </div>
                                    <span class="text-sm text-gray-500">{{ timeAgo(entry.created_at) }}</span>
                                </div>

                                <!-- Entry Content -->
                                <div class="space-y-4">
                                    <p class="text-gray-700">{{ entry.mood_description }}</p>
                                    
                                    <!-- Expandable Details -->
                                    <details class="group">
                                        <summary class="flex items-center cursor-pointer">
                                            <span class="text-sm font-medium text-indigo-600">View Details</span>
                                            <svg class="w-5 h-5 ml-2 text-indigo-600 transform transition-transform group-open:rotate-90" 
                                                 fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                            </svg>
                                        </summary>
                                        
                                        <div class="mt-4 pl-4 space-y-4">
                                            <!-- Triggers -->
                                            <div v-if="entry.trigger_description" class="bg-orange-50 p-4 rounded-lg">
                                                <h4 class="font-semibold text-orange-700 mb-2">Triggers</h4>
                                                <p class="text-orange-600">{{ entry.trigger_description }}</p>
                                            </div>

                                            <!-- Activities -->
                                            <div v-if="entry.activities_description" class="bg-blue-50 p-4 rounded-lg">
                                                <h4 class="font-semibold text-blue-700 mb-2">Activities</h4>
                                                <p class="text-blue-600">{{ entry.activities_description }}</p>
                                            </div>

                                            <!-- Sleep Quality -->
                                            <div class="bg-purple-50 p-4 rounded-lg">
                                                <h4 class="font-semibold text-purple-700 mb-2">Sleep Quality</h4>
                                                <div class="flex items-center space-x-1">
                                                    <template v-for="n in 4" :key="n">
                                                        <svg class="w-5 h-5" :class="n <= entry.sleep_quality ? 'text-purple-500' : 'text-gray-300'"
                                                             fill="currentColor" viewBox="0 0 20 20">
                                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                                        </svg>
                                                    </template>
                                                </div>
                                            </div>

                                            <!-- Suggestions -->
                                            <div v-if="entry.suggestions && entry.suggestions.length > 0" 
                                                 class="bg-green-50 p-4 rounded-lg">
                                                <h4 class="font-semibold text-green-700 mb-2">Suggestions</h4>
                                                <ul class="list-disc list-inside space-y-1">
                                                    <li v-for="(suggestion, index) in entry.suggestions" 
                                                        :key="index" 
                                                        class="text-green-600">
                                                        {{ suggestion }}
                                                    </li>
                                                </ul>
                                            </div>

                                            <!-- Analytics Insights -->
                                            <div v-if="entry.analytics" class="bg-indigo-50 p-4 rounded-lg">
                                                <h4 class="font-semibold text-indigo-700 mb-2">Insights</h4>
                                                <div class="space-y-2">
                                                    <p v-if="entry.analytics.sleepImpact" class="text-indigo-600">
                                                        Sleep Impact: 
                                                        <span :class="{
                                                            'text-green-600': entry.analytics.sleepImpact.impact === 'Positive',
                                                            'text-red-600': entry.analytics.sleepImpact.impact === 'Negative'
                                                        }">
                                                            {{ entry.analytics.sleepImpact.impact }}
                                                        </span>
                                                    </p>
                                                    <p v-if="entry.analytics.activityCorrelation" class="text-indigo-600">
                                                        Activity Impact: {{ Math.round(entry.analytics.activityCorrelation.value * 100) }}%
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </details>
                                </div>
                            </div>
                        </div>
                    </template>

                    <!-- Loading State -->
                    <div v-if="isLoading" class="text-center py-12">
                        <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-indigo-500 mx-auto"></div>
                        <p class="mt-4 text-gray-600">Loading your entries...</p>
                    </div>

                    <!-- No Results State -->
                    <div v-if="!isLoading && filteredEntries.length === 0" class="text-center py-12">
                        <p class="text-gray-600">{{ noEntriesMessage }}</p>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import { ref, computed, onMounted, watch } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import MoodAnalyticsCharts from '@/Components/Analytics/MoodAnalyticsCharts.vue';
import TodoList from '@/Components/Todo/TodoList.vue';

// Refs for state management
const moodEntries = ref([]);
const isLoading = ref(true);
const errorMessage = ref('');
const searchQuery = ref('');
const emotionFilter = ref('');
const dateFilter = ref('all');
const showAnalytics = ref(false);
const showTodoList = ref(false);

// Available emotions for filter
const emotions = [
    'happy',
    'sad',
    'anxious',
    'excited',
    'calm',
    'angry',
    'frustrated'
];

// Computed stats
const stats = computed(() => {
    const entries = moodEntries.value;
    if (!entries.length) return {
        totalEntries: 0,
        averageMood: 0,
        topEmotion: 'N/A',
        averageSleep: 0
    };

    // Calculate statistics
    const emotionCounts = {};
    let totalMood = 0;
    let totalSleep = 0;

    entries.forEach(entry => {
        totalMood += entry.mood_intensity;
        totalSleep += entry.sleep_quality;
        emotionCounts[entry.emotional_state] = (emotionCounts[entry.emotional_state] || 0) + 1;
    });

    const topEmotion = Object.entries(emotionCounts)
        .sort(([,a], [,b]) => b - a)[0][0];

    return {
        totalEntries: entries.length,
        averageMood: totalMood / entries.length,
        topEmotion: topEmotion.charAt(0).toUpperCase() + topEmotion.slice(1),
        averageSleep: totalSleep / entries.length
    };
});

// Computed filtered entries
const filteredEntries = computed(() => {
    return moodEntries.value
        .filter(entry => {
            // Search query filter
            if (searchQuery.value && !entry.mood_description.toLowerCase().includes(searchQuery.value.toLowerCase())) {
                return false;
            }
            
            // Emotion filter
            if (emotionFilter.value && entry.emotional_state !== emotionFilter.value) {
                return false;
            }
            
            // Date filter
            if (dateFilter.value !== 'all') {
                const entryDate = new Date(entry.created_at);
                const today = new Date();
                
                switch (dateFilter.value) {
                    case 'today':
                        return isSameDay(entryDate, today);
                    case 'week':
                        return isThisWeek(entryDate);
                    case 'month':
                        return isSameMonth(entryDate, today);
                }
            }
            
            return true;
        })
        .sort((a, b) => new Date(b.created_at) - new Date(a.created_at));
});

// Computed message for no entries
const noEntriesMessage = computed(() => {
    if (searchQuery.value || emotionFilter.value || dateFilter.value !== 'all') {
        return 'No entries match your filters. Try adjusting your search criteria.';
    }
    return 'No mood entries yet. Start tracking your mood to see your journey!';
});

// Helper functions
const isSameDay = (date1, date2) => {
    return date1.getDate() === date2.getDate() &&
           date1.getMonth() === date2.getMonth() &&
           date1.getFullYear() === date2.getFullYear();
};

const isThisWeek = (date) => {
    const today = new Date();
    const weekStart = new Date(today.setDate(today.getDate() - today.getDay()));
    const weekEnd = new Date(today.setDate(today.getDate() - today.getDay() + 6));
    return date >= weekStart && date <= weekEnd;
};

const isSameMonth = (date1, date2) => {
    return date1.getMonth() === date2.getMonth() &&
           date1.getFullYear() === date2.getFullYear();
};

const formatDate = (dateString) => {
    return new Date(dateString).toLocaleDateString('en-US', {
        weekday: 'long',
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    });
};

const timeAgo = (dateString) => {
    const date = new Date(dateString);
    const now = new Date();
    const seconds = Math.floor((now - date) / 1000);
    
    let interval = Math.floor(seconds / 31536000);
    if (interval > 1) return `${interval} years ago`;
    
    interval = Math.floor(seconds / 2592000);
    if (interval > 1) return `${interval} months ago`;
    
    interval = Math.floor(seconds / 86400);
    if (interval > 1) return `${interval} days ago`;
    
    interval = Math.floor(seconds / 3600);
    if (interval > 1) return `${interval} hours ago`;
    
    interval = Math.floor(seconds / 60);
    if (interval > 1) return `${interval} minutes ago`;
    
    return 'just now';
};

const getEmotionEmoji = (emotion) => {
    const emojis = {
        happy: '😊',
        sad: '😢',
        anxious: '😰',
        excited: '🤩',
        calm: '😌',
        angry: '😠',
        frustrated: '😤'
    };
    return emojis[emotion] || '😐';
};

// Generate suggestions based on mood entry
const generateSuggestions = (entry) => {
    const suggestions = [];
    
    // Emotional state based suggestions
    const emotionalStateSuggestions = {
        'anxious': [
            'Try deep breathing exercises (4-7-8 breathing technique)',
            'Consider mindfulness meditation',
            'Take a walk in nature to clear your mind',
        ],
        'sad': [
            'Reach out to a friend or family member',
            'Practice self-compassion exercises',
            'Consider journaling your feelings',
            'Engage in a hobby you enjoy',
        ],
        'angry': [
            'Practice progressive muscle relaxation',
            'Try physical exercise to release tension',
            'Take time to cool down before making decisions',
        ],
        'happy': [
            'Share your positive energy with others',
            'Document what made you happy today',
            'Build on this momentum for tomorrow',
        ],
        'excited': [
            'Channel your energy into productive tasks',
            'Share your enthusiasm with others',
            'Set new goals while motivated',
        ],
        'calm': [
            'Perfect time for reflection and planning',
            'Practice gratitude meditation',
            'Maintain this balanced state through mindfulness',
        ],
        'frustrated': [
            'Break down problems into smaller tasks',
            'Take short breaks between activities',
            'Practice stress-management techniques',
        ],
    };

    // Add suggestions based on entry data
    if (entry.emotional_state && emotionalStateSuggestions[entry.emotional_state]) {
        suggestions.push(...emotionalStateSuggestions[entry.emotional_state]);
    }

    // Add more contextual suggestions based on other entry data
    if (entry.sleep_quality <= 2) {
        suggestions.push(
            'Consider establishing a regular sleep schedule',
            'Create a calming bedtime routine',
            'Limit screen time before bed'
        );
    }

    return suggestions;
};

// Fetch mood entries
const fetchMoodEntries = async () => {
    try {
        const response = await fetch('/api/mood-entries');
        if (!response.ok) throw new Error('Failed to fetch entries');
        const data = await response.json();
        moodEntries.value = data.map(entry => ({
            ...entry,
            suggestions: generateSuggestions(entry)
        }));
    } catch (error) {
        errorMessage.value = 'Failed to load mood entries. Please try again later.';
        console.error('Error:', error);
    } finally {
        isLoading.value = false;
    }
};

// Optional: Add this if you want to persist the analytics view state
watch(showAnalytics, (newValue) => {
    localStorage.setItem('showMoodAnalytics', newValue);
});

onMounted(() => {
    // Restore analytics view state
    const savedState = localStorage.getItem('showMoodAnalytics');
    if (savedState !== null) {
        showAnalytics.value = savedState === 'true';
    }
    
    // Your existing onMounted logic
    fetchMoodEntries();
});
</script>

<style scoped>
details summary::-webkit-details-marker {
    display: none;
}

.group-open\:rotate-90 {
    transform: rotate(90deg);
}

/* Add these styles for the transition */
.transition-all {
    transition-property: all;
}

.duration-300 {
    transition-duration: 300ms;
}

.ease-in {
    transition-timing-function: cubic-bezier(0.4, 0, 1, 1);
}

.ease-out {
    transition-timing-function: cubic-bezier(0, 0, 0.2, 1);
}

.transform {
    transform: translateZ(0);
}

.-translate-y-4 {
    transform: translateY(-1rem);
}

.translate-y-0 {
    transform: translateY(0);
}

.opacity-0 {
    opacity: 0;
}

.opacity-100 {
    opacity: 1;
}
</style>
