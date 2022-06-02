import { handleDelegation, handleTracking } from './actions'

window.addEventListener('alpine:init', () => {
    const Alpine = window.Alpine || {}
    const cardanoPress = window.cardanoPress || {}

    Alpine.data('cardanoPressISPO', () => ({
        isProcessing: false,
        ration: 1,
        minimum: 1,
        maximum: 2,
        duration: 1,
        delegate: 1,
        epochs: 1,
        address: '',
        trackedReward: '',
        transactionHash: '',

        async init() {
            this.ration = this.$root.dataset.ration
            this.minimum = this.$root.dataset.minimum
            this.maximum = this.$root.dataset.maximum
            this.duration = this.$root.dataset.duration

            console.log('CardanoPress ISPO ready!')
        },

        getRewards() {
            return ((this.ration / 100) * this.delegate * this.epochs).toFixed(6)
        },

        async handleDelegation() {
            this.transactionHash = ''

            cardanoPress.api.addNotice({
                id: 'ispo-delegation',
                type: 'info',
                text: 'Processing...',
            })

            this.isProcessing = true
            const response = await handleDelegation()

            cardanoPress.api.removeNotice('ispo-delegation')

            if (response.success) {
                this.transactionHash = response.data.hash

                cardanoPress.api.addNotice({ type: 'info', text: response.data.message })
            } else {
                cardanoPress.api.addNotice({ type: 'warning', text: response.data })
            }

            this.isProcessing = false
        },

        async handleTracking() {
            this.trackedReward = ''

            cardanoPress.api.addNotice({
                id: 'ispo-tracking',
                type: 'info',
                text: 'Tracking...',
            })

            this.isProcessing = true
            const response = await handleTracking(this.address)

            cardanoPress.api.removeNotice('ispo-tracking')

            if (response.success) {
                this.trackedReward = response.data.toFixed(6)
            } else {
                cardanoPress.api.addNotice({ type: 'warning', text: response.data })
            }

            this.isProcessing = false
        },
    }))
})
