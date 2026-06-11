import './bootstrap'
import Alpine from 'alpinejs'
import focus from '@alpinejs/focus'

// Alpine plugins 
Alpine.plugin(focus)

// Global Alpine stores

/**
 * theme store — class-based dark/light mode with localStorage persistence
 * Usage in Blade: $store.theme.toggle()  |  :class="{'text-white': $store.theme.dark}"
 */
Alpine.store('theme', {
    dark: false,

    init() {
        this.dark = localStorage.theme === 'dark'
            || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)
        document.documentElement.classList.toggle('dark', this.dark)
    },

    toggle() {
        this.dark = !this.dark
        document.documentElement.classList.toggle('dark', this.dark)
        localStorage.theme = this.dark ? 'dark' : 'light'
    },
})



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