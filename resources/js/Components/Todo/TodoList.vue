<template>
    <div class="bg-white rounded-xl shadow-lg p-4 sm:p-6 transition-all duration-300 hover:shadow-xl">
        <!-- Header and Add Todo Button -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4 sm:gap-0">
            <div>
                <h3 class="text-xl font-bold text-gray-800 mb-1">My Todo List</h3>
                <p class="text-sm text-gray-500">Keep track of your tasks and stay organized</p>
            </div>
            <button
                @click="openAddTodoModal"
                class="w-full sm:w-auto inline-flex items-center justify-center px-4 py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white rounded-lg text-sm font-medium transition-all duration-300 transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
            >
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    class="h-5 w-5 mr-2"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                >
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Add New Task
            </button>
        </div>

        <!-- Add Todo Modal -->
        <div v-if="showTodoForm" 
             class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4"
             @click.self="closeAddTodoModal"
        >
            <div class="bg-white rounded-xl p-6 w-full max-w-md mx-4 transform transition-all duration-300 scale-100 shadow-2xl">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-xl font-bold text-gray-800">Add New Task</h3>
                    <button 
                        @click="closeAddTodoModal" 
                        class="text-gray-500 hover:text-gray-700 transition-colors duration-200 p-1 hover:bg-gray-100 rounded-full"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <form @submit.prevent="addTodo" class="space-y-4">
                    <div>
                        <label for="todo-input" class="block text-sm font-medium text-gray-700 mb-1">Task Description</label>
                        <input
                            id="todo-input"
                            v-model="newTodo"
                            type="text"
                            placeholder="What needs to be done?"
                            class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                            required
                            ref="todoInput"
                        />
                    </div>
                    <div class="flex justify-end gap-3">
                        <button
                            type="button"
                            @click="closeAddTodoModal"
                            class="px-4 py-2 text-gray-700 hover:text-gray-900 bg-gray-100 hover:bg-gray-200 rounded-lg transition-all duration-200 font-medium"
                        >
                            Cancel
                        </button>
                        <button
                            type="submit"
                            class="px-4 py-2 bg-gradient-to-r from-green-600 to-green-500 hover:from-green-700 hover:to-green-600 text-white rounded-lg transition-all duration-200 font-medium focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500"
                        >
                            Add Task
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Todo List -->
        <TransitionGroup
            name="todo"
            tag="div"
            class="space-y-3"
        >
            <div v-for="todo in sortedTodos" 
                 :key="todo.id" 
                 class="group flex items-center justify-between p-4 bg-gray-50 hover:bg-white rounded-xl transition-all duration-300 border border-gray-100 hover:border-blue-200 hover:shadow-md"
                 :class="{ 'opacity-75 hover:opacity-100': todo.completed }"
            >
                <div class="flex items-center flex-1 min-w-0 gap-4">
                    <div class="flex-shrink-0">
                        <input
                            type="checkbox"
                            :checked="todo.completed"
                            @change="toggleTodo(todo)"
                            class="h-5 w-5 text-blue-600 rounded-md border-gray-300 focus:ring-blue-500 cursor-pointer transition-all duration-200"
                        />
                    </div>
                    <div class="flex-1 min-w-0">
                        <div v-if="!todo.isEditing" 
                             class="text-sm sm:text-base break-words"
                             :class="{ 'line-through text-gray-400': todo.completed }"
                        >
                            {{ todo.task }}
                        </div>
                        <input v-else
                               v-model="todo.editedTask"
                               @keyup.enter="updateTodo(todo)"
                               @keyup.esc="cancelEdit(todo)"
                               @blur="updateTodo(todo)"
                               class="w-full text-sm sm:text-base rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                               ref="editInput"
                        />
                    </div>
                </div>
                
                <div class="flex items-center space-x-2 ml-4 flex-shrink-0 opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                    <button
                        @click="startEditing(todo)"
                        class="text-blue-600 hover:text-blue-800 transition-colors duration-200 p-2 hover:bg-blue-50 rounded-lg"
                        v-if="!todo.isEditing"
                        title="Edit task"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 sm:h-5 sm:w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                    </button>
                    <button
                        @click="confirmDelete(todo)"
                        class="text-red-600 hover:text-red-800 transition-colors duration-200 p-2 hover:bg-red-50 rounded-lg"
                        title="Delete task"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 sm:h-5 sm:w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                    </button>
                </div>
            </div>
        </TransitionGroup>

        <!-- Empty State -->
        <div v-if="!isLoading && !todos.length" class="text-center py-12 px-4">
            <div class="text-gray-400 mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
            </div>
            <h4 class="text-lg font-semibold text-gray-800 mb-2">No Tasks Yet</h4>
            <p class="text-gray-500 mb-6">Start adding tasks to stay organized and productive!</p>
            <button
                @click="openAddTodoModal"
                class="inline-flex items-center justify-center px-4 py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white rounded-lg text-sm font-medium transition-all duration-300 transform hover:scale-105"
            >
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    class="h-5 w-5 mr-2"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                >
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Add Your First Task
            </button>
        </div>

        <!-- Loading State -->
        <div v-if="isLoading" class="flex flex-col items-center justify-center py-12">
            <div class="animate-spin rounded-full h-10 w-10 border-b-2 border-blue-500 mb-4"></div>
            <p class="text-gray-500">Loading your tasks...</p>
        </div>

        <!-- Delete Confirmation Modal -->
        <div v-if="showDeleteConfirm" 
             class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4"
             @click.self="showDeleteConfirm = false"
        >
            <div class="bg-white rounded-xl p-6 w-full max-w-md mx-4 transform transition-all duration-300 scale-100 shadow-2xl">
                <div class="text-center">
                    <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100 mb-4">
                        <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Delete Task</h3>
                    <p class="text-gray-500 mb-6">Are you sure you want to delete this task? This action cannot be undone.</p>
                </div>
                <div class="flex justify-end gap-3">
                    <button
                        @click="showDeleteConfirm = false"
                        class="px-4 py-2 text-gray-700 hover:text-gray-900 bg-gray-100 hover:bg-gray-200 rounded-lg transition-all duration-200 font-medium"
                    >
                        Cancel
                    </button>
                    <button
                        @click="deleteConfirmed"
                        class="px-4 py-2 bg-gradient-to-r from-red-600 to-red-500 hover:from-red-700 hover:to-red-600 text-white rounded-lg transition-all duration-200 font-medium focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500"
                    >
                        Delete
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, nextTick } from 'vue';
import axios from 'axios';

