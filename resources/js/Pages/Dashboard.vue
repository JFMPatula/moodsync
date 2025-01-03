<template>
    <Head title="Mood Tracker" />

    <AuthenticatedLayout>
        <div class="min-h-screen bg-gradient-to-br from-violet-50 to-blue-50 py-8">
            <div class="max-w-4xl mx-auto">
                <!-- Main Card -->
                <div class="bg-white shadow-xl rounded-xl overflow-hidden border-t-4 border-indigo-500">
                    <!-- Header -->
                    <div class="bg-white pb-6 px-8 pt-8">
                        <div class="flex items-center justify-center mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-indigo-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                            </svg>
                            <h2 class="text-3xl font-npm ubold text-gray-800">Daily Mood Check-in</h2>
                        </div>
                        <p class="text-center text-lg text-gray-600">
                            Welcome back, <span class="font-semibold text-indigo-600">{{ name }}</span>! Let's reflect on your day.
                        </p>
                    </div>

                    <!-- Success Message -->
                    <div v-if="successMessage" class="mx-8 mb-6 p-4 bg-green-50 text-green-700 rounded-lg flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        {{ successMessage }}
                    </div>

                    <!-- Error Message -->
                    <div v-if="errorMessage" class="mx-8 mb-6 p-4 bg-red-50 text-red-700 rounded-lg flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        {{ errorMessage }}
                    </div>

                    <!-- Form Content -->
                    <form @submit.prevent="submitForm" class="bg-white p-8">
                        <div class="grid gap-8">
                            <!-- Mood Section -->
                            <div class="space-y-6">
                                <div class="flex items-center space-x-2 mb-4">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707" />
                                    </svg>
                                    <h3 class="text-xl font-semibold text-gray-800">Current Mood</h3>
                                </div>

                                <div class="grid md:grid-cols-2 gap-6">
                                    <div class="space-y-2">
                                        <label class="block text-sm font-medium text-gray-700">How are you feeling?</label>
                                        <input
                                            v-model="moodDescription"
                                            type="text"
                                            class="w-full p-3 rounded-lg border border-gray-200 bg-gray-50 focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-200"
                                            placeholder="Describe your mood..."
                                            required
                                        />
                                    </div>

                                    <div class="space-y-2">
                                        <label class="block text-sm font-medium text-gray-700">Mood Intensity (1-10)</label>
                                        <div class="relative"
                                             @mouseenter="showIntensity = true"
                                             @mouseleave="showIntensity = false">
                                            <input
                                                v-model="moodIntensity"
                                                type="range"
                                                min="1"
                                                max="10"
                                                class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer accent-indigo-600"
                                            />
                                            <div class="absolute -top-8 left-0 w-full">
                                                <div 
                                                    class="relative w-8 h-8 bg-indigo-600 text-white rounded-full flex items-center justify-center transform -translate-x-1/2 transition-all duration-300 ease-out"
                                                    :class="{ 'opacity-0': !showIntensity && !isDragging }"
                                                    :style="{ left: `${(moodIntensity - 1) * 11.1}%` }"
                                                >
                                                    {{ moodIntensity }}
                                                    <div class="absolute -bottom-1 left-1/2 transform -translate-x-1/2">
                                                        <div class="w-2 h-2 bg-indigo-600 rotate-45"></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="flex justify-between text-xs text-gray-500 mt-2">
                                                <span>Mild</span>
                                                <span>Moderate</span>
                                                <span>Intense</span>
                                            </div>
                                        </div>
                                        <div v-if="previousMoodIntensity" class="mt-2 flex items-center text-sm">
                                            <span class="mr-2">Compared to previous:</span>
                                            <div class="flex items-center" :class="moodTrendColor">
                                                <svg v-if="moodIntensity > previousMoodIntensity" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                                                </svg>
                                                <svg v-else-if="moodIntensity < previousMoodIntensity" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                                </svg>
                                                <svg v-else xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                                                </svg>
                                                <span class="ml-1">{{ moodIntensityDiff }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="grid md:grid-cols-2 gap-6">
                                    <div class="space-y-2">
                                        <label class="block text-sm font-medium text-gray-700">Emotional State</label>
                                        <select
                                            v-model="emotionalState"
                                            class="w-full p-3 rounded-lg border border-gray-200 bg-gray-50 focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                                            required
                                        >
                                            <option value="">Select your emotion...</option>
                                            <option value="happy">😊 Happy</option>
                                            <option value="sad">😢 Sad</option>
                                            <option value="anxious">😰 Anxious</option>
                                            <option value="excited">🤩 Excited</option>
                                            <option value="calm">😌 Calm</option>
                                            <option value="angry">😠 Angry</option>
                                            <option value="frustrated">😤 Frustrated</option>
                                        </select>
                                    </div>

                                    <div class="space-y-2">
                                        <label class="block text-sm font-medium text-gray-700">Sleep Quality</label>
                                        <select
                                            v-model="sleepQuality"
                                            class="w-full p-3 rounded-lg border border-gray-200 bg-gray-50 focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                                            required
                                        >
                                            <option value="1">😴 Poor</option>
                                            <option value="2">🛏️ Fair</option>
                                            <option value="3">✨ Good</option>
                                            <option value="4">⭐ Excellent</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <!-- Reflection Section -->
                            <div class="space-y-6">
                                <div class="flex items-center space-x-2 mb-4">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                    </svg>
                                    <h3 class="text-xl font-semibold text-gray-800">Daily Reflection</h3>
                                </div>

                                <div class="space-y-6">
                                    <div class="space-y-2">
                                        <label class="block text-sm font-medium text-gray-700">What influenced your mood today?</label>
                                        <textarea
                                            v-model="triggerDescription"
                                            rows="4"
                                            class="w-full p-3 rounded-lg border border-gray-200 bg-gray-50 focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                                            placeholder="Reflect on your day's experiences..."
                                            required
                                        ></textarea>
                                    </div>

                                    <div class="space-y-2">
                                        <label class="block text-sm font-medium text-gray-700">Self-care Activities</label>
                                        <textarea
                                            v-model="activitiesDescription"
                                            rows="4"
                                            class="w-full p-3 rounded-lg border border-gray-200 bg-gray-50 focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                                            placeholder="What did you do to take care of yourself today?"
                                            required
                                        ></textarea>
                                    </div>

                                    <div class="space-y-2">
                                        <label class="block text-sm font-medium text-gray-700">Gratitude Journal</label>
                                        <textarea
                                            v-model="gratitudeEntry"
                                            rows="4"
                                            class="w-full p-3 rounded-lg border border-gray-200 bg-gray-50 focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                                            placeholder="What are you thankful for today?"
                                            required
                                        ></textarea>
                                    </div>

                                    <div class="space-y-2">
                                        <label class="block text-sm font-medium text-gray-700">Tomorrow's Goal</label>
                                        <textarea
                                            v-model="goalDescription"
                                            rows="4"
                                            class="w-full p-3 rounded-lg border border-gray-200 bg-gray-50 focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                                            placeholder="What would you like to achieve tomorrow?"
                                            required
                                        ></textarea>
                                    </div>
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="flex flex-col sm:flex-row justify-center gap-4 mt-6">
                                <button
                                    type="submit"
                                    class="px-6 py-3 bg-indigo-600 text-white font-semibold rounded-lg shadow-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-600 focus:ring-opacity-50 transition-all duration-300 flex items-center justify-center"
                                >
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                    </svg>
                                    Save Mood Entry
                                </button>

                                <a
                                    href="/mood-entries"
                                    class="px-6 py-3 bg-violet-600 text-white font-semibold rounded-lg shadow-md hover:bg-violet-700 focus:outline-none focus:ring-2 focus:ring-violet-600 focus:ring-opacity-50 transition-all duration-300 flex items-center justify-center"
                                >
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                    </svg>
                                    View Past Entries
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { ref, computed, onMounted } from 'vue';
import { usePage } from '@inertiajs/vue3';
import axios from 'axios';

// Configure axios CSRF token
axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

// Access the logged-in user data from Inertia page props
const page = usePage();
const name = computed(() => page.props.auth.user.name);

// Form state
const moodDescription = ref('');
const moodIntensity = ref(5);
const emotionalState = ref('');
const sleepQuality = ref(3);
const triggerDescription = ref('');
const activitiesDescription = ref('');
const gratitudeEntry = ref('');
const goalDescription = ref('');
const successMessage = ref('');
const errorMessage = ref('');

// New ref for previous mood intensity
const previousMoodIntensity = ref(null);

// New computed properties for mood trend
const moodIntensityDiff = computed(() => {
    if (!previousMoodIntensity.value) return '';
    const diff = moodIntensity.value - previousMoodIntensity.value;
    return diff > 0 ? `+${diff}` : diff;
});

const moodTrendColor = computed(() => {
    if (!previousMoodIntensity.value) return '';
    const diff = moodIntensity.value - previousMoodIntensity.value;
    if (diff > 0) return 'text-red-500';
    if (diff < 0) return 'text-green-500';
    return 'text-gray-500';
});

// Add these new refs
const showIntensity = ref(false);
const isDragging = ref(false);

// Add event listeners for the range input
onMounted(() => {
    const rangeInput = document.querySelector('input[type="range"]');
    
    rangeInput.addEventListener('mousedown', () => {
        isDragging.value = true;
    });
    
    window.addEventListener('mouseup', () => {
        isDragging.value = false;
    });
});

// Submit the form
const submitForm = async () => {
    try {
        const data = {
            mood_description: moodDescription.value,
            mood_intensity: parseInt(moodIntensity.value),
            emotional_state: emotionalState.value,
            sleep_quality: parseInt(sleepQuality.value),
            trigger_description: triggerDescription.value,
            activities_description: activitiesDescription.value,
            gratitude_entry: gratitudeEntry.value,
            goal_description: goalDescription.value,
        };

        // Store current mood intensity before submission
        previousMoodIntensity.value = moodIntensity.value;

        // Send the data using axios to the API endpoint
        const response = await axios.post('/api/mood-entry', data);

        if (response.status === 201) {
            // Show success message
            successMessage.value = 'Your mood entry has been saved successfully!';
            errorMessage.value = ''; // Clear any previous error
            
            // Clear the form
            resetForm();

            // Redirect to mood entries page after short delay
            setTimeout(() => {
                window.location.href = '/mood-entries';
            }, 1500);
        }
    } catch (error) {
        console.error('Submission error:', error);
        errorMessage.value = error.response?.data?.message || 'There was an error saving your entry. Please try again later.';
        successMessage.value = ''; // Clear any previous success message
    }
};

// Reset form fields
const resetForm = () => {
    moodDescription.value = '';
    moodIntensity.value = 5;
    emotionalState.value = '';
    sleepQuality.value = 3;
    triggerDescription.value = '';
    activitiesDescription.value = '';
    gratitudeEntry.value = '';
    goalDescription.value = '';
};
</script>

<style scoped>
input[type="range"] {
    transition: all 0.3s ease;
}

input[type="range"]::-webkit-slider-thumb {
    -webkit-appearance: none;
    appearance: none;
    width: 16px;
    height: 16px;
    background: #4f46e5;
    border-radius: 50%;
    cursor: pointer;
    transition: all 0.3s ease;
}

input[type="range"]::-webkit-slider-thumb:hover {
    transform: scale(1.2);
}

input[type="range"]::-moz-range-thumb {
    width: 16px;
    height: 16px;
    background: #4f46e5;
    border-radius: 50%;
    cursor: pointer;
    transition: all 0.3s ease;
}

input[type="range"]::-moz-range-thumb:hover {
    transform: scale(1.2);
}
</style>
