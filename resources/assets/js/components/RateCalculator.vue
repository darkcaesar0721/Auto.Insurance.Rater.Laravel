<template>
    <div>
        <auto-rater></auto-rater>
    </div>
</template>

<script>
    import AutoRater from './AutoRater';

    export default {
        components: { AutoRater },

        mounted() {
            console.log('Component mounted.')
        }
    }
</script>