const showTodoForm = ref(false);
const newTodo = ref('');
const todos = ref([]);
const editingTodo = ref(null);
const editInput = ref(null);
const todoInput = ref(null);
const isLoading = ref(true);
const showDeleteConfirm = ref(false);
const todoToDelete = ref(null);

// Sort todos with completed items at the bottom
const sortedTodos = computed(() => {
    return [...todos.value].sort((a, b) => {
        if (a.completed === b.completed) {
            return new Date(b.created_at) - new Date(a.created_at);
        }
        return a.completed ? 1 : -1;
    });
});

const fetchTodos = async () => {
    try {
        const response = await axios.get('/api/todos');
        if (response.data) {
            todos.value = response.data.map(todo => ({
                ...todo,
                isEditing: false,
                editedTask: todo.task
            }));
        }
    } catch (error) {
        console.error('Error fetching todos:', error.response?.data?.error || 'Failed to fetch todos');
    } finally {
        isLoading.value = false;
    }
};

const openAddTodoModal = () => {
    showTodoForm.value = true;
    nextTick(() => {
        todoInput.value?.focus();
    });
};

const closeAddTodoModal = () => {
    showTodoForm.value = false;
    newTodo.value = '';
};

const addTodo = async () => {
    if (!newTodo.value.trim()) return;

    const task = newTodo.value.trim();
    
    try {
        const response = await axios.post('/api/todos', { task });
        if (response.data) {
            todos.value.unshift({
                ...response.data,
                isEditing: false,
                editedTask: response.data.task
            });
            closeAddTodoModal();
        }
    } catch (error) {
        console.error('Error adding todo:', error.response?.data?.error || 'Failed to add todo');
        alert(error.response?.data?.error || 'Failed to add todo. Please try again.');
    }
};

const startEditing = async (todo) => {
    // Cancel any other editing
    todos.value.forEach(t => {
        if (t.id !== todo.id) {
            t.isEditing = false;
            t.editedTask = t.task;
        }
    });

    todo.isEditing = true;
    todo.editedTask = todo.task;
    await nextTick();
    editInput.value?.focus();
};

const cancelEdit = (todo) => {
    todo.editedTask = todo.task;
    todo.isEditing = false;
};

const updateTodo = async (todo) => {
    if (!todo.editedTask.trim()) {
        todo.editedTask = todo.task;
        todo.isEditing = false;
        return;
    }

    if (todo.editedTask === todo.task) {
        todo.isEditing = false;
        return;
    }

    try {
        const response = await axios.put(`/api/todos/${todo.id}`, { task: todo.editedTask });
        if (response.data) {
            todo.task = todo.editedTask;
        }
    } catch (error) {
        console.error('Error updating todo:', error.response?.data?.error || 'Failed to update todo');
        todo.editedTask = todo.task;
    } finally {
        todo.isEditing = false;
    }
};

const toggleTodo = async (todo) => {
    try {
        const response = await axios.put(`/api/todos/${todo.id}`, { completed: !todo.completed });
        if (response.data) {
            todo.completed = !todo.completed;
        }
    } catch (error) {
        console.error('Error toggling todo:', error.response?.data?.error || 'Failed to toggle todo');
    }
};

const confirmDelete = (todo) => {
    todoToDelete.value = todo;
    showDeleteConfirm.value = true;
};

const deleteConfirmed = async () => {
    if (!todoToDelete.value) return;
    
    try {
        await axios.delete(`/api/todos/${todoToDelete.value.id}`);
        todos.value = todos.value.filter(todo => todo.id !== todoToDelete.value.id);
        showDeleteConfirm.value = false;
        todoToDelete.value = null;
    } catch (error) {
        console.error('Error deleting todo:', error.response?.data?.error || 'Failed to delete todo');
        alert(error.response?.data?.error || 'Failed to delete todo. Please try again.');
    }
};

// Initial fetch
fetchTodos();
</script>

<style scoped>
.todo-enter-active,
.todo-leave-active {
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
}

.todo-enter-from,
.todo-leave-to {
    opacity: 0;
    transform: translateY(20px);
}

.todo-move {
    transition: transform 0.4s cubic-bezier(0.4, 0, 0.2, 1);
}

/* Responsive text size */
@media (max-width: 640px) {
    .text-base {
        font-size: 0.875rem;
    }
}

/* Custom scrollbar for webkit browsers */
::-webkit-scrollbar {
    width: 8px;
}

::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 4px;
}

::-webkit-scrollbar-thumb {
    background: #c5c5c5;
    border-radius: 4px;
}

::-webkit-scrollbar-thumb:hover {
    background: #a8a8a8;
}
</style> 