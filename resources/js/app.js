import './bootstrap'
import Alpine from 'alpinejs'
import focus from '@alpinejs/focus'

// Alpine plugins 
Alpine.plugin(focus)

// Global Alpine stores 

/**
 * notifications store
 * Usage in Blade: $store.notifications.add('Device certified!', 'success')
 * Wire this to the WebSocket / Echo listener in Sprint 3.
 */
Alpine.store('notifications', {
    items: [],

    add(message, type = 'info') {
        const id = Date.now()
        this.items.push({ id, message, type })
        setTimeout(() => this.remove(id), 5000)
    },

    remove(id) {
        this.items = this.items.filter(n => n.id !== id)
    },
})

// Start Alpine
window.Alpine = Alpine
Alpine.start()