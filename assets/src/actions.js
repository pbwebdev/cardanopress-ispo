const getDelegation = async () => {
    return await fetch(cardanoPress.ajaxUrl, {
        method: 'POST',
        body: new URLSearchParams({
            _wpnonce: cardanoPress._nonce,
            action: 'cp-ispo_delegation_data',
        }),
    }).then((response) => response.json())
}

export const handleDelegation = async () => {
    const response = await getDelegation()

    if (!response.success) {
        return response
    }

    const poolId = response.data
    const result = await cardanoPress.wallet.delegationTx(poolId)

    if (result.success) {
        return await cardanoPress.api.saveWalletTx(result.data.network, 'delegation', result.data.transaction)
    }

    return result
}

export const handleTracking = async (stakeAddress) => {
    return await fetch(cardanoPress.ajaxUrl, {
        method: 'POST',
        body: new URLSearchParams({
            _wpnonce: cardanoPress._nonce,
            action: 'cp-ispo_track_rewards',
            stakeAddress,
        }),
    }).then((response) => response.json())
}
