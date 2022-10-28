<template>
    <div class="my-2">
        <span class="fw-bold">{{ message.user.name }}:</span>
        {{ message.message }}
    </div>
</template>

<script>
export default {
    props: {
        message: {
            type: Object,
            required: true
        }
    }
}
</script>
