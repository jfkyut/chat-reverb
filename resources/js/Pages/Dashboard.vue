<script setup>
import PrimaryButton from '@/Components/PrimaryButton.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import axios from 'axios';
import { onMounted, ref } from 'vue';

const chatChannel = Echo.channel('chat');

const form = ref({
    message: null
})

const messages = ref([]);

const send = () => {
    axios.post('/chat', form.value);

    form.value.message = null
}

onMounted(() => {
    chatChannel.listen('MessageCreated', (e) => {
        messages.value.push(e.message)
    })

    axios.get('/chat')
        .then((data) => {
            messages.value = data.data;
        })
        .catch((error) => {
            console.log(error);
        })
})


</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Dashboard</h2>
        </template>

        <div class="py-12">
  
            <div class="mx-auto max-w-2xl overflow-y-auto max-h-[60vh]" >
                <div 
                    class="my-4 flex w-full" 
                    :class="$page.props.auth.user.id === message.user.id ? 'justify-end' : 'justify-start'"
                    v-for="(message, index) in messages" :key="index">
                    <div>
                        <span>{{ message.user.name }}</span>
                        <div 
                            class="p-4 rounded"
                            :class="$page.props.auth.user.id === message.user.id ? 'bg-blue-400' : 'bg-gray-400'">
                            {{ message.text }}
                            <!-- {{ $page.props.auth.user }} -->
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <form @submit.prevent="send" class="fixed left-1/2 -translate-x-1/2 bottom-20">
            <input type="text" v-model="form.message">
            <PrimaryButton>Send</PrimaryButton>
        </form>
    </AuthenticatedLayout>
</template>
