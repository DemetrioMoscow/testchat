<template>
    <div>
        <div v-for="(error, index) in errors" class="invalid-feedback d-block" role="alert">
            <strong>{{ error }}</strong>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        errors: {
            type: Array,
            required: true
        }
    }
}
</script>
