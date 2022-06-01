window.addEventListener('alpine:init', () => {
    const Alpine = window.Alpine || {}
    const cardanoPress = window.cardanoPress || {}

    Alpine.data('cardanoPressISPO', () => ({
        isProcessing: false,

        async init() {
            console.log('CardanoPress ISPO ready!')
        },
    }))
})
