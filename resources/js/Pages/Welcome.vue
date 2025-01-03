<script setup>
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    canLogin: {
        type: Boolean,
    },
    canRegister: {
        type: Boolean,
    },
    laravelVersion: {
        type: String,
        required: true,
    },
    phpVersion: {
        type: String,
        required: true,
    },
});
</script>

<template>
    <Head title="MoodSync - Track Your Emotional Journey" />
    
    <div class="min-h-screen bg-gradient-to-br from-indigo-50 via-purple-50 to-pink-50">
        <!-- Navigation -->
        <nav class="fixed top-0 right-0 px-6 py-4 z-50">
            <div v-if="canLogin" class="flex space-x-4">
                <Link
                    v-if="$page.props.auth.user"
                    :href="route('dashboard')"
                    class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-all duration-300"
                >
                    Dashboard
                </Link>

                <template v-else>
                    <Link
                        :href="route('login')"
                        class="px-4 py-2 bg-white text-indigo-600 rounded-lg hover:bg-indigo-50 transition-all duration-300"
                    >
                        Log in
                    </Link>

                    <Link
                        v-if="canRegister"
                        :href="route('register')"
                        class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-all duration-300"
                    >
                        Register
                    </Link>
                </template>
            </div>
        </nav>

        <!-- Hero Section -->
        <div class="relative pt-24 pb-16 sm:pt-32 sm:pb-24">
            <div class="mx-auto max-w-7xl px-6 lg:px-8">
                <div class="text-center">
                    <h1 class="text-4xl font-bold tracking-tight text-gray-900 sm:text-6xl">
                        Track Your Emotional Journey with
                        <span class="text-indigo-600">MoodSync</span>
                    </h1>
                    <p class="mt-6 text-lg leading-8 text-gray-600">
                        Your personal companion for emotional well-being and self-discovery.
                        Track moods, identify patterns, and grow through mindful reflection.
                    </p>
                    <div class="mt-10 flex items-center justify-center gap-x-6">
                        <Link
                            v-if="!$page.props.auth.user"
                            :href="route('register')"
                            class="rounded-md bg-indigo-600 px-6 py-3 text-lg font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600"
                        >
                            Get Started Free
                        </Link>
                        <Link
                            v-else
                            :href="route('dashboard')"
                            class="rounded-md bg-indigo-600 px-6 py-3 text-lg font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600"
                        >
                            Go to Dashboard
                        </Link>
                    </div>
                </div>
            </div>
        </div>

        <!-- Features Section -->
        <div class="py-24 sm:py-32">
            <div class="mx-auto max-w-7xl px-6 lg:px-8">
                <div class="grid grid-cols-1 gap-12 sm:grid-cols-2 lg:grid-cols-3">
                    <!-- Feature 1 -->
                    <div class="bg-white rounded-2xl p-8 shadow-lg hover:shadow-xl transition-all duration-300">
                        <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-indigo-500 text-white">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <h3 class="mt-6 text-xl font-bold text-gray-900">Mood Tracking</h3>
                        <p class="mt-4 text-gray-600">
                            Log your daily emotions, sleep quality, and activities. Understand your emotional patterns over time.
                        </p>
                    </div>

                    <!-- Feature 2 -->
                    <div class="bg-white rounded-2xl p-8 shadow-lg hover:shadow-xl transition-all duration-300">
                        <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-purple-500 text-white">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <h3 class="mt-6 text-xl font-bold text-gray-900">Personalized Insights</h3>
                        <p class="mt-4 text-gray-600">
                            Receive tailored suggestions and insights based on your mood patterns and daily activities.
                        </p>
                    </div>

                    <!-- Feature 3 -->
                    <div class="bg-white rounded-2xl p-8 shadow-lg hover:shadow-xl transition-all duration-300">
                        <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-pink-500 text-white">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                            </svg>
                        </div>
                        <h3 class="mt-6 text-xl font-bold text-gray-900">Gratitude Journal</h3>
                        <p class="mt-4 text-gray-600">
                            Practice daily gratitude and set meaningful goals for personal growth and emotional well-being.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Testimonials Section -->
        <div class="bg-white py-24 sm:py-32">
            <div class="mx-auto max-w-7xl px-6 lg:px-8">
                <div class="text-center">
                    <h2 class="text-3xl font-bold tracking-tight text-gray-900 sm:text-4xl">
                        Transform Your Emotional Well-being
                    </h2>
                    <p class="mt-6 text-lg leading-8 text-gray-600">
                        Join thousands of users who have improved their emotional awareness and mental health with MoodSync.
                    </p>
                </div>
                
                <div class="mt-16 grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-3">
                    <div class="flex flex-col gap-4 rounded-2xl bg-gray-50 p-8">
                        <p class="text-lg text-gray-600">
                            "MoodSync has helped me understand my emotional patterns and make positive changes in my life."
                        </p>
                        <div class="flex items-center gap-4">
                            <div class="h-10 w-10 rounded-full bg-indigo-500"></div>
                            <div>
                                <h3 class="font-semibold text-gray-900">Sarah M.</h3>
                                <p class="text-gray-600">Daily User</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Add more testimonials as needed -->
                </div>
            </div>
        </div>

        <!-- Footer -->
        <footer class="bg-gray-50">
            <div class="mx-auto max-w-7xl px-6 py-12 md:flex md:items-center md:justify-between lg:px-8">
                <div class="mt-8 md:order-1 md:mt-0">
                    <p class="text-center text-xs leading-5 text-gray-500">
                        &copy; {{ new Date().getFullYear() }} MoodSync. All rights reserved.
                    </p>
                    <p class="text-center text-xs leading-5 text-gray-500 mt-2">
                        Laravel v{{ laravelVersion }} (PHP v{{ phpVersion }})
                    </p>
                </div>
            </div>
        </footer>
    </div>
</template>

<style scoped>
.bg-gradient-to-br {
    background-size: 200% 200%;
    animation: gradient 15s ease infinite;
}

@keyframes gradient {
    0% {
        background-position: 0% 50%;
    }
    50% {
        background-position: 100% 50%;
    }
    100% {
        background-position: 0% 50%;
    }
}
</style>
